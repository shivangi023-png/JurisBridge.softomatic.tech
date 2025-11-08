<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\DashboardTraits;
use App\Traits\ClientTraits;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use App\Traits\NotificationTraits;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use DashboardTraits;
    use ClientTraits;
    use ExpenseTraits;
    use StaffTraits;
    use NotificationTraits;
    //ecommerce
    public function dashboardEcommerce()
    {
        return view('pages.dashboard-ecommerce');
    }
    //presales_dashboard
    public function presales_dashboard()
    {
        $enquiry  = DB::table('enquiry')
            ->orderBy('id', 'desc')->get();
        return view('pages.presales-dashboard', compact('enquiry'));
    }

    //sales_dashboard
    public function sales_dashboard()
    {
        $leadtype = DB::table('lead_type')->select('id', 'type')->get();
        $data = $this->get_sales_activity();
        return view('pages.dashboard.sales-dashboard', compact('data', 'leadtype'));
    }

    public function get_leads_details(Request $request)
    {
        try {
            $leads_data = $this->get_client_by_id($request->client_id);
            if ($leads_data) {
                return response()->json(array('status' => 'success', 'response' => $leads_data));
            } else {
                return response()->json(array('status' => 'fail', 'msg' => 'Some Error while fetch data'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        }
    }

    public function get_contact_details(Request $request)
    {
        try {
            $contacts = DB::table('client_contacts')->where('client_id', $request->id)->get();;
            return view('pages.dashboard.contacts_table', compact('contacts'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        }
    }

    // analystic
    public function dashboardAnalytics()
    {
        
     
        try {
            if (session('role_id') == '8') {
                // return $this->sales_dashboard();
                return redirect()->route('sales_dashboard');
            } else {
                //$this->send_push_notification('Hello','Testing notification',[51],$click_action='task',$icon='');

                $month = date('m'); // THIS MONTH
                $year = date('Y');
                $data = $this->get_activity();
                $leadtype = DB::table('lead_type')->select('id', 'type')->get();
                $staff = $this->get_leads_staff();
                $staff1 = DB::table('staff')->get();

                $staff_id = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $staff_id[] = $stf->sid;
                        }
                    }
                }
                $leaves = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->join('leave_table', 'staff.sid', 'leave_table.staff_id')
                    ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                    ->select('staff.name', 'leave_table.*', 'leave_type.type')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $staff_id)
                    ->where('leave_table.company', session('company_id'))
                    ->orderBy('leave_table.start_date', 'desc')
                    ->get();

                return view('pages.dashboard.dashboard-analytics', compact('leadtype', 'data', 'leaves', 'staff', 'staff1'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
            }
        }
    }

 
    public function search_client(Request $request)
    {
        $value = $request->value;
        $type = $request->type;
        $data_array = array();
        if ($type == 'client_name') {

            $data = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->select('clients.client_name', 'clients.id','clients.address')->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')
                ->where('clients.client_name', 'like', $value . '%')->orWhere('clients.client_name', 'like', '%' . $value . '%')
                ->orWhere('clients.client_name', 'like', '%' . $value)->get();
            foreach ($data as $row) {
                $client_name=$row->client_name;
                $data_array[] = array('id' => $row->id, 'value' => $client_name,'address'=>$row->address);
            }
        }
        if ($type == 'case_no') {
            $data = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->select('clients.case_no', 'clients.id')
                ->where('clients.status', 'active')
                ->where('clients.case_no', 'like', $value . '%')
                ->orwhere('clients.case_no', 'like',  '%' . $value . '%')
                ->orwhere('clients.case_no', 'like', '%' . $value)->get();
            foreach ($data as $row) {
                if ($row->case_no != '' || $row->case_no != null || $row->case_no != NULL) {

                    $data_array[] = array('id' => $row->id, 'value' => $row->case_no,'address'=>"");
                }
            }
        }
        if ($type == 'email') {
            $data = DB::table('clients')
                ->join('client_contacts', 'client_contacts.client_id', 'clients.id')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->select('client_contacts.email', 'clients.id')->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')
                ->where('client_contacts.email', 'like', $value . '%')
                ->orwhere('client_contacts.email', 'like', '%' . $value . '%')
                ->orwhere('client_contacts.email', 'like', '%' . $value)
                ->get();
            foreach ($data as $row) {
                if ($row->email != '' || $row->email != null || $row->email != NULL) {

                    $data_array[] = array('id' => $row->id, 'value' => $row->email,'address'=>"");
                }
            }
        }

        if ($type == 'contact') {
            $data = DB::table('clients')
                ->join('client_contacts', 'client_contacts.client_id', 'clients.id')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->select('client_contacts.contact', 'clients.id')->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')
                ->where('client_contacts.contact', 'like', $value . '%')
                ->orwhere('client_contacts.contact', 'like', '%' . $value . '%')
                ->orwhere('client_contacts.contact', 'like',  '%' . $value)
                ->get();
            foreach ($data as $row) {
                if ($row->contact != '' || $row->contact != null || $row->contact != NULL) {

                    $data_array[] = array('id' => $row->id, 'value' => $row->contact,'address'=>"");
                }
            }
        }

        return $data_array;
    }


    public function search_client_by_name(Request $request)
    {
        $term = $request->term;
        $data_array = array();
        $data = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->select('clients.client_name', 'clients.id')->where('client_company_mapping.company', session('company_id'))->where('clients.client_name', 'like', '%' . $term . '%')->where('status', 'active')->get();

        foreach ($data as $row) {
            $data_array[] = array('id' => $row->id, 'value' => $row->client_name);
        }
        return $data_array;
    }


    public function search_exist_client(Request $request)
    {
        log::info("Get already exist client");
        try {

            $value = $request->value;
            $type = $request->type;
            if ($type == 'client_name') {
                $data = DB::table('clients')->where('id', $value)->get();
            }
            if ($type == 'case_no') {
                $data = DB::table('clients')->where('id', $value)->get();
            }
            if ($type == 'contact') {

                $data = DB::table('clients')->where('id', $value)->get();
            }
            if ($type == 'email') {

                $data = DB::table('clients')->where('id', $value)->get();
            }

            foreach ($data as $row) {
                $row->client_id = $row->id;
                $row->services_id = json_decode($row->services);
                $row->company_id = json_decode($row->default_company);
                $row->date_format = date('d/m/Y', strtotime($row->date));
                $row->client_visits = DB::table('client_visit')->where('client_id', $row->id)->value('enquery_details');
                $row->contacts = DB::table('client_contacts')->where('client_id', $row->id)->get();
                $row->assign_to = DB::table('clients')->where('id', $row->id)->value('assign_to');
                $row->assign_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                $assigned_date = DB::table('clients')->where('id', $row->id)->value('assigned_at');
                if ($assigned_date != '') {
                    $row->assigned_date = date('d-m-Y', strtotime($assigned_date));
                }
                $row->city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                $row->type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                $row->lead_type_name = DB::table('lead_type')->where('id', $row->lead_type)->value('type');
                $row->appointments = DB::table('appointment')->where('client', $row->id)->count();
                $row->followups = DB::table('follow_up')->where('client_id', $row->id)->where('company', session('company_id'))->count();
                $row->cases = DB::table('mycases')->where('client_id', $row->id)->count();
                $row->quotations = DB::table('quotation')
                    ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')
                    ->where('quotation.client_id', $row->id)->count();
                if (session('role_id') == 1 || $row->assign_to == session('staff_id')) {
                    $row->lead_change = 'yes';
                }
            }
            return $data;
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
            }
        }
    }



    public function filter_today_appointment(Request $request)
    {

        try {
            $value = $request->value;
            $today = date('Y-m-d');

            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');

            $date_array = array($startdate, $enddate);

            $appointments = DB::table('appointment')
                ->join('clients', 'clients.id', '=', 'appointment.client')
                ->join('appointment_places', 'appointment_places.id', '=', 'appointment.place')
                ->join('staff as meeting_with_staff', 'meeting_with_staff.sid', 'appointment.meeting_with')
                ->join('staff as schedule_by_staff', 'schedule_by_staff.sid', 'appointment.schedule_by')
                ->select('clients.client_name as cname', 'clients.case_no', 'appointment_places.name as aname', 'appointment_places.charges', 'meeting_with_staff.name as meetname', 'schedule_by_staff.name as schedule_by_name', 'appointment.*');
            if ($value == 'today_appointment') {
                $appointments = $appointments->whereDate('appointment.meeting_date', $today);
            }
            if ($value == 'weekly_appointment') {
                $appointments = $appointments->whereIn('appointment.meeting_date', $date_array);
            }
            $appointments = $appointments->orderBy('appointment.meeting_date', 'desc')
                ->get();


            if ($appointments) {
                return view('pages.dashboard.appointment_table', compact('appointments'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_quotation(Request $request)
    {

        try {
            $value = $request->value;
            $today = date('Y-m-d');

            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');
            $date_array = array($startdate, $enddate);

            $clients = DB::table('clients')->select('id')
                ->where('status', 'active')
                ->where('default_company', session('company_id'));
            if (session('role_id') != 1) {
                $clients = $clients->where('assign_to', session('staff_id'));
            }
            $clients = $clients->get();

            $client_ids = array_column(json_decode($clients), 'id');

            $quotation = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no', 'services.name as task_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation.*', 'quotation_details.finalize')
                ->whereIn('client_id', $client_ids);
            if ($value == 'today_quotation') {
                $quotation = $quotation->where('quotation.send_date', $today);
            }
            if ($value == 'weekly_quotation') {
                $quotation = $quotation->whereIn('quotation.send_date', $date_array);
            }
            $quotation = $quotation->where('quotation.company', session('company_id'))
                ->orderBy('quotation.send_date', 'desc')
                ->get();

            if ($quotation) {
                return view('pages.dashboard.quotation_sent_table', compact('quotation'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_leave(Request $request)
    {
        try {
            $value = $request->value;
            $today = date('Y-m-d');

            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');

            $date_array = array($startdate, $enddate);

            $staff_id = DB::table('staff')->pluck('sid');
           

            $leave = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->join('leave_table', 'staff.sid', 'leave_table.staff_id')
                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                ->select('staff.name', 'leave_table.*', 'leave_type.type')
                ->where('users.status', 'active');
            if ($value == 'today_leave') {
                $leave = $leave->where('leave_table.start_date', $today);
            }
            if ($value == 'weekly_leave') {
                $leave = $leave->whereBetween('leave_table.start_date', $date_array);
            }
            if (session('role_id') == 8) {
                $leave = $leave->where('staff.sid', session('staff_id'));
            } else {
                $leave = $leave->whereIn('staff.sid', $staff_id);
            }
            $leave = $leave->orderBy('leave_table.start_date', 'desc')
                ->get();

            if ($leave) {
                return view('pages.dashboard.leave_table', compact('leave'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_attendance()
    {
        try {
            $staff1 = DB::table('staff')->get();

            $staff_id = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $staff_id[] = $stf->sid;
                    }
                }
            }
            $today_attendance = DB::table('attendance')
                ->join('staff', 'staff.sid', 'attendance.staff_id')
                ->select('staff.name as staff_name', 'attendance.signin_date', 'attendance.signin_time', 'attendance.signout_date', 'attendance.signout_time')
                ->whereIn('staff.sid', $staff_id)
                ->where('attendance.signin_date', date('Y-m-d'))
                ->orderBy('attendance.signin_date', 'desc')
                ->get();

            if ($today_attendance) {
                return view('pages.dashboard.attendance_table', compact('today_attendance'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Some error while fetching data'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_client(Request $request)
    {

        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');

            $date_array = array($startdate, $enddate);

            if (session('role_id') == 8) {
                if ($value == 'today_client') {
                    $clients  = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->where('client_company_mapping.company', session('company_id'))
                        ->where('clients.assign_to', session('staff_id'))
                        ->whereDate('clients.assigned_at', $today)
                        ->orderBy('clients.id', 'desc')->get();
                }
                if ($value == 'weekly_client') {
                    $clients  = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->where('client_company_mapping.company', session('company_id'))
                        ->where('clients.assign_to', session('staff_id'))
                        ->whereBetween('clients.assigned_at', $date_array)
                        ->orderBy('clients.id', 'desc')->get();
                }
            } else {
                if ($value == 'today_client') {
                    $clients = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->where('client_company_mapping.company', session('company_id'))
                        ->where('clients.client_leads', 'client')
                        ->whereDate('clients.created_at', $today)
                        ->orderBy('clients.id', 'desc')->get();
                }
                if ($value == 'weekly_client') {
                    $clients  = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->where('client_company_mapping.company', session('company_id'))
                        ->where('clients.client_leads', 'client')
                        ->whereIn('clients.created_at', $date_array)
                        ->orderBy('clients.id', 'desc')->get();
                }
            }

            if ($clients) {
                return view('pages.dashboard.assign_lead_table', compact('clients'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Some error while fetching data'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_followup(Request $request)
    {

        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $enddate = date('Y-m-d', strtotime('+7 days'));
            $startdate = date('Y-m-d');
            $date_array = array($startdate, $enddate);

            Log::info($date_array);
            $staff = $this->get_staff_list_userid();
            $staff_id = array_column(json_decode($staff, true), 'sid');

            $client_ids = DB::table('follow_up')
                ->join('clients', 'clients.id', 'follow_up.client_id')
                ->select('follow_up.client_id')->where('follow_up.company', session('company_id'));
            if (session('role_id') == 8) {
                $client_ids = $client_ids->where('clients.assign_to', session('staff_id'));
            } else {
                $client_ids = $client_ids->whereIn('clients.assign_to', $staff_id);
            }

            $client_ids = $client_ids->orderBy('follow_up.followup_date', 'desc');

            $client_ids = $client_ids->distinct()->get();

            $today_follow_up = [];

            foreach ($client_ids as $row) {
                $followup = DB::table('follow_up')
                    ->leftJoin('clients', 'clients.id', 'follow_up.client_id')
                    ->leftJoin('staff', 'follow_up.contact_by', 'staff.sid')
                    ->select('follow_up.id', 'follow_up.client_id', 'follow_up.followup_date', 'follow_up.next_followup_date', 'follow_up.finalized', 'follow_up.lead_closed', 'follow_up.discussion', 'follow_up.contact_to', 'follow_up.contact_by', 'follow_up.method', 'staff.name as contact_by_name', 'clients.client_name', 'clients.case_no')
                    ->where('follow_up.client_id', $row->client_id);
                if ($value == 'today_followup') {
                    $followup = $followup->whereDate('follow_up.followup_date', $today)->orderBy('follow_up.followup_date', 'DESC');
                }
                if ($value == 'weekly_followup') {
                    $followup = $followup->whereBetween('follow_up.followup_date', $date_array)->orderBy('follow_up.followup_date', 'DESC');
                }

                $followup = $followup->first();
                if ($followup) {
                    array_push($today_follow_up, $followup);
                    $contact_to = $today_follow_up[sizeof($today_follow_up) - 1]->contact_to;
                    $today_follow_up[sizeof($today_follow_up) - 1]->method_data = $today_follow_up[sizeof($today_follow_up) - 1]->method;
                    $today_follow_up[sizeof($today_follow_up) - 1]->contact_to_data = DB::table('client_contacts')->where('id', $contact_to)->get(['name']);
                    $today_follow_up[sizeof($today_follow_up) - 1]->staff_name = DB::table('staff')->join('clients', 'clients.assign_to', 'staff.sid')->where('clients.id', $row->client_id)->value('staff.name');
                }
            }

            return view('pages.dashboard.followup_table', compact('today_follow_up'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function filter_today_nextfollowup(Request $request)
    {

        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $enddate = date('Y-m-d', strtotime('+7 days'));
            $startdate = date('Y-m-d');
            $date_array = array($startdate, $enddate);

            Log::info($date_array);
            $staff = $this->get_staff_list_userid();
            $staff_id = array_column(json_decode($staff, true), 'sid');

            $client_ids = DB::table('follow_up')
                ->join('clients', 'clients.id', 'follow_up.client_id')
                ->select('follow_up.client_id')->where('follow_up.company', session('company_id'));
            if (session('role_id') == 8) {
                $client_ids = $client_ids->where('clients.assign_to', session('staff_id'));
            } else {
                $client_ids = $client_ids->whereIn('clients.assign_to', $staff_id);
            }

            $client_ids = $client_ids->orderBy('follow_up.next_followup_date', 'desc');

            $client_ids = $client_ids->distinct()->get();

            $today_follow_up = [];

            foreach ($client_ids as $row) {
                $followup = DB::table('follow_up')
                    ->leftJoin('clients', 'clients.id', 'follow_up.client_id')
                    ->leftJoin('staff', 'follow_up.contact_by', 'staff.sid')
                    ->select('follow_up.id', 'follow_up.client_id', 'follow_up.followup_date', 'follow_up.next_followup_date', 'follow_up.finalized', 'follow_up.lead_closed', 'follow_up.discussion', 'follow_up.contact_to', 'follow_up.contact_by', 'follow_up.method', 'staff.name as contact_by_name', 'clients.client_name', 'clients.case_no')
                    ->where('follow_up.client_id', $row->client_id);
                if ($value == 'today_followup') {
                    $followup = $followup->whereDate('follow_up.next_followup_date', $today)->orderBy('follow_up.next_followup_date', 'DESC');
                }
                if ($value == 'weekly_followup') {

                    $followup = $followup->whereBetween('follow_up.next_followup_date', $date_array)->orderBy('follow_up.next_followup_date', 'DESC');
                }

                $followup = $followup->first();
                if ($followup) {
                    array_push($today_follow_up, $followup);
                    $contact_to = json_decode($today_follow_up[sizeof($today_follow_up) - 1]->contact_to);
                    if (!empty($today_follow_up)) {
                        if (isset($today_follow_up[sizeof($today_follow_up) - 1]->method)) {
                            $methodArr = is_array($today_follow_up[sizeof($today_follow_up) - 1]->method) ? $today_follow_up[sizeof($today_follow_up) - 1]->method : [$today_follow_up[sizeof($today_follow_up) - 1]->method];

                            $filteredMethodArr = array_filter($methodArr, function ($value) {
                                return !is_null($value) && $value !== '';
                            });

                            $methodDataString = implode(",", $filteredMethodArr);
                            $today_follow_up[sizeof($today_follow_up) - 1]->method_data = $methodDataString;
                        }
                    }
                    $today_follow_up[sizeof($today_follow_up) - 1]->contact_to_data = implode(",", array_column(json_decode(DB::table('client_contacts')->whereIn('id', $contact_to)->get(['name']), true), 'name'));
                    $today_follow_up[sizeof($today_follow_up) - 1]->staff_name = DB::table('staff')->join('clients', 'clients.assign_to', 'staff.sid')->where('clients.id', $row->client_id)->value('staff.name');
                }
            }


            return view('pages.dashboard.next_followup_table', compact('today_follow_up'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function filter_today_invoice(Request $request)
    {
        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $enddate = date('Y-m-d', strtotime('+7 days'));
            $startdate = date('Y-m-d');
            $date_array = array($startdate, $enddate);

            if ($value == 'today_invoice') {
                $raised_invoice  = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no')
                    ->where('bill.company', session('company_id'));
                if ($value == 'today_invoice') {
                    $raised_invoice = $raised_invoice->whereDate('bill.bill_date', $today);
                }
                if ($value == 'weekly_invoice') {
                    $raised_invoice = $raised_invoice->whereIn('bill.bill_date', $date_array);
                }
                $raised_invoice = $raised_invoice->where('bill.active', 'yes')
                    ->orderBy('bill.bill_date', 'desc')
                    ->get();
            }

            if ($raised_invoice) {
                return view('pages.dashboard.raised_invoice_table', compact('raised_invoice'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_payment(Request $request)
    {
        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $enddate = date('Y-m-d', strtotime('+7 days'));
            $startdate = date('Y-m-d');
            $date_array = array($startdate, $enddate);

            if ($value == 'today_payment') {
                $received_payment = DB::table('payment')
                    ->join('clients', 'clients.id', 'payment.client_id')
                    ->select('payment.*', 'clients.client_name', 'clients.case_no')
                    ->where('clients.default_company', session('company_id'))
                    ->where('payment.status', 'received');
                if ($value == 'today_payment') {
                    $received_payment = $received_payment->whereDate('payment.payment_date', $today);
                }
                if ($value == 'weekly_payment') {
                    $received_payment = $received_payment->whereIn('payment.payment_date', $date_array);
                }

                $received_payment = $received_payment->orderBy('payment.payment_date', 'desc')
                    ->get();
            }

            if ($received_payment) {
                return view('pages.dashboard.received_payment_table', compact('received_payment'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_sales_lead(Request $request)
    {

        try {
            $value = $request->value;
            $staff_id = $request->staff_id;
            $today = date('Y-m-d');
            $date =  Carbon::now()->month(Carbon::now()->month - 6);
            $startDate = Carbon::now()->startOfQuarter(); // the actual start of quarter method
            $endDate = Carbon::now()->endOfQuarter();
            $staff = $this->get_sales_staff_list();

            if ($value == 'today_sales_lead' || $value == 'Today') {
                if ($staff_id == '') {
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->where('users.role_id', 8)->whereDate('clients.date', $today)->orderBy('clients.date', 'desc')->get();
                } else {
                    $sid = $staff_id;
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereDate('clients.date', $today)->where('users.role_id', 8)->where('clients.created_by', $sid)->orderBy('clients.date', 'desc')->get();
                }

                foreach ($sales_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }
            if ($value == 'monthly_sales_lead' || $value == 'This Month') {
                if ($staff_id == '') {
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereYear('clients.date', date('Y'))->whereMonth('clients.date', date('m'))->where('users.role_id', 8)->orderBy('clients.date', 'desc')->get();
                } else {
                    $sid = $staff_id;
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereYear('clients.date', date('Y'))->whereMonth('clients.date', date('m'))->where('users.role_id', 8)->where('clients.created_by', $sid)->orderBy('clients.date', 'desc')->get();
                }

                foreach ($sales_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }

            if ($value == 'quarterly_sales_lead' || $value == 'This Quarter') {
                if ($staff_id == '') {
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereBetween('clients.date', [$startDate, $endDate])->where('users.role_id', 8)->orderBy('clients.date', 'desc')->get();
                } else {
                    $sid = $staff_id;
                    $sales_lead = DB::table('clients')
                        ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                        ->join('city', 'city.id', 'clients.city')
                        ->join('staff', 'staff.sid', 'clients.created_by')
                        ->join('users', 'users.user_id', 'staff.sid')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                        ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereBetween('clients.date', [$startDate, $endDate])->where('users.role_id', 8)->where('clients.created_by', $sid)->orderBy('clients.date', 'desc')->get();
                }

                foreach ($sales_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }

            if ($sales_lead) {
                return view('pages.dashboard.leads_by_sales_table', compact('sales_lead', 'staff'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }

    public function filter_today_office_lead(Request $request)
    {

        try {
            $value = $request->value;

            $today = date('Y-m-d');
            $date =  Carbon::now()->month(Carbon::now()->month - 6);
            $startDate = Carbon::now()->startOfQuarter(); // the actual start of quarter method
            $endDate = Carbon::now()->endOfQuarter();

            if ($value == 'today_office_lead') {
                $office_lead = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->join('staff', 'staff.sid', 'clients.created_by')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                    ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->where('users.role_id', '!=', 8)->whereDate('clients.date', $today)->orderBy('clients.date', 'desc')->get();

                foreach ($office_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }
            if ($value == 'monthly_office_lead') {
                $office_lead = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->join('staff', 'staff.sid', 'clients.created_by')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                    ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereYear('clients.date', date('Y'))->whereMonth('clients.date', date('m'))->where('users.role_id', '!=', 8)->orderBy('clients.date', 'desc')->get();

                foreach ($office_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }

            if ($value == 'quarterly_office_lead') {
                $office_lead = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->join('staff', 'staff.sid', 'clients.created_by')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.date', 'clients.source', 'clients.services', 'users.role_id')
                    ->where('client_company_mapping.company', session('company_id'))->where('clients.status', 'active')->where('clients.client_leads', 'leads')->whereBetween('clients.date', [$startDate, $endDate])->where('users.role_id', '!=', 8)->orderBy('clients.date', 'desc')->get();

                foreach ($office_lead as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                }
            }

            if ($office_lead) {
                return view('pages.dashboard.leads_by_office_table', compact('office_lead'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function export_client_report()
    {
        $fileName = 'client_report.csv';
        $month = date('m');
        $year = date('Y');
        $company = session('company_id');
        $client_list = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->join('city', 'city.id', 'clients.city')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.remarks', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services')
            ->where('client_company_mapping.company', $company)
            ->whereMonth('date', $month)->whereYear('date', $year)
            ->where('clients.status', 'active')
            ->orderBy('clients.id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $client_array = array('Client Name', 'Case Number', 'No of units', 'Area', 'Property type', 'Address', 'City', 'Pincode');

        $callback = function () use ($client_list, $client_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $client_array);

            foreach ($client_list as $client) {
                $client->property_type_name = DB::table('property_type')->where('id', $client->property_type)->value('type');
                $row['client_name']  = $client->client_name;
                $row['case_no']    = $client->case_no;
                $row['no_of_units']    = $client->no_of_units;
                $row['area']  = $client->area;
                $row['property_type']  = $client->property_type_name;
                $row['address']  = $client->address;
                $row['city']  = $client->city_name;
                $row['pincode']  = $client->pincode;

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['no_of_units'], $row['area'], $row['property_type'], $row['address'], $row['city'], $row['pincode']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_appointment_report()
    {
        $fileName = 'appointment_report.csv';
        $month = date('m');
        $year = date('Y');
        $appointment_list = DB::table('appointment')
            ->join('clients', 'clients.id', '=', 'appointment.client')
            ->join('appointment_places', 'appointment_places.id', '=', 'appointment.place')
            ->join('staff', 'staff.sid', '=', 'appointment.meeting_with')
            ->whereMonth('appointment.meeting_date', $month)
            ->whereYear('appointment.meeting_date', $year)
            ->where('clients.default_company', session('company_id'))
            ->select('clients.client_name as cname', 'clients.case_no', 'appointment_places.name as aname', 'appointment_places.charges', 'staff.name as meetname', 'appointment.*')
            ->orderBy('appointment.id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $appointment_array = array('Client Name', 'Case Number', 'Visit Type', 'Status', 'Meeting Date', 'Meeting Time', 'Attended by');

        $callback = function () use ($appointment_list, $appointment_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $appointment_array);

            foreach ($appointment_list as $appointment) {
                $row['cname']  = $appointment->cname;
                $row['case_no']  = $appointment->case_no;
                $row['aname']    = $appointment->aname;
                $row['status']    = $appointment->status;
                $row['meeting_date']  = date("d-m-Y", strtotime($appointment->meeting_date));
                $row['meeting_time']  = $appointment->meeting_time;
                $row['meetname']  = $appointment->meetname;

                fputcsv($file, array($row['cname'], $row['case_no'], $row['aname'], $row['status'], $row['meeting_date'], $row['meeting_time'], $row['meetname']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_invoice_report()
    {
        $fileName = 'invoice_report.csv';
        $month = date('m');
        $year = date('Y');
        $invoice_list = DB::table('bill')
            ->join('clients', 'clients.id', 'bill.client')
            ->join('staff', 'staff.sid', 'bill.sign')
            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
            ->where('bill.company', session('company_id'))
            ->where('bill.quotation', '!=', 'null')
            ->where('bill.active', 'yes')
            ->whereMonth('bill_date', $month)->whereYear('bill_date', $year)
            ->orderBy('bill.bill_date', 'desc')->get()->toArray();
        $pro_invoice_list = DB::table('proforma_invoice')
            ->join('clients', 'clients.id', 'proforma_invoice.client')
            ->join('staff', 'staff.sid', 'proforma_invoice.sign')
            ->select('proforma_invoice.*', 'clients.client_name', 'clients.case_no', 'staff.name')
            ->where('proforma_invoice.company', session('company_id'))
            ->where('proforma_invoice.quotation', '!=', 'null')
            ->where('proforma_invoice.active', 'yes')
            ->whereMonth('proforma_invoice.bill_date', $month)->whereYear('proforma_invoice.bill_date', $year)
            ->orderBy('proforma_invoice.bill_date', 'desc')->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $invoice_array = array('Client Name', 'Case Number', 'Service', 'Amount', 'Status', 'Bill Date', 'Due Date', 'Seal', 'Sign', 'Type');

        $callback = function () use ($invoice_list, $invoice_array, $pro_invoice_list) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $invoice_array);

            foreach ($invoice_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('paid_amount');
                $inv->payable = $inv->total_amount - $paid_amt;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }


                $inv->service = $service;
                $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                $row['client_name']  = $inv->client_name;
                $row['case_no']  = $inv->case_no;
                $row['service']    = $inv->service;
                $row['amount']    = number_format($inv->total_amount, 2);
                $row['status']  = $inv->status;
                $row['bill_date']  = date('d-m-Y', strtotime($inv->bill_date));
                $row['due_date']  = date('d-m-Y', strtotime($inv->due_date));
                $row['seal']  = $inv->seal;
                $row['name']  = $inv->name;
                $row['type']  = 'Invoice';

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['service'], $row['amount'], $row['status'], $row['bill_date'], $row['due_date'], $row['seal'], $row['name'], $row['type']));
            }
            foreach ($pro_invoice_list as $inv1) {
                $services_arr = json_decode($inv1->service);
                $amount_arr = json_decode($inv1->amount);
                $quotation_array = json_decode($inv1->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv1->id)->where('active', 'yes')->sum('paid_amount');
                $inv1->payable = $inv1->total_amount - $paid_amt;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }


                $inv1->service = $service;
                $inv1->tds_applicable = DB::table('company')->where('id', $inv1->company)->value('tds_applicable');
                $row1['client_name']  = $inv1->client_name;
                $row1['case_no']  = $inv1->case_no;
                $row1['service']    = $inv1->service;
                $row1['amount']    = number_format($inv1->total_amount, 2);
                $row1['status']  = $inv1->status;
                $row1['bill_date']  = date('d-m-Y', strtotime($inv1->bill_date));
                $row1['due_date']  = date('d-m-Y', strtotime($inv1->due_date));
                $row1['seal']  = $inv1->seal;
                $row1['name']  = $inv1->name;
                $row1['type']  = 'Proforma Invoice';

                fputcsv($file, array($row1['client_name'], $row1['case_no'], $row1['service'], $row1['amount'], $row1['status'], $row1['bill_date'], $row1['due_date'], $row1['seal'], $row1['name']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_payment_report()
    {
        $fileName = 'payment_report.csv';
        $month = date('m');
        $year = date('Y');
        $payment_list = DB::table('payment')
            ->join('clients', 'clients.id', 'payment.client_id')
            ->select('payment.*', 'clients.client_name', 'clients.case_no')
            ->where('payment.company', session('company_id'))
            ->where('payment.active', 'yes')
            ->where('payment.status', 'approved')
            ->whereMonth('payment.payment_date', $month)
            ->whereYear('payment.payment_date', $year)
            ->orderBy('payment.id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $payment_array = array('Client Name', 'Case Number', 'Payment', 'TDS', 'Payment Date', 'Mode of Payment', 'Cheque No', 'Reference No', 'Narration', 'Deposite Bank', 'Approved By', 'Approved Date');

        $callback = function () use ($payment_list, $payment_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $payment_array);

            foreach ($payment_list as $pay) {
                $pay->deposite_bank_name = DB::table('bank_detailes')->where('id', $pay->deposit_bank)->value('bankname');
                $pay->approved_by_name = DB::table('staff')->where('sid', $pay->approved_by)->value('name');
                $row['client_name']  = $pay->client_name;
                $row['case_no']  = $pay->case_no;
                $row['payment']    = number_format($pay->payment, 2);
                $row['tds']  = $pay->tds;
                $row['payment_date']    = date('d-m-Y', strtotime($pay->payment_date));
                $row['mode_of_payment']  = $pay->mode_of_payment;
                $row['cheque_no']  = $pay->cheque_no;
                $row['reference_no']  = $pay->reference_no;
                $row['narration']  = $pay->narration;
                $row['deposite_bank_name']  = $pay->deposite_bank_name;
                $row['approved_by_name']  = $pay->approved_by_name;
                $row['approve_date']  = $pay->approve_date;

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['payment'], $row['tds'], $row['payment_date'], $row['mode_of_payment'], $row['cheque_no'], $row['reference_no'], $row['narration'], $row['deposite_bank_name'], $row['approved_by_name'], $row['approve_date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_quotation_report()
    {
        $fileName = 'quotation_report.csv';
        $month = date('m');
        $year = date('Y');
        $quotation_list = DB::table('quotation')
            ->join('clients', 'clients.id', '=', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
            ->join('services', 'services.id', '=', 'quotation_details.task_id')
            ->select('clients.client_name', 'clients.case_no', 'services.name as task_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date')
            ->where('quotation.company', session('company_id'))
            ->where('quotation_details.finalize', 'yes')
            ->whereMonth('quotation_details.finalize_date', $month)
            ->whereYear('quotation_details.finalize_date', $year)
            ->orderBy('quotation.id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $quotation_array = array('Client Name', 'Service', 'Amount', 'Finalized Date', 'Finalized');

        $callback = function () use ($quotation_list, $quotation_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $quotation_array);

            foreach ($quotation_list as $quot) {
                $row['client_name']  = $quot->client_name;
                $row['case_no']  = $quot->case_no;
                $row['task_name']    = $quot->task_name;
                $row['amount']  = number_format($quot->amount, 2);
                $row['finalize_date']    = date('d-m-Y', strtotime($quot->finalize_date));
                $row['finalize']  = $quot->finalize;


                fputcsv($file, array($row['client_name'], $row['case_no'], $row['task_name'], $row['amount'], $row['finalize_date'], $row['finalize']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_followup_report()
    {
        $fileName = 'followup_report.csv';
        $month = date('m');
        $year = date('Y');
        $followup_list = DB::table('follow_up')
            ->select('follow_up.*')
            ->whereMonth('followup_date', $month)
            ->whereYear('followup_date', $year)
            ->where('company', session('company_id'))
            ->orderBy('id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $followup_array = array('Client Name', 'Case Number', 'Contact To', 'Method', 'Contact By', 'Follow-Up Date', 'Next Follow-Up Date', 'Finalized', 'Lead closed', 'Discussion');

        $callback = function () use ($followup_list, $followup_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $followup_array);

            foreach ($followup_list as $follow) {
                $contact_to_data = '';
                $method_data = '';
                $contact_to = json_decode($follow->contact_to);
                $method = json_decode($follow->method);
                for ($j = 0; $j < sizeof($contact_to); $j++) {
                    log::info($contact_to[$j]);
                    $contact_by1 = DB::table('client_contacts')->where('id', $contact_to[$j])->value('name');
                    $contact_to_data .= ', ' . $contact_by1;
                }
                for ($k = 0; $k < sizeof($method); $k++) {
                    log::info($method[$k]);

                    $method_data .= ', ' . $method[$k];
                }
                $contact_to_data = ltrim($contact_to_data, ',');
                $method_data = ltrim($method_data, ',');
                $client_name = DB::table('clients')->where('id', $follow->client_id)->value('client_name');
                $case_no = DB::table('clients')->where('id', $follow->client_id)->value('case_no');
                $contact_by = DB::table('staff')->where('sid', $follow->contact_by)->value('name');

                $follow->contact_to_data = $contact_to_data;
                $follow->method_data = $method_data;

                $follow->contact_by = $contact_by;
                $follow->client_name = $client_name;
                $follow->case_no = $case_no;

                $row['client_name']  = $follow->client_name;
                $row['case_no']  = $follow->case_no;
                $row['contact_to_data']    = $follow->contact_to_data;
                $row['method_data']  = $follow->method_data;
                $row['contact_by']    = $follow->contact_by;
                $row['followup_date']  = date('d-m-Y', strtotime($follow->followup_date));
                $row['next_followup_date']  = date('d-m-Y', strtotime($follow->next_followup_date));
                $row['finalized']  = $follow->finalized;
                $row['lead_closed']  = $follow->lead_closed;
                $row['discussion']  = $follow->discussion;


                fputcsv($file, array($row['client_name'], $row['case_no'], $row['contact_to_data'], $row['method_data'], $row['contact_by'], $row['followup_date'], $row['next_followup_date'], $row['finalized'], $row['lead_closed'], $row['discussion']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_consultation_fees_report()
    {
        $fileName = 'consultation_fees_report.csv';
        $month = date('m');
        $year = date('Y');
        $consultation_fees_list = DB::table('consulting_fee')
            ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
            ->join('clients', 'clients.id', 'appointment.client')
            ->select('consulting_fee.*', 'appointment.place', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->whereMonth('consulting_fee.payment_date', $month)
            ->whereYear('consulting_fee.payment_date', $year)
            ->orderBy('consulting_fee.id', 'desc')
            ->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $consultation_fees_array = array('Client Name', 'Case Number', 'Place', 'Fees', 'Payment mode', 'Cheque No', 'Cheque Date', 'Reference', 'Bank');

        $callback = function () use ($consultation_fees_list, $consultation_fees_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $consultation_fees_array);

            foreach ($consultation_fees_list as $fee) {
                $fee->bankname = DB::table('bank_detailes')->where('id', $fee->bank)->value('bankname');
                $bank = DB::table('bank_detailes')->get();
                $place_charges = DB::table('appointment_places')->where('id', $fee->place)->value('charges');
                $fee->place_name = DB::table('appointment_places')->where('id', $fee->place)->value('name');
                $row['client_name']  = $fee->client_name;
                $row['case_no']  = $fee->case_no;
                $row['place_name']    = $fee->place_name;
                $row['fees']  = $fee->fees;
                $row['payment_mode'] = $fee->payment_mode;
                $row['cheque_no']  = $fee->cheque_no;
                $row['cheque_date']  = date('d-m-Y', strtotime($fee->cheque_date));
                $row['reference']  = $fee->reference;
                $row['bankname']  = $fee->bankname;

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['place_name'], $row['fees'], $row['payment_mode'], $row['cheque_no'], $row['cheque_date'], $row['reference'], $row['bankname']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_additional_invoice_report()
    {
        $fileName = 'additional_invoice_report.csv';
        $month = date('m');
        $year = date('Y');
        $additional_invoice_list = DB::table('bill')
            ->join('clients', 'clients.id', 'bill.client')
            ->join('staff', 'staff.sid', 'bill.sign')
            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
            ->where('bill.company', session('company_id'))
            ->where('bill.quotation', 'null')
            ->where('bill.active', 'yes')
            ->whereMonth('bill.bill_date', $month)
            ->whereYear('bill.bill_date', $year)
            ->orderBy('bill.bill_date', 'desc')->get()->toArray();

        $additional_pro_invoice_list = DB::table('proforma_invoice')
            ->join('clients', 'clients.id', 'proforma_invoice.client')
            ->join('staff', 'staff.sid', 'proforma_invoice.sign')
            ->select('proforma_invoice.*', 'clients.client_name', 'clients.case_no', 'staff.name')
            ->where('proforma_invoice.company', session('company_id'))
            ->where('proforma_invoice.quotation', 'null')
            ->where('proforma_invoice.active', 'yes')
            ->whereMonth('proforma_invoice.bill_date', $month)
            ->whereYear('proforma_invoice.bill_date', $year)
            ->orderBy('proforma_invoice.bill_date', 'desc')->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $additional_invoice_array = array('Client Name', 'Case Number', 'Service', 'Amount', 'Status', 'Bill Date', 'Due Date', 'Seal', 'Sign', 'Type');

        $callback = function () use ($additional_invoice_list, $additional_invoice_array, $additional_pro_invoice_list) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $additional_invoice_array);

            foreach ($additional_invoice_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('paid_amount');
                $inv->payable = $inv->total_amount - $paid_amt;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }


                $inv->service = $service;
                $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                $row['client_name']  = $inv->client_name;
                $row['case_no']  = $inv->case_no;
                $row['service']    = $inv->service;
                $row['amount']    = number_format($inv->total_amount, 2);
                $row['status']  = $inv->status;
                $row['bill_date']  = date('d-m-Y', strtotime($inv->bill_date));
                $row['due_date']  = date('d-m-Y', strtotime($inv->due_date));
                $row['seal']  = $inv->seal;
                $row['name']  = $inv->name;
                $row['type']  = 'Invoice';

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['service'], $row['amount'], $row['status'], $row['bill_date'], $row['due_date'], $row['seal'], $row['name'], $row['type']));
            }
            foreach ($additional_pro_invoice_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('paid_amount');
                $inv->payable = $inv->total_amount - $paid_amt;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }


                $inv->service = $service;
                $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                $row1['client_name']  = $inv->client_name;
                $row1['case_no']  = $inv->case_no;
                $row1['service']    = $inv->service;
                $row1['amount']    = number_format($inv->total_amount, 2);
                $row1['status']  = $inv->status;
                $row1['bill_date']  = date('d-m-Y', strtotime($inv->bill_date));
                $row1['due_date']  = date('d-m-Y', strtotime($inv->due_date));
                $row1['seal']  = $inv->seal;
                $row1['name']  = $inv->name;
                $row1['type']  = 'Proforma Invoice';

                fputcsv($file, array($row1['client_name'], $row1['case_no'], $row1['service'], $row1['amount'], $row1['status'], $row1['bill_date'], $row1['due_date'], $row1['seal'], $row1['name'], $row1['type']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export_due_payment_report()
    {
        $fileName = 'due_payment_report.csv';
        $due_payment_list = DB::table('bill')->where('company', session('company_id'))->where('status', '!=', 'paid')->where('active', 'yes')->orderBy('bill_date', 'desc')->get()->toArray();
        $due_pro_payment_list = DB::table('proforma_invoice')->where('proforma_invoice.company', session('company_id'))->where('convert_tax', 'no')->where('status', '!=', 'paid')->where('active', 'yes')->orderBy('bill_date', 'desc')->get()->toArray();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $due_payment_array = array('Client Name', 'Case Number','Assign to', 'Service', 'Amount', 'Due Amt', 'Status', 'Bill Date', 'Due Date', 'type');

        $callback = function () use ($due_payment_list, $due_payment_array, $due_pro_payment_list) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $due_payment_array);

            foreach ($due_payment_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('paid_amount');
                $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('tds_amount');
                $inv->payable = $inv->total_amount - ($paid_amt + $tds_amt);
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }
                if ($inv->payable > 0) {
                    $inv->service = $service;
                    $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                    $row['client_name']  = DB::table('clients')->where('id', $inv->client)->value('client_name');
                    $assign_to=DB::table('clients')->where('id', $inv->client)->value('assign_to');
                    $row['case_no']  = DB::table('clients')->where('id', $inv->client)->value('case_no');
                    $row['assign_to']=DB::table('staff')->where('sid',$assign_to)->value('name');
                    $row['service']    = $inv->service;
                    $row['total_amount']    = number_format($inv->total_amount, 2);
                    $row['due_amount']    = number_format($inv->payable, 2);
                    $row['status']  = $inv->status;
                    $row['bill_date']  = date('d-m-Y', strtotime($inv->bill_date));
                    $row['due_date']  = date('d-m-Y', strtotime($inv->due_date));

                    $row['type']  = 'Invoice';

                    fputcsv($file, array($row['client_name'], $row['case_no'],$row['assign_to'], $row['service'], $row['total_amount'], $row['due_amount'], $row['status'], $row['bill_date'], $row['due_date'], $row['type']));
                }
            }
            foreach ($due_pro_payment_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);

                $inv->payable = $inv->total_amount;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }

                if ($inv->payable > 0) {
                    $inv->service = $service;
                    $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                    $row1['client_name']  = DB::table('clients')->where('id', $inv->client)->value('client_name');
                    $assign_to=DB::table('clients')->where('id', $inv->client)->value('assign_to');
                    $row1['case_no']  = DB::table('clients')->where('id', $inv->client)->value('case_no');
                    $row1['assign_to']=DB::table('staff')->where('sid',$assign_to)->value('name');
                    $row1['service']    = $inv->service;
                    $row1['total_amount']    = number_format($inv->total_amount, 2);
                    $row1['due_amount']    = number_format($inv->payable, 2);
                    $row1['status']  = $inv->status;
                    $row1['bill_date']  = date('d-m-Y', strtotime($inv->bill_date));
                    $row1['due_date']  = date('d-m-Y', strtotime($inv->due_date));

                    $row1['type']  = 'Proforma Invoice';

                    fputcsv($file, array($row1['client_name'], $row1['case_no'],$row1['assign_to'], $row1['service'], $row1['total_amount'], $row1['due_amount'], $row1['status'], $row1['bill_date'], $row1['due_date'], $row1['type']));
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function get_lead_type(Request $request)
    {

        $staff_id = $request->staff_id;
        if ($staff_id == '') {
            $staff_id = session('staff_id');
        }
        $leadtype = DB::table('lead_type')->get();
        $data = array();
        $label = array();

        foreach ($leadtype as $row) {
            $label[] = $row->type;
            $data[] = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->where('client_company_mapping.company', session('company_id'))
                ->where('clients.assign_to', $staff_id)
                ->where('clients.status', 'active')->where('clients.lead_type', $row->id)->count();
        }

        return json_encode(array('label' => $label, 'data' => $data));
    }
    public function get_bar_chart(Request $request)
    {
        try {
            $value = $request->value;
            $value = explode('-', $value);
            $start_yr = $value[0];
            $end_yr = $value[1];
            $income_arr = array();
            $expenses_Arr = array();
            $month_arr = array('Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar');
            $month_no_array = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
            $year_arr = array($start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $end_yr, $end_yr, $end_yr);
            $total_inc = 0;
            $total_exp = 0;
            for ($i = 0; $i < sizeof($month_arr); $i++) {

                $income_arr[] = $this->get_income($month_no_array[$i], $year_arr[$i]);
                $expenses_arr[] = $this->get_expenses($month_no_array[$i], $year_arr[$i]);
            }
            $total_inc = array_sum($income_arr);
            $total_exp = array_sum($expenses_arr);

            $total_inc = $this->IND_money_format($total_inc);
            $total_exp = $this->IND_money_format($total_exp);
            $max_inc = max($income_arr);
            $max_exp = max($expenses_arr);
            if ($max_inc > $max_exp) {
                $max = $max_inc;
            } else {
                $max = $max_exp;
            }
            $max = round($max / 100000) * 100000;
            return json_encode(array('income' => $income_arr, 'expense' => $expenses_arr, 'month' => $month_arr, 'max' => $max, 'total_income' => $total_inc, 'total_expense' => $total_exp));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function get_line_chart(Request $request)
    {
        try {
            $value = $request->value;
            $value = explode('-', $value);
            $start_yr = $value[0];
            $end_yr = $value[1];
            $finalize_quotation = array();

            $month_arr = array('Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar');
            $month_no_array = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
            $year_arr = array($start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $start_yr, $end_yr, $end_yr, $end_yr);
            $total_inc = 0;
            $total_exp = 0;
            for ($i = 0; $i < sizeof($month_arr); $i++) {

                $finalize_quotation[] = $this->get_finalize_quotation($month_no_array[$i], $year_arr[$i]);
            }
            $total_finalize = array_sum($finalize_quotation);




            return json_encode(array('finalize' => $finalize_quotation, 'month' => $month_arr, 'total_finalize' => $total_finalize));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function get_all_leads(Request $request)
    {
        try {
            $leadtype = DB::table('lead_type')->get();
            $data = array();
            $label = array();

            foreach ($leadtype as $row) {
                $label[] = $row->type;
                $data[] = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->where('client_company_mapping.company', session('company_id'))
                    ->where('clients.status', 'active')->where('clients.lead_type', $row->id)->count();
            }
            $total = array_sum($data);

            return json_encode(array('label' => $label, 'data' => $data, 'total' => $total));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function add_pretty_cash(Request $request)
    {
        try {
            $date = $request->date;
            $date = date('Y-m-d', strtotime($date));
            $staff = $request->staff;
            $amount = $request->amount;
            $remark = $request->remark;
            $cash = $request->cash;
            $insert = DB::table('pretty_cash')->insert(['date' => $date, 'staff_id' => $staff, 'amount' => $amount, 'remark' => $remark, 'cash_type' => $cash, 'entry_by' => session('staff_id'), 'company' => session('company_id')]);
            if ($insert) {
                return json_encode(array('status' => 'success', 'msg' => 'Data inserted successfully'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Data can`t be inserted'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return json_encode(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }

    public function filter_leads_table(Request $request)
    {
        try {
            $value = $request->value;
            $today = date('Y-m-d');
            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $start_premonth_date = date('Y-m-1', strtotime('-1 month'));
            $end_premonth_date = date('Y-m-t', strtotime('-1 month'));
            $enddate = date('Y-m-d');

            $date_array = array($startdate, $enddate);

            $leads = DB::table('leads');
            if ($value == 'today_leads') {
                $leads = $leads->whereDate('created_at', $today);
            }
            if ($value == 'weekly_leads') {
                $leads = $leads->whereBetween('created_at', $date_array);
            }
            if ($value == 'month_leads') {
                $leads = $leads->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            }
            if ($value == 'previous_month_leads') {
                $leads = $leads->whereBetween('created_at', array($start_premonth_date, $end_premonth_date));
            }
            $leads = $leads->orderBy('created_at', 'desc')->get();
            if ($leads) {
                return view('pages.dashboard.leads_table', compact('leads'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function leads_details(Request $request)
    {
        try {
            $leads = DB::table('leads');
            $leads = $leads->orderBy('created_at', 'desc');

            if ($request->month != '' || $request->year != '') {
                $leads = $leads->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year);
            }
            $leads = $leads->get();
            if ($leads) {
                return view('pages.dashboard.leads_list', compact('leads'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function get_leads_list(Request $request)
    {
        try {
            $data = DB::table('leads')->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->get();
            $out = '';
            $out .= '<table class="table leads_list wrap dataTable js-exportable1">
            <thead>
            <tr>
                <th>#</th>
                 <th>Name</th>
                 <th>Society Name</th>
                 <th>Units</th>
                 <th>Mobile No</th>
                 <th>Email</th>
                 <th>City</th>
                 <th>Any Query</th>
                 <th>Area</th>
                 <th>Address</th>
                 <th>Role</th>
                 <th>Services</th>
                 <th>From</th>
                 <th>Lead Source</th>
                 <th>Created Date</th>
            </tr>
            </thead><tbody>';
            $i = 1;
            foreach ($data as $row) {
                $out .= '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . $row->name . '</td>
                    <td>' . $row->society_name . '</td>
                    <td>' . $row->units . '</td>
                    <td>' . $row->mobile_no . '</td>
                    <td>' . $row->email . '</td>
                    <td>' . $row->city . '</td>
                    <td>' . $row->any_query . '</td>
                    <td>' . $row->area . '</td>
                    <td>' . $row->address . '</td>
                    <td>' . $row->role . '</td>
                    <td>' . $row->services . '</td>
                    <td>' . $row->from . '</td>
                    <td>' . $row->lead_source . '</td>
                    <td>' . date('d-M-Y', strtotime($row->created_at)) . '</td>
                </tr>';
            }
            $out .= '</tbody></table>';
            return $out;
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        }
    }

    public function raise_attendance()
    {
        try {
            $all_raise_attendance = DB::table('attendance')
                ->join('staff', 'staff.sid', 'attendance.staff_id')
                ->select('attendance.created_at', 'attendance.remark', 'attendance.signin_time', 'attendance.signout_time', 'staff.name')
                ->where('status', 'raised')
                ->orderby('created_at', 'desc')
                ->get();

            if ($all_raise_attendance) {
                return view('pages.dashboard.raise_attendance', compact('all_raise_attendance'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Some error while fetching data'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        }
    }


    public function office_visit()
    {
        try {

            $get_office_visit = DB::table('office_visit')
                ->join('staff', 'staff.sid', 'office_visit.visit_by')
                ->select('office_visit.*', 'staff.name')
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($get_office_visit as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                $row->dept_address = DB::table('dept_address')->where('id', $row->dept_address_id)->value('department_name');
                $row->time = date('H:i', strtotime($row->created_at));
            }
            if ($get_office_visit) {
                return view('pages.dashboard.office_visit', compact('get_office_visit'));
            } else {
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong'));
        }
    }

    public function save_firebase_token(Request $request)
    {
        
        try {
         
            if (!empty($request->token)) {
                
                $save = false;
                //web token
                if ($request->type == 'web') {
                    Log::info('web token');
                    $check_web = DB::table('firebase_tokens')->where('type', 'web')->where('device_token', $request->token)->count();
                    if ($check_web == 0) {
                        $save = DB::table('firebase_tokens')->insert(['staff_id' => session('staff_id'), 'type' => $request->type, 'device_token' => $request->token, 'created_at' => now()]);
                    } else {
                        $save = DB::table('firebase_tokens')->where('staff_id', session('staff_id'))->where('device_token', $request->token)->update(['device_token' => $request->token, 'type' => $request->type, 'updated_at' => now()]);
                    }
                }
                //app device token save 
                if ($request->type == 'app') {
                    Log::info('app token');
                    $check_app = DB::table('firebase_tokens')->where('type', 'app')->where('device_token', $request->token)->count();
                    if ($check_app == 0) {
                        $save = DB::table('firebase_tokens')->insert(['staff_id' => $request->staff_id, 'type' => $request->type, 'device_token' => $request->token, 'created_at' => now()]);
                    } else {
                        $save = DB::table('firebase_tokens')->where('staff_id', $request->staff_id)->where('device_token', $request->token)->update(['device_token' => $request->token, 'type' => $request->type, 'updated_at' => now()]);
                    }
                }
                if ($save) {
                    return response()->json(array('status' => 'success'));
                } else {
                    return response()->json(array('status' => 'error'));
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
    public function get_lead_source(Request $request)
    {

        $year = $request->year;
        $year = explode('-', $year);
        $prev_yr = $year[0];
        $nxt_yr = $year[1];

        $from_dt = $year[0] . '-03-31';
        $to_dt = $year[1] . '-04-01';

        $source = DB::table('source')->get();
        $data = array();
        $label = array();
        $from_date = 0;
        foreach ($source as $row) {
            $label[] = $row->source;
            $data[] = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', [$from_date, $to_dt])
                ->where('clients.status', 'active')->where('clients.source', $row->id)->count();
        }

        return json_encode(array('label' => $label, 'data' => $data));
    }

    public function export_client_contacts(Request $request)
    {
        $month = $request->contact_select;
        $year = date('Y');
        $monthName = Carbon::create(null, $month, 1);
        $monthAbbr = $monthName->shortEnglishMonth;

        $fileName = 'client_contacts_' . $monthAbbr . '_' . $year . '.csv';

        $contact_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(
                'client_contacts.client_id',
                'clients.client_name',
                'clients.case_no',
                'clients.date',
                DB::raw("GROUP_CONCAT(DISTINCT client_contacts.contact SEPARATOR '|') as `client_contact`"),
                DB::raw("GROUP_CONCAT(DISTINCT client_contacts.email SEPARATOR '|') as `contact_email`"),
                DB::raw("GROUP_CONCAT(DISTINCT client_contacts.whatsapp SEPARATOR '|') as `client_whatsapp`")
            )
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->whereMonth('clients.date', $month)
            ->whereYear('clients.date', $year)
            ->groupBy('client_contacts.client_id', 'clients.client_name', 'clients.case_no', 'clients.date')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $contact_array = array('Client Name', 'Case Number', 'Mobile Number', 'Email', 'Whatsapp Number');

        $callback = function () use ($contact_list, $contact_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $contact_array);

            foreach ($contact_list as $cont) {
                $row['client_name']  = $cont->client_name;
                $row['case_no']  = $cont->case_no;
                $row['client_contact']  = $cont->client_contact;
                $row['contact_email']  = $cont->contact_email;
                $row['client_whatsapp']  = $cont->client_whatsapp;

                fputcsv($file, array($row['client_name'], $row['case_no'], $row['client_contact'], $row['contact_email'], $row['client_whatsapp']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
     public function assign_client_due(Request $request)
    {
       
        try
        {
            $due_payment_list = DB::table('bill')->join('clients', 'clients.id', 'bill.client')->select('bill.*')->where('clients.assign_to',session('staff_id'))->where('bill.company', session('company_id'))->where('bill.status', '!=', 'paid')->where('bill.active', 'yes')->orderBy('bill.bill_date', 'desc')->get();
            $due_pro_payment_list = DB::table('proforma_invoice')->join('clients', 'clients.id', 'proforma_invoice.client')
            ->where('clients.assign_to',session('staff_id'))
            ->where('proforma_invoice.company', session('company_id'))->where('proforma_invoice.convert_tax', 'no')->where('proforma_invoice.status', '!=', 'paid')->where('proforma_invoice.active', 'yes')->orderBy('proforma_invoice.bill_date', 'desc')->get();
            foreach ($due_payment_list as $inv) {
                $services_arr = json_decode($inv->service);
                $amount_arr = json_decode($inv->amount);
                $quotation_array = json_decode($inv->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('paid_amount');
                $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $inv->id)->where('active', 'yes')->sum('tds_amount');
                $inv->payable = $inv->total_amount - ($paid_amt + $tds_amt);
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }
                
                
                    $inv->service = $service;
                    $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
                    $inv->client_name = DB::table('clients')->where('id', $inv->client)->value('client_name');
                    
                    $assign_to=DB::table('clients')->where('id', $inv->client)->value('assign_to');
                    $inv->case_no  = DB::table('clients')->where('id', $inv->client)->value('case_no');
                    $inv->assign_to=DB::table('staff')->where('sid',$assign_to)->value('name');
                    $inv->total_amount    = number_format($inv->total_amount, 2);
                    $inv->due_amount    = number_format($inv->payable, 2);
                    $inv->status  = $inv->status;
                    $inv->bill_date  = date('d-m-Y', strtotime($inv->bill_date));
                    $inv->due_date  = date('d-m-Y', strtotime($inv->due_date));
                    $inv->type  = 'Invoice';
                
            }
            foreach ($due_pro_payment_list as $proinv) {
                $services_arr = json_decode($proinv->service);
                $amount_arr = json_decode($proinv->amount);
                $quotation_array = json_decode($proinv->quotation);

                $proinv->payable = $proinv->total_amount;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt;
                    }
                }

                
                    $proinv->service = $service;
                    $proinv->tds_applicable = DB::table('company')->where('id', $proinv->company)->value('tds_applicable');
                    $proinv->client_name  = DB::table('clients')->where('id', $proinv->client)->value('client_name');
                    $assign_to=DB::table('clients')->where('id', $proinv->client)->value('assign_to');
                   
                    $proinv->case_no  = DB::table('clients')->where('id', $proinv->client)->value('case_no');
                    $proinv->assign_to=DB::table('staff')->where('sid',$assign_to)->value('name');
                    
                    $proinv->total_amount    = number_format($proinv->total_amount, 2);
                    $proinv->due_amount    = number_format($proinv->payable, 2);
                    $proinv->status  = $proinv->status;
                    $proinv->bill_date  = date('d-m-Y', strtotime($proinv->bill_date));
                    $proinv->due_date  = date('d-m-Y', strtotime($proinv->due_date));

                    $proinv->type  = 'Proforma Invoice';

                    
                
            }
          
            return view('pages.dashboard.assign_due_table', compact('due_payment_list','due_pro_payment_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
       public function daily_report(Request $request)
    {
        
        try {
            Log::info("Cron is working fine!");
            $month = date('m');
            $year=date('Y');
            $open_quotation =DB::table('quotation')
                                    ->join('quotation_details','quotation_details.quotation_id','quotation.id')
                                    ->join('clients','quotation.client_id','clients.id')
                                    ->join('services','quotation_details.task_id','services.id')
                                    ->select('services.name As service_name','clients.client_name','quotation_details.*','quotation.send_date')
                                    ->where('quotation_details.finalize','no')->whereMonth('quotation.send_date',$month)
                                    ->whereYear('quotation.send_date',$year)->orderBy('quotation.send_date','desc')->get();
             
            return view('pages.dashboard.daily_report_table', compact('open_quotation'));
           
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
        }
    }
}
