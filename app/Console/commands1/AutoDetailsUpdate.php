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

        Log::info("Cron is working fine!");
        $today = date('Y-m-d');
        $no_of_new_leads = DB::table('clients')->select('id', 'case_no', 'client_name', 'city')->where('date', $today)->count();

        $new_leads = DB::table('clients')->where('date', $today)->get();
        foreach($new_leads as $newle)
        {
            $newle->city_name=DB::table('city')->where('id',$newle->city)->value('city_name');
        }

        $no_of_quotation_sent = DB::table('quotation')
        ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
        ->where('quotation.send_date',$today)->count();

        $quotation_sent = DB::table('quotation')
        ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
        ->join('clients', 'clients.id', 'quotation.client_id')
        ->where('quotation.send_date', $today)
        ->select('clients.client_name', 'clients.case_no', 'quotation.client_id', DB::raw("COUNT(client_id) as total"))
        ->groupBy('quotation.client_id')->get();

        $no_of_quotation_finalize = DB::table('quotation')
        ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
        ->join('clients', 'clients.id', 'quotation.client_id')
        ->where('quotation_details.finalize_date', $today)
        ->where('quotation_details.finalize', 'yes')->count();

        $quotation_finalize = DB::table('quotation')
            ->join('clients', 'clients.id', 'quotation.client_id')
            ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
            ->Join('services', 'services.id', 'quotation_details.task_id')
            ->select('clients.client_name', 'clients.case_no', 'services.name', 'quotation_details.finalize_date')
            ->where('quotation_details.finalize_date', $today)->get();

        $no_of_visit_follow_up = DB::table('follow_up')->where('followup_date', $today)->where('method', 'visit')->count();

        $visit_follow_up = DB::table('follow_up')->join('clients', 'follow_up.client_id', 'clients.id')
            ->select('follow_up.*', 'clients.client_name', 'clients.case_no')->where('followup_date', $today)->where('method','visit')->get();

        $no_of_call_follow_up = DB::table('follow_up')->where('followup_date', $today)->where('method','call')->count();

        $call_follow_up = DB::table('follow_up')->join('clients', 'follow_up.client_id', 'clients.id')
            ->select('follow_up.client_id', 'clients.client_name', 'clients.case_no')->where('followup_date', $today)->where('method', 'call')->get();

        $no_of_leaves = DB::table('leave_table')->where('start_date', '<=', $today)->where('end_date', '>=', $today)->count();

        $leaves = DB::table('leave_table')->join('staff', 'staff.sid', 'leave_table.staff_id')
            ->select('staff.name')->where('start_date', '<=', $today)->where('end_date', '>=', $today)->get();

        $no_of_assigned_leads = DB::table('clients')->where('assigned_at', $today)->where('assign_to', '!=', NULL)->count();

        $assigned_leads = DB::table('clients')->select('case_no', 'client_name', 'staff.name')->join('staff', 'staff.sid', 'clients.assign_to')->where('assigned_at', $today)->where('status', 'active')->where('assign_to', '!=', NULL)->get();

        $no_of_unassigned_leads = DB::table('clients')->where('assigned_at', NULL)->where('date', $today)->where('status', 'active')->where('client_leads', 'leads')->count();

        $unassigned_leads = DB::table('clients')->select('case_no', 'client_name')->where('assigned_at', NULL)->where('assign_to', NULL)->where('status', 'active')->where('client_leads', 'leads')->get();
         $total_no_of_task = DB::table('task')->whereDate('task.created_at',$today)->count();
            $total_no_of_assigned_task = DB::table('task')->where('start_date', '<=',$today)->where('end_date', '>=', $today)->count();
              $total_no_of_unassigned_task = DB::table('task')->where('task.assignee', '')->count();
            $task_type=DB::table('task_type')->get();
            $task_type_total=array();
            foreach($task_type as $row)
            {
               $total=DB::table('task')->where('type',$row->id)->where('start_date', '<=',$today)->where('end_date', '>=', $today)->count();
              $task_type_total[$row->type]=$total;
            }
     
        $to_email = ['tripathi.u@gmail.com','yuvrajupawar@gmail.com','punamlagad.123@gmail.com'];
        //$to_email = ['yadavneha46@gmail.com','vandana.yadav@softomatic.tech'];
        $subject = 'Today`s summary';
        $mailer_username = 'no-reply@dearsociety.in';
        $mailer_password = 'FDRT56EDRT';
        $mailer_name = 'Dear Society';
        $email_from = 'no-reply@dearsociety.in';
        $reply_to = null;
        $reply_to_name = null;
        for($i=0;$i<sizeof($to_email);$i++) {
            $mailsent = PhpMailerController::sendEmail($to_email[$i], null, null, view('pages.emails.new_update', compact('no_of_new_leads', 'new_leads', 'no_of_quotation_sent', 'quotation_sent', 'no_of_quotation_finalize', 'quotation_finalize', 'no_of_visit_follow_up', 'visit_follow_up', 'no_of_call_follow_up', 'call_follow_up', 'no_of_leaves', 'leaves', 'no_of_assigned_leads', 'assigned_leads', 'no_of_unassigned_leads', 'unassigned_leads','total_no_of_task', 'total_no_of_assigned_task', 'total_no_of_unassigned_task','task_type_total','task_type')), $subject, null, $mailer_username, $mailer_password, $mailer_name, $email_from, $reply_to, $reply_to_name);
        }
    }
}
