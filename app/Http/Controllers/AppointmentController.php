<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;
use App\Traits\StaffTraits;
use App\Traits\ClientTraits;
use App\Traits\ExpenseTraits;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    use StaffTraits;
    use ClientTraits;
    use ExpenseTraits;
    public function appointment_list(Request $request)
    {
        // $client_id=array(6828,6829,6830,6831,6832,6833,6834,6835,6836,6837,6838,6839,6840,6841,6842,6843,6844,6846,6847,6848,6849,6850,6851,6852,6853,6854,6855,6856,6857,6859);
        // $case_prefix='TDS/LEAD/2024/';
        // $case_start=420;
        
        // for($i=0;$i<sizeof($client_id);$i++)
        // {
        //     $no=str_pad($case_start, 5, '0', STR_PAD_LEFT);
        //     $case_no = $case_prefix . $no;
        //     log::info('client_id='.$client_id[$i].' case no='.$case_no);
        //     $update=DB::table('clients')->where('id',$client_id[$i])->update(['case_no'=>$case_no,'default_company'=>2]);
        //     if($update)
        //     {
        //         $up=DB::table('client_company_mapping')->where('client_id',$client_id[$i])->update(['company'=>2]);
        //     }
        //     $case_start++;
        // }
        // $data=DB::table('clients')->where('case_no','like','TDS/LEAD/2024/%')->where('default_company',2)->orderBY('id')->get();
        // $case_prefix='TDS/LEAD/2024/';
        
        // $case_start=1;
       
        // foreach($data as $row)
        // {
        //   $no=str_pad($case_start, 5, '0', STR_PAD_LEFT);
        //     $case_no = $case_prefix . $no;
        //     log::info('client_id='.$row->id.' case no='.$case_no);
        //     $update=DB::table('clients')->where('id',$row->id)->update(['case_no'=>$case_no]);
        //     $case_start++;
        // }
     
        try {
            if (!$request->wantsJson()) {
                if (session('username') == "") {
                    return redirect('/')->with('alert-danger', "Please login First");
                }
                $company = session('company_id');
            } else {
                $v = Validator::make($request->all(), ['company_id' => 'numeric|required']);

                if ($v->fails()) {
                    return $v->errors();
                }
                $company = $request->company_id;
            }
            $appointmentsData = DB::table('appointment')
                ->join('clients', 'clients.id', '=', 'appointment.client')
                ->leftjoin('appointment_places', 'appointment_places.id', '=', 'appointment.place')
                ->join('staff', 'staff.sid', '=', 'appointment.meeting_with')
                ->select('clients.id as client_ids', 'clients.client_name as cname', 'clients.default_company', 'clients.case_no', 'appointment_places.name as aname', 'appointment_places.charges', 'staff.name as meetname', 'appointment.*')
                ->orderBy('appointment.meeting_date', 'desc')
                ->get();

            if ($request->wantsJson()) {
                foreach ($appointmentsData as $row) {
                    $row->scheduled_by_staff = DB::table('staff')->where('sid', $row->schedule_by)->value('name');
                    $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                    if ($row->status == 'finalize') {
                        $row->link = 'consulting_fee_reciept-' . $row->id;
                    } else {
                        $row->link = '';
                    }
                }
                return response()->json(array('status' => 'success', 'data' => $appointmentsData));
            } else {
                foreach ($appointmentsData as $row) {
                    $row->scheduled_by_staff = DB::table('staff')->where('sid', $row->schedule_by)->value('name');
                    $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                }
                $appointment_places = DB::table('appointment_places')->get();
                $staff = DB::table('staff')->get();
                $bank = DB::table('bank_detailes')->get();
                return view('pages.appointments.appointment-list', compact('appointmentsData', 'bank', 'appointment_places', 'staff'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function get_appointment_by_status(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', "Please login First");
            }
            $input = $request->pass_data;
            $status = $input['status'];

            $appointmentsData = DB::table('appointment')
                ->join('clients', 'clients.id', '=', 'appointment.client')
                ->leftJoin('appointment_places', 'appointment_places.id', '=', 'appointment.place')
                ->join('staff', 'staff.sid', '=', 'appointment.meeting_with')
                ->select('appointment.*', 'clients.client_name as cname', 'clients.case_no', 'clients.id as client_id', 'appointment_places.name as aname', 'appointment_places.charges', 'staff.name as meetname')->orderBy('appointment.meeting_date', 'desc');
            if ($input['meeting_with'] != 'all') {
                if ($input['meeting_with'] != '') {
                    $appointmentsData = $appointmentsData->where('meeting_with', $input['meeting_with']);
                }
                if ($input['schedule_by'] != '') {
                    $appointmentsData = $appointmentsData->where('schedule_by', $input['schedule_by']);
                }
                if ($input['from_date'] != '' && $input['to_date'] != '') {
                    $from_date =  date('Y-m-d', strtotime(str_replace('/', '-', $input['from_date'])));
                    $to_date =  date('Y-m-d', strtotime(str_replace('/', '-', $input['to_date'])));
                    $appointmentsData = $appointmentsData->whereBetween('meeting_date', [$from_date, $to_date]);
                }
                if ($input['meeting_type'] != '') {
                    $free = 0;
                    if ($input['meeting_type'] == 'free') {
                        $appointmentsData = $appointmentsData->where('appointment_places.charges', $free);
                    } else {
                        $appointmentsData = $appointmentsData->where('appointment_places.charges', '!=', $free);
                    }
                }
            }
            if ($input['status'] != '') {
                $appointmentsData = $appointmentsData->where('appointment.status', $input['status']);
            }
            $appointmentsData = $appointmentsData->where('appointment.company', session('company_id'))
                ->get();

            foreach ($appointmentsData as $item) {
                $item->client_case_no = $this->get_client_case_no_by_id($item->client_id);
                $item->payment_mode = DB::table('consulting_fee')->where('appointment_id', $item->id)->value('payment_mode');
                $item->scheduled_by_staff = DB::table('staff')->where('sid', $item->schedule_by)->value('name');
            }

            return view('pages.appointments.get_appointments', compact('appointmentsData', 'status'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }


    public function appointment_add(Request $request)
    {
        if (session('username') == "") {
            return redirect('/')->with('alert-danger', "Please login First");
        }
        try {
            $client_id = $request->id;
            if ($client_id == '') {
                $client_id = '';
            }
            $clients = DB::table('clients')->get();
            $appointment_places = DB::table('appointment_places')->get();
            $staff = $this->get_staff_list_userid_company();
            return view('pages.appointments.appointment-add', compact('client_id', 'clients', 'appointment_places', 'staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function fetch_appointment(Request $request)
    {
        try {
            $data = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->join('staff', 'staff.sid', 'appointment.schedule_by')
                ->select('appointment.*', 'clients.client_name', 'staff.name')
                ->where('appointment.meeting_date', date('Y-m-d'))
                ->where('appointment.company', session('company_id'))
                ->get();

            $time_arr = array_column(json_decode($data, true), 'meeting_time');
            $timestamps = array_map('strtotime', $time_arr);
            array_multisort($timestamps, $time_arr);

            $time_wise = collect($time_arr);

            $data = $time_wise->map(function ($time) use ($data) {
                return $data->where('meeting_time', $time)->first();
            });

            foreach ($data as $row) {
                $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                $row->meeting_with_name = DB::table('staff')->where('sid', $row->meeting_with)->value('name');
                $row->place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                $fee_id = DB::table('consulting_fee')->where('appointment_id', $row->id)->value('id');
                $row->payment_mode = DB::table('consulting_fee')->where('id', $fee_id)->value('payment_mode');
                $row->cheque_no = DB::table('consulting_fee')->where('id', $fee_id)->value('cheque_no');
                $row->reference = DB::table('consulting_fee')->where('id', $fee_id)->value('reference');
                $row->amount = DB::table('consulting_fee')->where('id', $fee_id)->value('fees');
            }
            return response()->json(array('status' => 'success', 'appointments' => $data));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function fetch_date_wise_appointment(Request $request)
    {
        try {

            $v = Validator::make($request->all(), [
                'date' => 'required|date_format:Y-m-d',
                'company_id' => 'required|numeric',

            ]);
            if ($v->fails()) {
                return $v->errors();
            }
            $date = $request->date;
            $company_id = $request->company_id;
             $json = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->join('staff', 'staff.sid', 'appointment.schedule_by')
                ->select('appointment.*', 'clients.client_name', 'clients.id as client_id', 'clients.case_no', 'staff.name')
                ->where('appointment.meeting_date', $date)
                ->get();

                foreach($json as $jso)
                {
                      $jso->time=str_replace(' : ',':',$jso->meeting_time);
                }
               
                $data = json_decode($json);
                  $data = json_decode($json);
                    usort($data, function ($a, $b) {
                       
                        return strtotime($a->time) - strtotime($b->time);
                    });
            
              
            foreach ($data as $row) {
                $meeting_time = str_replace(' : ', ':',$row->meeting_time);
               $row->meeting_time = date('H:i', strtotime($meeting_time));
                $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                $row->meeting_with_name = DB::table('staff')->where('sid', $row->meeting_with)->value('name');
                $row->place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                $fee_id = DB::table('consulting_fee')->where('appointment_id', $row->id)->value('id');
                $row->payment_mode = DB::table('consulting_fee')->where('id', $fee_id)->value('payment_mode');
                $row->cheque_no = DB::table('consulting_fee')->where('id', $fee_id)->value('cheque_no');
                $row->reference = DB::table('consulting_fee')->where('id', $fee_id)->value('reference');
                $row->amount = DB::table('consulting_fee')->where('id', $fee_id)->value('fees');

                if ($row->status == 'finalize') {
                    $row->link= 'consulting_fee_reciept-' . $row->id;
                } else {
                    $row->link = '';
                }
            }
            return response()->json(array('status' => 'success', 'appointments' => $data));
        } catch (\Throwable $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function submit_appointment(Request $request)
    {
        if ($request->request_from!='app') {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', "Please login First");
            }
        }
        try {
            $v = Validator::make($request->all(), [

                'client' => 'required',
                'meeting_with' => 'required',
                'schedule_by' => 'required',
                'meeting_date' => 'required',
                'time' => 'required',
                'meeting_type' => 'required'

            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            log::info('appointment='.json_encode($request->all()));
          
            $client = $request->client;
            $meeting_with = $request->meeting_with;
            $schedule_by = $request->schedule_by;
            
           
            $meeting_place = $request->meeting_type;
            $online_meeting = $request->online_meeting;
            if ($request->request_from=='app') {
                
                 $company = $request->company;
                 $meeting_date = $request->meeting_date;
                 $datetime = $request->time;
                 log::info('$datetime='.$datetime);
                    $format_time = str_replace(" PM", "", $datetime);
                    $format_time = str_replace(" AM", "", $format_time);
                     log::info('$format_time='.$format_time);
                    $time= date("h : i A", strtotime($format_time));
                 
            } else {
               $company = session('company_id');
               $var = $request->meeting_date;
               $date = str_replace('/', '-', $var);
               $meeting_date = date('Y-m-d', strtotime($date));
                $time = $request->time;
            }
            $meeting_with_name = DB::table('staff')->where('sid', $meeting_with)->value('name');
            $check = DB::table('appointment')->where('meeting_with', $meeting_with)->where('meeting_date', $meeting_date)->where('meeting_time', $time)->count();
            if ($check > 0) {
                return json_encode(array('status' => 'error', 'msg' => $meeting_with_name . ' is not available at ' . $time . ' on ' . $meeting_date . ' please try with other time'));
            } else {
                $insert = DB::table('appointment')->insert([
                    'client' => $client,
                    'place' => $meeting_place,
                    'meeting_with' => $meeting_with,
                    'meeting_date' => $meeting_date,
                    'meeting_time' => $time,
                    'schedule_by' => $schedule_by,
                    'company' => $company,
                    'online_meeting' => $online_meeting
                ]);
                if ($insert) {
                    return json_encode(array('status' => 'success', 'msg' => 'Appointment fixed successfully'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Appointment can`t be fixed '));
                }
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function view_consulting_fee(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', "Please login First");
            }
            $appointment_id = $request->appointment_id;

            $data = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('consulting_fee.*', 'appointment.place', 'clients.id as client_id', 'clients.client_name')
                ->where('consulting_fee.appointment_id', $appointment_id)
                ->where('appointment.company', session('company_id'))
                ->get();

            $bank = DB::table('bank_detailes')->get();
            foreach ($data as $row) {
                $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
                $row->bankname = DB::table('bank_detailes')->where('id', $row->bank)->value('bankname');
                $row->place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
            }
            if ($data != '') {
                return view('pages.appointments.get_consulting_fees', compact('data', 'bank'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Can not get consulting fee details !!'));
            }
        } catch (\Throwable $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function update_consulting_fee(Request $request)
    {
        if (session('username') == "") {
            return redirect('/')->with('alert-danger', "Please login First");
        }
        try {
            $payment_mode = $request->payment_mode;
            $cheque_no = $request->cheque_no;
            $var = $request->cheque_date;
            $date = str_replace('/', '-', $var);
            if ($date != '') {
                $cheque_date = date('Y-m-d', strtotime($date));
            } else {
                $cheque_date = '';
            }

            $bank = $request->bank;
            $reference = $request->reference;
            $remark = $request->remark;
            $consulting_id = $request->consulting_id;

            $update = DB::table('consulting_fee')->where('id', $consulting_id)->update([
                'payment_mode' => $payment_mode,
                'cheque_no' => $cheque_no,
                'cheque_date' => $cheque_date,
                'bank' => $bank,
                'reference' => $reference,
                'remark' => $remark
            ]);

            if ($update) {
                return json_encode(array('status' => 'success'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function delete_consulting_fee(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $id = $request->id;
            $appointment_id = $request->appointment_id;
            $delete = DB::table('consulting_fee')->where('id', $id)->delete();
            if ($delete) {
                $update = DB::table('appointment')->where('id', $appointment_id)->update([
                    'status' => 'pending',
                    'updated_at' => now()
                ]);

                if ($update) {
                    return json_encode(array('status' => 'success'));
                } else {
                    return json_encode(array('status' => 'error'));
                }
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function submit_consulting_fee(Request $request)
    {
        if (session('username') == "") {
            return redirect('/')->with('status', "Please login First");
        }
        try {
            $fees = $request->fees;
            $fees = str_replace(" INR", "", $fees);

            $payment_date = $request->payment_date;
            $payment_date = str_replace('/', '-', $payment_date);
            $payment_date = date('Y-m-d', strtotime($payment_date));

            $payment_mode = $request->payment_mode;
            $cheque_no = $request->cheque_no;
            if ($payment_mode == 'cheque') {
                $var = $request->cheque_date;
                $date = str_replace('/', '-', $var);
                $cheque_date = date('Y-m-d', strtotime($date));
            } else {
                $cheque_date = '';
            }

            $bank = $request->bank;
            $reference = $request->reference;
            $remark = $request->remark;
            $appointment_id = $request->appointment_id;
            $insert = DB::table('consulting_fee')->insert([
                'appointment_id' => $appointment_id,
                'fees' => $fees,
                'payment_date' => $payment_date,
                'payment_mode' => $payment_mode,
                'cheque_no' => $cheque_no,
                'cheque_date' => $cheque_date,
                'bank' => $bank,
                'reference' => $reference,
                'remark' => $remark,

            ]);

            if ($insert) {
                $update = DB::table('appointment')->where('id', $appointment_id)->update([
                    'status' => 'finalize',
                    'updated_at' => now()
                ]);

                if ($update) {
                    return json_encode(array('status' => 'success'));
                } else {
                    return json_encode(array('status' => 'error'));
                }
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function consulting_fee_reciept(Request $request)
    {
        $id = $request->id;
        try {
            $count = DB::table('consulting_fee')->where('appointment_id', $id)->count();
            if ($count == 0) {
                return redirect()->back()->with('alert-danger', 'Not paid');
            }
            $data = DB::table('appointment')
                ->join('clients', 'appointment.client', 'clients.id')
                ->join('consulting_fee', 'appointment.id', 'consulting_fee.appointment_id')
                ->select('appointment.*', 'clients.client_name', 'clients.address', 'clients.city', 'consulting_fee.id as consulting_fee_id', 'consulting_fee.fees', 'consulting_fee.payment_date', 'consulting_fee.payment_mode', 'consulting_fee.cheque_no', 'consulting_fee.cheque_date', 'consulting_fee.reference', 'consulting_fee.remark', 'consulting_fee.bank')
                ->where('appointment.id', $id)
                ->get();

            require_once base_path('vendor/autoload.php');

            $html = "<style>
                body{
                    font-family: 'Ubuntu', sans-serif;
                }
                .main {
                   
                    margin:15;
                   
                   
                }
                .head{
                    background-color:#000;
                    padding:2px 10px 2px 10px;
                    overflow:hidden;
                }
                .logo{
                    float:left;
                }
                .logo img{
                    width: 80px;
                    margin-top: 0px;
                }
                .add{
                    float:right;
                }
              
                .main h4{
                    text-align:center;
                    margin: 0px 0px 0px 0px;
                    font-size: 20px;
                }
                p.signtext{
                    text-align: right;
                    margin-left: 30px;
                    margin-bottom:0px;
                    margin-top:0px;
                }
                
                .footer{
                    margin-top:40px;
                }
                .footer p{
                    font-size:13px;
                    text-align:center;
                    border-bottom: 1px solid #faa41a;
                    padding-bottom:5px;
                    margin:0px;
                }
                .footer ul{
                    margin: 6px 0px 0px 0px;
                }
                .footer ul li{
                    display:inline-block;
                    font-size:13px;
                }
                .footer li{
                    padding-right:24px;
                }
                .footer li i{
                    color:#d58504;
                }
                .abc {
                    border-collapse: collapse;
                }
                .abc th, td {
                    padding: 6px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .footer-tbl
                {
                    border-collapse: collapse;
                    margin-bottom:20;
                }
                #leftbox { 
                    float:left;  
                   
                    width:50%; 
                    
                } 
                
                #rightbox{ 
                    float:right; 
                 
                    width:50%; 
                    
                } 
               
                </style>";
            foreach ($data as $row) {
                $jd = gregoriantojd(date('m', strtotime($row->payment_date)), date('d', strtotime($row->payment_date)), date('Y', strtotime($row->payment_date)));
                $month_name = jdmonthname($jd, 0);
                $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
                $place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                $city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                if ($city_name == '') {
                    $city_name = $row->city;
                }
                $sign_name = DB::table('staff')->where('sid', 1)->value('name');
                $companies = DB::table("company")->where("id", $row->company)->get();

                foreach ($companies as $com) {
                    $company = strtolower($com->company_name);
                    $seal = str_replace(' ', '_', $company);
                }

                $sign_name = str_replace(" ", "_", $sign_name);
                $image_path = 'images/invoice_img/sign/' . $seal . $row->company . '_' . $sign_name . '.png';
                $html .= "
                    <body>
                    <table class='head' width='100%'>
                    <tr>
                    <td class='logo' style='border:none' width='75%'>
                    <img width='80px' src='" . session('company_logo') . "'>
                </td>
        <td  class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
                    <p style='color:#fff;
                    
                    padding-left: 10px;
                    margin: 10px 0px 0px 0px;
                    font-size: 15px;'>
                    " . session('company_address') . "
                    </p>
                </td>
                    </tr>
                           
                        </table>
                    <div class='main'>
                    
                  
                    
                      <h4> Receipt</h4>
                      <table width='100%' style='border:none'>
                        <tr style='height:3px'>
                        <td style='border:none;font-family: 'Ubuntu',sans-serif;'>Receipt No. <strong>$receipt_no</strong></td>
                         <td style='border:none' align='right'>Date: <strong>" . date('d', strtotime($row->payment_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->payment_date)) . "</strong></td>
                        </tr>
                      </table>
                      <table width='100%' style='border:none'>
                        <tr>
                            <td style='border:none'>Received From: <strong>$row->client_name</strong>, $row->address, $city_name</td>
                        </tr>
                      </table>
                      
                      
                      <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                        <tr>
                          <th style='text-align:center;'>S. No.</th>
                          <th style='text-align:center;'>Particulars</th>
                          <th style='text-align:center;'>Amount</th>
                        </tr>
                        <tr>
                          <td style='text-align:center;'>1</td>
                          <td style='text-align:center;'>" . ucwords($place_name) . " Visit - ConsultationCharges</td>
                          <td style='text-align:right;'>" . number_format($row->fees, 2) . "</td>
                        </tr>
                       
                        <tr>
                          <td>&nbsp;</td>
                          <td style='text-align:right;'><strong>Total</strong></td>
                          <td style='text-align:right;'><strong>" . number_format($row->fees, 2) . "</strong></td>
                        </tr>
                        <tr>
                          <td style='text-align:center;'><strong>In  Words</strong></td>
                          <td colspan='2'><strong>" . $this->displaywords($row->fees) . "</strong></td>
                        </tr>
                      </table>
                      
                     
                      
                     
                      <div id='leftbox'>
                      <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                          <tr>
                          <th style='text-align:center;' colspan='3'>Mode</th>
                        </tr>
                        <tr>
                          <td width='20%'>Cash</td>
                          <td width='20%'>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'cash') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }

                $html .= "</form>
                              </center>
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Cheque</td>
                          <td>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'cheque') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }
                $html .= "</form>
                              </center>
                          </td>
                          <td><strong>$row->cheque_no</strong></td>
                        </tr>
                        <tr>
                          <td>Online</td>
                          <td>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'online') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }
                $html .= "</form>
                              </center> 
                          </td>
                          <td><strong>$row->reference</strong></td>
                        </tr>
                      </table></div>
                      <div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='140px'></p>
                      <p class='signtext'>Authorised Signature</p></div>
                      </div>
                      
                      
                      
                    </div>
                    </body>
                    ";
            }

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 148]]);

            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->setHTMLFooter("<div class='footer'>
                    <p>Head Office : " . session('head_office') . ", Our Branches:  " . session('company_branch') . "</p>
                   <table class='footer-tbl'>
                        <tr>
                        <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>
                        <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>
                        <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>
                        <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>
                        <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>
                        </tr>
                   </table>
                     </div>");
            $mpdf->WriteHTML($html);

            $mpdf->Output();
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function delete_appointment(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $appointment_id = $request->appointment_id;

            $delete = DB::table('appointment')->where('id', $appointment_id)->delete();
            if ($delete) {
                $delete1 = DB::table('consulting_fee')->where('appointment_id', $appointment_id)->delete();
                return json_encode(array('status' => 'success'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function reschedule_meeting(Request $request)
    {

        $v = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'appointment_id' => 'required|numeric',
            'meeting_with' => 'required',
            'meeting_place' => 'required',


        ]);
        if ($v->fails()) {
            return $v->errors();
        }
        try {
            $time = $request->time;
            $appointment_id = $request->appointment_id;
            $var = $request->date;
            $date = str_replace('/', '-', $var);
            $meeting_date = date('Y-m-d', strtotime($date));
            $meeting_with = $request->meeting_with;
            $meeting_place = $request->meeting_place;
            $status = $request->status;
            $online_meeting = $request->online_meeting;
            if (!$request->wantsJson()) {
                $company = session('company');
            } else {
                $company = $request->company;
            }
            $meeting_with_name = DB::table('staff')->where('sid', $meeting_with)->value('name');
            $check = DB::table('appointment')->where('meeting_with', $meeting_with)->where('meeting_date', $meeting_date)->where('meeting_time', $time)->count();

            if ($check > 0) {
                if (!$request->wantsJson()) {
                    return json_encode(array('status' => 'error', 'msg' => $meeting_with_name . ' is not available at ' . $time . ' on ' . $meeting_date . ' please try with other time'));
                } else {
                    return response()->json(array('status' => 'success', 'msg' => $meeting_with_name . ' is not available at ' . $time . ' on ' . $meeting_date . ' please try with other time'));
                }
            }
            $update = DB::table('appointment')->where('id', $appointment_id)->update([
                'meeting_time' => $time,
                'meeting_date' => $meeting_date,
                'meeting_with' => $meeting_with,
                'place' => $meeting_place,
                'online_meeting' => $online_meeting,
                'company' => $company,
                'updated_at' => now()
            ]);
            if (!$request->wantsJson()) {
                if ($update) {
                    return json_encode(array('status' => 'success'));
                } else {
                    return json_encode(array('status' => 'error'));
                }
            } else {
                return response()->json(array('status' => 'success', 'msg' => 'Appointment reschedule successfully'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
    public function get_meeting_place(Request $request)
    {
        try {
            $data = DB::table('appointment_places')->get();
            return json_encode(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
    public function get_meeting_with(Request $request)
    {
        try {
            $data = $this->get_staff_list();
            return json_encode(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
    public function fetch_meeting_with_appointment(Request $request)
    {
        $v = Validator::make($request->all(), [
            'sid' => 'required|numeric'
        ]);
        if ($v->fails()) {
            return $v->errors();
        }
        try {
            $sid = $request->sid;
            $data = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->join('staff', 'staff.sid', 'appointment.schedule_by')
                ->select('appointment.*', 'clients.client_name', 'clients.id as client_id', 'clients.case_no', 'staff.name As schedule_by_name')
                ->where('appointment.meeting_with', $sid)
                ->orderBy('appointment.meeting_date')
                ->get();

            $time_arr = array_column(json_decode($data, true), 'meeting_time');
            $timestamps = array_map('strtotime', $time_arr);
            array_multisort($timestamps, $time_arr);

            $time_wise = collect($time_arr);

            $data = $time_wise->map(function ($time) use ($data) {
                return $data->where('meeting_time', $time)->first();
            });

            foreach ($data as $row) {
                $meeting_time = str_replace(' : ', ':', $row->meeting_time);
                $row->meeting_time = date('H:i', strtotime($meeting_time));
                $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                $row->meeting_with_name = DB::table('staff')->where('sid', $row->meeting_with)->value('name');
                $row->place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                $fee_id = DB::table('consulting_fee')->where('appointment_id', $row->id)->value('id');
                $row->payment_mode = DB::table('consulting_fee')->where('id', $fee_id)->value('payment_mode');
                $row->cheque_no = DB::table('consulting_fee')->where('id', $fee_id)->value('cheque_no');
                $row->reference = DB::table('consulting_fee')->where('id', $fee_id)->value('reference');
                $row->amount = DB::table('consulting_fee')->where('id', $fee_id)->value('fees');

                if ($row->status == 'finalize') {
                    $row->link = 'consulting_fee_reciept-' . $row->id;
                } else {
                    $row->link = '';
                }
            }
            return json_encode(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
    public function save_meeting_notes(Request $request)
    {
        $v = Validator::make($request->all(), [
            'appointment_id' => 'required|numeric',
            'meeting_notes' => 'required',
        ]);
        if ($v->fails()) {
            return $v->errors();
        }
        try {
            $meeting_notes = $request->meeting_notes;
            $appointment_id = $request->appointment_id;
            $update = DB::table('appointment')->where('id', $appointment_id)->update([
                'meeting_notes' => $meeting_notes
            ]);
            if ($update) {
                return response()->json(array('status' => 'success', 'msg' => 'Meeting notes save successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Meeting notes can`t be saved'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
     public function consulting_fee_reciept_UT($id)
    {
        $id = $id;
        try {
            $count = DB::table('consulting_fee')->where('appointment_id', $id)->count();
            if ($count == 0) {
                return redirect()->back()->with('alert-danger', 'Not paid');
            }
            $data = DB::table('appointment')
                ->join('clients', 'appointment.client', 'clients.id')
                ->join('consulting_fee', 'appointment.id', 'consulting_fee.appointment_id')
                ->select('appointment.*', 'clients.client_name', 'clients.address', 'clients.city', 'consulting_fee.id as consulting_fee_id', 'consulting_fee.fees', 'consulting_fee.payment_date', 'consulting_fee.payment_mode', 'consulting_fee.cheque_no', 'consulting_fee.cheque_date', 'consulting_fee.reference', 'consulting_fee.remark', 'consulting_fee.bank')
                ->where('appointment.id', $id)
                ->get();

            require_once base_path('vendor/autoload.php');

            $html = "<style>
            .logo{
                float: left;
            }
            body
                {
                    font-family: 'Ubuntu', sans-serif;
                }
                .main 
                {
                    
                    margin:15;
                }
                
                
            .add{
                float:right;
            }
                
            .main h4{
                text-align:center;
                margin: 0px 0px 0px 0px;
                font-size: 20px;
            }
            p.signtext{
                text-align: right;
                margin-left: 30px;
                margin-bottom:0px;
                margin-top:0px;
            }


            .abc {
                border-collapse: collapse;
            }
            .abc th, td {
                padding: 6px;
                text-align: left;
                border: 1px solid #ddd;
            }
            .footer {
            width: 100%;
            padding: 14px 12px;
            background-color: #000;
            }
                    .footer_tbl {
                margin: auto;
                border-collapse: collapse;
                text-align: center;
                font-family: Georgia, serif;
                color: white;
                width: 100%;
                padding: 12px 10px;
            }

            .footer_tbl td {
                padding: 5px 20px;
                border: none;
                background:black;
                color:white;
                font-size:16px;
            }

            .footer_tbl a {
                color: #fff;
                
                font-weight: bold;
            }

            .footer_tbl a:hover {
                text-decoration: underline;
            }

            .footer_tbl img.icon {
                vertical-align: middle;
                width: 20px;
                height: 20px;
                margin-right: 5px;
            }

            .footer_address {
                padding-top: 20px;
                color: white;
            }
            

                #leftbox
                { 
                float:left;  
                width:50%; 
                } 
                            
                #rightbox{ 
                    float:right; 
                    
                    width:50%; 
                    
                    
                }
                .header-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 5px solid #d4ad7f;
                padding: 10px 0;
                }
                .header-top img.logo {
                height: 50px;
                }
                .header-contact {
                
                padding-left: 15px;
                font-size: 16px;
                }
                .header-contact div {
                margin-bottom: 5px;
                }
                .header-contact img.icon {
                height: 14px;
                vertical-align: middle;
                margin-right: 5px;
                }
                </style>";
            foreach ($data as $row) {
                $jd = gregoriantojd(date('m', strtotime($row->payment_date)), date('d', strtotime($row->payment_date)), date('Y', strtotime($row->payment_date)));
                $month_name = jdmonthname($jd, 0);
                $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
                $place_charges = DB::table('appointment_places')->where('id', $row->place)->value('charges');
                $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                $city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                if ($city_name == '') {
                    $city_name = $row->city;
                }
                $sign_name = DB::table('staff')->where('sid', 1)->value('name');
                $companies = DB::table("company")->where("id", $row->company)->get();

                foreach ($companies as $com) {
                    $company = strtolower($com->company_name);
                    $seal = str_replace(' ', '_', $company);
                }

                $sign_name = str_replace(" ", "_", $sign_name);
               
                  $image_path = 'images/invoice_img/sign/UT_uma_tripathi.png';
                
                $html .= "
                       <div class='header-top'>
      <table class='header-contact' width='100%'>
        <tr>
            <td  style='border:none' width='75%'>
              <img src=".base_path(session('company_logo'))." data-holder-rendered='true' />
            </td>
            <td class='add' style='color:#fff;border:none;border-left: 2px solid #1e1d1cff' width='35%'>
                <p style='color:#000;padding-left: 10px;font-size: 15px;'>
                     <img src=".base_path('images/invoice_img/mailicon.jpg')." data-holder-rendered='true' /> legal@dearsociety.in
                </p>
                <p style='color:#000;padding-left: 10px;margin: 10px 0px 0px 0px;font-size: 15px;'><img src=".url('images/invoice_img/callicon.jpg')." data-holder-rendered='true' /> +91 7020876285</p>
            </td>
        </tr>
    </table>
</div>
                 <div class='main'>
        <h4>Receipt</h4>
                      <table width='100%' style='border:none'>
                        <tr style='height:3px'>
                        <td style='border:none;font-family: 'Ubuntu',sans-serif;'>Receipt No. <strong>$receipt_no</strong></td>
                         <td style='border:none' align='right'>Date: <strong>" . date('d', strtotime($row->payment_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->payment_date)) . "</strong></td>
                        </tr>
                      </table>
                      <table width='100%' style='border:none'>
                        <tr>
                            <td style='border:none'>Received From: <strong>$row->client_name</strong>, $row->address, $city_name</td>
                        </tr>
                      </table>
                      
                      
                      <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                        <tr>
                          <th style='text-align:center;'>S. No.</th>
                          <th style='text-align:center;'>Particulars</th>
                          <th style='text-align:center;'>Amount</th>
                        </tr>
                        <tr>
                          <td style='text-align:center;'>1</td>
                          <td style='text-align:center;'>" . ucwords($place_name) . " Visit - ConsultationCharges</td>
                          <td style='text-align:right;'>" . number_format($row->fees, 2) . "</td>
                        </tr>
                       
                        <tr>
                          <td>&nbsp;</td>
                          <td style='text-align:right;'><strong>Total</strong></td>
                          <td style='text-align:right;'><strong>" . number_format($row->fees, 2) . "</strong></td>
                        </tr>
                        <tr>
                          <td style='text-align:center;'><strong>In  Words</strong></td>
                          <td colspan='2'><strong>" . $this->displaywords($row->fees) . "</strong></td>
                        </tr>
                      </table>
                      
                     
                      
                     
                      <div id='leftbox'>
                      <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                          <tr>
                          <th style='text-align:center;' colspan='3'>Mode</th>
                        </tr>
                        <tr>
                          <td width='20%'>Cash</td>
                          <td width='20%'>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'cash') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }

                $html .= "</form>
                              </center>
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Cheque</td>
                          <td>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'cheque') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }
                $html .= "</form>
                              </center>
                          </td>
                          <td><strong>$row->cheque_no</strong></td>
                        </tr>
                        <tr>
                          <td>Online</td>
                          <td>
                              <center>
                                <form action=''>";
                if ($row->payment_mode == 'online') {
                    $html .= "<img  width='30px' src='images/invoice_img/checked.png'>";
                } else {
                    $html .= "<img  width='15px' src='images/invoice_img/unchecked.png'>";
                }
                $html .= "</form>
                              </center> 
                          </td>
                          <td><strong>$row->reference</strong></td>
                        </tr>
                      </table></div>
                      <div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='140px'></p>
                      <p class='signtext'>Authorised Signature</p></div>
                      </div>
                      
                      
                      
                    </div>
                    </body>
                    ";
            }

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 156]]);

            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->setHTMLFooter("<div class='footer'>
            <table class='footer_tbl'>
            <tr>
                <td style='text-align:right'>
                
                    <img src=". base_path('images/invoice_img/insta.jpg')." class='icon' alt='Instagram' />
                    uma_tripa
                
                </td>
                <td style='text-align:left'>
                
                    <img src=". base_path('images/invoice_img/linkdin.jpg')." class='icon' alt='LinkedIn' />
                    Adv Uma Tripathi
                
                </td>
            </tr>
            <tr >
                <td colspan='2' class='footer_address' style='text-align:center'>
                Office No 213, City Avenue, Shankar Kalat Nagar, Bangalore - Mumbai Highway, Wakad, Pimpri - Chinchwad Maharastra - 411057
                </td>
            </tr>
            </table>
            </div>");
            $mpdf->WriteHTML($html);

            $mpdf->Output();
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

}
