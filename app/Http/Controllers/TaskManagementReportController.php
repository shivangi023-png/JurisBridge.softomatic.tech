<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Traits\NotificationTraits;
use Yajra\DataTables\Facades\DataTables;

date_default_timezone_set("Asia/Kolkata");

use App\Traits\StaffTraits;
use App\Traits\TaskTraits;

class TaskManagementReportController extends Controller
{
    public function task_report()
    {
        $office_list = DB::table('dept_address')->get();
        $staff_list = DB::table("staff")->get(["sid", "name"]);
        $services_list = DB::table("quotation_details")
            ->join("services", "quotation_details.task_id", "services.id")
            ->distinct()
            ->where("quotation_details.finalize", "yes")
            ->get(["services.id", "services.name"]);
        $project_status_master = DB::table("project_status_master")->get(["id","status",]);
        $task_status_master = DB::table("task_status_master")->get(["id","status"]);
        //task_priority
        $task_priority = DB::table("task_priority")->where('status', 1)->get(["id", "priority"]);
        //task_type
        $task_type = DB::table("task_type")->where('status', 1)->get(["id", "type"]);
         if (session('role_id') == 1) {
            $project_list = DB::table('projects')->where('active', 'yes')->get();
        } else {
            $project_ids = DB::table('projects_assignee')
                ->where('staff_id', session('staff_id'))
                ->pluck('projects_id');
            $project_list = DB::table('projects')->where('active', 'yes')->whereIn('id', $project_ids)->get();
        }
        return view(
            "pages.task.task_report",
            compact(
                "services_list",
                "staff_list",
                "project_status_master",
                "task_status_master",
                "task_priority",
                "task_type",
                "project_list",
                "office_list"
            )
        );
    }

    public function get_task_report(Request $request)
    {
        $id = $request->id;
        if ($id == 'onhold_task_report') {
        return view('pages.reports.onhold_task_report');
        }
    }

    public function onhold_task_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
        
