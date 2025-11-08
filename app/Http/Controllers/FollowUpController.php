<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\StaffTraits;
use App\Traits\ClientTraits;

class FollowUpController extends Controller
{
    use StaffTraits;
    use ClientTraits;
    public function followUp_fetch(Request $request)
    { //FETCHING FOLLOWUP TABLE DETAILS BASED ON CLIENT ID

        $v = Validator::make($request->all(), [
            'id' => 'required|numeric',

        ]);

        if ($v->fails()) {
            return $v->errors();
        }

        $followUp = DB::table('follow_up')->where('client_id', $request->id)->get();
        return response()->json($followUp);
    }
    public function followUpById_fetch(Request $request)
    { //FETCHING FOLLOWUP TABLE DETAILS BASED ON CLIENT ID
        $v = Validator::make($request->all(), [
            'id' => 'required|numeric',

        ]);

        if ($v->fails()) {
            return $v->errors();
        }
        $followUp = DB::table('follow_up')
            ->join('clients', 'follow_up.client_id', '=', 'clients.id')
            ->join('client_contacts', 'follow_up.client_id', '=', 'client_contacts.client_id')
            ->where('follow_up.client_id', $request->id)
            ->select('follow_up.*', 'clients.*', 'client_contacts.*')
            ->get();
        foreach ($followUp as $row) {
               $method_data =$row->method;
                $contact_to_data = DB::table('client_contacts')->where('id',$row->contact_to)->value('name');
            $row->contact_to_data = $contact_to_data;
            $row->contact_by = DB::table('staff')->where('sid', $row->contact_by)->value('name');
        }
        return response()->json($followUp);
    }
    public function search_followup(Request $request)
    {
        $v = Validator::make($request->all(), ['type' => 'string|required', 'company' => 'numeric|required']);

        if ($v->fails()) {
            return $v->errors();
        }

        try {
            $select_by = $request->type;
            $company = $request->company;
            $contact_by = $request->staff_id;

            $date_array = array();
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');
            $date_array = array($startdate, $enddate);
            if ($select_by == 'today') {

                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->where('follow_up.followup_date', date('Y-m-d'))
                    ->where('follow_up.company', $company)
                    ->where('follow_up.contact_by', $contact_by)
                    ->orderBy('follow_up.followup_date','desc')->get();
            }
            if ($select_by == 'weekly') {
                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->whereBetween('follow_up.followup_date', $date_array)
                    ->where('follow_up.company', $company)
                    ->where('follow_up.contact_by', $contact_by)
                    ->orderBy('follow_up.followup_date','desc')->get();
            }
            if ($select_by == 'monthly') {
                $month = $request->month;
                $year = $request->year;
                if ($month == '' || $year == '') {
                    return response()->json(array('status' => 'Error', 'msg' => 'Month and year required'));
                }
                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->whereMonth('follow_up.followup_date', $month)->whereYear('follow_up.followup_date', $year)
                    ->where('follow_up.company', $company)
                    ->where('follow_up.contact_by', $contact_by)
                    ->orderBy('follow_up.followup_date','desc')->get();
            }
            foreach ($follow_up as $row) {

              if($row->followup_date != '' || $row->followup_date != null){
                $row->followup_date=date('d-M-Y',strtotime($row->followup_date));
              }
              if($row->next_followup_date != '' || $row->next_followup_date != null){
                $row->next_followup_date=date('d-M-Y',strtotime($row->next_followup_date));
              }

                $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
               
               $method_data=$row->method;
               $row->contact_to_data = DB::table('client_contacts')->where('id',$row->contact_to)->value('name');
               $row->contact_no=DB::table('client_contacts')->where('id',$row->contact_to)->value('contact');
               $row->contact_by = DB::table('staff')->where('sid', $row->contact_by)->value('name');
            }

            return response()->json(array('status' => 'success', 'follow_up' => $follow_up));
        } catch (\Throwable $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function search_next_followup(Request $request)
    {
        $v = Validator::make($request->all(), ['type' => 'string|required', 'company' => 'numeric|required']);

        if ($v->fails()) {
            return $v->errors();
        }

        try {
            $select_by = $request->type;
            $company = $request->company;
            $contact_by = $request->staff_id;

            $date_array = array();
            $startdate = date('Y-m-d');
            $enddate = date('Y-m-d', strtotime('+7 days'));
            $date_array = array($startdate, $enddate);
            if ($select_by == 'today') {

                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->where('follow_up.next_followup_date', date('Y-m-d'))->where('follow_up.company', $company)->orderBy('follow_up.next_followup_date', 'desc')
                     ->where('follow_up.contact_by', $contact_by)->get();
            }
            if ($select_by == 'weekly') {
                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->whereBetween('follow_up.next_followup_date', $date_array)->where('follow_up.company', $company)->orderBy('follow_up.next_followup_date', 'desc')
                    ->where('follow_up.contact_by', $contact_by)->get();
            }
            if ($select_by == 'monthly') {
                $month = $request->month;
                $year = $request->year;
                if ($month == '' || $year == '') {
                    return response()->json(array('status' => 'Error', 'msg' => 'Month and year required'));
                }
                $follow_up = DB::table('follow_up')
                    ->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')
                    ->whereMonth('follow_up.next_followup_date', $month)->whereYear('follow_up.followup_date', $year)->where('follow_up.company', $company)->orderBy('follow_up.next_followup_date', 'desc')
                    ->where('follow_up.contact_by', $contact_by)->get();
            }
            foreach ($follow_up as $row) {
                if($row->followup_date != '' || $row->followup_date != null){
                  $row->followup_date=date('d-M-Y',strtotime($row->followup_date));
                }
                if($row->next_followup_date != '' || $row->next_followup_date != null){
                  $row->next_followup_date=date('d-M-Y',strtotime($row->next_followup_date));
                }
                $row->client_case_no = $this->get_client_case_no_by_id($row->client_id);
               
               
               

                    $method_data=$row->method;
              
                $row->contact_to_data = DB::table('client_contacts')->where('id',$row->contact_to)->value('name');
                $row->contact_by = DB::table('staff')->where('sid', $row->contact_by)->value('name');
            }

            return response()->json(array('status' => 'success', 'follow_up' => $follow_up));
        } catch (\Throwable $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function save_follow_up(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'client_id' => 'numeric|required',
                'followup_date' => 'required',
                'contact_by' => 'numeric|required',
                'contact_to' => 'required',
                'method' => 'required',
                'discussion' => 'string|required',
                'company' => 'numeric|required'
            ]);

            if ($v->fails()) {
                return $v->errors();
            }



            $company = $request->company;
            $client_id = $request->client_id;

            if ($request->wantsJson()) {
                $followup_date = $request->followup_date;
                $contact_by = $request->contact_by;
                $contact_by = DB::table('users')->where('id', $contact_by)->value('user_id');
            } else {

                $followup_date = $request->followup_date;
                $followup_date = str_replace('/', '-', $followup_date);
                $followup_date = date('Y-m-d', strtotime($followup_date));
                $contact_by = $request->contact_by;
            }
            $next_followup_date = $request->next_followup_date;
            $contact_to = $request->contact_to;
            $method = $request->method;
            if ($request->wantsJson()) {
                $next_followup_date = $request->next_followup_date;
                $method = $request->method;
            
            $method=$method;
            $contact_to = $request->contact_to;
            $contact_to=$contact_to;
            } else {
                if ($next_followup_date != "") {

                    $next_followup_date = str_replace('/', '-', $next_followup_date);
                    $next_followup_date = date('Y-m-d', strtotime($next_followup_date));
                    $method = $request->method;
                   $contact_to = $request->contact_to;
          
                }
            }

            
            
            
            $finalized = $request->finalized;
            $lead_closed = $request->lead_closed;
            $discussion = $request->discussion;
            if ($next_followup_date == "" && $finalized == "") {
                return response()->json(array('status' => 'error', 'msg' => 'Please select next_follow-Up date or Check finalized'));
            }
            $insert = DB::table('follow_up')->insert([
                'client_id' => $client_id,
                'contact_to' => $contact_to,
                'method'    => $method,
                'contact_by' => $contact_by,
                'followup_date' => $followup_date,
                'next_followup_date' => $next_followup_date,
                'finalized'    => $finalized,
                'lead_closed' => $lead_closed,
                'discussion' => $discussion,
                'company' => $company,
                'created_at' => now()
            ]);
            if ($insert) {
                return response()->json(array('status' => 'success', 'msg' => 'Follow-up detail Submited'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Follow-up can`t be Submit'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function follow_up_list(Request $request)
    {
        
        try {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', 'Please login first');
            }
            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();
            $staff = $this->get_staff_list_userid();
            $company = DB::table('company')->get();

            return view('pages.follow_up.follow_up-list', compact('clients', 'staff', 'company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function follow_up_add(Request $request)
    {
        try {
            $client_id = $request->id;
            if ($client_id == '') {
                $client_id = '';
            }
            $clients = DB::table('clients')->where('status', 'active')->get();
            $staff = $this->get_staff_list_userid_company();
            $company = DB::table('company')->get();
            return view('pages.follow_up.follow-up-add', compact('client_id', 'clients', 'staff', 'company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function get_contacts_followup(Request $request)
    {
        try {
            $client_id = $request->id;
            $clients = DB::table('clients')->get();
            $client_contact = DB::table('client_contacts')->where('client_id', $client_id)->get();

            return view('pages.follow_up.get_contacts_follow_up', compact('client_contact'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function autocomplete_followup_disc(Request $request)
    {
        $followup_disc = DB::table('follow_up')->where('followup_date', '>=', '2021-05-31')->get();
        $followup_disc_array = array();
        foreach ($followup_disc as $row) {
            array_push($followup_disc_array, $row->discussion);
        }
        return $followup_disc_array = $followup_disc_array;
    }

    public function get_follow_up_details(Request $request)
    {
        try {

            $staff = $this->get_staff_list_userid();
            $staff_id = array_column(json_decode($staff, true), 'sid');

            $clients = DB::table('follow_up')
                ->join('clients', 'clients.id', 'follow_up.client_id')
                ->select('follow_up.client_id')->where('follow_up.company', session('company_id'));
            if (session('role_id') == 1) {
                if ($request->page == 'my_follow_up' || $request->page == 'my_next_follow_up') {
                    $clients = $clients->where('clients.assign_to', session('staff_id'));
                } else {
                    $clients = $clients->whereIn('clients.assign_to', $staff_id);
                }
            } else {
                if ($request->page == 'follow_up' || $request->page == 'my_follow_up' || $request->page == 'my_next_follow_up') {
                    $clients = $clients->where('clients.assign_to', session('staff_id'));
                }
            }

            if ($request->page == 'my_next_follow_up') {
                $clients = $clients->orderBy('follow_up.next_followup_date', 'desc');
            } else {
                $clients = $clients->orderBy('follow_up.followup_date', 'desc');
            }

            $clients = $clients->distinct()->get();

            $follow_up = [];
            foreach ($clients as $client) {
                $followup = DB::table('follow_up')
                    ->leftJoin('clients', 'clients.id', 'follow_up.client_id')
                    ->leftJoin('staff', 'follow_up.contact_by', 'staff.sid')
                    ->select('follow_up.id', 'follow_up.client_id', 'follow_up.followup_date', 'follow_up.next_followup_date', 'follow_up.finalized', 'follow_up.lead_closed', 'follow_up.discussion', 'follow_up.contact_to', 'follow_up.contact_by', 'follow_up.method', 'staff.name as contact_by_name', 'clients.client_name', 'clients.case_no')
                    ->where('follow_up.client_id', $client->client_id);


                if (($request->from_date != '' && $request->to_date != '') || $request->staff != '' || $request->method != '' || $request->type != '' || $request->follow) {
                    if ($request->staff != '') {
                        $followup = $followup->where('follow_up.contact_by', $request->staff);
                    }
                    if ($request->method != '') {
                        $followup = $followup->where('follow_up.method',$request->method);
                    }
                    if ($request->type == 'finalized') {
                        $followup = $followup->where('follow_up.finalized', 'yes');
                    }
                    if ($request->type == 'lead_closed') {
                        $followup = $followup->where('follow_up.lead_closed', 'yes');
                    }

                    if ($request->page == 'my_follow_up') {
                        if ($request->from_date != '' && $request->to_date != '') {
                            $from_date = date('Y-m-d', strtotime($request->from_date));
                            $to_date = date('Y-m-d', strtotime($request->to_date));
                            $followup = $followup->whereBetween('follow_up.followup_date', [$from_date, $to_date]);
                        }
                        $followup = $followup->orderBy('follow_up.followup_date', 'DESC');
                    } elseif ($request->page == 'my_next_follow_up') {
                        if ($request->from_date != '' && $request->to_date != '') {
                            $from_date = date('Y-m-d', strtotime($request->from_date));
                            $to_date = date('Y-m-d', strtotime($request->to_date));
                            $followup = $followup->whereBetween('follow_up.next_followup_date', [$from_date, $to_date]);
                        }
                        $followup = $followup->orderBy('follow_up.next_followup_date', 'DESC');
                    } else {
                        if ($request->follow == 'next_follow_up') {
                            if ($request->from_date != '' && $request->to_date != '') {
                                $from_date = date('Y-m-d', strtotime($request->from_date));
                                $to_date = date('Y-m-d', strtotime($request->to_date));
                                $followup = $followup->whereBetween('follow_up.next_followup_date', [$from_date, $to_date]);
                            }
                            $followup = $followup->orderBy('follow_up.next_followup_date', 'DESC');
                        } elseif ($request->follow == 'follow_up') {
                            if ($request->from_date != '' && $request->to_date != '') {
                                $from_date = date('Y-m-d', strtotime($request->from_date));
                                $to_date = date('Y-m-d', strtotime($request->to_date));
                                $followup = $followup->whereBetween('follow_up.followup_date', [$from_date, $to_date]);
                            }
                            $followup = $followup->orderBy('follow_up.followup_date', 'DESC');
                        } else {
                            if ($request->from_date != '' && $request->to_date != '') {
                                $from_date = date('Y-m-d', strtotime($request->from_date));
                                $to_date = date('Y-m-d', strtotime($request->to_date));
                                $followup = $followup->whereBetween('follow_up.followup_date', [$from_date, $to_date]);
                            }
                            $followup = $followup->orderBy('follow_up.followup_date', 'DESC');
                        }
                    }
                } else {
                    if ($request->page == 'my_next_follow_up') {
                        $followup = $followup->orderBy('follow_up.next_followup_date', 'DESC');
                    } else {
                        $followup = $followup->orderBy('follow_up.followup_date', 'DESC');
                    }
                }

                $followup = $followup->first();
                if ($followup) {
                    array_push($follow_up, $followup);
                    $contact_to = $follow_up[sizeof($follow_up) - 1]->contact_to;
                    $follow_up[sizeof($follow_up) - 1]->method_data = $follow_up[sizeof($follow_up) - 1]->method;
                    $follow_up[sizeof($follow_up) - 1]->contact_to_data = DB::table('client_contacts')->where('id', $contact_to)->value('name');
                    $follow_up[sizeof($follow_up) - 1]->staff_name = DB::table('staff')->join('clients', 'clients.assign_to', 'staff.sid')->where('clients.id', $client->client_id)->value('staff.name');
                }
            }
            if ($request->page == 'my_next_follow_up') {
                usort($follow_up, function ($a, $b) {
                    return strtotime($b->next_followup_date) - strtotime($a->next_followup_date);
                });
            } else {
                if ($request->follow == 'follow_up') {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->followup_date) - strtotime($a->followup_date);
                    });
                } elseif ($request->follow == 'next_follow_up') {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->next_followup_date) - strtotime($a->next_followup_date);
                    });
                } else {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->followup_date) - strtotime($a->followup_date);
                    });
                }
            }
            $follow_up=(array)json_encode($follow_up);
            return view('pages.follow_up.get_follow_up', compact('follow_up', 'staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return json_encode(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'Error'));
        }
    }

    public function delete_follow_up(Request $request)
    {
        try {
            $id = $request->id;
            $delete = DB::table('follow_up')->where('id', $id)->delete();
            if ($request->wantsJson()) {
                if ($delete) {
                    return json_encode(array('status' => 'success', 'msg' => 'Follow up has been deleted successfully!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Follow up can not be deleted'));
                }
            } else {
                log::info("delete client by=" . session('username'));
                if ($delete) {
                    return json_encode(array('status' => 'success', 'msg' => 'Follow up has been deleted successfully!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Follow up can not be deleted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        }
    }

    public function get_followup_call_detail(Request $request)
    {
        try {
            $client_id = $request->client_id;
            $follow_up = DB::table('follow_up')->where('client_id', $client_id)->get();

            foreach ($follow_up as $row) {
               
               
                $method_data =$row->method;
                $contact_to_data = DB::table('client_contacts')->where('id',$row->contact_to)->value('name');
               
                $contact_by = DB::table('staff')->where('sid', $row->contact_by)->value('name');

                $row->contact_to_data = $contact_to_data;
                $row->method_data = $method_data;
                $row->contact_by = $contact_by;
            }

            return view('pages.follow_up.get_follow_up_call_details', compact('follow_up'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function my_next_followup(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', 'Please login first');
            }
            $clients = DB::table('clients')->get();
            $staff = $this->get_staff_list_userid_company();
            $company = DB::table('company')->get();

            return view('pages.follow_up.my_next_followup', compact('clients', 'staff', 'company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }

    public function my_followup(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('alert-danger', 'Please login first');
            }
            $clients = DB::table('clients')->get();
            $staff = $this->get_staff_list_userid_company();
            $company = DB::table('company')->get();

            return view('pages.follow_up.my_followup', compact('clients', 'staff', 'company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function search_follow_up(Request $request)
    {
        try {

           
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            $staff=$request->staff;
            $method=$request->method;
            $follow=$request->follow;
            $type=$request->type;
            $company=session('company_id');
         
                $clients = DB::table('follow_up')->where('company',$company)->groupBy('client_id')->pluck('client_id');
                // $client_id = array_column(json_decode($clients, true), 'client_id');
               
               
                    $follow_up_id_arr=array();$follow_up=[];
                   
                        if($follow=='next_follow_up')
                        {
                        $follow_up_id = DB::table('follow_up')->where('company',$company)->whereIn('client_id',$clients)->orderBy('next_followup_date','desc')->pluck('id');
                        $followup =DB::table('follow_up')->whereIn('id',$follow_up_id);   
                        }
                       else if($follow=='follow_up')
                        {
                            $follow_up_id = DB::table('follow_up')->where('company',$company)->whereIn('client_id',$clients)->orderBy('followup_date','desc')->pluck('id');
                            $followup =DB::table('follow_up')->whereIn('id',$follow_up_id);
                        }
                        else
                        {
                            $followup =DB::table('follow_up');
                        }
                       
                        if($staff!='')
                    {
                        $followup =$followup->where('contact_by',$staff);
                    }
                    if($type!='')
                    {
                        if ($request->type == 'finalized') {
                            $followup =$followup->where('finalized', 'yes');
                        }
                        if ($request->type == 'lead_closed') {
                            $followup =$followup->where('lead_closed', 'yes');
                        }
                    }
                    if ($from_date != '' && $to_date != '') {
                        if($follow=='next_follow_up')
                        {
                        $followup =$followup->whereBetween('next_followup_date', [$from_date, $to_date]);
                        }
                       else if($follow=='follow_up')
                        {
                        $followup =$followup->whereBetween('followup_date', [$from_date, $to_date]);
                        }
                        else
                        {
                            $followup =$followup->whereBetween('followup_date', [$from_date, $to_date]);
                        }
                    }
                    
                    $followup1 =$followup->whereIn('client_id',$clients)->get();
                   
                    
                    
                      
                       
                            foreach($followup1 as $follow1)
                            {
                                
                                $follow1->method_data=$follow1->method;
                                $follow1->contact_to_data=DB::table('client_contacts')->where('id',$follow1->contact_to)->value('name');
                                $follow1->client_name=DB::table('clients')->where('id',$follow1->client_id)->value('client_name');
                                $follow1->case_no=DB::table('clients')->where('id',$follow1->client_id)->value('case_no');
                                $follow1->staff_name=DB::table('staff')->join('clients', 'clients.assign_to', 'staff.sid')->where('clients.id', $follow1->client_id)->value('staff.name');
                                $follow1->contact_by_name=DB::table('staff')->where('sid', $follow1->contact_by)->value('name');
                            }
                           
                           
                        
                    
                   
                $follow_up=(array)json_encode($followup1);
              
                if($follow=='next_follow_up')
                {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->next_followup_date) - strtotime($a->next_followup_date);
                    });
                }
               else if($follow=='follow_up')
                {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->followup_date) - strtotime($a->followup_date);
                    });
                }
                else
                {
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->followup_date) - strtotime($a->followup_date);
                    });
                }
             
           
            return view('pages.follow_up.get_follow_up', compact('follow_up', 'staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return json_encode(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function search_mynext_followup(Request $request)
    {
        try {

            
            $staff_id = session('staff_id');
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
           
            $method=$request->method;
          
            $type=$request->type;
            $company=session('company_id');
         
                $clients = DB::table('follow_up')
                ->join('clients','clients.id','follow_up.client_id')
                ->select('follow_up.client_id')->where('follow_up.company',$company)->where('clients.assign_to',$staff_id)->where('clients.status','active')->groupBy('follow_up.client_id')->get();
                // $client_id = array_column(json_decode($clients, true), 'client_id');
               
               
                    $follow_up_id_arr=array();$follow_up=[];
                    foreach($clients as $client)
                    {
                        
                        $follow_up_id = DB::table('follow_up')->where('company',$company)->where('client_id',$client->client_id)->orderBy('next_followup_date','desc')->value('id');
                        $followup =DB::table('follow_up')->where('id',$follow_up_id);   
                       
                       
                   
                    if($type!='')
                    {
                        if ($request->type == 'finalized') {
                            $followup =$followup->where('finalized', 'yes');
                        }
                        if ($request->type == 'lead_closed') {
                            $followup =$followup->where('lead_closed', 'yes');
                        }
                    }
                    if ($from_date != '' && $to_date != '') {
                       
                        $followup =$followup->whereBetween('next_followup_date', [$from_date, $to_date]);
                      
                    }
                    $followup =$followup->value('id');
                    if($follow_up!='')
                    {
                        $followup1 = DB::table('follow_up')
                        ->leftJoin('clients', 'clients.id', 'follow_up.client_id')
                        ->leftJoin('staff', 'follow_up.contact_by', 'staff.sid')
                        ->select('follow_up.id', 'follow_up.client_id', 'follow_up.followup_date', 'follow_up.next_followup_date', 'follow_up.finalized', 'follow_up.lead_closed', 'follow_up.discussion', 'follow_up.contact_to', 'follow_up.contact_by', 'follow_up.method', 'staff.name as contact_by_name', 'clients.client_name', 'clients.case_no')
                        ->where('follow_up.id',$followup)->first();
                        if ($followup) {
                            array_push($follow_up, $followup1);
                            $contact_to = json_decode($follow_up[sizeof($follow_up) - 1]->contact_to);
                            $follow_up[sizeof($follow_up) - 1]->method_data = $follow_up[sizeof($follow_up) - 1]->method;
                            $follow_up[sizeof($follow_up) - 1]->contact_to_data =DB::table('client_contacts')->where('id',$contact_to)->value('name');
                            $follow_up[sizeof($follow_up) - 1]->staff_name = DB::table('staff')->join('clients', 'clients.assign_to', 'staff.sid')->where('clients.id', $client->client_id)->value('staff.name');
                        }
                    }
                   
                }

               
                    usort($follow_up, function ($a, $b) {
                        return strtotime($b->next_followup_date) - strtotime($a->next_followup_date);
                    });
               
               $follow_up=(array)json_encode($follow_up);
            
           
            return view('pages.follow_up.get_follow_up', compact('follow_up'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return json_encode(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'Error'));
        }
    }

    public function save_check_in(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'client_id' => 'numeric|required',
                'location'=>'array|required',
                'contact_by' => 'numeric|required',
                'company' => 'numeric|required',
            ]);
            if ($v->fails()) {
                return $v->errors();
            }

            log::info("save_check_in");
            log::info(json_encode($request->all()));
             if($request->location==array("\"\"","\"\""))
            {
                return response()->json(array('status' => 'error', 'msg' => 'Please select location first'));
            }
            $img_s3_url = null;
            if ($request->has('image') || $request->image != '') {
                    $target_dir = 'checkin_images/';
                    $base64Image = explode(";base64,", $request->image);
                    $explodeImage = explode("image/", $base64Image[0]);
                    $imageType = $explodeImage[1];
                    $image_base64 = base64_decode($base64Image[1]);
                    $path = $target_dir .'check_in_selfie_'.strtotime(date('Y-m-d H:i:s')) . '.'.$imageType;
                    
                    Storage::disk('s3_quotations')->put($path, $image_base64, 'public');
                    $img_s3_url = Storage::disk('s3_quotations')->url($path);
            }

            $company = $request->company;
            $client_id = $request->client_id;
            $contact_by = $request->contact_by;
            $method =  "visit";
            $discussion = $request->discussion;
            $location = json_encode($request->location);
            $address=$request->address;
            $followup_date = date('Y-m-d');

             $next_followup_date = null;
             if($request->next_followup_date != ''){
                $next_followup_date = str_replace('/', '-', $request->next_followup_date);
                $next_followup_date = date('Y-m-d', strtotime($next_followup_date));
             }
            $insert = DB::table('follow_up')->insert([
                'client_id' => $client_id,
                'method'    => $method,
                'contact_by' => $contact_by,
                'followup_date' => $followup_date,
                'next_followup_date' => $next_followup_date,
                'discussion' => $discussion,
                'location' => $location,
                'address'=>$address,
                'image' => $img_s3_url,
                'company' => $company,
                'created_at' => now()
            ]);
             $insert_ofc_visit = DB::table('office_visit')->insert([
                'client_id' => $client_id,
                'visit_by' => $contact_by,
                'visit_date' => $followup_date,
                'discussion' => $discussion,
                'location' => $location,
                 'address'=>$address,
                'photo' => $img_s3_url,
                'company' => $company,
                'created_at' => now()
            ]);
            if ($insert && $insert_ofc_visit) {
                return response()->json(array('status' => 'success', 'msg' => 'Check-In added successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Check-In can`t be added'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }

        public function save_office_visit(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'dept_address_id' => 'numeric|required',
                'visit_by' => 'numeric|required',
                'company' => 'numeric|required',
                'location'=>'array|required'
            ]);
            if ($v->fails()) {
                return $v->errors();
            }
            
            if($request->location==array("\"\"","\"\""))
            {
                return response()->json(array('status' => 'error', 'msg' => 'Please select location first'));
            }
           
            $img_s3_url = null;
            if ($request->has('photo') || $request->photo != '') {
                    $target_dir = 'office_visit_photos/';
                    $base64Image = explode(";base64,", $request->photo);
                    $explodeImage = explode("image/", $base64Image[0]);
                    $imageType = $explodeImage[1];
                    $image_base64 = base64_decode($base64Image[1]);
                    $path = $target_dir .'selfie_'.strtotime(date('Y-m-d H:i:s')) . '.'.$imageType;
                    
                    Storage::disk('s3_quotations')->put($path, $image_base64, 'public');
                    $img_s3_url = Storage::disk('s3_quotations')->url($path);
            }

            $location = $request->location;
            $address=$request->address;
            $visit_date = date('Y-m-d');
 
            $insert = DB::table('office_visit')->insert([
                'dept_address_id' => $request->dept_address_id,
                'visit_by' => $request->visit_by,
                'visit_date' => $visit_date,
                'company' => $request->company,
                'location' => json_encode($location),
                'address'=>$address,
                'photo' => $img_s3_url,
                'discussion' => $request->discussion,
                'created_at' => now()
            ]);
            if ($insert) {
                return response()->json(array('status' => 'success', 'msg' => 'Office Visit Added Successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Office Visit Can`t be Added'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }


     public function fetch_check_in(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'staff_id' => 'numeric|required',
                'date' => 'required',
              
            ]);
            if ($v->fails()) {
                  return $v->errors();
            }
            log::info('fech_check_in');
            $staff_id=$request->staff_id;
            log::info('staff_id'.$staff_id);
            $date=$request->date;
            $date=date('Y-m-d',strtotime($date));
            log::info('date'.$date);
            $follow_up_created=DB::table('follow_up')
            ->join('clients','clients.id','follow_up.client_id')
            ->where('method','visit')->select('follow_up.discussion','follow_up.location','follow_up.address','follow_up.created_at','follow_up.next_followup_date','clients.client_name')->where('follow_up.contact_by',$staff_id)->where('follow_up.followup_date',$date)->get();
            foreach($follow_up_created as $row)
            {
                $row->visit_type='client';
                $row->time=date('h:i a',strtotime($row->created_at));
                if($row->next_followup_date != ''){
                   $row->next_followup_date=date('d-M-Y',strtotime($row->next_followup_date));
                 }
            }
            $visit_created=DB::table('office_visit')
            ->join('dept_address','dept_address.id','office_visit.dept_address_id')
            ->select('office_visit.discussion','office_visit.location','office_visit.created_at','office_visit.address','dept_address.department_name')->where('office_visit.visit_by',$staff_id)->where('office_visit.visit_date',$date)->get();
            foreach($visit_created as $row1)
            {
                 $row1->visit_type='office';
                 $row1->time=date('h:i a',strtotime($row1->created_at));
                 
            }
            $data= array_merge(json_decode($follow_up_created),json_decode($visit_created));
            usort($data, function($a, $b) {
                return strtotime($a->created_at) - strtotime($b->created_at);
             });
            return json_encode(array('status'=>'success','data'=>$data));
            
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
  public function get_whatsapp_no(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'client_id' => 'numeric|required',
                
              
            ]);
            if ($v->fails()) {
                  return $v->errors();
            }
            log::info('get_whatsapp_no');
            $client_id=$request->client_id;
            log::info('client_id'.$client_id);
            $contact_no=DB::table('client_contacts')->where('client_id',$client_id)->get(['contact']);
            $out='  <style>
    .contact-list {
      max-height: 400px;
      overflow-y: auto;
    }
    .whatsapp-link {
      display: flex;
      align-items: center;
      text-decoration: none;
      padding: 6px;
      border-bottom: 1px solid #eee;
      transition: background-color 0.2s;
    }
    .whatsapp-link:hover {
      background-color: #f0fdf4;
    }
    .whatsapp-icon {
      color: #25D366;
      margin-right: 10px;
    }
  </style>';
            if(sizeof($contact_no)>0)
            {
                foreach($contact_no as $con)
                {
                     $out.='<a class="whatsapp-link" href="https://wa.me/'.$con->contact.'" target="_blank"><i class="bx bxl-whatsapp whatsapp-icon"></i>'.$con->contact.'</a>';
                }
               
            }
            else
            {
                 $out.='<div class="row">
                    <div class="col-lg-12">NO contact number found</div>
                    </div>';
            }
            
            return json_encode(array('status'=>'success','data'=>$out));
            
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }


}
