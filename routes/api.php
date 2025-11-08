<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

   use App\Http\Controllers\LoginController;
    use App\Http\Controllers\ClientController;
    use App\Http\Controllers\FollowUpController;
    use App\Http\Controllers\VisitController;
    use App\Http\Controllers\LeaveController;
    use App\Http\Controllers\AppointmentController;
    use App\Http\Controllers\BillController;
    use App\Http\Controllers\BankController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\ServiceController;
    use App\Http\Controllers\DepartmentController;
    use App\Http\Controllers\ExpenseController;
    use App\Http\Controllers\ResetPasswordController; 
    use App\Http\Controllers\AttendanceController; 
    use App\Http\Controllers\QuotationController; 
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\MyCasesController;
    use App\Http\Controllers\TaskManagementController;
     use App\Http\Controllers\InvoiceController;
   
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::group([
    'middleware' => 'jwt.verify',
   

], function ($router) {
   
        
Route::post('update_token_app', [LoginController::class, 'update_token_app'])->name('update_token_app');
Route::post('get_latest_version', [LoginController::class, 'get_latest_version'])->name('get_latest_version');


        //---------------------ClientController--------------------------------------------------------// 
        $router->post('newClient', [ClientController::class, 'client_insert'])->name('client_insert');
        $router->post('get_clients', [ClientController::class, 'get_clients'])->name('get_clients');
        $router->post('get_clients_leads', [ClientController::class, 'get_clients_leads'])->name('get_clients_leads');
        $router->post('clientContact', [ClientController::class, 'clientContact_insert'])->name('clientContact_insert');
        $router->post('contactDetails', [ClientController::class, 'clientContact_fetch'])->name('clientContact_fetch');
        $router->post('get_client_on_id', [ClientController::class, 'get_client_on_id'])->name('get_client_on_id');
        $router->post('mobile_client_add', [ClientController::class, 'mobile_client_add'])->name('mobile_client_add');
        $router->get('get_city', [ClientController::class, 'get_city'])->name('get_city');
        $router->post('get_client_full_detail', [ClientController::class, 'get_client_full_detail'])->name('get_client_full_detail');
        $router->get('get_source', [ClientController::class, 'get_source'])->name('get_source');
        $router->post('get_client_address', [ClientController::class, 'get_client_address'])->name('get_client_address');
        $router->post('get_all_leads', [ClientController::class, 'get_all_leads'])->name('get_all_leads');
        $router->post('my_leads', [ClientController::class, 'my_leads'])->name('my_leads');
        $router->post('save_contact_detail', [ClientController::class, 'save_contact_detail'])->name('save_contact_detail');
        $router->post('mobile_client_edit', [ClientController::class, 'mobile_client_edit'])->name('mobile_client_edit');
        $router->post('delete_client', [ClientController::class,'delete_client'])->name('delete_client');
          $router->post('get_all_clients', [ClientController::class,'get_all_clients'])->name('get_all_clients');
          $router->post('mobile_addnew_leads', [ClientController::class, 'mobile_addnew_leads'])->name('mobile_addnew_leads');
          $router->post('mobile_client_search', [ClientController::class, 'mobile_client_search'])->name('mobile_client_search');
         //--------------------------------------------------------------------------------------------------------------//
         
          //---------------------DashboardController---------------------------------------------------------------------------// 
          $router->post('save_firebase_token',[DashboardController::class, 'save_firebase_token'])->name('save_firebase_token');
         //--------------------------------------------------------------------------------------------------------------------//
         
        //---------------------FollowUpController--------------------------------------------------------// 

        $router->post('followUp', [FollowUpController::class, 'followUp_fetch'])->name('followUp_fetch');
        $router->post('followUpId', [FollowUpController::class, 'followUpById_fetch'])->name('followUpById_fetch');
        $router->post('search_followup', [FollowUpController::class, 'search_followup'])->name('search_followup');
        $router->post('search_next_followup', [FollowUpController::class, 'search_next_followup'])->name('search_next_followup');
        $router->post('save_follow_up', [FollowUpController::class, 'save_follow_up'])->name('save_follow_up');
         $router->post('save_check_in', [FollowUpController::class, 'save_check_in'])->name('save_check_in');
        $router->post('save_office_visit', [FollowUpController::class, 'save_office_visit'])->name('save_office_visit');
        $router->post('fetch_check_in', [FollowUpController::class, 'fetch_check_in'])->name('fetch_check_in');

        //---------------------VisitController--------------------------------------------------------// 
        $router->post('save_leads', [VisitController::class, 'save_leads'])->name('save_leads');
        $router->post('get_leads', [VisitController::class, 'get_leads'])->name('get_leads');
    

        //---------------------LeaveController--------------------------------------------------------//  

        $router->post('fetch_leave_type', [LeaveController::class, 'fetch_leave_type'])->name('fetch_leave_type');
        $router->post('leave_table_save', [LeaveController::class, 'leave_table_save'])->name('leave_table_save');
        $router->post('leave_table_update', [LeaveController::class, 'leave_table_update'])->name('leave_table_update');
        $router->post('fetch_leave_table', [LeaveController::class, 'fetch_leave_table'])->name('fetch_leave_table');
        $router->post('delete_leave_records', [LeaveController::class, 'delete_leave_records'])->name('delete_leave_records');
        $router->post('delete_leave_records_id', [LeaveController::class, 'delete_leave_records_id'])->name('delete_leave_records_id');
        $router->post('delete_leave_records_id', [LeaveController::class, 'delete_leave_records_id'])->name('delete_leave_records_id');
        $router->post('reschedule_leave_record', [LeaveController::class, 'reschedule_leave_record'])->name('reschedule_leave_record');
        $router->post('get_pending_leaves', [LeaveController::class, 'get_pending_leaves'])->name('get_pending_leaves');
         $router->post('update_leave', [LeaveController::class, 'leave_table_update'])->name('leave_table_update');
    
        //---------------------AppointmentController--------------------------------------------------------//
       
    

        //---------------------BillController--------------------------------------------------------//
        $router->post('get_bill_on_client_id', [BillController::class, 'get_bill_on_client_id'])->name('get_bill_on_client_id');

        //---------------------BankController--------------------------------------------------------//
        $router->get('get_bank', [BankController::class, 'get_bank'])->name('get_bank');

        //---------------------PaymentController--------------------------------------------------------//
        $router->post('get_payment', [PaymentController::class, 'get_payment'])->name('get_payment');
        $router->post('accept_payment', [PaymentController::class, 'accept_payment'])->name('accept_payment');
        $router->post('deposite_payment', [PaymentController::class, 'deposite_payment'])->name('deposite_payment');
        $router->post('approve_payment', [PaymentController::class, 'approve_payment'])->name('approve_payment');
        $router->post('delete_payment', [PaymentController::class, 'delete_payment'])->name('delete_payment');
        $router->post('get_card_payment', [PaymentController::class, 'get_card_payment'])->name('get_card_payment');
        //---------------------ServiceController--------------------------------------------------------//
        $router->get('get_services', [ServiceController::class, 'get_services'])->name('get_services');
        $router->get('get_property', [ServiceController::class, 'get_property'])->name('get_property');
        //---------------------DepartmentController--------------------------------------------------------//
        $router->post('save_department', [DepartmentController::class, 'save_department'])->name('save_department');
        $router->get('get_department', [DepartmentController::class, 'get_department'])->name('get_department');
    
       
         //---------------------ExpenseController--------------------------------------------------------//
        $router->get('get_ledger', [ExpenseController::class, 'get_ledger'])->name('get_ledger');
        $router->post('expense_entry', [ExpenseController::class, 'expense_entry'])->name('expense_entry');
        $router->post('get_expenses', [ExpenseController::class, 'get_expenses'])->name('get_expenses');
        $router->post('update_expenses', [ExpenseController::class, 'update_expenses'])->name('update_expenses');
        $router->post('delete_expense', [ExpenseController::class, 'delete_expense'])->name('delete_expense');
        $router->post('add_distance', [ExpenseController::class,'add_distance'])->name('add_distance');
        $router->post('get_distance', [ExpenseController::class,'get_distance'])->name('get_distance');
         
         
         //----------------------------------------AppointmentController------------------------------------------------//
          $router->get('fetch_appointment', [AppointmentController::class, 'fetch_appointment'])->name('fetch_appointment');
        $router->post('fetch_date_wise_appointment', [AppointmentController::class, 'fetch_date_wise_appointment'])->name('fetch_date_wise_appointment');
          $router->post('appointment_list', [AppointmentController::class, 'appointment_list'])->name('appointment_list');
          $router->post('submit_appointment', [AppointmentController::class, 'submit_appointment'])->name('submit_appointment');
           $router->get('get_meeting_place', [AppointmentController::class, 'get_meeting_place'])->name('get_meeting_place');
           $router->get('get_meeting_with', [AppointmentController::class, 'get_meeting_with'])->name('get_meeting_with');
          $router->get('consulting_fee_reciept-{id}', [AppointmentController::class, 'consulting_fee_reciept'])->name('consulting_fee_reciept');
          $router->post('reschedule_meeting', [AppointmentController::class, 'reschedule_meeting'])->name('reschedule_meeting');
          $router->post('fetch_meeting_with_appointment', [AppointmentController::class, 'fetch_meeting_with_appointment'])->name('fetch_meeting_with_appointment');
           $router->post('save_meeting_notes', [AppointmentController::class, 'save_meeting_notes'])->name('save_meeting_notes');
        //------------------------------------------------------------------------------------------------------------------// 
        
        
          //----------------------------------------AttendanceController------------------------------------------------//
          $router->get('get_cur_date_time', [AttendanceController::class, 'get_cur_date_time'])->name('get_cur_date_time');
          $router->post('attendance', [AttendanceController::class, 'attendance'])->name('attendance');
          $router->post('get_attendance', [AttendanceController::class, 'get_attendance'])->name('get_attendance');
          $router->post('raise_attendance_api', [AttendanceController::class, 'raise_attendance_api'])->name('raise_attendance_api');
          $router->post('raise_other_attendance', [AttendanceController::class, 'raise_other_attendance'])->name('raise_other_attendance');
          $router->post('attendance_chart', [AttendanceController::class, 'attendance_chart'])->name('attendance_chart');
          //------------------------------------------------------------------------------------------------------------------//
          
           //----------------------------------------QuotationController------------------------------------------------//
          $router->post('get_client_quotation', [QuotationController::class, 'get_client_quotation'])->name('get_client_quotation');
            $router->post('finalize_quotation', [QuotationController::class, 'finalize_quotation'])->name('finalize_quotation');
              $router->post('unfinalize_quotation', [QuotationController::class, 'unfinalize_quotation'])->name('unfinalize_quotation');
          //------------------------------------------------------------------------------------------------------------------//
          
          
           //----------------------------------------MyCasesController------------------------------------------------//
          $router->post('mycases', [MyCasesController::class, 'mycases'])->name('mycases');
          $router->post('getContacts', [MyCasesController::class, 'getContacts'])->name('getContacts');
          $router->post('remove_participate', [MyCasesController::class, 'remove_participate'])->name('remove_participate');
          $router->post('add_participate', [MyCasesController::class, 'add_participate'])->name('add_participate');
          $router->post('get_case_clients', [MyCasesController::class, 'get_case_clients'])->name('get_case_clients');
          $router->post('upload_mycases_doc', [MyCasesController::class, 'upload_mycases_doc'])->name('upload_mycases_doc');
          $router->post('case_document', [MyCasesController::class, 'case_document'])->name('case_document');
          $router->post('case_quotation', [MyCasesController::class, 'case_quotation'])->name('case_quotation');
          $router->post('case_invoice', [MyCasesController::class, 'case_invoice'])->name('case_invoice');
          $router->post('fetch_all_chatuser', [MyCasesController::class, 'fetch_all_chatuser'])->name('fetch_all_chatuser');
          Route::post('new_chat_notification','MyCasesController@new_chat_notification')->middleware('checkKey');
          //------------------------------------------------------------------------------------------------------------------//
          
        //----------------------------------------TaskManagementController------------------------------------------------//
          $router->get('task_list', [TaskManagementController::class, 'task_status_list'])->name('task_status_list');
          $router->post('get_task_on_status', [TaskManagementController::class, 'get_task_on_status'])->name('get_task_on_status');
          $router->post('get_task_status_pie', [TaskManagementController::class, 'get_task_status_pie'])->name('get_task_status_pie');
          $router->get('get_project_clients', [TaskManagementController::class, 'get_project_clients'])->name('get_project_clients');
          $router->get('task_type_list', [TaskManagementController::class, 'task_type_list'])->name('task_type_list');
          $router->get('assignee_list', [TaskManagementController::class, 'assignee_list'])->name('assignee_list');
          $router->get('priority_list', [TaskManagementController::class, 'priority_list'])->name('priority_list');
          $router->post('test_form_data', [TaskManagementController::class, 'test_form_data'])->name('test_form_data');
          $router->post('update_task', [TaskManagementController::class, 'update_task'])->name('update_task');
          $router->post('api_test', [TaskManagementController::class, 'api_test'])->name('api_test');
          $router->post('add_task', [TaskManagementController::class, 'add_task'])->name('add_task');
          $router->post('daily_tasks', [TaskManagementController::class, 'daily_tasks'])->name('daily_tasks');
          $router->post('my_tasks', [TaskManagementController::class, 'my_tasks'])->name('my_tasks');
          $router->post('get_project', [TaskManagementController::class, 'get_project'])->name('get_project');
          $router->post('get_project_list', [TaskManagementController::class, 'get_project_list'])->name('get_project_list');
          $router->post('get_total_projects', [TaskManagementController::class, 'get_total_projects'])->name('get_total_projects');
          $router->post('get_total_tasks', [TaskManagementController::class, 'get_total_tasks'])->name('get_total_tasks');
          $router->post('get_milestone', [TaskManagementController::class, 'get_milestone'])->name('get_milestone');
          $router->post('get_task_on_type', [TaskManagementController::class, 'get_task_on_type'])->name('get_task_on_type');
          $router->post('get_project_client', [TaskManagementController::class, 'get_project_client'])->name('get_project_client');
          $router->post('get_client_task', [TaskManagementController::class, 'get_client_task'])->name('get_client_task');
           $router->post('get_total_milestone', [TaskManagementController::class, 'get_total_milestone'])->name('get_total_milestone');
           $router->post('get_notifications', [TaskManagementController::class, 'get_notifications'])->name('get_notifications');
         
          $router->post('get_project_task', [TaskManagementController::class, 'get_project_task'])->name('get_project_task');
          $router->get('get_project_status_master', [TaskManagementController::class, 'get_project_status_master'])->name('get_project_status_master');
           $router->post('get_task_comment', [TaskManagementController::class, 'get_task_comment'])->name('get_task_comment');
           $router->post('save_task_comment', [TaskManagementController::class, 'save_task_comment'])->name('save_task_comment');
            $router->post('get_project_comment', [TaskManagementController::class, 'get_project_comment'])->name('get_project_comment');
            $router->post('save_project_comment', [TaskManagementController::class, 'save_project_comment'])->name('save_project_comment');
            $router->post('get_chart_task_type', [TaskManagementController::class, 'get_chart_task_type'])->name('get_chart_task_type');
             $router->post('app_task_hearing', [TaskManagementController::class, 'app_task_hearing'])->name('app_task_hearing');
            // subtask
          $router->post('add_subtask', [TaskManagementController::class, 'add_subtask'])->name('add_subtask');
          $router->post('update_subtask', [TaskManagementController::class, 'update_subtask'])->name('update_subtask');

          // task comment inbox/outbox
          $router->post('task_comment_inbox', [TaskManagementController::class, 'task_comment_inbox'])->name('task_comment_inbox');
          $router->post('task_comment_outbox', [TaskManagementController::class, 'task_comment_outbox'])->name('task_comment_outbox');
        //----------------------------------------------------------------------------------------------------------------//
          
          
          
        });
     
        //----------------------------------------ResetPasswordController------------------------------------------------//

        $router->get('forgot_password', [ResetPasswordController::class, 'forgot_password'])->name('forgot_password');
        $router->post('send_otp', [ResetPasswordController::class, 'send_otp'])->name('send_otp');
        $router->post('forgot_password', [ResetPasswordController::class, 'reset_password'])->name('reset_password');
        $router->post('reset_password', [ResetPasswordController::class, 'reset_password_submit'])->name('reset_password_submit');
        //---------------------------------------------------------------------------------------------------------------//
        
  
         $router->post('client_invoices', [InvoiceController::class, 'client_invoices'])->name('client_invoices');
         Route::get('generate_invoice-{id}-{type}', [InvoiceController::class, 'generate_invoice'])->name('generate_invoice');
         $router->post('get_card_invoice', [InvoiceController::class, 'get_card_invoice'])->name('get_card_invoice');