            $fields = [
                "task.id",
                "task.project_id",
                "task.title",
                "task.description",
                "task.type",
                "task.file_link",
                "task.priority",
                "task.assignee",
                "task.due_date",
                "task.start_date",
                "task.end_date",
                "task.is_milestone",
                "task.status",
                "task.office_id",
                "task.working_hr",
                "task.created_at",
                "projects.project_name",
                "projects.start_date as project_start_date",
                "projects.end_date  as project_end_date",
                "projects.service_id as poject_service_id",
                "projects.created_at as poject_created_at"

            ];
            $task_status_id=[3,4];
            $staff = DB::table('staff')->select('sid', 'name')->get();
            foreach ($staff as $stf) {
                $staff_id = (string)$stf->sid;
                $task_list = DB::table("task")
                    ->leftjoin("projects", "projects.id", "task.project_id")
                    ->where(function ($query) use ($staff_id, $task_status_id) {
                    if ($staff_id != '') {
                        $query->whereJsonContains('task.assignee', $staff_id);
                    }
                    if ($task_status_id != '') {
                        $query->whereIn('task.status', $task_status_id);
                    }
                })->get($fields);
                
                foreach ($task_list as $row) {
                    $row->priority_name = DB::table('task_priority')->where('id', $row->priority)->value('priority');
                    $row->status_name = DB::table('task_status_master')->where('id', $row->status)->value('status');
                    $row->type_name = DB::table('task_type')->where('id', $row->type)->value('type');
                    if ($row->assignee != null) {
                        $assignee = json_decode($row->assignee);
                        $staff_name = DB::table('staff')->whereIn('sid', $assignee)->pluck('name');
                        $row->assignee_name = rtrim(implode(',', json_decode($staff_name)), ',');
                    } else {
                        $row->assignee_name = '';
                    }
                    $row->dept_name = DB::table('dept_address')->where('id', $row->office_id)->value('department_name');
                    $row->dept_address = DB::table('dept_address')->where('id', $row->office_id)->value('address');
                    $row->dept_geolocation = DB::table('dept_address')->where('id', $row->office_id)->value('geolocation');
                    if ($row->dept_geolocation == '["\"\"","\"\""]') {
                        $row->dept_geolocation = null;
                    }
                    $row->lat_long = null;
                    if ($row->dept_geolocation != null) {
                        $lat_long = json_decode($row->dept_geolocation, true);
                        $row->lat_long = $lat_long[1] . "," . $lat_long[0];
                    }
                    //Total Working Hr
                    $total_working_hr = 0;
                    if ($row->working_hr != null) {
                        $total_working_hr =  $this->totalWorkingHr($row->working_hr);
                    }
                    $row->total_working_hr = $total_working_hr . " Hr";
                }
                $stf->task = $task_list;
            }
            $fileName='OnHold Task Report'.date('d-M-Y');
            if (!$staff->isEmpty()) {
                ini_set("pcre.backtrack_limit", "5000000");
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
                $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
                $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML(view('pages.task.reports.onhold_task_report', compact('staff')));
            } else {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            return ($mpdf->Output($fileName.'.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function onhold_task_excel(Request $request)
    {
        $fields = [
                "task.id",
                "task.project_id",
                "task.title",
                "task.description",
                "task.type",
                "task.file_link",
                "task.priority",
                "task.assignee",
                "task.due_date",
                "task.start_date",
                "task.end_date",
                "task.is_milestone",
                "task.status",
                "task.office_id",
                "task.working_hr",
                "task.created_at",
                "projects.project_name",
                "projects.start_date as project_start_date",
                "projects.end_date  as project_end_date",
                "projects.service_id as poject_service_id",
                "projects.created_at as poject_created_at"

            ];
            $task_status_id=[3,4];
            $staff = DB::table('staff')->select('sid', 'name')->get();

            $export_data = "OnHold Task Report -\n\n";
            if ($staff != '[]') {
                foreach($staff as $stf) {
                        $staff_id = (string)$stf->sid;
                        $task_list = DB::table("task")
                        ->leftjoin("projects", "projects.id", "task.project_id")
                        ->where(function ($query) use ($staff_id, $task_status_id) {
                        if ($staff_id != '') {
                            $query->whereJsonContains('task.assignee', $staff_id);
                        }
                        if ($task_status_id != '') {
                            $query->whereIn('task.status', $task_status_id);
                        } })->get($fields);
                    
                     if ($task_list->isNotEmpty()) { 
                            $i = 1; 
                            $export_data .= "Staff - " . $stf->name."\n";
                                $export_data .= "\n";
                                $export_data .= "Sr. No.\tProject Name\tTitle\tDescription\tStart Date\tEnd Date\tType\tPriority\tStatus\tAssignee Name\tDepartment\tTotal Working Hour\n";
                        foreach ($task_list as $row) {
                                $row->priority_name = DB::table('task_priority')->where('id', $row->priority)->value('priority');
                                $row->status_name = DB::table('task_status_master')->where('id', $row->status)->value('status');
                                $row->type_name = DB::table('task_type')->where('id', $row->type)->value('type');

                                if ($row->assignee != null) {
                                    $assignee = json_decode($row->assignee);
                                    $staff_name = DB::table('staff')->whereIn('sid', $assignee)->pluck('name');
                                    $row->assignee_name = rtrim(implode(',', json_decode($staff_name)), ',');
                                } else {
                                    $row->assignee_name = '';
                                }

                                $row->dept_name = DB::table('dept_address')->where('id', $row->office_id)->value('department_name');
                                $row->dept_address = DB::table('dept_address')->where('id', $row->office_id)->value('address');
                                $row->dept_geolocation = DB::table('dept_address')->where('id', $row->office_id)->value('geolocation');
                                
                                if ($row->dept_geolocation == '["\"\"","\"\""]') {
                                    $row->dept_geolocation = null;
                                }
                                $row->lat_long = null;
                                if ($row->dept_geolocation != null) {
                                    $lat_long = json_decode($row->dept_geolocation, true);
                                    $row->lat_long = $lat_long[1] . "," . $lat_long[0];
                                }

                                $total_working_hr = 0;
                                if ($row->working_hr != null) {
                                    $total_working_hr = $this->totalWorkingHr($row->working_hr);
                                }
                                $row->total_working_hr = $total_working_hr . " Hr";
                                
                                $row->description = strip_tags($row->description);
                                $row->start_date = $row->start_date ? date('d-M-Y', strtotime($row->start_date)) : ''; 
                                $row->end_date = $row->end_date ? date('d-M-Y', strtotime($row->end_date)) : ''; 
                            if($row->dept_name){
                                $row->department = $row->dept_name . ' , ' . $row->dept_address . ' , ' . $row->lat_long;
                            }else{
                                $row->department ='';
                            }
                            $lineData = [
                                $i++, 
                                $row->project_name, 
                                $row->title, 
                                $row->description, 
                                $row->start_date, 
                                $row->end_date, 
                                $row->type_name, 
                                $row->priority_name, 
                                $row->status_name, 
                                $row->assignee_name, 
                                $row->department, 
                                $row->total_working_hr
                            ];
                            $export_data .= implode("\t", $lineData) . "\n";
                        }
                        $export_data .= "\n";
                    }   
                }
                
            }
            $fileName = 'OnHold_Task_Report_' . date('d-M-Y') . '.xls';
            return response($export_data)
                ->header("Content-Type", "application/vnd.ms-excel")
                ->header("Content-Disposition", "attachment;filename=\"$fileName\"");
    }

     public function onhold_task_print()
    {
        try {
            $fields = [
                "task.id",
                "task.project_id",
                "task.title",
                "task.description",
                "task.type",
                "task.file_link",
                "task.priority",
                "task.assignee",
                "task.due_date",
                "task.start_date",
                "task.end_date",
                "task.is_milestone",
                "task.status",
                "task.office_id",
                "task.working_hr",
                "task.created_at",
                "projects.project_name",
                "projects.start_date as project_start_date",
                "projects.end_date  as project_end_date",
                "projects.service_id as poject_service_id",
                "projects.created_at as poject_created_at"

            ];
            $task_status_id=[3,4];
            $staff = DB::table('staff')->select('sid', 'name')->get();
            foreach ($staff as $stf) {
                $staff_id = (string)$stf->sid;
                $task_list = DB::table("task")
                    ->leftjoin("projects", "projects.id", "task.project_id")
                    ->where(function ($query) use ($staff_id, $task_status_id) {
                    if ($staff_id != '') {
                        $query->whereJsonContains('task.assignee', $staff_id);
                    }
                    if ($task_status_id != '') {
                        $query->whereIn('task.status', $task_status_id);
                    }
                })->get($fields);
                
                foreach ($task_list as $row) {
                    $row->priority_name = DB::table('task_priority')->where('id', $row->priority)->value('priority');
                    $row->status_name = DB::table('task_status_master')->where('id', $row->status)->value('status');
                    $row->type_name = DB::table('task_type')->where('id', $row->type)->value('type');
                    if ($row->assignee != null) {
                        $assignee = json_decode($row->assignee);
                        $staff_name = DB::table('staff')->whereIn('sid', $assignee)->pluck('name');
                        $row->assignee_name = rtrim(implode(',', json_decode($staff_name)), ',');
                    } else {
                        $row->assignee_name = '';
                    }
                    $row->dept_name = DB::table('dept_address')->where('id', $row->office_id)->value('department_name');
                    $row->dept_address = DB::table('dept_address')->where('id', $row->office_id)->value('address');
                    $row->dept_geolocation = DB::table('dept_address')->where('id', $row->office_id)->value('geolocation');
                    if ($row->dept_geolocation == '["\"\"","\"\""]') {
                        $row->dept_geolocation = null;
                    }
                    $row->lat_long = null;
                    if ($row->dept_geolocation != null) {
                        $lat_long = json_decode($row->dept_geolocation, true);
                        $row->lat_long = $lat_long[1] . "," . $lat_long[0];
                    }
                    //Total Working Hr
                    $total_working_hr = 0;
                    if ($row->working_hr != null) {
                        $total_working_hr =  $this->totalWorkingHr($row->working_hr);
                    }
                    $row->total_working_hr = $total_working_hr . " Hr";
                }
                $stf->task = $task_list;
            }
            
            return view('pages.task.reports.onhold_task_report', compact('staff'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }
      public function hearing_task_excel(Request $request)
    {        $not_status=[5,7];
             $hearing_task_project_id=DB::table('task')->where('type',4)->whereNotIn('status',$not_status)->pluck('project_id');
             
             $project=DB::table('projects')->whereIn('id',$hearing_task_project_id)->get();
             
             $today=date('Y-m-d');
             $export_data = "Hearing Task Report -\n\n";

             
                   if ($project->isNotEmpty()) { 
                            $i = 1; 
                           
                                $export_data = "Sr. No.\tProject Name\tDescription\tNext Hearing Description\tHearing Date\tNext Hearing Date\n";
                                foreach ($project as $row) 
                                {
                                    $project_name = DB::table('projects')->where('id', $row->id)->value('project_name');
                                    $next_hearing_date=DB::table('task')->where('project_id',$row->id)->where('type',4)->where('start_date','>',$today)->value('start_date');
                                if($next_hearing_date!='')
                                {
                                    
                                    $hearing_date1=DB::table('task')->where('project_id',$row->id)->where('type',4)->orderBy('id','desc')->limit(2)->pluck('start_date');
                                    if(sizeof($hearing_date1)>1)
                                    {
                                        $hearing_date=$hearing_date1[1];
                                    }
                                   else
                                   {
                                    $hearing_date="";
                                   }
                                    $description=DB::table('task')->where('project_id',$row->id)->where('type',4)->orderBy('id','desc')->limit(2)->pluck('description');
                                    $description1='';
                                    $next_hearing_desc='';
                                    log::info(json_decode($description));
                                    if(sizeof($description)>1)
                                    {
                                        if($description[1]!=null || $description[1]!='null')
                                        {
                                            $description1=strip_tags($description[1]);
                                        }
                                        if($description[0]!=null || $description[0]!='' || $description!='null')
                                        {
                                            $next_hearing_desc=strip_tags($description[0]);
                                        }
                                    }
                                   
                                    
                                    $lineData = [
                                    $i++, 
                                    $project_name, 
                                    $description1, 
                                    $next_hearing_desc,
                                    $hearing_date, 
                                    $next_hearing_date, 
                                    ];
                                      $export_data .= implode("\t", $lineData) . "\n";
                                }
                               
                          
                        }
                        $export_data .= "\n";
                    }   
                
                
            
            $fileName = 'OnHold_Task_Report_' . date('d-M-Y') . '.xls';
            return response($export_data)
                ->header("Content-Type", "application/vnd.ms-excel")
                ->header("Content-Disposition", "attachment;filename=\"$fileName\"");
    }
}