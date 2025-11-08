<?php

namespace App\Http\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
class PhpMailerController extends Controller
{
    public static function sendEmail($mail_to,$cc_email, $to_name, $msg, $subject, $attachment,$mailer_username,$mailer_password,$mailer_name,$email_from,$reply_to,$reply_to_name)
	{
		ob_start();
		$type="mail"; //set the type
	
		$mail = new PHPMailer(true); // notice the \  you have to use root namespace here
	    try {
	    	Log::info('inside PhpmailerController');
	        $mail->isSMTP(); // tell to use smtp
	        $mail->CharSet = "utf-8"; // set charset to utf8
	        $mail->SMTPAuth = true;  // use smpt auth
	        //$mail->SMTPDebug = 2;
	        $mail->SMTPSecure = "tls"; // or ssl
	        $mail->Host = "smtp.gmail.com";
	        $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing. 
	        $mail->Username = $mailer_username;
	        $mail->Password = $mailer_password;
	        if($reply_to!=null)
	        $mail->AddReplyTo($reply_to, $reply_to_name);
	        $mail->setFrom($email_from, $mailer_name);
	        $mail->Subject = nl2br($subject);
	        $mail->MsgHTML($msg);
	        if($attachment!=null)
	        {
	        	if(gettype($attachment)=='array')
		        {
					log::info('attachment type is array');
		        	for($i=0;$i<sizeof($attachment);$i++)
		        	{
						if (substr($attachment[$i], 0, 3) === "all")
						{
							$mail->addAttachment($attachment[$i]);
						}
					   else
					   {
						$file = str_replace('https://karyarat-quotations.s3.ap-south-1.amazonaws.com/', '',$attachment[$i]);
						$filename = str_replace('https://karyarat-quotations.s3.ap-south-1.amazonaws.com/quotation_file', '',$attachment[$i]);
						$s3 = new S3client(array(
						  
						  'region'=>'ap-south-1',
						  'version'=>'latest',
						  'credentials' => array(
							'key'    => env('AWS_ACCESS_KEY_ID'),  
							'secret' => env('AWS_SECRET_ACCESS_KEY') 
						)
						));
						$obj_data = $s3->getObject([
						  'Bucket' => env('AWS_BUCKET'),
						  'Key'    => urldecode($file)
					   ]);
						log::info('abc='.$obj_data);
						
						$mail->AddStringAttachment((string)$obj_data['Body'],$filename, $encoding = 'base64', $type = $obj_data['ContentType']);
		        	}
				}
				
		        }
		        else
				{
					$mail->addAttachment($attachment);
				}
	        		
	        }	        
	        Log::info($mail_to);
			if($cc_email!='')
			{
				$mail->AddCC($cc_email);
			}
			
	        if(gettype($mail_to)=='array')
	        {
	        	$count=sizeof($mail_to);
	        	for($i=0;$i<sizeof($mail_to);$i++)
	        	{
	        	   
	        		if($mail_to[$i]!=NULL || $mail_to[$i]!="")
	        		{
	        		   
	        	    log::info('mail_to='.$mail_to[$i]);
	        	   
	        		$mail->addAddress($mail_to[$i]);
	        		}
	        	
	        	}
	        }
	        else
	        {
	        	$count=1;
	        	if($mail_to!=NULL || $mail_to!='')
	        	{
	        	    	$mail->addAddress($mail_to);
	        	}
	        
	        }
	        
	        $result = $mail->send();


	    //count code//
			$send_to=json_encode($mail_to);
   			
			log::info($count);
			//log::info($cnt);
			log::info($result);
	        return $result;
	    } catch (phpmailerException $e) {
	    	Log::error('PhpmailerController error: ');
	        Log::error($e->getMessage());
	       
	    } catch (Exception $e) {
	    	Log::error('PhpmailerController error : ');
	        Log::error($e->getMessage());
	        
	    }
    }
}
