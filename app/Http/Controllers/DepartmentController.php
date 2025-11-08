<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class DepartmentController extends Controller
{
    public function save_department(Request $request)
    {
        Log::info('Inside save_deparment');
        $v = Validator::make($request->all(), [
            'department_name'=>'string|required',
            'short_name'=>'string|required',
            'address'=>'string|required',
            'geolocation'=>'array|required',
            'landmark'=>'string|required'
            
        ]);
        if ($v->fails())
        {
           return $v->errors();
        }
       try{ 
          
            $department_name=$request->department_name;
            $short_name=$request->short_name;
            $address=$request->address;
            $geolocation=$request->geolocation;
            $landmark=$request->landmark;   
            Log::info('Inside save_deparment');        
            $insert=DB::table('dept_address')->insert([
                'department_name'=>$department_name,
                'short_name'=>$short_name,
                'address'=>$address,
                'geolocation'=>json_encode($geolocation),
                'landmark'=>$landmark
            ]);
            if($insert)
            {
                return response()->json(['status'=>'success','mag'=>'Data inserted successfully']);
            }
            else
            {
                return response()->json(['status'=>'error','mag'=>'Data can`t be inserted']);
            }
            
       } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function get_department(Request $request)
    {
        
       try{ 
                $data=DB::table('dept_address')->get();
                return response()->json(['status'=>'success','data'=>$data]);
               
        }catch(QueryException $e){
            Log::error($e->getMessage());
            return response()->json(['status'=>'failed','msg'=> 'Something Went Wrong',], 500);
        }
    }
     public function update_department(Request $request)
    {
        $v = Validator::make($request->all(), [
            'department_name'=>'string|required',
            'short_name'=>'string|required',
            'address'=>'string|required',
            'geolocation'=>'array|required',
            'landmark'=>'string|required'
            
        ]);
        if ($v->fails())
        {
           return $v->errors();
        }
       try{ 
          
            $department_name=$request->department_name;
            $short_name=$request->short_name;
            $address=$request->address;
            $geolocation=$request->geolocation;
            $landmark=$request->landmark;   
            $update=DB::table('dept_address')->where('id',$request->office_id)->update([
                'department_name'=>$department_name,
                'short_name'=>$short_name,
                'address'=>$address,
                'geolocation'=>json_encode($geolocation),
                'landmark'=>$landmark
            ]);
            if($update)
            {
                return response()->json(['status'=>'success','mag'=>'Data updated successfully']);
            }
            else
            {
                return response()->json(['status'=>'error','mag'=>'Data can`t be updated']);
            }
            
        }catch(QueryException $e){
            Log::error($e->getMessage());
            return response()->json(['status'=>'failed','msg'=> 'Something Went Wrong',], 500);
        }
    }
      public function testing_mail(Request $request)
    {
     try {
            $appointmentSchedule = '';
            $staff = DB::table('users')->join('staff', 'staff.sid', 'users.user_id')->select('staff.sid', 'staff.name', 'staff.emailid', 'users.role_id')->where('users.status', 'active')->get();
            $appointmentSchedule = $appointmentMeet = $newTask = '';
            foreach ($staff as $row) {
                $company = DB::table('company')->select('id', 'company_name')->get();
                foreach ($company as $row1) {
                    if ($row->role_id == 8) {
                        $row1->todayFollowup = DB::table('clients')->join('follow_up', 'follow_up.client_id', 'clients.id')->select('clients.id', 'clients.client_name', 'clients.case_no', 'follow_up.followup_date', 'follow_up.next_followup_date')->where('assign_to', $row->sid)->where('default_company', $row1->id)->where('finalized', 'no')->where('lead_closed', 'no')
                            ->where('follow_up.next_followup_date', date('Y-m-d'))->get();

                        $row1->pendingFollowup = DB::table('clients')->join('follow_up', 'follow_up.client_id', 'clients.id')->select('clients.id', 'clients.client_name', 'clients.case_no', 'follow_up.followup_date', 'follow_up.next_followup_date')->where('assign_to', $row->sid)->where('default_company', $row1->id)->where('finalized', 'no')->where('lead_closed', 'no')->get();

                        $row1->newLead = $this->get_clients_leads_list($row1->id, 'leads', 'active', $row->sid, '', '', '', '', date("Y-m-d"), date("Y-m-d"), '', '', 1, '');

                        $row1->pendingNewLead = $this->get_clients_leads_list($row1->id, 'leads', 'active', $row->sid, '', '', '', '', '', '', '', '', 1, '');
                    }
                }

                $roleArr = [1, 2, 10];
                if (!in_array($row->role_id, $roleArr)) {
                    $row->appointmentSchedule = DB::table('appointment')->join('clients', 'clients.id', '=', 'appointment.client')->leftjoin('appointment_places', 'appointment_places.id', '=', 'appointment.place')->select('clients.id as client_ids', 'clients.client_name', 'clients.case_no', 'appointment_places.name as aname', 'appointment_places.charges', 'appointment.*')->where('schedule_by', $row->sid)->whereDate('appointment.created_at', date('Y-m-d', strtotime("-1 days")))
                        ->get();
                    $appointmentSchedule = $row->appointmentSchedule;
                    foreach ($row->appointmentSchedule as $apps) {
                        $apps->scheduled_by_staff = DB::table('staff')->where('sid', $apps->schedule_by)->value('name');
                        $apps->meeting_with_staff = DB::table('staff')->where('sid', $apps->meeting_with)->value('name');
                    }
                }

                $row->appointmentMeet = DB::table('appointment')->join('clients', 'clients.id', '=', 'appointment.client')->leftjoin('appointment_places', 'appointment_places.id', '=', 'appointment.place')->select('clients.id as client_ids', 'clients.client_name', 'clients.case_no', 'appointment_places.name as aname', 'appointment_places.charges', 'appointment.*')
                    ->where('meeting_with', $row->sid)->whereDate('appointment.meeting_date', date('Y-m-d'))
                    ->get();
                $appointmentMeet = $row->appointmentMeet;
                foreach ($row->appointmentMeet as $appm) {
                    $appm->scheduled_by_staff = DB::table('staff')->where('sid', $appm->schedule_by)->value('name');
                    $appm->meeting_with_staff = DB::table('staff')->where('sid', $appm->meeting_with)->value('name');
                }
                $staff_id= (string)($row->sid);
                $curdate= date('Y-m-d');
               $row->newTask = DB::table('task')->leftjoin("projects", "projects.id", "task.project_id")->leftjoin("task_status_master", "task_status_master.id", "task.status")->select('task.id', 'title', 'project_name', "task_status_master.status as task_status", "task.start_date", "task.end_date")
               ->whereJsonContains('task.assignee', $staff_id)
                ->whereDate('task.start_date','<=',$curdate)
                ->whereDate('task.end_date','>=',$curdate)
                ->get();
                $not_status=[5,7];
                $row->hearing = DB::table('task')->leftjoin("projects", "projects.id", "task.project_id")->leftjoin("task_status_master", "task_status_master.id", "task.status")->select('task.id', 'title', 'project_name', "task_status_master.status as task_status", "task.start_date", "task.end_date")
               ->whereJsonContains('task.assignee', $staff_id)
               ->where('task.type',4)
                ->where('task.start_date','<=','2025-02-12')
                ->where('task.end_date','>=','2025-02-12')
                ->get();
                
                $newTask = $row->newTask;
                $to_email = $row->emailid;
                $subject = 'Today`s summary';
                $mailer_username = 'no-reply@dearsociety.in';
                $mailer_password = 'uzutnhpvqrxppmdq';
                $mailer_name = 'Dear Society';
                $email_from = 'no-reply@dearsociety.in';
                $reply_to = null;
                $reply_to_name = null;
                
                return view('pages.emails.send_mail_to_sales', compact('company', 'appointmentSchedule', 'appointmentMeet', 'newTask'));
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
        }
        
    }
}
