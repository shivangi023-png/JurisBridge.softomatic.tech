<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use App\Traits\ClientTraits;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use App\Traits\NotificationTraits;
use App\Traits\DashboardTraits;
class PaymentController extends Controller
{
  use ClientTraits;
  use ExpenseTraits;
  use StaffTraits;
  use NotificationTraits;
 use DashboardTraits;
  public function payment_list(Request $request)
  {
       
       
       
        try {
      if (session('username') == "") {
        return redirect('/')->with('status', "Please login First");
      }

      $clients = DB::table('clients')->where('default_company', session('company_id'))->get();
      $bank_detail = DB::table('bank_detailes')->get();
      $staff = $this->get_staff_list_userid();

      $received = DB::table('payment')
        ->join('clients', 'clients.id', 'payment.client_id')
        ->select('payment.*', 'clients.client_name', 'clients.case_no')
        ->where('payment.company', session('company_id'))
        ->where('payment.status', 'received')
        ->where('payment.payment_source', 'payment')
        ->where('payment.active', 'yes')
        ->get();
      $deposited = DB::table('payment')
        ->join('clients', 'clients.id', 'payment.client_id')
        ->select('payment.*', 'clients.client_name', 'clients.case_no')
        ->where('payment.company', session('company_id'))
        ->where('payment.status', 'deposited')
        ->where('payment.payment_source', 'payment')
        ->where('payment.active', 'yes')
        ->get();
      $approved = DB::table('payment')
        ->join('clients', 'clients.id', 'payment.client_id')
        ->select('payment.*', 'clients.client_name', 'clients.case_no')
        ->where('payment.company', session('company_id'))
        ->where('payment.status', 'approved')
        ->where('payment.payment_source', 'payment')
        ->where('payment.active', 'yes')
        ->get();
      foreach ($received as $rc) {
        $rc->client_case_no = $this->get_client_case_no_by_id($rc->client_id);
      }
      foreach ($deposited as $dp) {
        $dp->client_case_no = $this->get_client_case_no_by_id($dp->client_id);
      }
      foreach ($approved as $ap) {
        $ap->client_case_no = $this->get_client_case_no_by_id($ap->client_id);
        $ap->deposite_bank_name = DB::table('bank_detailes')->where('id', $ap->deposit_bank)->value('bankname');
        $ap->approved_by_name = DB::table('staff')->where('sid', $ap->approved_by)->value('name');
      }

      $total_received = DB::table('payment')->where('status', 'received')->where('payment_source', 'payment')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
      $total_deposited = DB::table('payment')->where('status', 'deposited') ->where('payment_source', 'payment')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
      $total_approved = DB::table('payment')
        ->join('clients', 'clients.id', 'payment.client_id')
        ->select('payment.*', 'clients.client_name', 'clients.case_no')
        ->where('payment.company', session('company_id'))
        ->where('payment.status', 'approved')
        ->where('payment.active', 'yes')->sum('payment.payment');
      return view('pages.payments.payment_list', compact('received', 'deposited', 'approved', 'bank_detail', 'staff', 'clients', 'bank_detail', 'total_received', 'total_deposited', 'total_approved'));
    } catch (QueryException $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
    }
  }
  public function get_payment(Request $request)
  {
    $v = Validator::make($request->all(), ['list_type' => 'string|required', 'company_id' => 'required']);

    if ($v->fails()) {
      return $v->errors();
    }

    try {

      $type = $request->list_type;
      $company_id = $request->company_id;
      $payment = DB::table('payment')
        ->join('clients', 'clients.id', 'payment.client_id')
        ->select('payment.*', 'clients.client_name', 'clients.case_no')
        ->where('payment.status', $type)
        ->where('company', $company_id)
        ->where('payment.active', 'yes')
        ->get();
      foreach ($payment as $row) {
        $row->approve_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
        $row->deposited_by_name = DB::table('staff')->where('sid', $row->deposited_by)->value('name');
        $row->received_by_name = DB::table('staff')->where('sid', $row->received_by)->value('name');
      }

      return response()->json(array('status' => 'success', 'payment' => $payment));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function accept_payment(Request $request)
  {


    $v = Validator::make($request->all(), [
      'client' => 'string|required',
      'payment_date' => 'string|required',
      'payment' => 'string|required',
      'mode_of_payment' => 'string|required',
      'bill_id' => 'array|required',
      'bill_amt' => 'array|required',
      'narration' => 'string|required',
    ]);

    if ($v->fails()) {
      return response()->json(array('status' => 'validation_error', 'msg' => $v->errors()));
    }

    try {

      $client = $request->client;
      $client_id = $client;
      $case_no = $request->case_no;
      $tds = $request->tds;
      if ($tds == '') {
        $tds = 0;
      }
      $company = $request->company;
      $payment_date = $request->payment_date;
      if ($request->wantsJson()) {
        $payment_date = date('Y-m-d', strtotime($payment_date));
      } else {
        $payment_date = str_replace('/', '-', $payment_date);
        $payment_date = date('Y-m-d', strtotime($payment_date));
      }

      $payment = $request->payment;
      $mode_of_payment = $request->mode_of_payment;
      $ref_no = $request->ref_no;
      $bank_name = $request->bank_name;
      $cheque_no = $request->cheque_no;
      $bill_id = $request->bill_id;
      $bill_amt = $request->bill_amt;
      $narration = $request->narration;

      if ($mode_of_payment == 'cheque') {
        $ref_no = "";

        $status = 'received';
      } else if ($mode_of_payment == 'online') {
        $cheque_no = "";


        $status = 'received';
      } else {
        $ref_no = "";

        $cheque_no = "";

        $bank_name = '';
        $status = 'received';
      }
         $insert = DB::table('payment')->insertGetId([
        'client_id' => $client_id,
        'bill_id' => json_encode($bill_id),
        'receipt_no' => (DB::table('payment')->where('company', $company)->orderBy('id', 'desc')->value('receipt_no')) + 1,
        'bill_amt' => json_encode($bill_amt),
        'payment_date' => $payment_date,
        'payment' => $payment,
        'tds' => $tds,
        'mode_of_payment' => $mode_of_payment,
        'cheque_no' => $cheque_no,
        'reference_no' => $ref_no,
        'bank_name' => $bank_name,
        'narration' => $narration,
        'status' => $status,
        'company' => $company,
        'created_by' => session('user_id'),
        'created_at' => now(),
      ]);
      if ($insert) {
        $j = 0;
        for ($i = 0; $i < sizeof($bill_id); $i++) {
         
          $find_prev_payment = DB::table('bill_payment_mapping')->where('bill_id', $bill_id[$i])->where('active', 'yes')->sum('paid_amount');
          $find_tds = DB::table('bill_payment_mapping')->where('bill_id', $bill_id[$i])->where('active', 'yes')->sum('tds_amount');
          $bill_actual_amt = DB::table('bill')->where('id', $bill_id[$i])->value('total_amount');
          $bill_actual_amt = $bill_actual_amt - ($find_prev_payment + $find_tds);
          log::info('bill_acual_amt ' . $bill_actual_amt);
          log::info('bill_amt ' . $bill_amt[$i]);
          if (($payment+$tds) < $bill_actual_amt) {
            $status = 'partial';
          } else {
            $status = 'paid';
          }
          $update = DB::table('bill')->where('id', $bill_id[$i])->update(['status' => $status, 'updated_at' => now()]);
          $insert1 = DB::table('bill_payment_mapping')->insert([
            'bill_id' => $bill_id[$i],
            'payment_id' => $insert,
            'paid_amount' => $bill_amt[$i],
            'tds_amount' => $tds
          ]);
          if ($update) {
            $j++;
          }
        }

        if (sizeof($bill_id) == $j) {
          $invoice=DB::table('bill')->where('id',$bill_id[0])->first(['invoice_no','company','year']);
          $short_code=DB::table('company')->where('id',$invoice->company)->value('short_code');
          $invoice_no=$short_code. '-' . str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$invoice->year;
          $data1 = DB::table('clients')->where('id',$client_id)->first(['case_no','client_name','assign_to']);
          $notification_data = $this->push_notification_list('Payment_Receivals');
          $title = $notification_data['title'];
          $body = $notification_data['body'];
          $icon = $notification_data['icon'];
          $click_action = $notification_data['click_action'];
          $module = $notification_data['module'];
          $case_no1 = $data1->case_no;
          $client_name = $data1->client_name;
         
         
          $staff_id= array((string)$data1->assign_to);
         
          log::info('assign_to='.$data1->assign_to);

          $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
          $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);

          $data = DB::table('bill')
            ->join('clients', 'clients.id', 'bill.client')
            ->join('staff', 'staff.sid', 'bill.sign')
            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.company', session('company_id'))->where('bill.status', '!=', 'paid')->get();
          $out = '<div class="action-dropdown-btn d-none">
                     <div class="dropdown invoice-filter-action">
                       <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                         Filter Invoice
                       </button>
                       <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                         <a class="dropdown-item" href="javascript:;">Partial Payment</a>
                         <a class="dropdown-item" href="javascript:;">Unpaid</a>
                         <a class="dropdown-item" href="javascript:;">Paid</a>
                       </div>
                     </div>
                     <div class="dropdown invoice-options">
                       <button class="btn border dropdown-toggle mr-2" type="button" id="invoice-options-btn" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                         Options
                       </button>
                       <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-options-btn">
                      
                         <a class="dropdown-item" href="javascript:;">Delete</a>
                         <a class="dropdown-item" href="javascript:;">Send</a>
                       </div>
                 
                       <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                       <i class="bx bx-plus"></i>Add Invoice</a>
                 
                     </div>
                   </div>
                  
                   <div class="table-responsive">
                     <table class="table invoice-data-table dt-responsive wrap" style="width:100%">
                       <thead>
                         <tr>
                           <th></th>
                           <th></th>
                           <th>
                             <span class="align-middle">Invoice#</span>
                           </th>
                           
                           <th>Client</th>
                           <th>Service</th>
                           <th>Amount</th>
                           <th>Status</th>
                           <th>Action</th>
                          
                           <th>Bill Date</th>
                           <th>Due Date</th>
                           <th>Seal</th>
                           <th>Sign</th>   
                   </div>
                         </tr>
                       </thead>
                       
                       <tbody id="invoice_table">';
          foreach ($data as $row) {
            $row->client_case_no = $this->get_client_case_no_by_id($row->client);
            $services_arr = json_decode($row->service);
            $amount_arr = json_decode($row->amount);
            $quotation_array = json_decode($row->quotation);
            $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
            $row->payable = $row->total_amount - $paid_amt;
            $service = '';
            if ($services_arr != '') {
              for ($i = 0; $i < sizeof($services_arr); $i++) {

                $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                $amt = $amount_arr[$i];
                $service .= $ser . ' : ' . $amt . '/- <br>';
              }
            } else {
              for ($i = 0; $i < sizeof($quotation_array); $i++) {
                $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                $ser = DB::table('services')->where('id', $service_id)->value('name');
                $amt = $amount_arr[$i];
                $service .= $ser . ' : ' . $amt . '/- <br>';
              }
            }


            $row->service = $service;
            $invoice_no=session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date));
            $out .= '<tr>
                           <td></td>
                           <td></td>
                           <td>
                             <a href="generate_invoice-' . $row->id . '">INV-' . $row->id . '</a>
                           </td>
                           <td><span class="invoice-customer">' . $row->client_case_no . '</span></td>
                           <td>
                             <!-- <span class="bullet bullet-success bullet-sm"></span> -->
                             <small class="text-muted">' . nl2br($row->service) . '</small>
                           </td> 
                           <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
            if ($row->status == 'unpaid') {
              $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
            } else if ($row->status == 'paid') {
              $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
            } else {
              $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
            }
            $out .= '<td>
                             <div class="invoice-action">
                               <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                                 <i class="bx bx-show-alt"></i>
                               </a> -->
                               <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1" data-invoice_id=' . $row->id . '>
                                 <i class="bx bx-printer"></i>
                               </a>
                               <a href="invoice_edit-' . $row->id . '" class="invoice-action-edit cursor-pointers mr-1 " data-id=' . $row->id . '>
                               <i class="bx bx-edit"></i>
                 
                               <a href="#delete" class="invoice-action-edit cursor-pointers mr-1 " data-id=' . $row->id . '>
                               <i class="bx bx-trash-alt"></i>
                               </a>
                               <a data-toggle="modal" data-target="#default" class="invoice_payment_btn cursor-pointer mr-1" data-id="' . $row->id . '" data-amount="' . $row->payable . '" data-client_id="' . $row->client . '">
                               <i class="bx bx-money"></i>
                               </a>
                               <a href="" class="invoice-action-edit cursor-pointer" data-id=' . $row->id . '>
                               <i class="bx bx-send"></i>
                               </a>
                               <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id="'.$row->id.'" data-payable="'.$row->payable.'" data-client_id="'.$row->client.'" data-invoice_no="'.$invoice_no.'" data-tooltip="write off">
                               <i class="bx bxs-credit-card-alt"></i>
                               </a>
                               <a type="button" class="credit_note btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="'.$row->id.'" data-tooltip="Credit note">
                                   <i class="bx bxs-credit-card"></i>
                               </a>

                 
                             </div>
                           </td>
                           
                           <td>' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                           <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                           <td>' . $row->seal . '</td>
                           <td>' . $row->name . '</td>  
                         </tr>';
          }
          $out .= '</tbody>
                     </table>
                   </div>';


          return response()->json(array('status' => 'success', 'msg' => 'payment Done', 'out' => $out));
        } else {
          return response()->json(array('status' => 'error', 'msg' => 'payment can`t be accepted'));
        }
      } else {
        return response()->json(array('status' => 'error', 'msg' => 'payment can`t be accepted'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function deposite_payment(Request $request)
  {
    log::info('deposite_payment() call');
    try {
      $id = $request->id;
      $status = 'deposited';
      $deposit_date = $request->deposit_date;
      $deposit_bank = $request->deposit_bank;
      $deposited_by = $request->deposit_by;
      $updated_by = $request->deposit_by;
      $update = DB::table('payment')->where('id', $id)->update([
        'deposit_date' => $deposit_date,
        'deposit_bank' => $deposit_bank,
        'deposited_by' => $deposited_by,
        'status' => $status,
        'updated_by' => $updated_by,
        'updated_at' => now(),

      ]);
      
      if ($update) {
        $payment_data=DB::table('payment')->where('id',$id)->first(['client_id','bill_id','payment']);
        $payment=$payment_data->payment;
        $bill_id=json_decode($payment_data->bill_id);
        $client_id=$payment_data->client_id;
        $invoice=DB::table('bill')->where('id',$bill_id[0])->first(['invoice_no','company','year']);
        $short_code=DB::table('company')->where('id',$invoice->company)->value('short_code');
        $invoice_no=$short_code. '-' . str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$invoice->year;
        $data1 = DB::table('clients')->where('id',$client_id)->first(['case_no','client_name','assign_to']);
        $notification_data = $this->push_notification_list('Payment_deposited');
        $title = $notification_data['title'];
        $body = $notification_data['body'];
        $icon = $notification_data['icon'];
        $click_action = $notification_data['click_action'];
        $module = $notification_data['module'];
        $case_no1 = $data1->case_no;
        $client_name = $data1->client_name;
       
       
        $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
        $assign_to=(string)$data1->assign_to;
        $staff_id= array($assign_to);
       
        log::info('assign_to='.$staff_id);

        $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
        $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);
        return response()->json(array('success' => 'Payment Deposited'));
      } else {
        return response()->json(array('error' => 'Payment cant`t be deposited'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function approve_payment(Request $request)
  {
    try {
      $id = $request->id;
      $approve_date = $request->approve_date;
      $approved_by = $request->approve_by;
      $status = 'approved';
      $updated_by = $request->approve_by;
      $update = DB::table('payment')->where('id', $id)->update([
        'status' => $status,
        'approve_date' => $approve_date,
        'approved_by' => $approved_by,
        'updated_by' => $updated_by,
        'updated_at' => now(),

      ]);
      if ($update) {
        Log::info('-------Push Notification: Payment_Approved----');
        $payment_data=DB::table('payment')->where('id',$id)->first(['client_id','bill_id','payment']);
       
        $bill_id=json_decode($payment_data->bill_id);
        $client_id=$payment_data->client_id;
        $invoice=DB::table('bill')->where('id',$bill_id[0])->first(['invoice_no','company','year']);
        $short_code=DB::table('company')->where('id',$invoice->company)->value('short_code');
        $invoice_no=$short_code. '-' . str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$invoice->year;
        $data1 = DB::table('clients')->where('id',$client_id)->first(['case_no','client_name','assign_to']);
        $notification_data = $this->push_notification_list('Payment_Approved');
        $title = $notification_data['title'];
        $body = $notification_data['body'];
        $icon = $notification_data['icon'];
        $click_action = $notification_data['click_action'];
        $module = $notification_data['module'];
        $case_no1 = $data1->case_no;
        $client_name = $data1->client_name;
        $payment = AppHelper::moneyFormatWithoutZeroIndia($payment_data->payment);
        $staff_id=$this->admin_id();
        $assign_to=(string)$data1->assign_to;
        
       
        array_push($staff_id,$assign_to);
        log::info('staff_id='.json_encode($staff_id));
        log::info('assign_to='.$data1->assign_to);

        $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
        $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);

        return response()->json(array('success' => 'Payment approved'));
      } else {
        return response()->json(array('error' => 'Payment cant`t be approved'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function deposite_payment_index(Request $request)
  {
    try {
      $id = $request->id;
      $status = $request->status;
      $deposit_date = str_replace('/', '-', $request->deposit_date);
      $deposit_date = date('Y-m-d', strtotime($deposit_date));
      $deposit_bank = $request->deposit_bank;
      $deposited_by = $request->deposit_by;

      $update = DB::table('payment')->where('id', $id)->update([
        'deposit_date' => $deposit_date,
        'deposit_bank' => $deposit_bank,
        'deposited_by' => $deposited_by,
        'status' => $status,
        'updated_by' => session('user_id'),
        'updated_at' => now(),

      ]);
      if ($update) {
        $payment_data=DB::table('payment')->where('id',$id)->first(['client_id','bill_id','payment']);
        $payment=$payment_data->payment;
        $bill_id=json_decode($payment_data->bill_id);
        $client_id=$payment_data->client_id;
        $invoice=DB::table('bill')->where('id',$bill_id[0])->first(['invoice_no','company','year']);
        $short_code=DB::table('company')->where('id',$invoice->company)->value('short_code');
        $invoice_no=$short_code. '-' . str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$invoice->year;
        $data1 = DB::table('clients')->where('id',$client_id)->first(['case_no','client_name','assign_to']);
        $notification_data = $this->push_notification_list('Payment_deposited');
        $title = $notification_data['title'];
        $body = $notification_data['body'];
        $icon = $notification_data['icon'];
        $click_action = $notification_data['click_action'];
        $module = $notification_data['module'];
        $case_no1 = $data1->case_no;
        $client_name = $data1->client_name;
        $staff_id= array((string)$data1->assign_to);
        log::info('assign_to='.json_encode($staff_id));

        $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
        $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);
        $received = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'received')
          ->where('payment.active', 'yes')
          ->get();
        $deposited = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'deposited')
          ->where('payment.active', 'yes')
          ->get();
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->get();
        $total_received = DB::table('payment')->where('status', 'received')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_deposited = DB::table('payment')->where('status', 'deposited')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        foreach ($received as $rc) {
          $rc->client_case_no = $this->get_client_case_no_by_id($rc->client_id);
        }
        foreach ($deposited as $dp) {
          $dp->client_case_no = $this->get_client_case_no_by_id($dp->client_id);
        }
        foreach ($approved as $ap) {
          $ap->client_case_no = $this->get_client_case_no_by_id($ap->client_id);
          $ap->deposite_bank_name = DB::table('bank_detailes')->where('id', $ap->deposit_bank)->value('bankname');
          $ap->approved_by_name = DB::table('staff')->where('sid', $ap->approved_by)->value('name');
        }

        $bank_detail = DB::table('bank_detailes')->get();
        $staff = $this->get_staff_list_userid();
        $rc_out = '';
        $dp_out = '';
        $ap_out = '';

        $rc_out .= '
        <div class="body">
                                <h5><b>Total received payment : <span
                                            class="total_rc_h4">' . number_format($total_received, 2) . '</span></b></h5>
                            </div>

        <div class="table-responsive">                            
                        <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Client Name</th>
                                    <th>Payment</th>
                                    <th>TDS</th>
                                    <th>Payment Date</th>
                                    <th>Mode of Payment</th>
                                    <th>Cheque No</th>
                                    <th>Deposite Date</th>
                                    <th>Deposite Bank</th>
                                    <th>Deposite By</th>
                                </tr>
                            </thead>
                            <tbody class="">';
        foreach ($received as $rc) {
          $rc_out .= '<tr>
                                              <td></td>
                                            <td></td>
                                            <td>';
          if ($rc->mode_of_payment == 'cash' || $rc->mode_of_payment == 'cheque') {
            $rc_out .= '<div class="payment-action">
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1 create_deposit_btn" data-tooltip="Deposite">
                                                            <i class="bx bxs-receipt"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 deposit_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                      </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_deposit_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>';
          } else if ($rc->mode_of_payment == 'online') {
            if (session('role_id') == 1 || session('role_id') == 3) {
              $rc_out .= '<div class="payment-action">
                                                      <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                            <i class="bx bx-money"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                    </button>';
            }
          }
          $rc_out .= '<button data-id="' . $rc->id . '"
                                                        class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>' . $rc->client_case_no . '
                                                    </span></td>
                                            <td >' . number_format($rc->payment, 2) . '</td>
                                            <td>' . $rc->tds . '</td>
                                            <td data-sort="' . strtotime($rc->payment_date) . '">' . date('d-m-Y', strtotime($rc->payment_date)) . '</td>
                                            <td>' . $rc->mode_of_payment . '</td>
                                            <td>' . $rc->cheque_no . '</td>
                                            <td>
                                                <div class="depo_dt_data">';
          if ($rc->deposit_date != '') {
            $rc_out .= date('d-m-Y', strtotime($rc->deposit_date));
          }
          $rc_out .= '</div>
                                                <div class="depo_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker deposit_date"
                                                        placeholder="Deposit date">
                                                    <span class="valid_err deposit_date_err"></span>
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker approve_date"
                                                        placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_bank_data">' . $rc->deposit_bank . '</div>
                                                <div class="depo_bank_ui" style="display:none">
                                                    <select class="form-control required deposit_bank" name="deposit_bank"
                                                        id="deposit_bank">
                                                        <option value="">---Select deposit bank---</option>';
          foreach ($bank_detail as $bank) {
            if ($bank->default_bank_account == 'yes') {
              $rc_out .= '<option value="' . $bank->id . '" selected>' . $bank->bankname . '</option>';
            } else {
              $rc_out .= '<option value="' . $bank->id . '" >' . $bank->bankname . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_bank_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_by_data">' . $rc->deposited_by . '</div>
                                                <div class="depo_by_ui" style="display:none">
    
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';
          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                                <div class="apr_by_ui" style="display:none">

                                                    <select class="form-control required approve_by" name="approve_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (
              session('role_id') != 1 &&
              (session('user_id') == $stf->user_id)
            ) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }

          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select approve by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }

        $rc_out .= '</tbody>
                                </table>                            
                        </div>';
        $dp_out .= '
        <div class="body">
                                <h5><b>Total deposited payment: <span
                                            class="total_dp_h4">' . number_format($total_deposited, 2) . '</span></b></h5>
                            </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>
    
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Approve date</th>
                                            <th>Approve by</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($deposited as $dp) {
          $dp_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>';
          if (session('role_id') == 1 || session('role_id') == 3) {
            $dp_out .= '<div class="payment-action">
                                                <button data-id="' . $dp->id . '"
                                                    class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                    <i class="bx bx-money"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                    style="display:none;" data-tooltip="Done">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                    style="display:none;" data-tooltip="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>';
          }
          $dp_out .= '<button data-id="' . $dp->id . '"
                                                class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-id="' . $dp->id . '" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                                </div>
                                            </td>
                                            <td><span>' . $dp->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($dp->payment, 2) . '</td>
                                            <td>' . $dp->tds . '</td>
                                            <td data-sort="' . strtotime($dp->payment_date) . '">' . date('d-m-Y', strtotime($dp->payment_date)) . '</td>
                                            <td>' . $dp->mode_of_payment . '</td>
                                            <td>' . $dp->cheque_no . '</td>
                                            <td>' . $dp->reference_no . '</td>
                                            <td>
                                                <div>
                                                <div class="apr_dt_data">';
          if ($dp->deposit_date != '') {
            date('d-m-Y', strtotime($dp->deposit_date));
          }
          $dp_out .= '</div>
                                          </div>
                                              <div class="apr_dt_ui" style="display:none">
                                                  <input type="text" style="top: 461.4px"
                                                      class="form-control datepicker approve_date"
                                                      placeholder="approve_date">
                                                  <span class="valid_err approve_date_err"></span>
                                              </div>
                                            </td>
                                            <td>
                                                <div class="apr_by_data">' . $dp->approved_by . '</div>
    
                                                <div class="apr_by_ui" style="display:none">
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $dp_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $dp_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $dp_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $dp_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }
        $dp_out .= '</tbody>
                                </table>                           
                        </div>';
        $ap_out .= '
        <div class="body">
                                <h5><b>Total approved payment : <span
                                            class="total_ap_h4">' . number_format($total_approved, 2) . '</span></b></h5>
                            </div>
        <div class="action-dropdown-btn d-none">
        <div class="dropdown payment-filter-action">
            <button class="btn border dropdown-toggle mr-1" type="button"
                id="payment-filter-btn" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="selection">Filter Payment</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="payment-filter-btn">
                <a type="button" href="javascript:void(0);"
                    class="dropdown-item filter_approve_btn" data-value="today">Today</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="next_day">Next
                    Day</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_week">This
                    Week</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_month">This Month</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_year">This
                    Year</a>
            </div>
        </div>
    </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>payment Date</th>
                                            <th>Mode of payment</th>
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Narration</th>
                                            <th>Deposite Bank</th>
                                            <th>Approved By</th>
                                            <th>Approved Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($approved as $ap) {
          $ap_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                <a href="payment_reciept-' . $ap->id . '"
                                                      class="payment-action-receipt btn btn-icon rounded-circle btn-warning mr-1 mb-1 " data-tooltip="Payment Receipt">
                                                      <i class="bx bx-printer"></i>
                                                    </a>
    
                                                    <a href="#"
                                                        class="payment-action-delete btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_payment" data-id="' . $ap->id . '" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span>' . $ap->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($ap->payment, 2) . '</td>
                                            <td>' . $ap->tds . '</td>
                                            <td data-sort="' . strtotime($ap->payment_date) . '">
                                                ' . date('d-m-Y', strtotime($ap->payment_date)) . '
                                            </td>
                                            <td>' . $ap->mode_of_payment . '</td>
                                            <td>' . $ap->cheque_no . '</td>
                                            <td>' . $ap->reference_no . '</td>
                                            <td>' . $ap->narration . '</td>
                                            <td>' . $ap->deposite_bank_name . '</td>
                                            <td>' . $ap->approved_by_name . '</td>
                                            <td>' . date('d-m-Y', strtotime($ap->approve_date)) . '</td>
                                        </tr>';
        }
        $ap_out .= '</tbody>
                                </table>                                                    
                    </div>
                ';

        return json_encode(array('status' => 'success', 'rc_out' => $rc_out, 'dp_out' => $dp_out, 'ap_out' => $ap_out));
      }
    } catch (QueryException $e) {

      Log::error($e->getMessage());

      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    }
  }

  public function approve_payment_index(Request $request)
  {
    try {
      log::info('approve_payment_index call');  
      $id = $request->id;
      $approve_date = str_replace('/', '-', $request->approve_date);
      $approve_date = date('Y-m-d', strtotime($approve_date));
      $approved_by = $request->approve_by;
      $status = $request->status;
      $update = DB::table('payment')->where('id', $id)->update([
        'status' => $status,
        'approve_date' => $approve_date,
        'approved_by' => $approved_by,
        'updated_by' => session('user_id'),
        'updated_at' => now(),

      ]);
      $bank_detail = DB::table('bank_detailes')->get();
      $staff = $this->get_staff_list_userid();
      if ($update) {
        Log::info('-------Push Notification: Payment_Approved----');
        $payment_data=DB::table('payment')->where('id',$id)->first(['client_id','bill_id','payment']);
       
        $bill_id=json_decode($payment_data->bill_id);
        $client_id=$payment_data->client_id;
        $invoice=DB::table('bill')->where('id',$bill_id[0])->first(['invoice_no','company','year']);
        $short_code=DB::table('company')->where('id',$invoice->company)->value('short_code');
        $invoice_no=$short_code. '-' . str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$invoice->year;
        $data1 = DB::table('clients')->where('id',$client_id)->first(['case_no','client_name','assign_to']);
        $notification_data = $this->push_notification_list('Payment_Approved');
        $title = $notification_data['title'];
        $body = $notification_data['body'];
        $icon = $notification_data['icon'];
        $click_action = $notification_data['click_action'];
        $module = $notification_data['module'];
        $case_no1 = $data1->case_no;
        $client_name = $data1->client_name;
        $payment = AppHelper::moneyFormatWithoutZeroIndia($payment_data->payment);
        $staff_id=$this->admin_id();
        array_push($staff_id,(string)$data1->assign_to);
        log::info('staff_id='.json_encode($staff_id));
        log::info('assign_to='.$data1->assign_to);

        $body = str_replace(['{amount}','{invoice_no}','{case_no}','{client_name}'],[$payment,$invoice_no,$case_no1,$client_name],$body);
        $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);


        $received = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'received')
          ->where('payment.active', 'yes')
          ->get();
        $deposited = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'deposited')
          ->where('payment.active', 'yes')
          ->get();
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->get();
        
        foreach ($received as $rc) {
          $rc->client_case_no = $this->get_client_case_no_by_id($rc->client_id);
        }
        foreach ($deposited as $dp) {
          $dp->client_case_no = $this->get_client_case_no_by_id($dp->client_id);
        }
        foreach ($approved as $ap) {
          $ap->client_case_no = $this->get_client_case_no_by_id($ap->client_id);
          $ap->deposite_bank_name = DB::table('bank_detailes')->where('id', $ap->deposit_bank)->value('bankname');
          $ap->approved_by_name = DB::table('staff')->where('sid', $ap->approved_by)->value('name');
        }
        $total_received = DB::table('payment')->where('status', 'received')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_deposited = DB::table('payment')->where('status', 'deposited')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');


        $rc_out = '';
        $dp_out = '';
        $ap_out = '';
        $rc_out .= '
        <div class="body">
                                <h5><b>Total received payment : <span
                                            class="total_rc_h4">' . number_format($total_received, 2) . '</span></b></h5>
                            </div>

        <div class="table-responsive">                            
                        <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Client Name</th>
                                    <th>Payment</th>
                                    <th>TDS</th>
                                    <th>Payment Date</th>
                                    <th>Mode of Payment</th>
                                    <th>Cheque No</th>
                                    <th>Deposite Date</th>
                                    <th>Deposite Bank</th>
                                    <th>Deposite By</th>
                                </tr>
                            </thead>
                            <tbody class="">';
        foreach ($received as $rc) {
          $rc_out .= '<tr>
                                              <td></td>
                                            <td></td>
                                            <td>';
          if ($rc->mode_of_payment == 'cash' || $rc->mode_of_payment == 'cheque') {
            $rc_out .= '<div class="payment-action">
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1 create_deposit_btn" data-tooltip="Deposite">
                                                            <i class="bx bxs-receipt"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 deposit_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                      </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_deposit_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>';
          } else if ($rc->mode_of_payment == 'online') {
            if (session('role_id') == 1 || session('role_id') == 3) {
              $rc_out .= '<div class="payment-action">
                                                      <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                            <i class="bx bx-money"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                    </button>';
            }
          }
          $rc_out .= '<button data-id="' . $rc->id . '"
                                                        class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>' . $rc->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($rc->payment, 2) . '</td>
                                            <td>' . $rc->tds . '</td>
                                            <td data-sort="' . strtotime($rc->payment_date) . '">' . date('d-m-Y', strtotime($rc->payment_date)) . '</td>
                                            <td>' . $rc->mode_of_payment . '</td>
                                            <td>' . $rc->cheque_no . '</td>
                                            <td>
                                                <div class="depo_dt_data">';
          if ($rc->deposit_date != '') {
            $rc_out .= date('d-m-Y', strtotime($rc->deposit_date));
          }
          $rc_out .= '</div>
                                                <div class="depo_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker deposit_date"
                                                        placeholder="Deposit date">
                                                    <span class="valid_err deposit_date_err"></span>
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker approve_date"
                                                        placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_bank_data">' . $rc->deposit_bank . '</div>
                                                <div class="depo_bank_ui" style="display:none">
                                                    <select class="form-control required deposit_bank" name="deposit_bank"
                                                        id="deposit_bank">
                                                        <option value="">---Select deposit bank---</option>';
          foreach ($bank_detail as $bank) {
            if ($bank->default_bank_account == 'yes') {
              $rc_out .= '<option value="' . $bank->id . '" selected>' . $bank->bankname . '</option>';
            } else {
              $rc_out .= '<option value="' . $bank->id . '" >' . $bank->bankname . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_bank_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_by_data">' . $rc->deposited_by . '</div>
                                                <div class="depo_by_ui" style="display:none">
    
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';
          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                                <div class="apr_by_ui" style="display:none">

                                                    <select class="form-control required approve_by" name="approve_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (
              session('role_id') != 1 &&
              (session('user_id') == $stf->user_id)
            ) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }

          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select approve by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }

        $rc_out .= '</tbody>
                                </table>                            
                        </div>';
        $dp_out .= '
        <div class="body">
                                <h5><b>Total deposited payment: <span
                                            class="total_dp_h4">' . number_format($total_deposited, 2) . '</span></b></h5>
                            </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>
    
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Approve date</th>
                                            <th>Approve by</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($deposited as $dp) {
          $dp_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>';
          if (session('role_id') == 1 || session('role_id') == 3) {
            $dp_out .= '<div class="payment-action">
                                                <button data-id="' . $dp->id . '"
                                                    class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                    <i class="bx bx-money"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                    style="display:none;" data-tooltip="Done">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                    style="display:none;" data-tooltip="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>';
          }
          $dp_out .= '<button data-id="' . $dp->id . '"
                                                class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-id="' . $dp->id . '" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                                </div>
                                            </td>
                                            <td><span>' . $dp->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($dp->payment, 2) . '</td>
                                            <td>' . $dp->tds . '</td>
                                            <td data-sort="' . strtotime($dp->payment_date) . '">' . date('d-m-Y', strtotime($dp->payment_date)) . '</td>
                                            <td>' . $dp->mode_of_payment . '</td>
                                            <td>' . $dp->cheque_no . '</td>
                                            <td>' . $dp->reference_no . '</td>
                                            <td>
                                                <div>
                                                <div class="apr_dt_data">';
          if ($dp->deposit_date != '') {
            date('d-m-Y', strtotime($dp->deposit_date));
          }
          $dp_out .= '</div>
                                          </div>
                                              <div class="apr_dt_ui" style="display:none">
                                                  <input type="text" style="top: 461.4px"
                                                      class="form-control datepicker approve_date"
                                                      placeholder="approve_date">
                                                  <span class="valid_err approve_date_err"></span>
                                              </div>
                                            </td>
                                            <td>
                                                <div class="apr_by_data">' . $dp->approved_by . '</div>
    
                                                <div class="apr_by_ui" style="display:none">
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $dp_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $dp_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $dp_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $dp_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }
        $dp_out .= '</tbody>
                                </table>                           
                        </div>';
        $ap_out .= '
        <div class="body">
                                <h5><b>Total approved payment : <span
                                            class="total_ap_h4">' . number_format($total_approved, 2) . '</span></b></h5>
                            </div>
        <div class="action-dropdown-btn d-none">
        <div class="dropdown payment-filter-action">
            <button class="btn border dropdown-toggle mr-1" type="button"
                id="payment-filter-btn" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="selection">Filter Payment</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="payment-filter-btn">
                <a type="button" href="javascript:void(0);"
                    class="dropdown-item filter_approve_btn" data-value="today">Today</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="next_day">Next
                    Day</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_week">This
                    Week</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_month">This Month</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_year">This
                    Year</a>
            </div>
        </div>
    </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>payment Date</th>
                                            <th>Mode of payment</th>
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Narration</th>
                                            <th>Deposite Bank</th>
                                            <th>Approved By</th>
                                            <th>Approved Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($approved as $ap) {
          $ap_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                <a href="payment_reciept-' . $ap->id . '"
                                                      class="payment-action-receipt btn btn-icon rounded-circle btn-warning mr-1 mb-1 " data-tooltip="Payment Receipt">
                                                      <i class="bx bx-printer"></i>
                                                    </a>
    
                                                    <a href="#"
                                                        class="payment-action-delete btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_payment" data-id="' . $ap->id . '" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span>' . $ap->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($ap->payment, 2) . '</td>
                                            <td>' . $ap->tds . '</td>
                                            <td data-sort="' . strtotime($ap->payment_date) . '">
                                                ' . date('d-m-Y', strtotime($ap->payment_date)) . '
                                            </td>
                                            <td>' . $ap->mode_of_payment . '</td>
                                            <td>' . $ap->cheque_no . '</td>
                                            <td>' . $ap->reference_no . '</td>
                                            <td>' . $ap->narration . '</td>
                                            <td>' . $ap->deposite_bank_name . '</td>
                                            <td>' . $ap->approved_by_name . '</td>
                                            <td>' . date('d-m-Y', strtotime($ap->approve_date)) . '</td>
                                        </tr>';
        }
        $ap_out .= '</tbody>
                                </table>                                                    
                    </div>
                ';

        return json_encode(array('status' => 'success', 'rc_out' => $rc_out, 'dp_out' => $dp_out, 'ap_out' => $ap_out));
      }
    } catch (QueryException $e) {

      Log::error($e->getMessage());

      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    }
  }

  public function delete_payment(Request $request)
  {
    try {
      $id = $request->id;

      $delete = DB::table('payment')->where('id', $id)->update(['active' => 'no', 'updated_at' => now()]);

      $bank_detail = DB::table('bank_detailes')->get();
      $staff = $this->get_staff_list_userid();
      if ($delete)
       {
        $delete_mapping_table = DB::table('bill_payment_mapping')->where('payment_id', $id)->update(['active' => 'no', 'updated_at' => now()]);
        $mappingdata = DB::table('bill_payment_mapping')->where('payment_id', $id)->get();
        $pay_amt=DB::table('bill_payment_mapping')->where('payment_id', $id)->value('paid_amount');
        $tds_amt=DB::table('bill_payment_mapping')->where('payment_id', $id)->value('tds_amount');
        $paid_amt=$pay_amt+$tds_amt;
        $bill_id=DB::table('bill_payment_mapping')->where('payment_id', $id)->value('bill_id');
        $bill_total_amount = DB::table('bill')->where('id',$bill_id)->value('total_amount');
            if ($bill_total_amount > $paid_amt) 
            {
               $status = "partial";
            } 
            if($paid_amt==0)
            {
               $status = 'unpaid';
            }
          
        $update_bill = DB::table('bill')->where('id', $bill_id)->update(['status' => $status, 'updated_at' => now()]);
        
        $received = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'received')
          ->where('payment.active', 'yes')
          ->get();
        $deposited = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'deposited')
          ->where('payment.active', 'yes')
          ->get();
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->get();
        foreach ($received as $rc) {
          $rc->client_case_no = $this->get_client_case_no_by_id($rc->client_id);
        }
        foreach ($deposited as $dp) {
          $dp->client_case_no = $this->get_client_case_no_by_id($dp->client_id);
        }
        foreach ($approved as $ap) {
          $ap->client_case_no = $this->get_client_case_no_by_id($ap->client_id);
          $ap->deposite_bank_name = DB::table('bank_detailes')->where('id', $ap->deposit_bank)->value('bankname');
          $ap->approved_by_name = DB::table('staff')->where('sid', $ap->approved_by)->value('name');
        }
        $rc_out = '';
        $dp_out = '';
        $ap_out = '';
        $total_received = DB::table('payment')->where('status', 'received')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_deposited = DB::table('payment')->where('status', 'deposited')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $rc_out .= '
        <div class="body">
                                <h5><b>Total received payment : <span
                                            class="total_rc_h4">' . number_format($total_received, 2) . '</span></b></h5>
                            </div>

        <div class="table-responsive">                            
                        <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Client Name</th>
                                    <th>Payment</th>
                                    <th>TDS</th>
                                    <th>Payment Date</th>
                                    <th>Mode of Payment</th>
                                    <th>Cheque No</th>
                                    <th>Deposite Date</th>
                                    <th>Deposite Bank</th>
                                    <th>Deposite By</th>
                                </tr>
                            </thead>
                            <tbody class="">';
        foreach ($received as $rc) {
          $rc_out .= '<tr>
                                              <td></td>
                                            <td></td>
                                            <td>';
          if ($rc->mode_of_payment == 'cash' || $rc->mode_of_payment == 'cheque') {
            $rc_out .= '<div class="payment-action">
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1 create_deposit_btn" data-tooltip="Deposite">
                                                            <i class="bx bxs-receipt"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 deposit_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                      </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_deposit_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>';
          } else if ($rc->mode_of_payment == 'online') {
            if (session('role_id') == 1 || session('role_id') == 3) {
              $rc_out .= '<div class="payment-action">
                                                      <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                            <i class="bx bx-money"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                    </button>';
            }
          }
          $rc_out .= '<button data-id="' . $rc->id . '"
                                                        class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>' . $rc->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($rc->payment, 2) . '</td>
                                            <td>' . $rc->tds . '</td>
                                            <td data-sort="' . strtotime($rc->payment_date) . '">' . date('d-m-Y', strtotime($rc->payment_date)) . '</td>
                                            <td>' . $rc->mode_of_payment . '</td>
                                            <td>' . $rc->cheque_no . '</td>
                                            <td>
                                                <div class="depo_dt_data">';
          if ($rc->deposit_date != '') {
            $rc_out .= date('d-m-Y', strtotime($rc->deposit_date));
          }
          $rc_out .= '</div>
                                                <div class="depo_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker deposit_date"
                                                        placeholder="Deposit date">
                                                    <span class="valid_err deposit_date_err"></span>
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker approve_date"
                                                        placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_bank_data">' . $rc->deposit_bank . '</div>
                                                <div class="depo_bank_ui" style="display:none">
                                                    <select class="form-control required deposit_bank" name="deposit_bank"
                                                        id="deposit_bank">
                                                        <option value="">---Select deposit bank---</option>';
          foreach ($bank_detail as $bank) {
            if ($bank->default_bank_account == 'yes') {
              $rc_out .= '<option value="' . $bank->id . '" selected>' . $bank->bankname . '</option>';
            } else {
              $rc_out .= '<option value="' . $bank->id . '" >' . $bank->bankname . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_bank_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_by_data">' . $rc->deposited_by . '</div>
                                                <div class="depo_by_ui" style="display:none">
    
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';
          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                                <div class="apr_by_ui" style="display:none">

                                                    <select class="form-control required approve_by" name="approve_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (
              session('role_id') != 1 &&
              (session('user_id') == $stf->user_id)
            ) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }

          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select approve by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }

        $rc_out .= '</tbody>
                                </table>                            
                        </div>';
        $dp_out .= '
        <div class="body">
                                <h5><b>Total deposited payment: <span
                                            class="total_dp_h4">' . number_format($total_deposited, 2) . '</span></b></h5>
                            </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>
    
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Approve date</th>
                                            <th>Approve by</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($deposited as $dp) {
          $dp_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>';
          if (session('role_id') == 1 || session('role_id') == 3) {
            $dp_out .= '<div class="payment-action">
                                                <button data-id="' . $dp->id . '"
                                                    class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                    <i class="bx bx-money"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                    style="display:none;" data-tooltip="Done">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                    style="display:none;" data-tooltip="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>';
          }
          $dp_out .= '<button data-id="' . $dp->id . '"
                                                class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-id="' . $dp->id . '" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                                </div>
                                            </td>
                                            <td><span>' . $dp->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($dp->payment, 2) . '</td>
                                            <td>' . $dp->tds . '</td>
                                            <td data-sort="' . strtotime($dp->payment_date) . '">' . date('d-m-Y', strtotime($dp->payment_date)) . '</td>
                                            <td>' . $dp->mode_of_payment . '</td>
                                            <td>' . $dp->cheque_no . '</td>
                                            <td>' . $dp->reference_no . '</td>
                                            <td>
                                                <div>
                                                <div class="apr_dt_data">';
          if ($dp->deposit_date != '') {
            date('d-m-Y', strtotime($dp->deposit_date));
          }
          $dp_out .= '</div>
                                          </div>
                                              <div class="apr_dt_ui" style="display:none">
                                                  <input type="text" style="top: 461.4px"
                                                      class="form-control datepicker approve_date"
                                                      placeholder="approve_date">
                                                  <span class="valid_err approve_date_err"></span>
                                              </div>
                                            </td>
                                            <td>
                                                <div class="apr_by_data">' . $dp->approved_by . '</div>
    
                                                <div class="apr_by_ui" style="display:none">
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $dp_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $dp_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $dp_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $dp_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }
        $dp_out .= '</tbody>
                                </table>                           
                        </div>';
        $ap_out .= '
        <div class="body">
                                <h5><b>Total approved payment : <span
                                            class="total_ap_h4">' . number_format($total_approved, 2) . '</span></b></h5>
                            </div>
        <div class="action-dropdown-btn d-none">
        <div class="dropdown payment-filter-action">
            <button class="btn border dropdown-toggle mr-1" type="button"
                id="payment-filter-btn" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="selection">Filter Payment</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="payment-filter-btn">
                <a type="button" href="javascript:void(0);"
                    class="dropdown-item filter_approve_btn" data-value="today">Today</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="next_day">Next
                    Day</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_week">This
                    Week</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_month">This Month</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_year">This
                    Year</a>
            </div>
        </div>
    </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>payment Date</th>
                                            <th>Mode of payment</th>
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Narration</th>
                                            <th>Deposite Bank</th>
                                            <th>Approved By</th>
                                            <th>Approved Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($approved as $ap) {
          $ap_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                <a href="payment_reciept-' . $ap->id . '"
                                                      class="payment-action-receipt btn btn-icon rounded-circle btn-warning mr-1 mb-1 " data-tooltip="Payment Receipt">
                                                      <i class="bx bx-printer"></i>
                                                    </a>
    
                                                    <a href="#"
                                                        class="payment-action-delete btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_payment" data-id="' . $ap->id . '" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span>' . $ap->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($ap->payment, 2) . '</td>
                                            <td>' . $ap->tds . '</td>
                                            <td data-sort="' . strtotime($ap->payment_date) . '">
                                                ' . date('d-m-Y', strtotime($ap->payment_date)) . '
                                            </td>
                                            <td>' . $ap->mode_of_payment . '</td>
                                            <td>' . $ap->cheque_no . '</td>
                                            <td>' . $ap->reference_no . '</td>
                                            <td>' . $ap->narration . '</td>
                                            <td>' . $ap->deposite_bank_name . '</td>
                                            <td>' . $ap->approved_by_name . '</td>
                                            <td>' . date('d-m-Y', strtotime($ap->approve_date)) . '</td>
                                        </tr>';
        }
        $ap_out .= '</tbody>
                                </table>                                                    
                    </div>
                ';

        return json_encode(array('status' => 'success', 'rc_out' => $rc_out, 'dp_out' => $dp_out, 'ap_out' => $ap_out, 'msg' => 'payment deposited'));
      }
    } catch (QueryException $e) {

      Log::error($e->getMessage());

      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    }
  }

  public function filter_approve_payment(Request $request)
  {
    try {
      $type = $request->value;
      $today = date('Y-m-d');
      $tomorrow = date(('Y-m-d'), strtotime("+1 day"));
      $this_week = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
      if (date('m') > 03) {
        $year = date('Y');
        $year1 = $year + 1;
      } else {
        $year = date('Y') - 1;
        $year1 = $year + 1;
      }

      $start_fiscal_year = strtotime('1-April-' . $year);
      $end_fiscal_year = strtotime('31-March-' . $year1);
      $start_year = date('Y-m-d', $start_fiscal_year);
      $end_year = date('Y-m-d', $end_fiscal_year);

      if ($type == 'today') {
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereDate('payment_date', $today)
          ->get();

        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->whereDate('payment_date', $today)->sum('payment');
      } else if ($type == 'next_day') {
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereDate('payment_date', $tomorrow)
          ->get();

        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->whereDate('payment_date', $tomorrow)->sum('payment');
      } else if ($type == 'this_week') {
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereBetween('payment_date', $this_week)
          ->get();

        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->whereBetween('payment_date', $this_week)->sum('payment');
      } else if ($type == 'this_month') {
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereMonth('payment_date', date('m'))
          ->whereYear('payment_date', $year)
          ->get();

        $total_approved = DB::table('payment')->where('status', 'approved')->where('company', session('company_id'))->where('active', 'yes')->whereMonth('payment_date', date('m'))->whereYear('payment_date', date('Y'))->sum('payment');
      } else if ($type == 'this_year') {
        $approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereBetween('payment_date', [$start_year, $end_year])
          ->get();

        $total_approved = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'approved')
          ->where('payment.active', 'yes')
          ->whereBetween('payment_date', [$start_year, $end_year])->sum('payment.payment');
      }

      if ($approved) {
        $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

        $bank_detail = DB::table('bank_detailes')->get();
        $staff = DB::table('staff')
          ->join('users', 'users.user_id', 'staff.sid')
          ->select('staff.*', 'users.id as user_id')
          ->get();
        $received = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'received')
          ->where('payment.active', 'yes')
          ->get();
        $deposited = DB::table('payment')
          ->join('clients', 'clients.id', 'payment.client_id', 'clients.case_no')
          ->select('payment.*', 'clients.client_name', 'clients.case_no')
          ->where('payment.company', session('company_id'))
          ->where('payment.status', 'deposited')
          ->where('payment.active', 'yes')
          ->get();
        foreach ($received as $rc) {
          $rc->client_case_no = $this->get_client_case_no_by_id($rc->client_id);
        }
        foreach ($deposited as $dp) {
          $dp->client_case_no = $this->get_client_case_no_by_id($dp->client_id);
        }
        foreach ($approved as $ap) {
          $ap->client_case_no = $this->get_client_case_no_by_id($ap->client_id);
          $ap->deposite_bank_name = DB::table('bank_detailes')->where('id', $ap->deposit_bank)->value('bankname');
          $ap->approved_by_name = DB::table('staff')->where('sid', $ap->approved_by)->value('name');
        }
        $rc_out = '';
        $dp_out = '';
        $ap_out = '';
        $total_received = DB::table('payment')->where('status', 'received')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');
        $total_deposited = DB::table('payment')->where('status', 'deposited')->where('company', session('company_id'))->where('active', 'yes')->sum('payment');

        $rc_out .= '
        <div class="body">
                                <h5><b>Total received payment : <span
                                            class="total_rc_h4">' . number_format($total_received, 2) . '</span></b></h5>
                            </div>

        <div class="table-responsive">                            
                        <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Client Name</th>
                                    <th>Payment</th>
                                    <th>TDS</th>
                                    <th>Payment Date</th>
                                    <th>Mode of Payment</th>
                                    <th>Cheque No</th>
                                    <th>Deposite Date</th>
                                    <th>Deposite Bank</th>
                                    <th>Deposite By</th>
                                </tr>
                            </thead>
                            <tbody class="">';
        foreach ($received as $rc) {
          $rc_out .= '<tr>
                                              <td></td>
                                            <td></td>
                                            <td>';
          if ($rc->mode_of_payment == 'cash' || $rc->mode_of_payment == 'cheque') {
            $rc_out .= '<div class="payment-action">
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1 create_deposit_btn" data-tooltip="Deposite">
                                                            <i class="bx bxs-receipt"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 deposit_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                      </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_deposit_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>';
          } else if ($rc->mode_of_payment == 'online') {
            if (session('role_id') == 1 || session('role_id') == 3) {
              $rc_out .= '<div class="payment-action">
                                                      <button data-id="' . $rc->id . '"
                                                            class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                            <i class="bx bx-money"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                            style="display:none;" data-tooltip="Done">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                    <button data-id="' . $rc->id . '"
                                                            class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                            style="display:none;" data-tooltip="Close">
                                                            <i class="bx bx-x"></i>
                                                    </button>';
            }
          }
          $rc_out .= '<button data-id="' . $rc->id . '"
                                                        class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>' . $rc->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($rc->payment, 2) . '</td>
                                            <td>' . $rc->tds . '</td>
                                            <td data-sort="' . strtotime($rc->payment_date) . '">' . date('d-m-Y', strtotime($rc->payment_date)) . '</td>
                                            <td>' . $rc->mode_of_payment . '</td>
                                            <td>' . $rc->cheque_no . '</td>
                                            <td>
                                                <div class="depo_dt_data">';
          if ($rc->deposit_date != '') {
            $rc_out .= date('d-m-Y', strtotime($rc->deposit_date));
          }
          $rc_out .= '</div>
                                                <div class="depo_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker deposit_date"
                                                        placeholder="Deposit date">
                                                    <span class="valid_err deposit_date_err"></span>
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px"
                                                        class="form-control datepicker approve_date"
                                                        placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_bank_data">' . $rc->deposit_bank . '</div>
                                                <div class="depo_bank_ui" style="display:none">
                                                    <select class="form-control required deposit_bank" name="deposit_bank"
                                                        id="deposit_bank">
                                                        <option value="">---Select deposit bank---</option>';
          foreach ($bank_detail as $bank) {
            if ($bank->default_bank_account == 'yes') {
              $rc_out .= '<option value="' . $bank->id . '" selected>' . $bank->bankname . '</option>';
            } else {
              $rc_out .= '<option value="' . $bank->id . '" >' . $bank->bankname . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_bank_err"></span>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="depo_by_data">' . $rc->deposited_by . '</div>
                                                <div class="depo_by_ui" style="display:none">
    
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';
          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                                <div class="apr_by_ui" style="display:none">

                                                    <select class="form-control required approve_by" name="approve_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (
              session('role_id') != 1 &&
              (session('user_id') == $stf->user_id)
            ) {
              $rc_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }

          if (session('role_id') == 1) {
            $rc_out .= '<option value="">---Select approve by---</option>';
            foreach ($staff as $stf) {
              $rc_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $rc_out .= '</select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }

        $rc_out .= '</tbody>
                                </table>                            
                        </div>';
        $dp_out .= '
        <div class="body">
                                <h5><b>Total deposited payment: <span
                                            class="total_dp_h4">' . number_format($total_deposited, 2) . '</span></b></h5>
                            </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>
    
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Approve date</th>
                                            <th>Approve by</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($deposited as $dp) {
          $dp_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>';
          if (session('role_id') == 1 || session('role_id') == 3) {
            $dp_out .= '<div class="payment-action">
                                                <button data-id="' . $dp->id . '"
                                                    class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                    <i class="bx bx-money"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn"
                                                    style="display:none;" data-tooltip="Done">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            <button data-id="' . $dp->id . '"
                                                    class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn"
                                                    style="display:none;" data-tooltip="Close">
                                                    <i class="bx bx-x"></i>
                                                </button>';
          }
          $dp_out .= '<button data-id="' . $dp->id . '"
                                                class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-id="' . $dp->id . '" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                                </div>
                                            </td>
                                            <td><span>' . $dp->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($dp->payment, 2) . '</td>
                                            <td>' . $dp->tds . '</td>
                                            <td data-sort="' . strtotime($dp->payment_date) . '">' . date('d-m-Y', strtotime($dp->payment_date)) . '</td>
                                            <td>' . $dp->mode_of_payment . '</td>
                                            <td>' . $dp->cheque_no . '</td>
                                            <td>' . $dp->reference_no . '</td>
                                            <td>
                                                <div>
                                                <div class="apr_dt_data">';
          if ($dp->deposit_date != '') {
            date('d-m-Y', strtotime($dp->deposit_date));
          }
          $dp_out .= '</div>
                                          </div>
                                              <div class="apr_dt_ui" style="display:none">
                                                  <input type="text" style="top: 461.4px"
                                                      class="form-control datepicker approve_date"
                                                      placeholder="approve_date">
                                                  <span class="valid_err approve_date_err"></span>
                                              </div>
                                            </td>
                                            <td>
                                                <div class="apr_by_data">' . $dp->approved_by . '</div>
    
                                                <div class="apr_by_ui" style="display:none">
                                                    <select class="form-control required deposit_by" name="deposit_by"
                                                        style="width:100%">';

          foreach ($staff as $stf) {
            if (session('role_id') != 1 && (session('user_id') == $stf->user_id)) {
              $dp_out .= '<option value="' . $stf->sid . '" selected>' . $stf->name . '</option>';
            }
          }
          if (session('role_id') == 1) {
            $dp_out .= '<option value="">---Select deposit by---</option>';
            foreach ($staff as $stf) {
              $dp_out .= '<option value="' . $stf->sid . '">' . $stf->name . '</option>';
            }
          }
          $dp_out .= '</select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>';
        }
        $dp_out .= '</tbody>
                                </table>                           
                        </div>';
        $ap_out .= '
        <div class="body">
                                <h5><b>Total approved payment : <span
                                            class="total_ap_h4">' . number_format($total_approved, 2) . '</span></b></h5>
                            </div>
        <div class="action-dropdown-btn d-none">
        <div class="dropdown payment-filter-action">
            <button class="btn border dropdown-toggle mr-1" type="button"
                id="payment-filter-btn" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="selection">Filter Payment</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="payment-filter-btn">
                <a type="button" href="javascript:void(0);"
                    class="dropdown-item filter_approve_btn" data-value="today">Today</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="next_day">Next
                    Day</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_week">This
                    Week</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_month">This Month</a>
                <a class="dropdown-item filter_approve_btn" href="javascript:void(0);"
                    data-value="this_year">This
                    Year</a>
            </div>
        </div>
    </div>
        <div class="table-responsive">                            
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>payment Date</th>
                                            <th>Mode of payment</th>
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Narration</th>
                                            <th>Deposite Bank</th>
                                            <th>Approved By</th>
                                            <th>Approved Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">';
        foreach ($approved as $ap) {
          $ap_out .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                <a href="payment_reciept-' . $ap->id . '"
                                                      class="payment-action-receipt btn btn-icon rounded-circle btn-warning mr-1 mb-1 " data-tooltip="Payment Receipt">
                                                      <i class="bx bx-printer"></i>
                                                    </a>
    
                                                    <a href="#"
                                                        class="payment-action-delete btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_payment" data-id="' . $ap->id . '" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span>' . $ap->client_case_no . '
                                                    </span></td>
                                            <td>' . number_format($ap->payment, 2) . '</td>
                                            <td>' . $ap->tds . '</td>
                                            <td data-sort="' . strtotime($ap->payment_date) . '">
                                                ' . date('d-m-Y', strtotime($ap->payment_date)) . '
                                            </td>
                                            <td>' . $ap->mode_of_payment . '</td>
                                            <td>' . $ap->cheque_no . '</td>
                                            <td>' . $ap->reference_no . '</td>
                                            <td>' . $ap->narration . '</td>
                                            <td>' . $ap->deposite_bank_name . '</td>
                                            <td>' . $ap->approved_by_name . '</td>
                                            <td>' . date('d-m-Y', strtotime($ap->approve_date)) . '</td>
                                        </tr>';
        }
        $ap_out .= '</tbody>
                                </table>                                                    
                    </div>
                ';

        return json_encode(array('status' => 'success', 'rc_out' => $rc_out, 'dp_out' => $dp_out, 'ap_out' => $ap_out, 'msg' => 'payment deposited'));
      }
    } catch (QueryException $e) {

      Log::error($e->getMessage());

      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
    }
  }

//   public function payment_reciept($id)
//   {
//     try {

//       $data = DB::table('payment')
//         ->join('clients', 'payment.client_id', 'clients.id')
//         ->select('payment.*', 'clients.client_name', 'clients.address', 'clients.city')
//         ->where('payment.id', $id)->get();
//       require_once base_path('vendor/autoload.php');

//       $html = "<style>
//                 body{
//                     font-family: 'Ubuntu', sans-serif;
//                 }
//                 .main {
                   
//                     margin:15;
                   
                   
//                 }
//                 .head{
//                     background-color:#000;
//                     padding:2px 10px 2px 10px;
//                     overflow:hidden;
//                 }
//                 .logo{
//                     float:left;
//                 }
//                 .logo img{
//                     width: 80px;
//                     margin-top: 0px;
//                 }
//                 .add{
//                     float:right;
//                 }
              
//                 .main h4{
//                     text-align:center;
//                     margin: 0px 0px 0px 0px;
//                     font-size: 20px;
//                 }
//                 p.signtext{
//                     text-align: right;
//                     margin-left: 30px;
//                     margin-bottom:0px;
//                     margin-top:0px;
//                 }
                
//                 .footer{
//                     margin-top:40px;
//                 }
//                 .footer p{
//                     font-size:13px;
//                     text-align:center;
//                     border-bottom: 1px solid #faa41a;
//                     padding-bottom:5px;
//                     margin:0px;
//                 }
//                 .footer ul{
//                     margin: 6px 0px 0px 0px;
//                 }
//                 .footer ul li{
//                     display:inline-block;
//                     font-size:13px;
//                 }
//                 .footer li{
//                     padding-right:24px;
//                 }
//                 .footer li i{
//                     color:#d58504;
//                 }
//                 .abc {
//                     border-collapse: collapse;
//                 }
//                 .abc th, td {
//                     padding: 6px;
//                     text-align: left;
//                     border: 1px solid #ddd;
//                 }
//                 .footer-tbl
//                 {
//                     border-collapse: collapse;
//                     margin-bottom:30;
//                 }
//                 #leftbox { 
//                     float:left;  
                   
//                     width:50%; 
                    
//                 } 
                
//                 #rightbox{ 
//                     float:right; 
                 
//                     width:50%; 
                    
//                 } 
               
//                 </style>";
//       foreach ($data as $row) {
//         $jd = gregoriantojd(date('m', strtotime($row->payment_date)), date('d', strtotime($row->payment_date)), date('Y', strtotime($row->payment_date)));
//         $month_name = jdmonthname($jd, 0);
//         $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
//         $receipt_no = $short_code . '/' . date('Y', strtotime($row->payment_date)) . '/' . str_pad($row->receipt_no, 4, '0', STR_PAD_LEFT);
//         // $receipt_no = 'PRC' . '-' . str_pad($row->id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
//         if (is_numeric($row->city)) {
//           $city = DB::table('city')->where('id', $row->city)->value('city_name');
//         } else {
//           $city = $row->city;
//         }

//         $html .= "<body>
//                     <table class='head' width='100%'>
//                     <tr>
//                     <td class='logo' style='border:none' width='75%'>";
//         if (session('company_id') == 3) {
//           $html .= "<img width='150px' src='" . session('company_logo') . "'>";
//         } else {
//           $html .= "<img width='80px' src='" . session('company_logo') . "'>";
//         }

//         $html .= "</td>
//         <td  class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
//                     <p style='color:#fff;
//                     padding-left: 10px;
//                     margin: 10px 0px 0px 0px;
//                     font-size: 15px;'>
//                     " . session('company_address') . "
//                     </p>
//                 </td>
//                     </tr>
                           
//                         </table>
//                     <div class='main'>
                    
                  
                    
//                       <h4> Receipt</h4>
//                       <table width='100%' style='border:none'>
//                         <tr style='height:4px'>
//                         <td style='border:none;font-family: 'Ubuntu',sans-serif;'>Receipt No. <strong>$receipt_no</strong></td>
//                          <td style='border:none' align='right'>Date: <strong>" . date('d', strtotime($row->payment_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->payment_date)) . "</strong></td>
//                         </tr>
//                       </table>
//                       <table width='100%' style='border:none'>
//                         <tr>
//                             <td style='border:none'>Received From: <strong>$row->client_name</strong>, $row->address, $city</td>
//                         </tr>
//                       </table>
                      
                      
//                       <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
//                         <tr>
//                           <th style='text-align:center;'>S. No.</th>
//                           <th style='text-align:center;'>Particulars</th>
//                           <th style='text-align:center;'>Amount</th>
//                         </tr>";
//         $total_amt = 0;
//         $c = 1;
//         $bill_html = '';
//         if ($row->bill_id != '') {
//           $bill_id_array = json_decode($row->bill_id);
//           $bill_amt = json_decode($row->bill_amt);
//           $tds_amt = $row->tds;
//           $bill = DB::table('bill')->whereIn('id', $bill_id_array)->get();
//           $a = 0;

//           foreach ($bill as $bi) {
//             $total_amount = DB::table('bill')->where('id', $bill_id_array[$a])->value('total_amount');
//             $paid_bill = $bill_amt[$a] + $tds_amt;
//             $bill_no = $short_code . '/' . date('Y', strtotime($bi->bill_date)) . '/' . str_pad($bi->invoice_no, 4, '0', STR_PAD_LEFT);
//             $desc=$bi->description;
//             if ($paid_bill < $total_amount) {

//               $bill_html .= "<tr><td>" . $bill_no . "</td><td>Partial</td></tr>";
//             } else {
//               $bill_html .= "<tr><td>" . $bill_no . "</td><td>Full</td></tr>";
//             }

//             $services_arr = json_decode($bi->service);
//             $quotation_array = json_decode($bi->quotation);
//             //$bill_amt_array=json_encode($bi->amount);

//             $total_amt += $bill_amt[$a];
//             $service = '';
//             if ($services_arr != '') {
//               for ($i = 0; $i < sizeof($services_arr); $i++) {

//                 $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');

//                 $service .= $ser . '<br>';
//               }
//             } else {
//               for ($i = 0; $i < sizeof($quotation_array); $i++) {
//                 $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
//                 $ser = DB::table('services')->where('id', $service_id)->value('name');

//                 $service .= $ser . '<br>';
//               }
//             }


//             $html .= "<tr>
//                               <td style='text-align:center;'>" . $c++ . "</td>
//                               <td style='text-align:left;font-family:hindi'>" .$service .$desc."</td>
//                               <td style='text-align:right;'>" . number_format($bill_amt[$a], 2) . "</td>
//                             </tr>";

//             $a++;
//             $seal = $bi->seal;
//             $sign = $bi->sign;
//             $sign_name = DB::table('staff')->where('sid', $sign)->value('name');
//             $sign_name = str_replace(" ", "_", $sign_name);
//             $image_path = 'images/invoice_img/sign/' . $bi->seal . '_' . $sign_name . '.png';
//           }
//         } else {
//           $html .= "<tr>
//                             <td style='text-align:center;'>" . $c++ . "</td>
//                             <td style='text-align:left;font-family:hindi'></td>
//                             <td style='text-align:right;'>" . number_format($row->payment, 2) . "</td>
//                           </tr>";
//           $total_amt = $row->payment;
//         }
//         $html .= "<tr>
//                           <td>&nbsp;</td>
//                           <td style='text-align:right;'><strong>Total</strong></td>
//                           <td style='text-align:right;'><strong>" . number_format($total_amt, 2) . "</strong></td>
//                         </tr>
//                         <tr>
//                           <td style='text-align:center;'><strong>In  Words</strong></td>
//                           <td colspan='2'><strong>" . $this->displaywords($total_amt) . "</strong></td>
//                         </tr>
//                       </table>
                      
                     
                      
                     
//                       <div id='leftbox'>
//                       <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
//                           <tr>
//                           <th style='text-align:center;' colspan='3'>Mode</th>
//                         </tr>
//                         <tr>
//                           <td width='20%'>Cash</td>
//                           <td width='20%'>
//                               <center>
//                                 <form action=''>";
//         if ($row->mode_of_payment == 'cash') {
//           $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
//         } else {
//           $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
//         }

//         $html .= "</form>
//                               </center>
//                           </td>
//                           <td></td>
//                         </tr>
//                         <tr>
//                           <td>Cheque</td>
//                           <td>
//                               <center>
//                                 <form action=''>";
//         if ($row->mode_of_payment == 'cheque') {
//           $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
//         } else {
//           $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
//         }
//         $html .= "</form>
//                               </center>
//                           </td>
//                           <td><strong>$row->cheque_no</strong></td>
//                         </tr>
//                         <tr>
//                           <td>Online</td>
//                           <td>
//                               <center>
//                                 <form action=''>";
//         if ($row->mode_of_payment == 'online') {
//           $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
//         } else {
//           $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
//         }
//         $html .= "</form>
//                               </center>	
//                           </td>
//                           <td><strong>$row->reference_no</strong></td>
//                         </tr>
//                       </table><br>
                      
                      
//                       <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>";

//         $html .= "<tr><th>Bill No</th><th>payment</th></tr>";
//         $html .= $bill_html;
//         $html .= "</table><br>


//                       </div>
//                       <div id='rightbox'><p class='signtext' ><img src='" . base_path($image_path) . "' width='150px'></p></div>
//                       <p class='signtext'>Authorised Signature</p></div>
//                       </div>
                      
                      
                      
//                     </div>
//                     </body>
//                     ";
//       }

//       $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
//       $fontDirs = $defaultConfig['fontDir'];

//       $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
//       $fontData = $defaultFontConfig['fontdata'];
//       $mpdf = new \Mpdf\Mpdf([
//         'mode' => 'utf-8', 'format' => [210, 190],
//         'tempDir' => base_path('upload'),
//         'fontDir' => array_merge($fontDirs, [
//           base_path('fonts')
//         ]),
//         'fontdata' => $fontData + [
//           'hindi' => [
//             'R' => '',

//           ],
//         ],
//       ]);

//       $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);
//       $mpdf->SetDisplayMode('fullpage');
//       $footer = "<div class='footer'>";
//       if (session('head_office') != "") {
//         $footer .= "<p>Head Office : " . session('head_office') . ", Our Branches:  " . session('company_branch') . "</p>";
//       }


//       $footer .= "<table class='footer-tbl'>
//                   <tr>";
//       if (session('company_contact') != "") {
//         $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>";
//       }
//       if (session('company_email') != "") {
//         $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>";
//       }
//       if (session('website_url') != "") {
//         $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>";
//       }
//       if (session('facebook_url') != "") {
//         $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>";
//       }
//       if (session('youtube_url') != "") {
//         $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>";
//       }
//       $footer .= "</tr>
//              </table>
//               </div>";
//       $mpdf->SetHTMLFooter($footer);
//       $mpdf->WriteHTML($html);

//       $mpdf->Output();
//     } catch (QueryException $e) {

//       Log::error($e->getMessage());

//       return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
//     } catch (Exception $e) {
//       Log::error($e->getMessage());
//       return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
//     }
//   }
   public function get_card_payment(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'month' => 'required|numeric',
            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            $month=$request->month;
            $year=date('Y');
            $approved=DB::table('payment')->whereMonth('payment_date',$month)->whereYear('payment_date',$year)->where('active','yes')->where('status','approved')->sum('payment');
            $received=DB::table('payment')->whereMonth('payment_date',$month)->whereYear('payment_date',$year)->where('active','yes')->where('status','!=','approved')->sum('payment');
            $approved=$this->IND_money_format($approved);
            $received=$this->IND_money_format($received);
            $data=array('approved'=>$approved,'received'=>$received);
            return response()->json(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    
     public function payment_reciept($id)
{
  
    try {
        $data = DB::table('payment')
            ->join('clients', 'payment.client_id', '=', 'clients.id')
            ->select('payment.*', 'clients.client_name', 'clients.address', 'clients.city')
            ->where('payment.id', $id)
            ->first();

        if (!$data) {
            return response()->json(['status' => 'error', 'msg' => 'Payment not found']);
        }

        $jd = gregoriantojd(date('m', strtotime($data->payment_date)), date('d', strtotime($data->payment_date)), date('Y', strtotime($data->payment_date)));
        $month_name = jdmonthname($jd, 0);
        $short_code = DB::table('company')->where('id', $data->company)->value('short_code');
        $receipt_no = $short_code . '/' . date('Y', strtotime($data->payment_date)) . '/' . str_pad($data->receipt_no, 4, '0', STR_PAD_LEFT);

        $city = is_numeric($data->city) ? DB::table('city')->where('id', $data->city)->value('city_name') : $data->city;

        $bill_html = '';
        $total_amt = 0;
        $image_path = '';
        if ($data->bill_id != '') {
            $bill_id_array = json_decode($data->bill_id);
            $bill_amt = json_decode($data->bill_amt);
            $tds_amt = $data->tds;
            $bills = DB::table('bill')->whereIn('id', $bill_id_array)->get();
            $a = 0;

            foreach ($bills as $bill) {
                $total_amount = $bill->total_amount;
                $paid_bill = $bill_amt[$a] + $tds_amt;
                $bill_no = $short_code . '/' . date('Y', strtotime($bill->bill_date)) . '/' . str_pad($bill->invoice_no, 4, '0', STR_PAD_LEFT);

                $bill_html .= "<tr><td>$bill_no</td><td>" . ($paid_bill < $total_amount ? 'Partial' : 'Full') . "</td></tr>";

                $services_arr = json_decode($bill->service);
                $quotation_array = json_decode($bill->quotation);

                $service = '';
                if ($services_arr) {
                    foreach ($services_arr as $s) {
                        $service .= DB::table('services')->where('id', $s)->value('name') . '<br>';
                    }
                } elseif ($quotation_array) {
                    foreach ($quotation_array as $q) {
                        $service_id = DB::table('quotation_details')->where('id', $q)->value('task_id');
                        $service .= DB::table('services')->where('id', $service_id)->value('name') . '<br>';
                    }
                }

                $data->services[] = [
                    'name' => $service . $bill->description,
                    'amount' => number_format($bill_amt[$a], 2)
                ];

                $total_amt += $bill_amt[$a];

                $sign_name = str_replace(" ", "_", DB::table('staff')->where('sid', $bill->sign)->value('name'));
               

                if(session('company_id')==10)
                {
                  $image_path = 'images/invoice_img/sign/UT_uma_tripathi.png';
                }
                else
                {
                  $image_path = 'images/invoice_img/sign/' . $bill->seal . '_' . $sign_name . '.png';
                }
                $a++;
            }
        } else {
            $data->services[] = [
                'name' => '',
                'amount' => number_format($data->payment, 2)
            ];
            $total_amt = $data->payment;
        }
       
        // Convert amount to words
        $amount_in_words = $this->displaywords($total_amt);

        // Load Blade View as HTML
             $footer = "<div class='footer'>";
      if (session('head_office') != "") {
        $footer .= "<p>Head Office : " . session('head_office') . ", Our Branches:  " . session('company_branch') . "</p>";
      }


      $footer .= "<table class='footer-tbl'>
                  <tr>";
      if (session('company_contact') != "") {
        $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>";
      }
      if (session('company_email') != "") {
        $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>";
      }
      if (session('website_url') != "") {
        $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>";
      }
      if (session('facebook_url') != "") {
        $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>";
      }
      if (session('youtube_url') != "") {
        $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>";
      }
      $footer .= "</tr>
             </table>
               </div>";
          if(session('header_footer')=='yes')
          {
           $page_name='pages.payments.'.$short_code.'_receipt';
          $footer_page='pages.payments.'.$short_code.'_footer';
          }
          else
          {
            $page_name='pages.payments.receipt';
            $footer_page='pages.payments.footer';
          }
         
        $html = view($page_name, compact(
            'data',
            'receipt_no',
            'month_name',
            'city',
            'total_amt',
            'bill_html',
            'amount_in_words',
            'image_path'
        ))->render();
          
        // mPDF Setup
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 'format' => [210, 190],
            'tempDir' => base_path('upload'),
            'fontDir' => array_merge($fontDirs, [base_path('fonts')]),
            'fontdata' => $fontData + ['hindi' => ['R' => '']]
        ]);

        $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetHTMLFooter(view($footer_page)->render());
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json(['status' => 'error', 'msg' => 'Something went wrong']);
    }
}
}
