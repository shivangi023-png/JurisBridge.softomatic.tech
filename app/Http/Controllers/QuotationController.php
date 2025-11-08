<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\NotificationTraits;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Aws\S3\S3Client;
use App\Traits\ClientTraits;
use App\Traits\QuotationTraits;
use App\Traits\StaffTraits;

class QuotationController extends Controller
{
  use QuotationTraits;
  use ClientTraits;
  use NotificationTraits;
  use StaffTraits;
  public function quotation_list(Request $request)
  {
    try {
     
      if (session('username') == "") {
        return redirect('/')->with('status', "Please login First");
      }

        $staff_list = DB::table('staff')->get(['sid','name']);
        $project_status_master = DB::table('project_status_master')->get(['id','status']);
        $services_list = DB::table('quotation_details')
           ->join('services','quotation_details.task_id','services.id')
           ->distinct()
           ->where('quotation_details.finalize','yes')
    ->get(['services.id','services.name']);
      $clients = DB::table('clients')->select('id',DB::raw("CONCAT(client_name,'-(',case_no,')') AS client_case_no"))->where('case_no','!=','')->get();
    
      $services = DB::table('services')->get();
      return view('pages.quotation_list', compact('clients', 'services','staff_list','services_list','project_status_master'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }
  public function quotation_add(Request $request)
  {
    try {

      if (session('username') == "") {
        return redirect('/')->with('status', "Please login First");
      }
      $client_id = $request->id;
      if ($client_id == '') {
        $client_id = '';
      }
      $clients = DB::table('clients')->where('default_company', session('company_id'))->get();
      $company = DB::table('company')->get();
      $services = DB::table('services')->get();
      return view('pages.quotation_add', compact('client_id', 'clients', 'company', 'services'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }

  public function get_client_mail_info(Request $request)
  {
    try {
      $template = DB::table('email_template')->orderBy('id', 'asc')->get();

      $template_list = '';
      $template_list .= '<select class="form-control mailTemplate">
                        <option value="">Select Template</option>';
      foreach ($template as $val) {
        $template_list .= '<option value="' . $val->id . '">' . $val->template_name . '</option>';
      }
      $template_list .= '</select>';

      $quotation_id = $request->quotation_id;

      $quotation_list = DB::table('quotation')
        ->join('clients', 'clients.id', '=', 'quotation.client_id')
        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
        ->select('quotation_details.file', 'quotation.client_id')
        ->whereIn('quotation_details.id', $quotation_id)
        ->where('quotation.company', session('company_id'))
        ->get();

      $quotation_details_ID = implode(',', $quotation_id);
      $client_email = [];
      $quotation_file = [];
      $bytes_sum = 0;
      foreach ($quotation_list as $list) {
        $client_email = DB::table('client_contacts')->where('client_id', $list->client_id)->get(['email']);
        $quotation_file[] = $list->file;

        if (substr($list->file, 0, 3) === "all") {
          $path = base_path('/' . $list->file);
          $bytes = filesize($path);
        } else {

          $file = str_replace('https://karyarat-quotations.s3.ap-south-1.amazonaws.com/', '', $list->file);
          $s3 = new S3client(array(

            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => array(
              'key'    => env('AWS_ACCESS_KEY_ID'),
              'secret' => env('AWS_SECRET_ACCESS_KEY')
            )
          ));

          $obj_data = $s3->headObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => urldecode($file)
          ]);
          $bytes = $obj_data['ContentLength'];
        }
        $bytes_sum += $bytes;
      }

      if (!empty($quotation_list)) {
        return json_encode(array('status' => 'success', 'email' => $client_email, 'file' => $quotation_file, 'quotation_details_id' => $quotation_details_ID, 'template_list' => $template_list, 'unit' => $bytes_sum));
      } else {
        return json_encode(array('status' => 'error', 'msg' => 'Some error occurs while getting client`s emails!'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }

  public function get_template_info(Request $request)
  {
    try {
      $id = $request->id;

      $template = DB::table('email_template')->where('id', $id)->get();
      foreach ($template as $val) {
        $subject = json_decode($val->subject);
        $message = json_decode($val->message);
      }

      if (!empty($template)) {
        return json_encode(array('status' => 'success', 'subject' => $subject, 'message' => $message));
      } else {
        return json_encode(array('status' => 'error', 'msg' => 'Some error occurs while getting template!'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }

  public function send_quotation_mail(Request $request)
  {

    try {

      $quotation_details_id = $request->quotation_details_id;
      $client_email = $request->client_email;
      $cc_email = $request->cc_email;
      $subject = $request->subject;
      $message = $request->body;
      $quotation_file = $request->quotation_file;
      $quotation_details_ID = explode(",", $quotation_details_id);
      $to_email = explode(",", $client_email);
      $attach_file = explode(",", $quotation_file);
      $send = $this->sendquotationmail($quotation_details_ID, $to_email, $attach_file, $cc_email, $subject, $message);
      if ($send == 1) {
        Log::info('mail sent');
        return  json_encode(array('status' => 'success', 'msg' => 'mail sent successfully'));
      } else {
        Log::error('mail not sent');
        return  json_encode(array('status' => 'failure', 'msg' => 'mail can`t be send'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return  json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return  json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }

  public function get_client_quotation(Request $request)
  {
    try {



      if ($request->wantsJson()) {
        $role_id=$request->role_id;
        $company = $request->company;
        $client = $request->client;
        
        $quotation_list = DB::table('quotation')
          ->join('clients', 'clients.id', '=', 'quotation.client_id')
          ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
          ->join('services', 'services.id', '=', 'quotation_details.task_id')
          ->select('clients.id as client_id','clients.client_name', 'clients.case_no', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
          ->where('quotation.client_id', $client)

          ->where('quotation.company', $company)
          ->get();
          foreach ($quotation_list as $row) {
              if($row->finalize_date != '' || $row->finalize_date != null){
                $row->finalize_date=date('d-M-Y',strtotime($row->finalize_date));
              }
              if($row->send_date != '' || $row->send_date != null){
                $row->send_date=date('d-M-Y',strtotime($row->send_date));
              }
             
            }
           $visible=0;
          if($role_id==1)
          {
              $visible=1;
          }
     
          
      } else {
        $company = session('company_id');
        $client = $request->client;

        $quotation_list = DB::table('quotation')
          ->join('clients', 'clients.id', '=', 'quotation.client_id')
          ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
          ->join('services', 'services.id', '=', 'quotation_details.task_id')
          ->select('clients.id as client_id','clients.client_name', 'clients.case_no', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
          ->whereIn('quotation.client_id', $client)
          ->where('quotation.company', $company)
          ->get();
      }

      if ($request->wantsJson()) {
        return json_encode(array('status' => 'success', 'data' => $quotation_list,'visible'=>$visible));
      } else {
         $out = $this->quotation_html_tbl();
      
          $out.=$this->quotation_data_html_tbl($quotation_list);
        
        $out .= '</tbody>
                  </table>
                </div>';
        return json_encode(array('status' => 'success', 'out' => $out));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }
  public function finalize_quotation(Request $request)
  {
    try {
   
      log::info('inside finalize quotation');
      //
       $finalizeId = $request->quotation_details_id;
       $clientId = $request->client;
       if ($request->wantsJson()) 
           {
               $finalize_id=array($finalizeId);
               $client=array($clientId);
               $company=$request->company;
               $finalize_date = $request->finalize_date;
               $total=1;
               $status='';
           }
           else
           {
                  if (Session::get('username') == '')
                     return redirect('/')->with('status', "Please login First");
               
                 $finalize_id = explode(',', $finalizeId);
                  
                  $client = explode(',', $clientId);
                  $finalize_date = $request->finalize_date;
                  $finalize_date = str_replace('/', '-', $finalize_date);
                  $finalize_date = date('Y-m-d', strtotime($finalize_date));
                  $status = $request->status;
                  $total = sizeof($finalize_id);
                  $company=session('company_id');
               
           }
     
      $check_leads = DB::table('clients')->where('id', $clientId)->value('client_leads');
      if ($check_leads == 'leads') {
        return json_encode(array('status' => 'error', 'msg' => 'Please conver this leads to client first'));
      }
      $case_no = '';
      for ($i = 0; $i < $total; $i++) {
        $finalize_quotation = DB::table('quotation_details')
          ->where('id', $finalize_id[$i])
          ->update(['finalize' => 'YES', 'finalize_date' => $finalize_date]);
        log::info('update_quotation_finalize');
        log::info($finalize_quotation);

        $quotation_id = DB::table('quotation_details')->where('id',$finalize_id[$i])->value('quotation_id');
        $task_id = DB::table('quotation_details')->where('id',$finalize_id[$i])->value('task_id');
        $service_name = DB::table('services')->where('id',$task_id)->value('name');
        $client_id = DB::table('quotation')->where('id',$quotation_id)->value('client_id');


         if($finalize_quotation){
           Log::info('-------Push Notification: Quotation_Finalized----');
          $notification_data = $this->push_notification_list('Quotation_Finalized');

          $title = $notification_data['title'];
          $body = $notification_data['body'];
          $icon = $notification_data['icon'];
          $click_action = $notification_data['click_action'];
          $module=$notification_data['module'];

          $data1 = DB::table('clients')->where('id', $client_id)->first(['case_no','client_name','assign_to']);
          $case_no1 = $data1->case_no;
          $client_name = $data1->client_name;
          $staff_id=$this->admin_id();
          log::info('admin_id',$staff_id);
          array_push($staff_id,(string)$data1->assign_to);
          
          $body = str_replace(['{quotation_id}','{case_no}','{client_name}'],[$quotation_id,$case_no1,$client_name],$body);
          $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);
        }


        $sess = '';
        $prev_yr=substr (date('Y')-1,-2);
        $cur_yr=substr (date('Y'),2);
        $nxt_yr=substr (date('Y')+1,-2);
        if(date('m')<=3)
        {
           $sess=$prev_yr.'-'.$cur_yr;
        }
        else
        {
           $sess=$cur_yr.'-'.$nxt_yr;
        }
        $mycases_id = DB::table('mycases')->max('id') + 1;
        $mycase_exist = DB::table('mycases')->where('quotation_id',$finalize_id[$i])->where('status','unfinalized')->count();

          if($mycase_exist){
            Log::info('mycase update - unfinalized to finalize ,quotation_id'.$finalize_id[$i]);
            $update_status = DB::table('mycases')->where('quotation_id',$finalize_id[$i])->update(['description'=>$service_name,'client_id'=>$client_id,'status'=>'finalize']);
          $case_no = DB::table('mycases')->where('quotation_id',$finalize_id[$i])->value('case_no');
          }else{
            Log::info('new insert mycase --'.$finalize_id[$i]);
            $case_no =  $sess . '/' . str_pad($mycases_id, 5, '0', STR_PAD_LEFT);
            $insert_mycases = DB::table('mycases')->insertGetId(['case_no'=>$case_no,'description'=>$service_name,'quotation_id'=>$finalize_id[$i],'client_id'=>$client_id,'status'=>'finalize']);
            if($insert_mycases)
            {
                $contacts=DB::table('client_contacts')->where('client_id',$client_id)->get(['contact']);
                foreach($contacts as $con)
                {
                        $contacts=DB::table('assign_cases')->insert(['case_id'=>$insert_mycases,'client_id'=>$client_id,'phone_no'=>$con->contact,'type'=>'client']);
                }
            
            }
            
              
          }
        }
      if ($finalize_quotation) {
           if ($request->wantsJson()) {
               
                 return json_encode(array('status' => 'success','msg' => 'Quotation finalized successfully'));
           }
           else
           {
               if ($status == '') {
          $quotation_list = DB::table('quotation')
            ->join('clients', 'clients.id', '=', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
            ->join('services', 'services.id', '=', 'quotation_details.task_id')
            ->select('clients.client_name', 'clients.case_no', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
            ->whereIn('client_id', $client)
            ->where('quotation.company', $company)
            ->get();
        } else if ($status != '') {
          $quotation_list = DB::table('quotation')
            ->join('clients', 'clients.id', '=', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
            ->join('services', 'services.id', '=', 'quotation_details.task_id')
            ->select('clients.client_name','clients.case_no', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
            ->whereIn('client_id', $client)
            ->where('quotation.company', $company)
            ->where('quotation_details.finalize', $status)
            ->get();
        }
         foreach ($quotation_list as $row) {
              if($row->finalize_date != '' || $row->finalize_date != null){
                $row->finalize_date=date('d-M-Y',strtotime($row->finalize_date));
              }
             
             
            }
        $out = $this->quotation_html_tbl();
      
          $out.=$this->quotation_data_html_tbl($quotation_list);
        
        $out .= '</tbody>
                  </table>
                </div>';
        return json_encode(array('status' => 'success', 'out' => $out,'case_no'=>$case_no, 'msg' => 'Quotation finalized successfully'));
           }
        
      } else {
        log::info('Quotation Not  Finalize...');
        return json_encode(array('status' => 'error', 'msg' => 'Quotation can`t be finalized'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }

  public function unfinalize_quotation(Request $request)
  {
    try {
        if ($request->wantsJson()) {
            $finalize_id = array($request->quotation_details_id);
            $company=$request->company;
            $status='';
            $date=$request->unfinalize_date;
        }
        else
        {
             if (Session::get('username') == '')
                return redirect('/')->with('status', "Please login First");
              log::info('inside unfinalize quotation');
        
              $finalize_id = $request->quotation_details_id;
              $client = $request->client;
              $status ='no';
              $company=session('company');
              $date=date('Y-m-d');
        }
     

      $total = count($finalize_id);
      $case_no = '';
        $a=0;
      for ($b = 0; $b < $total; $b++) {
        $unfinalize_quotation = DB::table('quotation_details')
          ->where('id', $finalize_id[$b])
          ->update(['finalize' => 'no','finalize_date'=>$date]);
        $update_mycases = DB::table('mycases')->where('quotation_id',$finalize_id[$b])->update(['status'=>'unfinalized']);
        $case_no = DB::table('mycases')->where('quotation_id',$finalize_id[$b])->value('case_no');
        log::info('update_quotation_unfinalize');
        log::info($unfinalize_quotation);

        $quotation_id = DB::table('quotation_details')->where('id',$finalize_id[$b])->value('quotation_id');
        $client_id = DB::table('quotation')->where('id',$quotation_id)->value('client_id');

        if($unfinalize_quotation){
          $a++;
          Log::info('-------Push Notification: Quotation_Unfinalize----');
          $notification_data = $this->push_notification_list('Quotation_Unfinalize');

          $title = $notification_data['title'];
          $body = $notification_data['body'];
          $icon = $notification_data['icon'];
          $click_action = $notification_data['click_action'];
          $module=$notification_data['module'];
          
          $data1 = DB::table('clients')->where('id', $client_id)->first(['case_no','client_name','assign_to']);
          $case_no1 = $data1->case_no;
          $client_name = $data1->client_name;
         
          $staff_id=$this->admin_id();
          array_push($staff_id,(string)$data1->assign_to);

          $body = str_replace(['{quotation_id}','{case_no}','{client_name}'],[$quotation_id,$case_no1,$client_name],$body);
          $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);
        }
      }

      if ($a==$b) {
          if ($request->wantsJson()) {
               return json_encode(array('status' => 'success', 'msg' => 'Quotation unfinalized successfully'));
          }
          else
          {
               if ($status == '') {
          $quotation_list = DB::table('quotation')
            ->join('clients', 'clients.id', '=', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
            ->join('services', 'services.id', '=', 'quotation_details.task_id')
            ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize','quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
            ->whereIn('client_id', $client)
            ->where('quotation.company',$company)
            ->get();
        } else if ($status != '') {
          $quotation_list = DB::table('quotation')
            ->join('clients', 'clients.id', '=', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
            ->join('services', 'services.id', '=', 'quotation_details.task_id')
            ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize','quotation_details.finalize_date', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
            ->whereIn('client_id', $client)
            ->where('quotation.company', $company)
            ->where('quotation_details.finalize', $status)
            ->get();
        }
        foreach ($quotation_list as $row) {
              if($row->finalize_date != '' || $row->finalize_date != null){
                $row->finalize_date=date('d-M-Y',strtotime($row->finalize_date));
              }
             
             
            }
        $out = $this->quotation_html_tbl();
      
        $out.=$this->quotation_data_html_tbl($quotation_list);
      
      $out .= '</tbody>
                </table>
              </div>';
        $out .= '</tbody>
                                  </table>
                                </div>';
        return json_encode(array('status' => 'success', 'out' => $out, 'msg' => 'Quotation unfinalized successfully','case_no'=>$case_no));
              
          }
       
      } else {
        log::info('Quotation Not  Unfinalize...');
        return json_encode(array('status' => 'error', 'msg' => 'Quotation can`t be unfinalized'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }

  public function submit_quotation(Request $request)
  {

    try {
      Log::Info("Inside qutation");
      Log::Info($request);
      $task_array = [];

      $client_id = $request->client;
      $company = $request->company;
      $service_id = $request->service;
      $no_of_units = $request->no_of_units;
      $per_unit_amount = $request->per_unit_amount;
      $amount = $request->amount;
      $ref_no = $request->ref_no;
      $file = $request->file;
      $var = $request->date;
      $date = str_replace('/', '-', $var);
      $date = date('Y-m-d', strtotime($date));
      $send_date = $date;
      $totalamount = 0;

      for ($i = 0; $i < sizeof($service_id); $i++) {

        array_push($task_array, array('service_id' => $service_id[$i], 'amount' => $amount[$i], 'no_of_units' => $no_of_units[$i], 'units_per_amount' => $per_unit_amount[$i], 'ref_no' => $ref_no[$i], 'file' => $file[$i]));

        $totalamount += $amount[$i];
      }
      $last_quo_id = DB::table('quotation')->where('company', $company)->orderBy('id', 'desc')->value('id');
      $last_quotation_no = DB::table('quotation_details')->where('quotation_id', $last_quo_id)->value('quotation_no');
      $quotation_no = $last_quotation_no + 1;
      $quotation_id = DB::table('quotation')->insertGetId(['client_id' => $client_id, 'quotation_date' => $date, 'total_amt' => $totalamount, 'send_date' => $send_date, 'company' => $company, 'created_at' => now()]);
      if ($quotation_id != '') {
        $find_company = DB::table('client_company_mapping')->where('client_id', $client_id)->where('company', $company)->count();
         Log::info('-------Push Notification:Quotation_Sent----');
        $notification_data = $this->push_notification_list('Quotation_Sent');
        $title = $notification_data['title'];
        $body = $notification_data['body'];
        $icon = $notification_data['icon'];
        $click_action = $notification_data['click_action'];
        $module=$notification_data['module'];

        $data1 = DB::table('clients')->where('id', $client_id)->first(['case_no','client_name','assign_to']);
        $case_no = $data1->case_no;
        $client_name = $data1->client_name;
        $staff_id=$this->admin_id();
         
          array_push($staff_id,(string)$data1->assign_to);
        $body = str_replace(['{quotation_id}','{case_no}','{client_name}'],[$quotation_id,$case_no,$client_name],$body);
        $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);
        if ($find_company == 0) {
          $insert_company = DB::table('client_company_mapping')->insert(['client_id' => $client_id, 'company' => $company]);
        }
      }
      log::info($quotation_id);
      $j = 0;

      for ($i = 0; $i < sizeof($task_array); $i++) {
        $service_id = $task_array[$i]['service_id'];
        $amount = $task_array[$i]['amount'];
        $ref_no = $task_array[$i]['ref_no'];
        $no_of_units = $task_array[$i]['no_of_units'];
        $units_per_amount = $task_array[$i]['units_per_amount'];
        $uploadedfile = $task_array[$i]['file'];
        Log::Info($uploadedfile);
        $target_dir = 'all_doc/quotation/';
        Log::Info($target_dir);
        $fileofname = strtolower($uploadedfile->getClientOriginalName());
        Log::Info($fileofname);
        $file_name =  explode('.', $fileofname)[0];

        log::info($file_name);
        $extension = strtolower($uploadedfile->getClientOriginalExtension());
        Log::Info($extension);
        $filename = $file_name . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $client_id . '.' . $extension;
        $target_file = $target_dir . $filename;
        Log::Info($target_file);


        $foldername = 'quotation_file';



        $path = $foldername . '/' . $filename;
        Storage::disk('s3_quotations')->put($path, fopen($uploadedfile, 'r+'), 'public');
        $target_file = Storage::disk('s3_quotations')->url($path);

        $quotation_details = DB::table('quotation_details')
          ->insert([
            'quotation_id' => $quotation_id,
            'quotation_no' => $quotation_no,
            'task_id' => $service_id,
            'file' => $target_file,
            'no_of_units' => $no_of_units,
            'units_per_amount' => $units_per_amount,
            'amount' => $amount,
            'reference_no' => $ref_no,

            'created_at' => now()
          ]);
        if ($quotation_details) {
          $j++;
          $quotation_no++;
        }
      }
      if ($i == $j) {
        return response()->json(array('status' => 'success', 'msg' => 'Quotation created successfully'));
      } else {
        return response()->json(array('status' => 'error', 'msg' => 'Quotation can`t be created'));
      }
    } catch (QueryException $e) {
      Log::error("quotation->Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database error'));
    } catch (Exception $e) {
      Log::error("quotation-> error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }
  public function get_client_no_of_units(Request $request)
  {
    try {
      $client_id = $request->client_id;
      $no_of_units = DB::table('clients')->where('id', $client_id)->value('no_of_units');

      return $no_of_units;
    } catch (QueryException $e) {
      Log::error("quotation->Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database error'));
    } catch (Exception $e) {
      Log::error("quotation-> error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }
  public function update_quotation(Request $request)
  {
    try {
      if (Session::get('username') == '')
        return redirect('/')->with('status', "Please login First");
      log::info('update_quotation');
      //return $request->all();
      $client_id = $request->client_id;
      $q_id = $request->q_id;
      $q_detailid = $request->q_detailid;
      $service_id = $request->service_id;
      $var = $request->send_date;
      $date = str_replace('/', '-', $var);
      $send_date = date('Y-m-d', strtotime($date));
      $amount = $request->amount;

      $no_of_units = $request->no_of_units;
      $units_amount = $request->units_amount;
      $file = $request->file;
      log::info($request);
      $total_amt = $request->total_amt;
      $prev_amt = $request->prev_amt;

      $total_amt1 = $total_amt - $prev_amt;
      $new_total_amt = $total_amt1 + $amount;
      $quotation_id = DB::table('quotation')->where('id', $q_id)->update(['send_date' => $send_date, 'total_amt' => $new_total_amt, 'updated_at' => now()]);
      Log::info($quotation_id . " updated Successfully") . '<br>';


      if ($file != '') {
        $client = DB::table('quotation')->where('id', $q_id)->value('client_id');
        Log::info('Inside file not null ');
        $uploadedfile = $request->file;
        log::info($uploadedfile);
        $target_dir = 'all_doc/quotation/';
        log::info($target_dir);
        $fileofname = strtolower($uploadedfile->getClientOriginalName());
        Log::Info($fileofname);
        $file_name =  explode('.', $fileofname)[0];
        log::info($file_name);
        $extension = strtolower($uploadedfile->getClientOriginalExtension());
        Log::Info($extension);
        $filename = $file_name . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $client . '.' . $extension;
        $target_file = $target_dir . $filename;
        Log::Info($target_file);
        if ($uploadedfile->move(base_path() . '/all_doc/quotation/', $filename)) {
          log::info('inside if quotation_Details update');
          Log::info('Upload Successful');
          $quotation_details = DB::table('quotation_details')->where('id', $q_detailid)->update(['task_id' => $service_id, 'file' => $target_file, 'amount' => $amount, 'no_of_units' => $no_of_units, 'units_per_amount' => $units_amount, 'updated_at' => now()]);
          log::info('quotation_details updated');
        } else {
          Log::info('Upload file process failed ');
          return response()->json(array('status' => 'error', 'msg' => 'File can`t be uploaded'));
        }
      } else {
        Log::info('Inside file null ');
        $quotation_details = DB::table('quotation_details')->where('id', $q_detailid)->update(['task_id' => $service_id, 'amount' => $amount, 'no_of_units' => $no_of_units, 'units_per_amount' => $units_amount, 'updated_at' => now()]);
        log::info('quotation_details');
        log::info($quotation_details);
      }
      if ($quotation_details) {

        $quotation_list = DB::table('quotation')
          ->join('clients', 'clients.id', '=', 'quotation.client_id')
          ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
          ->join('services', 'services.id', '=', 'quotation_details.task_id')
          ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
          ->whereIn('client_id', $client_id)
          ->where('quotation.company', session('company_id'))
          ->get();
        $out = '<div class="action-dropdown-btn d-none">
              <div class="dropdown quotation-filter-action">
                <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  <span class="selection1">Filter Quotation</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
                  <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="finalize">Finalize</a>
                  <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="unfinalize">Unfinalize</a>
                </div>
              </div>
              <div class="dropdown quotation-options">
                <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  Options
                </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">                
                  <a class="dropdown-item" href="javascript:;">Delete</a>
                  <a class="dropdown-item all_finalize"  href="javascript:;">finalize</a>
                  <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
                  <a class="dropdown-item get_client_mail_info" href="javascript:;">Send Mail</a>
                  </div>
              </div>
                
              <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
                <i class="bx bx-plus"></i>Add Quotation</a>
          
            
            </div>
          
            <div class="table-responsive">
              <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th><span class="align-middle">#</span></th>
                    <th>Action</th>
                    <th>Client Name</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Send Date</th>
                    <th>Finalized</th>
                  
                  </tr>
                </thead>
                <tbody>';
        $i = 1;
        $j = 0;
        foreach ($quotation_list as $row) {
          $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
          $out .= '<tr>
                          <td></td>
                          <td></td>
                          <td scope="row" style="font-size:19px; font-style:bold;"><input type="hidden" class="form-control quotation_details_id" value="' . $row->quotation_details_id . '"><a href="' . $row->file . '" target="_blank">Q' . str_pad($i++, 5, "0", STR_PAD_LEFT) . '</a></td>
                          <td><div class="quotation-action">';
          if ($row->finalize == 'YES') {
            $out .= '<button type="button" class="btn btn-icon rounded-circle btn-primary glow mr-1 mb-1 unfinalize" data-tooltip="Unfinalize"><i class="bx bx-list-check"></i></button>';
          } else {
            $out .= '<button type="button" class="btn btn-icon rounded-circle btn-success glow mr-1 mb-1 finalize" data-tooltip="Finalize"><i class="bx 
                                                            bx-list-check"></i></button>';
          }
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-warning glow mr-1 mb-1 update_modal" data-toggle="modal" data-target="#updatequotation" data-send_date="' . date('d/m/Y', strtotime($row->send_date)) . '" data-service="' . $row->task_id . '" data-no_of_units="' . $row->no_of_units . '" data-per_unit_amount="' . $row->units_per_amount . '" data-amount="' . $row->amount . '" data-file="' . $row->file . '" data-id="' . $row->id . '" data-total_amt="' . $row->total_amt . '" data-quotation_detail_id="' . $row->quotation_details_id . '" data-client_name="' . $row->client_name . '" data-tooltip="Edit"><i class="bx bx-edit"></i></button>
                          <button type="button" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-quotation_details_id="' . $row->quotation_details_id . '" data-quotation_id="' . $row->id . '" data-qtotalamt="' . $row->total_amt . '"  data-qamount="' . $row->amount . '"  id="delete_quotation" data-tooltip="Delete"><i class="bx 
                          bx-trash"></i></button>
                            </div></td>
                          <td><b>' . $row->client_case_no . '</b></td>
                          <td>' . $row->task_name . '</td>
                          <td class="text-right">' . number_format($row->amount, 2) . '</td>                             
                        
                          <td>' . date('d-m-Y', strtotime($row->send_date)) . '</td>
                          <td style="width:5px"> ';
          if ($row->finalize == 'no') {
            $out .= '<span class="badge badge-light-danger badge-pill">' . $row->finalize . '</span>';
          } else {
            $out .= '<span class="badge badge-light-success badge-pill">' . $row->finalize . '</span>';
          }
          $out .= '</td>';

          $out .= '</tr>';
        }
        $out .= '</tbody>
                    </table>
                  </div>';
        return response()->json(array('status' => 'success', 'msg' => 'Quotation updated successfully', 'out' => $out));
      } else {
        Log::info('Upload file process failed ');
        return response()->json(array('status' => 'error', 'msg' => 'Quotation can`t be updated'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong'));
    }
  }

  public function get_finalize_quotation(Request $request)
  {

    try {

      $client = $request->client;
      $type = $request->value;
      if ($type == 'finalize') {
        $quotation_list = DB::table('quotation')
          ->join('clients', 'clients.id', '=', 'quotation.client_id')
          ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
          ->join('services', 'services.id', '=', 'quotation_details.task_id')
          ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
          ->whereIn('client_id', $client)
          ->where('quotation.company', session('company_id'))
          ->where('quotation_details.finalize', 'yes')
          ->get();
      }

      if ($type == 'unfinalize') {
        $quotation_list = DB::table('quotation')
          ->join('clients', 'clients.id', '=', 'quotation.client_id')
          ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
          ->join('services', 'services.id', '=', 'quotation_details.task_id')
          ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
          ->whereIn('client_id', $client)
          ->where('quotation.company', session('company_id'))
          ->where('quotation_details.finalize', 'no')
          ->get();
      }
      $out = '<div class="action-dropdown-btn d-none">
            <div class="dropdown quotation-filter-action">
              <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="selection1">Filter Quotation</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
                <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="finalize">Finalize</a>
                <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="unfinalize">Unfinalize</a>
              </div>
            </div>
            <div class="dropdown quotation-options">
              <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Options
              </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">              
                  <a class="dropdown-item" href="javascript:;">Delete</a>
                  <a class="dropdown-item all_finalize"  href="javascript:;">finalize</a>
                  <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
                  <a class="dropdown-item get_client_mail_info" href="javascript:;">Send Mail</a>
                </div>
            </div>
              
            <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
              <i class="bx bx-plus"></i>Add Quotation</a>
        
          
          </div>
          <input type="hidden" id="quotation_filter" value="' . $type . '">
          <div class="table-responsive">
            <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th><span class="align-middle">#</span></th>
                  <th>Action</th>
                  <th>Client Name</th>
                  <th>Service</th>
                  <th>Amount</th>
                  <th>Send Date</th>
                  <th>Finalized</th>
                
                 </tr>
              </thead>
              <tbody>';
      $i = 1;
      $j = 0;
      foreach ($quotation_list as $row) {
        $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
        $out .= '<tr>
                        <td></td>
                        <td></td>
                        <td scope="row" style="font-size:19px; font-style:bold;"><input type="hidden" class="form-control quotation_details_id" value="' . $row->quotation_details_id . '"><a href="' . $row->file . '" target="_blank">Q' . str_pad($i++, 5, "0", STR_PAD_LEFT) . '</a></td>
                        <td><div class="quotation-action">';
        if ($row->finalize == 'YES') {
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-primary glow mr-1 mb-1 unfinalize" data-tooltip="Unfinalize"><i class="bx bx-list-check"></i></button>';
        } else {
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-success glow mr-1 mb-1 finalize" data-tooltip="Finalize"><i class="bx 
                                                          bx-list-check"></i></button>';
        }
        $out .= '<button type="button" class="btn btn-icon rounded-circle btn-warning glow mr-1 mb-1 update_modal" data-toggle="modal" data-target="#updatequotation" data-send_date="' . date('d/m/Y', strtotime($row->send_date)) . '" data-service="' . $row->task_id . '" data-no_of_units="' . $row->no_of_units . '" data-per_unit_amount="' . $row->units_per_amount . '" data-amount="' . $row->amount . '" data-file="' . $row->file . '" data-id="' . $row->id . '" data-total_amt="' . $row->total_amt . '" data-quotation_detail_id="' . $row->quotation_details_id . '" data-client_name="' . $row->client_name . '" data-tooltip="Edit"><i class="bx bx-edit"></i></button>
                        <button type="button" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-quotation_details_id="' . $row->quotation_details_id . '" data-quotation_id="' . $row->id . '" data-qtotalamt="' . $row->total_amt . '"  data-qamount="' . $row->amount . '"  id="delete_quotation" data-tooltip="Delete"><i class="bx 
                        bx-trash"></i></button>
                          </div></td>
                        <td><b>' . $row->client_case_no . '</b></td>
                        <td>' . $row->task_name . '</td>
                        <td class="text-right">' . number_format($row->amount, 2) . '</td>                             
                      
                        <td>' . date('d-m-Y', strtotime($row->send_date)) . '</td>
                        <td style="width:5px"> ';
        if ($row->finalize == 'no') {
          $out .= '<span class="badge badge-light-danger badge-pill">' . $row->finalize . '</span>';
        } else {
          $out .= '<span class="badge badge-light-success badge-pill">' . $row->finalize . '</span>';
        }
        $out .= '</td>';

        $out .= '</tr>';
      }
      $out .= '</tbody>
                  </table>
                </div>';
      return json_encode(array('status' => 'success', 'out' => $out));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }

  public function delete_quotation(Request $request)
  {
    try {
      if (Session::get('username') == '')
        return redirect('/')->with('status', "Please login First");
      log::info('delete_quotation');
      $quotation_details_id = $request->quotation_details_id;
      $quotation_id = $request->quotation_id;
      $client_id = $request->client_name;
      $amount = $request->amount;
      $total_amount = $request->total_amount;
      $filter = $request->filter;

      $count = DB::table('quotation_details')->where('quotation_id', $quotation_id)->count();

      if ($count == 1) {
        $delete_details = DB::table('quotation_details')->where('id', $quotation_details_id)->delete();
        if ($delete_details) {
          $delete_quotation = DB::table('quotation')->where('id', $quotation_id)->delete();
          if ($delete_quotation) {
            if ($filter == null) {
              $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
                ->whereIn('client_id', $client_id)
                ->where('quotation.company', session('company_id'))
                ->get();
            } else if ($filter == 'finalize') {
              $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
                ->whereIn('client_id', $client_id)
                ->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->get();
            } else if ($filter == 'unfinalize') {
              $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
                ->whereIn('client_id', $client_id)
                ->where('quotation_details.finalize', 'no')
                ->where('quotation.company', session('company_id'))
                ->get();
            }

            $out = '';
            $out = '<div class="action-dropdown-btn d-none">
                <div class="dropdown quotation-filter-action">
                  <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="selection1">Filter Quotation</span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
                    <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="finalize">Finalize</a>
                    <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="unfinalize">Unfinalize</a>
                  </div>
                </div>
                <div class="dropdown quotation-options">
                  <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Options
                  </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">                
                    <a class="dropdown-item" href="javascript:;">Delete</a>
                    <a class="dropdown-item all_finalize"  href="javascript:;">finalize</a>
                    <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
                    <a class="dropdown-item get_client_mail_info" href="javascript:;">Send Mail</a>
                    </div>
                </div>
                  
                <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
                  <i class="bx bx-plus"></i>Add Quotation</a>
            
              
              </div>
            
              <div class="table-responsive">
                <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th></th>
                      <th><span class="align-middle">#</span></th>
                      <th>Action</th>
                      <th>Client Name</th>
                      <th>Service</th>
                      <th>Amount</th>
                      <th>Send Date</th>
                      <th>Finalized</th>
                    
                    </tr>
                  </thead>
                  <tbody>';
            $i = 1;
            $j = 0;
            foreach ($quotation_list as $row) {
              $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
              $out .= '<tr>
                                <td></td>
                                <td></td>
                                <td scope="row" style="font-size:19px; font-style:bold;"><input type="hidden" class="form-control quotation_details_id" value="' . $row->quotation_details_id . '"><a href="' . $row->file . '" target="_blank">Q' . str_pad($i++, 5, "0", STR_PAD_LEFT) . '</a></td>
                                <td><div class="quotation-action">';
              if ($row->finalize == 'YES') {
                $out .= '<button type="button" class="btn btn-icon rounded-circle btn-primary glow mr-1 mb-1 unfinalize" data-tooltip="Unfinalize"><i class="bx bx-list-check"></i></button>';
              } else {
                $out .= '<button type="button" class="btn btn-icon rounded-circle btn-success glow mr-1 mb-1 finalize" data-tooltip="Finalize"><i class="bx 
                                                                  bx-list-check"></i></button>';
              }
              $out .= '<button type="button" class="btn btn-icon rounded-circle btn-warning glow mr-1 mb-1 update_modal" data-toggle="modal" data-target="#updatequotation" data-send_date="' . date('d/m/Y', strtotime($row->send_date)) . '" data-service="' . $row->task_id . '" data-no_of_units="' . $row->no_of_units . '" data-per_unit_amount="' . $row->units_per_amount . '" data-amount="' . $row->amount . '" data-file="' . $row->file . '" data-id="' . $row->id . '" data-total_amt="' . $row->total_amt . '" data-quotation_detail_id="' . $row->quotation_details_id . '" data-client_name="' . $row->client_name . '" data-tooltip="Edit"><i class="bx bx-edit"></i></button>
                                <button type="button" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-quotation_details_id="' . $row->quotation_details_id . '" data-quotation_id="' . $row->id . '" data-qtotalamt="' . $row->total_amt . '"  data-qamount="' . $row->amount . '"  id="delete_quotation" data-tooltip="Delete"><i class="bx 
                                bx-trash"></i></button>
                                  </div></td>
                                <td><b>' . $row->client_case_no . '</b></td>
                                <td>' . $row->task_name . '</td>
                                <td class="text-right">' . number_format($row->amount, 2) . '</td>                             
                              
                                <td>' . date('d-m-Y', strtotime($row->send_date)) . '</td>
                                <td style="width:5px"> ';
              if ($row->finalize == 'no') {
                $out .= '<span class="badge badge-light-danger badge-pill">' . $row->finalize . '</span>';
              } else {
                $out .= '<span class="badge badge-light-success badge-pill">' . $row->finalize . '</span>';
              }
              $out .= '</td>';

              $out .= '</tr>';
            }
            $out .= '</tbody>
                        </table>
                      </div>';

            return json_encode(array('status' => 'success', 'msg' => 'Quotation updated successfully', 'out' => $out));
          } else {
            return json_encode(array('status' => 'error', 'msg' => 'Quotation can`t be deleted!'));
          }
        } else {
          return json_encode(array('status' => 'error', 'msg' => 'Quotation can`t be deleted!'));
        }
      } else {
        $delete_details = DB::table('quotation_details')->where('id', $quotation_details_id)->delete();
        $amt = $total_amount - $amount;

        if ($delete_details) {
          $update_quotation = DB::table('quotation')->where('id', $quotation_id)->update(['total_amt' => $amt]);
          if ($filter == null) {
            $quotation_list = DB::table('quotation')
              ->join('clients', 'clients.id', '=', 'quotation.client_id')
              ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
              ->join('services', 'services.id', '=', 'quotation_details.task_id')
              ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
              ->whereIn('client_id', $client_id)
              ->where('quotation.company', session('company_id'))
              ->get();
          } else if ($filter == 'finalize') {
            $quotation_list = DB::table('quotation')
              ->join('clients', 'clients.id', '=', 'quotation.client_id')
              ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
              ->join('services', 'services.id', '=', 'quotation_details.task_id')
              ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
              ->whereIn('client_id', $client_id)
              ->where('quotation_details.finalize', 'yes')
              ->where('quotation.company', session('company_id'))
              ->get();
          } else if ($filter == 'unfinalize') {
            $quotation_list = DB::table('quotation')
              ->join('clients', 'clients.id', '=', 'quotation.client_id')
              ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
              ->join('services', 'services.id', '=', 'quotation_details.task_id')
              ->select('clients.client_name', 'services.name as task_name', 'services.id as task_id', 'quotation_details.id as quotation_details_id', 'quotation_details.finalize', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*')
              ->whereIn('client_id', $client_id)
              ->where('quotation_details.finalize', 'no')
              ->where('quotation.company', session('company_id'))
              ->get();
          }
          $out = '<div class="action-dropdown-btn d-none">
            <div class="dropdown quotation-filter-action">
              <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="selection1">Filter Quotation</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
                <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="finalize">Finalize</a>
                <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="unfinalize">Unfinalize</a>
              </div>
            </div>
            <div class="dropdown quotation-options">
              <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Options
              </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">                
                <a class="dropdown-item" href="javascript:;">Delete</a>
                <a class="dropdown-item all_finalize"  href="javascript:;">finalize</a>
                <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
                  <a class="dropdown-item get_client_mail_info" href="javascript:;">Send Mail</a>
                </div>
            </div>
              
            <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
              <i class="bx bx-plus"></i>Add Quotation</a>
        
          
          </div>
        
          <div class="table-responsive">
            <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th><span class="align-middle">#</span></th>
                  <th>Action</th>
                  <th>Client Name</th>
                  <th>Service</th>
                  <th>Amount</th>
                  <th>Send Date</th>
                  <th>Finalized</th>
                
                </tr>
              </thead>
              <tbody>';
          $i = 1;
          $j = 0;
          foreach ($quotation_list as $row) {
            $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
            $out .= '<tr>
                            <td></td>
                            <td></td>
                            <td scope="row" style="font-size:19px; font-style:bold;"><input type="hidden" class="form-control quotation_details_id" value="' . $row->quotation_details_id . '"><a href="' . $row->file . '" target="_blank">Q' . str_pad($i++, 5, "0", STR_PAD_LEFT) . '</a></td>
                            <td><div class="quotation-action">';
            if ($row->finalize == 'YES') {
              $out .= '<button type="button" class="btn btn-icon rounded-circle btn-primary glow mr-1 mb-1 unfinalize" data-tooltip="Unfinalize"><i class="bx bx-list-check"></i></button>';
            } else {
              $out .= '<button type="button" class="btn btn-icon rounded-circle btn-success glow mr-1 mb-1 finalize" data-tooltip="Finalize"><i class="bx 
                                                              bx-list-check"></i></button>';
            }
            $out .= '<button type="button" class="btn btn-icon rounded-circle btn-warning glow mr-1 mb-1 update_modal" data-toggle="modal" data-target="#updatequotation" data-send_date="' . date('d/m/Y', strtotime($row->send_date)) . '" data-service="' . $row->task_id . '" data-no_of_units="' . $row->no_of_units . '" data-per_unit_amount="' . $row->units_per_amount . '" data-amount="' . $row->amount . '" data-file="' . $row->file . '" data-id="' . $row->id . '" data-total_amt="' . $row->total_amt . '" data-quotation_detail_id="' . $row->quotation_details_id . '" data-client_name="' . $row->client_name . '" data-tooltip="Edit"><i class="bx bx-edit"></i></button>
                            <button type="button" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-quotation_details_id="' . $row->quotation_details_id . '" data-quotation_id="' . $row->id . '" data-qtotalamt="' . $row->total_amt . '"  data-qamount="' . $row->amount . '"  id="delete_quotation" data-tooltip="Delete"><i class="bx 
                            bx-trash"></i></button>
                              </div></td>
                            <td><b>' . $row->client_case_no . '</b></td>
                            <td>' . $row->task_name . '</td>
                            <td class="text-right">' . number_format($row->amount, 2) . '</td>                             
                          
                            <td>' . date('d-m-Y', strtotime($row->send_date)) . '</td>
                            <td style="width:5px"> ';
            if ($row->finalize == 'no') {
              $out .= '<span class="badge badge-light-danger badge-pill">' . $row->finalize . '</span>';
            } else {
              $out .= '<span class="badge badge-light-success badge-pill">' . $row->finalize . '</span>';
            }
            $out .= '</td>';

            $out .= '</tr>';
          }
          $out .= '</tbody>
                    </table>
                  </div>';
          return json_encode(array('status' => 'success', 'msg' => 'Quotation updated successfully', 'out' => $out));
        } else {
          return json_encode(array('status' => 'error', 'msg' => 'Quotation can`t be deleted!'));
        }
      }
      exit;
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
    }
  }
      public function open_quotation_report(Request $request)
    {
        try {
           
            return view('pages.open_quotation_report');
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        }
    }
        public function filter_open_quotation(Request $request)
    {
        try {
            $from_date =  date('Y-m-d', strtotime(str_replace('/', '-', $request->from_date)));
            $to_date=date('Y-m-d', strtotime(str_replace('/', '-', $request->to_date)));
            $data=DB::table('clients')->join('follow_up','follow_up.client_id','clients.id')->select('clients.*','follow_up.contact_by')->where('clients.client_leads','leads')->where('clients.status','active')->whereBetween('clients.date',[$from_date,$to_date])->orderBy('clients.date','asc')->get();
            foreach($data as $row)
            {
              $row->cp_name=DB::table('client_contacts')->where('client_id',$row->id)->value('name');
              $row->contact_no=DB::table('client_contacts')->where('client_id',$row->id)->value('contact');
              $ser=json_decode($row->services);
              $row->lead_by=DB::table('staff')->where('sid',$row->assign_to)->value('name');
              $row->quotation_send=DB::table('quotation')->where('client_id',$row->id)->value('send_date');
              $row->service_name=DB::table('services')->where('id',$ser)->value('name');  
            }
         
            return view('pages.get_open_quotation_report',compact('data'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        }
    }
}
