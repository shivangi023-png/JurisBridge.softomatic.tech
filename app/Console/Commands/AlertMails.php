<?php

namespace App\Console\Commands;

use App\Http\Controllers\PhpMailerController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlertMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AlertMails:cron';

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
        log::info("Cron is working fine!");
          $staff = DB::table('users')->join('staff','staff.sid','users.user_id')->select('staff.*')->where('users.role_id', 8)->where('users.status','active')->get();
           foreach ($staff as $row) 
           {
                log::info($row->sid);
                $row->follow_up = DB::table('follow_up')->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.followup_date', 'clients.client_name', 'clients.case_no')->where('contact_by', $row->sid)->where('followup_date', '>=', date('Y-m-d', strtotime("-1 days")))->get();
              
                $row->total_followUp = DB::table('follow_up')->where('contact_by', $row->sid)->where('followup_date', '>=', date('Y-m-d', strtotime("-1 days")))->count();
                log::info($row->follow_up);
                log::info($row->total_followUp);
                $row->assigned_leads = DB::table('clients')->select('case_no', 'client_name', 'address', 'assigned_at')->where('client_leads', 'leads')->where('assigned_at', '>=', date('Y-m-d', strtotime("-1 days")))->where('status', 'active')->where('client_leads', 'leads')->where('assign_to', $row->sid)->get();
                $row->total_assigned_leads = DB::table('clients')->where('client_leads', 'leads')->where('assigned_at', '>=', date('Y-m-d', strtotime("-1 days")))->where('client_leads', 'leads')->where('assign_to', $row->sid)->count();
                log::info($row->assigned_leads);
                log::info($row->total_assigned_leads);
                $row->followUp_by_visit = DB::table('follow_up')->join('clients', 'follow_up.client_id', 'clients.id')
                    ->select('follow_up.*', 'clients.client_name', 'clients.case_no')->where('method', '["visit"]')->where('contact_by', $row->sid)->where('followup_date', '>=', date('Y-m-d', strtotime("-1 days")))->get();
                $row->total_followUp_by_visit = DB::table('follow_up')->where('contact_by', $row->sid)->where('followup_date', '>=', date('Y-m-d', strtotime("-1 days")))->count();
                log::info($row->followUp_by_visit);
                log::info($row->total_followUp_by_visit);
                $to_email = $row->emailid;
                $subject = 'Today`s summary';
                $mailer_username = 'no-reply@dearsociety.in';
                $mailer_password = 'uzutnhpvqrxppmdq';
                $mailer_name = 'Dear Society';
                $email_from = 'no-reply@dearsociety.in';
                $reply_to = null;
                $reply_to_name = null;
                $mailsent = PhpMailerController::sendEmail($to_email, null, null, view('pages.emails.alert_mails', compact('staff')), $subject, null, $mailer_username, $mailer_password, $mailer_name, $email_from, $reply_to, $reply_to_name);
            }
    }
}
