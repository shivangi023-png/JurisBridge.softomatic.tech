<?php

namespace App\Console\Commands;

use App\Http\Controllers\PhpMailerController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class sendMailToSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMailToSales:cron';

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

                $row->newTask = DB::table('task')->leftjoin("projects", "projects.id", "task.project_id")->leftjoin("task_status_master", "task_status_master.id", "task.status")->select('task.id', 'title', 'project_name', "task_status_master.status as task_status", "task.start_date", "task.end_date")->whereJsonContains('task.assignee', (string)($row->sid))->whereDate('task.start_date',  date('Y-m-d'))->orWhereDate('task.end_date',  date('Y-m-d'))->get();
                $newTask = $row->newTask;
                $to_email = $row->emailid;
                $subject = 'Today`s summary';
                $mailer_username = 'no-reply@dearsociety.in';
                $mailer_password = 'FDRT56EDRT';
                $mailer_name = 'Dear Society';
                $email_from = 'no-reply@dearsociety.in';
                $reply_to = null;
                $reply_to_name = null;
                $mailsent = PhpMailerController::sendEmail($to_email, null, null, view('pages.emails.send_mail_to_sales', compact('company', 'appointmentSchedule', 'appointmentMeet', 'newTask')), $subject, null, $mailer_username, $mailer_password, $mailer_name, $email_from, $reply_to, $reply_to_name);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
        }
    }
}
