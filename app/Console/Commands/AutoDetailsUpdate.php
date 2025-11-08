<?php

namespace App\Console\Commands;

use App\Http\Controllers\PhpMailerController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoDetailsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autodetailsupdate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
          try {

            Log::info("Cron is working fine!");
            $today = date('Y-m-d');
            $yesterday=date('Y-m-d',strtotime("-1 days"));
            $no_of_new_leads = DB::table('clients')->select('id', 'case_no', 'client_name', 'city')->where('date', $yesterday)->count();
    
            $new_leads = DB::table('clients')->where('date', $yesterday)->get();
            foreach($new_leads as $newle)
            {
                $newle->city_name=DB::table('city')->where('id',$newle->city)->value('city_name');
            }
    
            $no_of_quotation_sent = DB::table('quotation')
            ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
            ->where('quotation.send_date',$yesterday)->count();
    
            $quotation_sent = DB::table('quotation')
            ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
            ->join('clients', 'clients.id', 'quotation.client_id')
            ->where('quotation.send_date', $yesterday)
            ->select('clients.client_name', 'clients.case_no','clients.no_of_units', 'quotation.client_id', DB::raw("COUNT(client_id) as total"), DB::raw("SUM(quotation_details.amount) as total_amt"))
            ->groupBy('quotation.client_id')->get();
    
            $no_of_quotation_finalize = DB::table('quotation')
            ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
            ->join('clients', 'clients.id', 'quotation.client_id')
            ->where('quotation_details.finalize_date', $yesterday)
            ->where('quotation_details.finalize', 'yes')->count();
            $json=DB::table('appointment')
            ->join('clients', 'clients.id', 'appointment.client')
            ->join('staff', 'staff.sid', 'appointment.schedule_by')
            ->select('appointment.*', 'clients.client_name','clients.case_no', 'staff.name')
            ->where('appointment.meeting_date',$today)
            ->get();
            foreach($json as $jso)
            {
                  $jso->time=str_replace(' : ',':',$jso->meeting_time);
            }
           
            $todays_appointment = json_decode($json);
              $todays_appointment = json_decode($json);
                usort($todays_appointment, function ($a, $b) {
                   
                    return strtotime($a->time) - strtotime($b->time);
                });
        
            foreach ($todays_appointment as $row) {
              
                $row->meeting_with_name = DB::table('staff')->where('sid', $row->meeting_with)->value('name');
              
                $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
            }
            $quotation_finalize = DB::table('quotation')
                ->join('clients', 'clients.id', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
                ->Join('services', 'services.id', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.no_of_units', 'services.name', 'quotation_details.finalize_date','quotation_details.amount')
                ->where('quotation_details.finalize_date', $yesterday)->get();
    
            $no_of_visit_follow_up = DB::table('follow_up')->where('followup_date', $yesterday)->where('method', 'visit')->count();
    
            $visit_follow_up = DB::table('follow_up')
            ->join('clients', 'follow_up.client_id', 'clients.id')
            ->join('staff','staff.sid','follow_up.contact_by')
            ->select('follow_up.*', 'clients.client_name', 'clients.case_no','staff.name as visit_by')
            ->where('followup_date', $yesterday)->where('method','visit')->get();
    
            $no_of_call_follow_up = DB::table('follow_up')->where('followup_date', $yesterday)->where('method','call')->count();
    
            $call_follow_up = DB::table('follow_up')->join('clients', 'follow_up.client_id', 'clients.id')
                ->join('staff','staff.sid','follow_up.contact_by')
                ->select('follow_up.*', 'clients.client_name', 'clients.case_no','staff.name as call_by')
                ->where('followup_date', $yesterday)->where('method', 'call')->get();
            $call_follow_up_new_leads = DB::table('lead_follow_up')
                ->join('leads', 'lead_follow_up.client_id', 'leads.id')
                ->join('staff','staff.sid','lead_follow_up.contact_by')
                ->select('lead_follow_up.*', 'leads.name','leads.mobile_no','staff.name')
                ->where('followup_date', $yesterday)->where('method', 'call')->get();
            $no_of_leaves = DB::table('leave_table')->where('start_date', '<=', $today)->where('end_date', '>=', $today)->count();
    
            $leaves = DB::table('leave_table')
                    ->join('staff', 'staff.sid', 'leave_table.staff_id')
                    ->select('staff.name','leave_table.status')
                    ->where('start_date', '<=', $today)->where('end_date', '>=', $today)->get();
    
            $no_of_assigned_leads = DB::table('clients')->where('assigned_at', $yesterday)->where('assign_to', '!=', NULL)->count();
    
            $assigned_leads = DB::table('clients')
            ->join('staff', 'staff.sid', 'clients.assign_to')
            ->select('case_no', 'client_name', 'staff.name')
            ->where('assigned_at', $yesterday)->where('status', 'active')->where('assign_to', '!=', NULL)->get();
    
             $no_of_unassigned_leads = DB::table('clients')->whereNull('assign_to')->where('status', 'active')->where('status', 'active')->where('client_leads', 'leads')->count();
             $unassigned_leads = DB::table('clients')->select('case_no', 'client_name')->whereNull('assign_to')->where('status', 'active')->where('client_leads', 'leads')->get();
          
             $total_task_created = DB::table('task')->whereDate('created_at',$yesterday)->where('active','yes')->count();
             $total_no_of_task=DB::table('task')->where('active','yes')->count();
    
                $today_task = DB::table('task')->where('start_date', '<=',$yesterday)->where('end_date', '>=', $yesterday)->where('active','yes')->count();
                $total_no_of_assigned_task = DB::table('task')->where('start_date', '<=',$yesterday)->where('end_date', '>=', $yesterday)->where('assignee','!=','')->where('active','yes')->count();
                $total_no_of_unassigned_task = DB::table('task')->where('assignee', '')->where('active','yes')->count();
                $task_type=DB::table('task_type')->where('status',1)->get();
                $task_status_master=DB::table('task_status_master')->get();
                $task_type_total=array();$task_status_total=array();
                foreach($task_type as $row)
                {
                   $total=DB::table('task')->where('type',$row->id)->where('start_date', '<=',$yesterday)->where('end_date', '>=', $yesterday)->where('active','yes')->count();
                  $task_type_total[$row->type]=$total;
                }
                foreach($task_status_master as $tsm)
                {
                  $total=DB::table('task')->where('status',$tsm->id)->where('start_date', '<=',$yesterday)->where('end_date', '>=', $yesterday)->where('active','yes')->count();
                  $task_status_total[$tsm->status]=$total;
                }
                $staff_wise_tot_task=DB::table('task_assignee')->join('task','task.id','task_assignee.task_id')->join('staff','staff.sid','task_assignee.assignee')->select('staff.name',DB::raw('count(task_assignee.assignee) as total_assignee'))->where('task.start_date', '<=',$yesterday)->where('task.end_date', '>=', $yesterday)->where('task.active','yes')->groupBy('task_assignee.assignee')->get();
                $checkins=DB::table('office_visit')->where('office_visit.visit_date',$yesterday)->get();
                foreach($checkins as $row1)
                {
                     $row1->visit_type='office';
                     $row1->time=date('h:i a',strtotime($row1->created_at));
                     $row1->name=DB::table('staff')->where('sid',$row1->visit_by)->value('name');
                     $row1->department_name=DB::table('dept_address')->where('id',$row1->dept_address_id)->value('department_name');
                     $row1->client_name=DB::table('clients')->where('id',$row1->client_id)->value('client_name');
                     $row1->case_no=DB::table('clients')->where('id',$row1->client_id)->value('case_no');
                     
                     
                }
                $hearing_id = 4;
                $fields = [
                  
                    "task.title",
                    "task.assignee",
                    "task.start_date",
                    "task.end_date",
                    "task.office_id",
                    "task.description",
                    "projects.project_name",
                 
                ];
                $task_list = DB::table("task")
                ->leftjoin("projects", "projects.id", "task.project_id")
                ->leftjoin(
                    "task_status_master",
                    "task_status_master.id",
                    "task.status"
                )->where('task.type', $hearing_id)->where('task.start_date', '<=', $today)
                ->where('task.end_date', '>=', $today)->orderBy('task.start_date')->get($fields);
                foreach ($task_list as $row) {
                    if ($row->assignee != null) {
                        $assignee = json_decode($row->assignee);
                        // $staff_name = DB::table('staff')->where('sid', $assignee)->pluck('name');
                        $staff_shortname = DB::table('staff')->where('sid', $assignee)->pluck('short_name');
                        $row->assignee_name = rtrim(implode(',', json_decode($staff_shortname)), ',');
                    }
                       else
                        {
                            $row->assignee_name='';
                        }
                    $row->dept_name = DB::table('dept_address')->where('id', $row->office_id)->value('department_name');
                    $row->dept_address = DB::table('dept_address')->where('id', $row->office_id)->value('address');
                }
      $to_email = ['tripathi.u@gmail.com','yuvrajupawar@gmail.com','punamlagad.123@gmail.com','ram.tripathi@softomatic.tech','vandana.yadav@softomatic.tech','sonuphadtare077@gmail.com'];
        
        //$to_email = ['yadavneha46@gmail.com','vandana.yadav@softomatic.tech','poojay015@gmail.com','tripathi.u@gmail.com'];
        $subject = 'Today`s summary';
        $mailer_username = 'no-reply@dearsociety.in';
        $mailer_password = 'uzutnhpvqrxppmdq';
        $mailer_name = 'Dear Society';
        $email_from = 'no-reply@dearsociety.in';
        $reply_to = null;
        $reply_to_name = null;
        $mailsent = PhpMailerController::sendEmail($to_email, null, null, view('pages.emails.new_update',compact('task_list','checkins','todays_appointment','call_follow_up_new_leads','no_of_new_leads', 'new_leads', 'no_of_quotation_sent', 'quotation_sent', 'no_of_quotation_finalize', 'quotation_finalize','no_of_visit_follow_up', 'visit_follow_up', 'no_of_call_follow_up','call_follow_up', 'no_of_leaves', 'leaves', 'no_of_assigned_leads','assigned_leads', 'no_of_unassigned_leads', 'unassigned_leads','total_task_created', 'today_task', 'total_no_of_assigned_task','total_no_of_unassigned_task','task_type_total','task_type','task_status_master','task_status_total','staff_wise_tot_task','total_no_of_task')), $subject, null, $mailer_username, $mailer_password, $mailer_name, $email_from, $reply_to, $reply_to_name);
  
    } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
  
}
}
