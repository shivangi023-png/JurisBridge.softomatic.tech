<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PhpMailerController;
use Google_Client;
ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait NotificationTraits
{

public static function sendquotationmail($quotation_details_ID,$to_email,$attach_file,$cc_email,$subject,$message)
	{
        $j=0;
        $i=0;
            $quo_det_id=$quotation_details_ID[$i];
            log::info($cc_email);
            $mailer_detail=json_decode(NotificationTraits::getMailerDetail('web'),true);
            $subject =nl2br($subject);
            $mailsent=PhpMailerController::sendEmail($to_email,$cc_email, null, view('pages.emails.quotation_mail',compact('message','quo_det_id')),$subject,$attach_file, $mailer_detail['mailer_username'], $mailer_detail['mailer_password'], $mailer_detail['mailer_name'], $mailer_detail['email_from'], $mailer_detail['reply_to'], $mailer_detail['reply_to_name']);
            $email_err='email can`t be sent to';
			      
                  if($mailsent)
                  {
                      $j++;

                      $in_log=DB::table('notification_log')->insert([
                          'page'=>'quotation_list',
                          'email'=>json_encode($to_email),	
                          'method'=>'email',	
                          'status'=>1,
                       ]);
                      if($in_log)
                      {
                          log::info('in success mail data is saved in table');
                        
                      }
                      else{
                          log::error('in success mail data can`t be saved in table');
                         
                      }
                      return 1;
                  }
                  else
                  {
                      $email_err= $email_err.','.json_encode($to_email);
                      log::info($email_err);
                      $in_log=DB::table('notification_log')->insert([
                        'page'=>'quotation_list',
                        'email'=>json_encode($to_email),	
                        'method'=>'email',	
                        'status'=>0,
                     ]);
                      if($in_log)
                      {

                          log::info('in failure mail data is saved in table');
                        
                      }
                      else{
                         
                          log::error('in failure mail data can`t be saved in table');
                      }
                      log::error('mail can`t be sent to '.$email_err);
                      return 0;
                  }
          
            
        
        
    
	}
    public static function getMailerDetail($request_from)
	{
		$mailer_username='';
		$mailer_password='';
		$mailer_name='';
		$email_from='';
		$reply_to='';
		$reply_to_name='';

		
        $mailer_username='no-reply@dearsociety.in';
        $mailer_password='uzutnhpvqrxppmdq';
        $mailer_name='Dear Society';
        $email_from='no-reply@dearsociety.in';
        $reply_to=null;
        $reply_to_name=null;
       

        return json_encode(array('mailer_username'=>$mailer_username,'mailer_password'=>$mailer_password,'mailer_name'=>$mailer_name,'email_from'=>$email_from,'reply_to'=>$reply_to,'reply_to_name'=>$reply_to_name));
	}

   function getFirebaseAccessToken() {
        // Path to the Firebase service account JSON file
        $pathToServiceAccount = storage_path('firebase/firebase_karyarat_credentials.json');
        
        // Initialize Google Client
        $client = new Google_Client();
        $client->setAuthConfig($pathToServiceAccount);
        
        // Set the required scope for Firebase Cloud Messaging
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        
        // Fetch the access token
        $tokenArray = $client->fetchAccessTokenWithAssertion();
    
        if (isset($tokenArray['access_token'])) {
            return $tokenArray['access_token'];
            
        } else {
            throw new Exception('Failed to generate access token: ' . json_encode($tokenArray));
        }
    }
public function send_push_notification($title,$body,$staff_id,$click_action,$icon='',$module)
    {
        log::info("heretrait".json_encode($staff_id));
       $firebaseToken = DB::table('staff')
            ->join('firebase_tokens', 'firebase_tokens.staff_id', 'staff.sid')
            ->whereIn('staff.sid',$staff_id)
            ->pluck('firebase_tokens.device_token');
            $accessToken=$this->getFirebaseAccessToken();
            log::info('accessToken='.$accessToken);
         log::info($firebaseToken);
         for($i=0;$i<sizeof($firebaseToken);$i++)
         {
            $data = [
                "message" => [
                    "token" => $firebaseToken[$i],
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                     
                    ],
                    "data" => ["click_action"=>$click_action]  
                ]
            ];
             
            $dataString = json_encode($data);
            
            //$SERVER_KEY = 'AAAAAaZVY4s:APA91bHCOpMkOUnpE5gcVJ6b39zyKaVMtsmJBzcNz-7bup7AP-5fzNdmljqhnhhdmHORWcqN5ZHPBcTYt8E4ZKkiwlOCs84Dp2AbaA0RGu7u1fnVz-uivKlG4HHgXvjQ-5uskd7lYvip';
            
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/karyarat-6e69b/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
           
          
            $response = curl_exec($ch);
    
            $err = curl_error($ch);
            curl_close($ch);
    
            if ($err) {
                log::info('Curl Error: ' . $err);
                
            } else {
                $insert= DB::table('notification')->insert([
                    'staff_id'=>json_encode($staff_id),
                    'module'=>$module,
                    'title'=>$title,
                    'description'=>$body,
                    'response'=>$response
                ]);
               
               
            }

         }// end for
        

    }
    public function push_notification_list($notification_name)
    {
      $jsonString = file_get_contents(base_path('data/push_notifications.json'));
      $data = json_decode($jsonString, true);
      return $data[$notification_name];
    }
   




}