<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\WebLoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PdfGenerationController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QuotationReportController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\ClientReportController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AccountingReportController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\PrettyCashReportController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\MyCasesController;
use App\Http\Controllers\TaskManagementController;
use App\Http\Controllers\TaskManagementReportController;
use App\Http\Controllers\DepartmentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

// dashboard Routes
//----------------------------------------DashboardController--------------------------------------------------//
Route::get('dashboard', [DashboardController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
Route::get('sales-dashboard', [DashboardController::class, 'sales_dashboard'])->name('sales_dashboard');
Route::post('get_contact_details', [DashboardController::class, 'get_contact_details'])->name('get_contact_details');
Route::post('get_leads_details', [DashboardController::class, 'get_leads_details'])->name('get_leads_details');
Route::get('presales-dashboard', [DashboardController::class, 'presales_dashboard'])->name('presales_dashboard');
$router->get('search_client', [DashboardController::class, 'search_client'])->name('search_client');
$router->get('search_client_by_name', [DashboardController::class, 'search_client_by_name'])->name('search_client_by_name');
$router->get('search_exist_client', [DashboardController::class, 'search_exist_client'])->name('search_exist_client');
$router->post('filter_today_appointment', [DashboardController::class, 'filter_today_appointment'])->name('filter_today_appointment');
$router->post('filter_today_leave', [DashboardController::class, 'filter_today_leave'])->name('filter_today_leave');
$router->post('filter_today_quotation', [DashboardController::class, 'filter_today_quotation'])->name('filter_today_quotation');
$router->get('filter_today_attendance', [DashboardController::class, 'filter_today_attendance'])->name('filter_today_attendance');
$router->post('filter_today_client', [DashboardController::class, 'filter_today_client'])->name('filter_today_client');
$router->post('filter_today_followup', [DashboardController::class, 'filter_today_followup'])->name('filter_today_followup');
$router->post('filter_today_nextfollowup', [DashboardController::class, 'filter_today_nextfollowup'])->name('filter_today_nextfollowup');
$router->post('filter_today_invoice', [DashboardController::class, 'filter_today_invoice'])->name('filter_today_invoice');
$router->post('filter_today_payment', [DashboardController::class, 'filter_today_payment'])->name('filter_today_payment');
$router->post('filter_today_sales_lead', [DashboardController::class, 'filter_today_sales_lead'])->name('filter_today_sales_lead');
$router->post('filter_today_office_lead', [DashboardController::class, 'filter_today_office_lead'])->name('filter_today_office_lead');
$router->get('export_client_report', [DashboardController::class, 'export_client_report'])->name('export_client_report');
$router->get('export_appointment_report', [DashboardController::class, 'export_appointment_report'])->name('export_appointment_report');
$router->get('export_invoice_report', [DashboardController::class, 'export_invoice_report'])->name('export_invoice_report');
$router->get('export_payment_report', [DashboardController::class, 'export_payment_report'])->name('export_payment_report');
$router->get('export_quotation_report', [DashboardController::class, 'export_quotation_report'])->name('export_quotation_report');
$router->get('export_followup_report', [DashboardController::class, 'export_followup_report'])->name('export_followup_report');
$router->get('export_consultation_fees_report', [DashboardController::class, 'export_consultation_fees_report'])->name('export_consultation_fees_report');
$router->get('export_additional_invoice_report', [DashboardController::class, 'export_additional_invoice_report'])->name('export_additional_invoice_report');
$router->get('export_due_payment_report', [DashboardController::class, 'export_due_payment_report'])->name('export_due_payment_report');
$router->get('get_lead_type', [DashboardController::class, 'get_lead_type'])->name('get_lead_type');
$router->post('get_bar_chart', [DashboardController::class, 'get_bar_chart'])->name('get_bar_chart');
$router->post('get_line_chart', [DashboardController::class, 'get_line_chart'])->name('get_line_chart');
$router->get('get_all_leads', [DashboardController::class, 'get_all_leads'])->name('get_all_leads');
$router->post('add_pretty_cash', [DashboardController::class, 'add_pretty_cash'])->name('add_pretty_cash');
$router->post('filter_leads_table', [DashboardController::class, 'filter_leads_table'])->name('filter_leads_table');
$router->get('leads_details', [DashboardController::class, 'leads_details'])->name('leads_details');
$router->post('get_leads_list', [DashboardController::class, 'get_leads_list'])->name('get_leads_list');
$router->get('raise_attendance', [DashboardController::class, 'raise_attendance'])->name('raise_attendance');
$router->get('office_visit', [DashboardController::class, 'office_visit'])->name('office_visit');
$router->post('get_lead_source', [DashboardController::class, 'get_lead_source'])->name('get_lead_source');
$router->post('export_client_contacts', [DashboardController::class, 'export_client_contacts'])->name('export_payment_report');
$router->get('assign_client_due', [DashboardController::class, 'assign_client_due'])->name('assign_client_due');
$router->get('daily_report', [DashboardController::class, 'daily_report'])->name('daily_report');
//--------------------------------------------------------------------------------------------------------------//

//----------------------------------------InvoiceController-----------------------------------------------------//
Route::get('invoice_list', [InvoiceController::class, 'invoice_list'])->name('app-invoice-list');
Route::get('proforma_invoice_list', [InvoiceController::class, 'proforma_invoice_list'])->name('proforma_invoice_list');
Route::post('delete_invoice', [InvoiceController::class, 'delete_invoice'])->name('app-invoice-list');
Route::get('invoice_add', [InvoiceController::class, 'add_invoice_index'])->name('app-invoice-add');
Route::get('invoice_edit-{id}', [InvoiceController::class, 'invoice_edit_index'])->name('invoice_edit_index');
Route::post('get_bill_quotation', [InvoiceController::class, 'get_bill_quotation'])->name('get_bill_quotation');
Route::post('invoice_submit', [InvoiceController::class, 'invoice_submit'])->name('invoice_submit');
Route::post('invoice_update', [InvoiceController::class, 'invoice_update'])->name('invoice_update');
Route::get('generate_invoice-{id}-{type}', [InvoiceController::class, 'generate_invoice'])->name('generate_invoice');
Route::get('download_invoice-{id}-{type}', [InvoiceController::class, 'download_invoice'])->name('download_invoice');
Route::post('filter_invoice', [InvoiceController::class, 'filter_invoice'])->name('filter_invoice');
Route::post('get_invoice_services', [InvoiceController::class, 'get_invoice_services'])->name('get_invoice_services');
Route::post('create_credit_note', [InvoiceController::class, 'create_credit_note'])->name('create_credit_note');

Route::post('writeoff', [InvoiceController::class, 'writeoff'])->name('writeoff');
Route::post('credit_note', [InvoiceController::class, 'credit_note'])->name('credit_note');
Route::post('convert_to_tax_invoice', [InvoiceController::class, 'convert_to_tax_invoice'])->name('convert_to_tax_invoice');
Route::post('filter_proforma_invoice', [InvoiceController::class, 'filter_proforma_invoice'])->name('filter_proforma_invoice');
Route::post('delete_proforma_invoice', [InvoiceController::class, 'delete_proforma_invoice'])->name('delete_proforma_invoice');
Route::get('proforma_edit-{id}', [InvoiceController::class, 'proforma_invoice_edit_index'])->name('proforma_invoice_edit_index');
Route::get('invoice_preview', [InvoiceController::class, 'invoice_preview'])->name('invoice_preview');
Route::get('generate_invoice_UT-{id}-{type}', [InvoiceController::class, 'generate_invoice_UT'])->name('generate_invoice_UT');
//-----------------------------------------------------------------------------------------------//

//----------------------------------------WebLoginController-----------------------------------------------------//
Route::get('/', [WebLoginController::class, 'showlogin'])->name('showlogin');
Route::post('/', [WebLoginController::class, 'weblogin'])->name('weblogin');
Route::post('change_company', [WebLoginController::class, 'change_company'])->name('change_company');
Route::get('logout', [WebLoginController::class, 'logout'])->name('logout');
//-------------------------------------------------------------------------------------------------------------//

//----------------------------------------ExpenseController----------------------------------------------------//
Route::get('expense_list', [ExpenseController::class, 'expenses_list'])->name('expenses_list');
Route::get('expenses_add', [ExpenseController::class, 'expenses_add'])->name('expenses_add');
Route::post('expense_entry', [ExpenseController::class, 'expense_entry'])->name('expense_entry');
Route::get('expenses_edit-{id}', [ExpenseController::class, 'expenses_edit_index'])->name('expenses_edit_index');
Route::post('expenses/edit', [ExpenseController::class, 'update_expenses'])->name('update_expenses');
Route::get('expenses/delete', [ExpenseController::class, 'delete_expenses'])->name('delete_expenses');
Route::get('expenses/filter', [ExpenseController::class, 'expenses_list_open'])->name('filter_expenses');
Route::post('get_filter_expense', [ExpenseController::class, 'get_filter_expense'])->name('get_filter_expense');
Route::post('filter_approve_expense', [ExpenseController::class, 'filter_approve_expense'])->name('filter_approve_expense');
Route::post('create_subhead', [ExpenseController::class, 'create_subhead'])->name('create_subhead');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------TemplateController-----------------------------------------------------//
Route::get('file-manager', [TemplateController::class, 'template_index'])->name('app-file-manager'); //Main Page
Route::post('template_create', [TemplateController::class, 'template_create'])->name('app-file-manager'); //Create New Template
Route::post('template_generation_index', [TemplateController::class, 'template_generation_index'])->name('app-file-manager'); //Template Generate
Route::post('template_list_gen', [TemplateController::class, 'template_list_gen'])->name('app-file-manager'); //Generate template list after client select
Route::post('generate_input', [TemplateController::class, 'generate_input'])->name('app-file-manager'); //Generate input in pop up modal
//----------------------------------------------------------------------------------------------------------------//

//----------------------------------------PdfGenerationController-----------------------------------------------------//
Route::get('preview_pdf', [PdfGenerationController::class, 'preview_pdf'])->name('app-file-manager'); //Preview Templates
Route::post('generate_pdf', [PdfGenerationController::class, 'generate_pdf'])->name('app-file-manager'); //Downlod template 
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------QuotationController-----------------------------------------------------//
Route::get('quotation_list', [QuotationController::class, 'quotation_list'])->name('quotation_list');
Route::get('quotation_add', [QuotationController::class, 'quotation_add'])->name('quotation_add');
Route::get('finalize_quotation', [QuotationController::class, 'finalize_quotation'])->name('finalize_quotation');
Route::post('unfinalize_quotation', [QuotationController::class, 'unfinalize_quotation'])->name('unfinalize_quotation');
Route::get('get_quotation', [QuotationController::class, 'get_client_quotation'])->name('get_client_quotation');
Route::post('submit/quotation', [QuotationController::class, 'submit_quotation'])->name('submit_quotation');
Route::post('get_client_no_of_units', [QuotationController::class, 'get_client_no_of_units'])->name('get_client_no_of_units');
Route::post('update/quotation', [QuotationController::class, 'update_quotation'])->name('update_quotation');
Route::post('get_finalize_quotation', [QuotationController::class, 'get_finalize_quotation'])->name('get_finalize_quotation');
Route::get('get_client_mail_info', [QuotationController::class, 'get_client_mail_info'])->name('get_client_mail_info');
Route::get('get_template_info', [QuotationController::class, 'get_template_info'])->name('get_template_info');
Route::post('send_quotation_mail', [QuotationController::class, 'send_quotation_mail'])->name('send_quotation_mail');
Route::post('delete_quotation', [QuotationController::class, 'delete_quotation'])->name('delete_quotation');
Route::get('open_quotation_report', [QuotationController::class, 'open_quotation_report'])->name('open_quotation_report');
Route::get('filter_open_quotation', [QuotationController::class, 'filter_open_quotation'])->name('filter_open_quotation');
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------ExpenseController-----------------------------------------------------//
Route::get('expenses/list', [ExpenseController::class, 'expenses_list'])->name('expenses_entry');
Route::get('expenses/add', [ExpenseController::class, 'expenses_add'])->name('expenses_add');
Route::post('expenses/entry', [ExpenseController::class, 'expenses_entry'])->name('expenses_entry');
$router->post('delete_expense', [ExpenseController::class, 'delete_expense'])->name('delete_expense');
Route::post('get_filter_expense', [ExpenseController::class, 'get_filter_expense'])->name('get_filter_expense');
Route::post('approve_expense', [ExpenseController::class, 'approve_expense'])->name('approve_expense');
Route::get('expenses_report', [ExpenseController::class, 'expenses_report'])->name('expenses_report');
Route::post('get_expenses_report', [ExpenseController::class, 'get_expenses_report'])->name('get_expenses_report');
Route::get('travelling_allowance', [ExpenseController::class, 'travelling_allowance_index'])->name('travelling_allowance_index');
Route::get('travelling_allowance_report', [ExpenseController::class, 'travelling_allowance_report'])->name('travelling_allowance_report');
Route::post('get_travelling_allowance_report', [ExpenseController::class, 'get_travelling_allowance_report'])->name('get_travelling_allowance_report');
Route::post('travelling_allowance', [ExpenseController::class, 'travelling_allowance_add'])->name('travelling_allowance_add');
Route::post('travelling_allowance_update', [ExpenseController::class, 'travelling_allowance_update'])->name('travelling_allowance_update');
Route::get('autocomplete_destination', [ExpenseController::class, 'autocomplete_destination'])->name('autocomplete_destination');
Route::post('delete_travelling_allowance', [ExpenseController::class, 'delete_travelling_allowance'])->name('delete_travelling_allowance');
Route::post('approve_travelling_allowance', [ExpenseController::class, 'approve_travelling_allowance'])->name('approve_travelling_allowance');
Route::post('reject_travelling_allowance', [ExpenseController::class, 'reject_travelling_allowance'])->name('reject_travelling_allowance');
Route::post('filter_travelling_allowance', [ExpenseController::class, 'filter_travelling_allowance'])->name('filter_travelling_allowance');
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------PaymentController-----------------------------------------------------//
$router->get('payment_list', [PaymentController::class, 'payment_list'])->name('payment_list');
$router->post('accept_payment', [PaymentController::class, 'accept_payment'])->name('accept_payment');
$router->post('delete_payment', [PaymentController::class, 'delete_payment'])->name('delete_payment');
$router->post('deposite_payment', [PaymentController::class, 'deposite_payment_index'])->name('deposite_payment');
$router->post('approve_payment', [PaymentController::class, 'approve_payment_index'])->name('approve_payment');
$router->get('payment_reciept-{id}', [PaymentController::class, 'payment_reciept'])->name('payment_reciept');
$router->post('filter_approve_payment', [PaymentController::class, 'filter_approve_payment'])->name('filter_approve_payment');
$router->get('get_payment_by_status', [PaymentController::class, 'get_payment_by_status'])->name('get_payment_by_status');
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------ClientController-----------------------------------------------------//
$router->get('client_ledger', [ClientController::class, 'client_ledger'])->name('client_ledger');
$router->get('client_add', [ClientController::class, 'clients_add_index'])->name('clients_add_index');
$router->get('autocomplete_client_name', [ClientController::class, 'autocomplete_client_name'])->name('autocomplete_client_name');
$router->get('get_exist_client', [ClientController::class, 'get_exist_client'])->name('get_exist_client');
$router->post('client_add', [ClientController::class, 'client_add'])->name('client_add');
$router->post('update_clients', [ClientController::class, 'update_clients'])->name('update_clients');
$router->post('get_active_client', [ClientController::class, 'get_active_client'])->name('get_active_client');
$router->post('delete_client', [ClientController::class, 'delete_client'])->name('delete_client');
$router->get('client_edit-{id}', [ClientController::class, 'client_edit'])->name('client_edit');
$router->get('client_list', [ClientController::class, 'client_list_index'])->name('client_list_index');
$router->get('leads', [ClientController::class, 'leads_index'])->name('leads_index');
$router->get('my-leads', [ClientController::class, 'my_leads_index'])->name('my_leads_index');
$router->post('convert_client', [ClientController::class, 'convert_client'])->name('convert_client');
$router->post('get_quo_appo_foll', [ClientController::class, 'get_quo_appo_foll'])->name('get_quo_appo_foll');
$router->post('filter_client', [ClientController::class, 'filter_client'])->name('filter_client');
$router->get('get_daily_leads_by_sales', [ClientController::class, 'get_daily_leads_by_sales'])->name('get_daily_leads_by_sales');
$router->get('get_client_contacts', [ClientController::class, 'get_client_contacts'])->name('get_client_contacts');
$router->post('assign_leads_to_staff', [ClientController::class, 'assign_leads_to_staff'])->name('assign_leads_to_staff');
$router->post('save_lead_type', [ClientController::class, 'save_lead_type'])->name('save_lead_type');
$router->get('lead_history', [ClientController::class, 'lead_history'])->name('lead_history');
$router->post('delete_lead_history', [ClientController::class, 'delete_lead_history'])->name('delete_lead_history');
$router->get('statistics_leads', [ClientController::class, 'statistics_leads'])->name('statistics_leads');
$router->post('get_leads', [ClientController::class, 'get_leads'])->name('get_leads');
$router->get('get_leads_list', [ClientController::class, 'get_leads_list'])->name('get_leads_list');
$router->get('get_client_ledger', [ClientController::class, 'get_client_ledger'])->name('get_client_ledger');
$router->post('get_credit_note_history', [ClientController::class, 'get_credit_note_history'])->name('get_credit_note_history');
$router->post('get_writeoff_history', [ClientController::class, 'get_writeoff_history'])->name('get_writeoff_history');

$router->get('new_leads', [ClientController::class, 'new_leads'])->name('new_leads');
//$router->get('get_new_leads', [ClientController::class, 'get_new_leads'])->name('get_new_leads');
$router->post('get_new_leads', [ClientController::class, 'get_new_leads'])->name('get_new_leads');
$router->post('delete_leads', [ClientController::class, 'delete_leads'])->name('delete_leads');
$router->get('leads_edit-{id}', [ClientController::class, 'leads_edit'])->name('leads_edit');
$router->post('update_leads', [ClientController::class, 'update_leads'])->name('update_leads');
$router->post('save_new_lead_type', [ClientController::class, 'save_new_lead_type'])->name('save_new_lead_type');
$router->post('convert_newleads_client', [ClientController::class, 'convert_newleads_client'])->name('convert_newleads_client');
$router->post('assign_newleads_to_staff', [ClientController::class, 'assign_newleads_to_staff'])->name('assign_newleads_to_staff');
$router->get('new_lead_history', [ClientController::class, 'new_lead_history'])->name('new_lead_history');
$router->get('get_cases-{id}', [ClientController::class, 'get_cases'])->name('get_cases');
$router->post('assign_leads_to_company', [ClientController::class, 'assign_leads_to_company'])->name('assign_leads_to_company');
$router->post('multi_convert_client', [ClientController::class, 'multi_convert_client'])->name('multi_convert_client');
$router->post('lead_complete', [ClientController::class, 'lead_complete'])->name('lead_complete');
$router->post('lead_delete', [ClientController::class, 'lead_delete'])->name('lead_delete');
$router->post('get_lead_followup', [ClientController::class, 'get_lead_followup'])->name('get_lead_followup');
$router->post('submit_lead_followUp', [ClientController::class, 'submit_lead_followUp'])->name('submit_lead_followUp');
$router->get('leads_timeline/{id}', [ClientController::class, 'leads_timeline'])->name('leads_timeline');

$router->get('campaign_wise_leads', [ClientController::class, 'campaign_wise_leads'])->name('campaign_wise_leads');
$router->get('campaign_wise_total', [ClientController::class, 'campaign_wise_total'])->name('campaign_wise_total');
$router->get('get_lead_report', [ClientController::class, 'get_lead_report'])->name('get_lead_report');
$router->post('save_new_leads', [ClientController::class, 'save_new_leads'])->name('save_new_leads');

$router->get('leads_followup_report', [ClientController::class, 'leads_followup_report'])->name('leads_followup_report');
$router->get('filter_leads_followup', [ClientController::class, 'filter_leads_followup'])->name('filter_leads_followup'); 

//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------LeaveController-----------------------------------------------------//
$router->post('approve_reject_leave', [LeaveController::class, 'approve_reject_leave'])->name('approve_reject_leave');
$router->get('staff_leave', [LeaveController::class, 'staff_leave'])->name('staff_leave');
$router->post('delete_staff_leave', [LeaveController::class, 'delete_staff_leave'])->name('delete_staff_leave');
$router->post('edit_staff_leave', [LeaveController::class, 'edit_staff_leave'])->name('edit_staff_leave');
$router->get('pending_leaves', [LeaveController::class, 'pending_leaves'])->name('pending_leaves');
$router->get('approved_leaves', [LeaveController::class, 'approved_leaves'])->name('approved_leaves');
$router->get('rejected_leaves', [LeaveController::class, 'rejected_leaves'])->name('rejected_leaves');
$router->post('show_statistics', [LeaveController::class, 'show_statistics'])->name('show_statistics');
$router->post('show_staffwise_statistics', [LeaveController::class, 'show_staffwise_statistics'])->name('show_staffwise_statistics');
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------Clear Cache-----------------------------------------------------//
Route::get('clear_cache', function () {
    $exitCode1 = Artisan::call('cache:clear');
    $exitCode2 = Artisan::call('view:clear');
    $exitCode3 = Artisan::call('route:clear');
    $exitCode4 = Artisan::call('config:clear');
    return "cleared";
});
//--------------------------------------------------------------------------------------------------------------------//

//----------------------------------------AppointmentController-----------------------------------------------------//
$router->post('get_appointment_by_status', [AppointmentController::class, 'get_appointment_by_status'])->name('get_appointment_by_status');
$router->get('appointment-list', [AppointmentController::class, 'appointment_list'])->name('appointment_list');
$router->get('appointment-add', [AppointmentController::class, 'appointment_add'])->name('appointment_add');
$router->post('submit_appointment', [AppointmentController::class, 'submit_appointment'])->name('submit_appointment');
$router->post('view_consulting_fee', [AppointmentController::class, 'view_consulting_fee'])->name('view_consulting_fee');
$router->post('update_consulting_fee', [AppointmentController::class, 'update_consulting_fee'])->name('update_consulting_fee');
$router->post('delete_consulting_fee', [AppointmentController::class, 'delete_consulting_fee'])->name('delete_consulting_fee');
$router->post('submit_consulting_fee', [AppointmentController::class, 'submit_consulting_fee'])->name('submit_consulting_fee');
$router->get('consulting_fee_reciept-{id}', [AppointmentController::class, 'consulting_fee_reciept'])->name('consulting_fee_reciept');
$router->post('delete_appointment', [AppointmentController::class, 'delete_appointment'])->name('delete_appointment');
$router->post('reschedule_meeting', [AppointmentController::class, 'reschedule_meeting'])->name('reschedule_meeting');
$router->post('save_meeting_notes', [AppointmentController::class, 'save_meeting_notes'])->name('save_meeting_notes');
$router->get('consulting_fee_reciept_UT-{id}', [AppointmentController::class, 'consulting_fee_reciept_UT'])->name('consulting_fee_reciept_UT');
//--------------------------------------------------------------------------------------------------------------------//

//-------------------------------------------AttendanceController-------------------------------------------------//

$router->get('raise_attendance-list', [AttendanceController::class, 'raise_attendance_list'])->name('raise_attendance_list');
$router->get('raise_attendance_table', [AttendanceController::class, 'raise_attendance_table'])->name('raise_attendance_table');
$router->post('attendance_status_update', [AttendanceController::class, 'attendance_status_update'])->name('attendance_status_update');
$router->post('reject_attendance', [AttendanceController::class, 'reject_attendance'])->name('reject_attendance');
$router->post('edit_raise_attendance', [AttendanceController::class, 'edit_raise_attendance'])->name('edit_raise_attendance');


//-------------------------------------------------------------------------------------------------------------------//


//----------------------------------------FollowUpController-----------------------------------------------------//
$router->get('follow-up-list', [FollowUpController::class, 'follow_up_list'])->name('follow_up_list');
$router->get('follow-up-add', [FollowUpController::class, 'follow_up_add'])->name('follow_up_add');
$router->post('get_contacts_followup', [FollowUpController::class, 'get_contacts_followup'])->name('get_contacts_followup');
$router->get('autocomplete_followup_disc', [FollowUpController::class, 'autocomplete_followup_disc'])->name('autocomplete_followup_disc');
$router->post('save_follow_up', [FollowUpController::class, 'save_follow_up'])->name('save_follow_up');
$router->post('delete_follow_up', [FollowUpController::class, 'delete_follow_up'])->name('delete_follow_up');
$router->post('get_followup_call_detail', [FollowUpController::class, 'get_followup_call_detail'])->name('get_followup_call_detail');
$router->post('get_follow_up_details', [FollowUpController::class, 'get_follow_up_details'])->name('get_follow_up_details');
$router->get('my_followup', [FollowUpController::class, 'my_followup'])->name('my_followup');
$router->get('my_next_followup', [FollowUpController::class, 'my_next_followup'])->name('my_next_followup');
$router->post('search_follow_up', [FollowUpController::class, 'search_follow_up'])->name('search_follow_up');
$router->post('search_mynext_followup', [FollowUpController::class, 'search_mynext_followup'])->name('search_mynext_followup');
$router->post('get_whatsapp_no', [FollowUpController::class, 'get_whatsapp_no'])->name('get_whatsapp_no');
//-------------------------------------------------------------------------------------------------------------------//

//----------------------------------------SettingController-----------------------------------------------------//
$router->get('staff_add', [SettingController::class, 'staff_add_index'])->name('staff_add_index');
$router->post('staff_add', [SettingController::class, 'staff_add'])->name('staff_add');
$router->get('staff_edit-{id}', [SettingController::class, 'staff_edit'])->name('staff_edit');
$router->post('staff_update', [SettingController::class, 'staff_update'])->name('staff_update');
$router->post('staff_status_change', [SettingController::class, 'staff_status_change'])->name('staff_status_change');
$router->get('get_staff_status_change', [SettingController::class, 'get_staff_status_change'])->name('get_staff_status_change');
$router->get('company_add', [SettingController::class, 'company_add_index'])->name('company_add_index');
$router->post('company_add', [SettingController::class, 'company_add'])->name('company_add');
$router->get('company_edit-{id}', [SettingController::class, 'company_edit'])->name('company_edit');
$router->post('company_update', [SettingController::class, 'company_update'])->name('company_update');
$router->get('get_company_update', [SettingController::class, 'get_company_update'])->name('get_company_update');
$router->get('bank_add', [SettingController::class, 'bank_add_index'])->name('bank_add_index');
$router->post('bank_add', [SettingController::class, 'bank_add'])->name('bank_add');
$router->post('bank_update', [SettingController::class, 'bank_update'])->name('bank_update');
$router->get('get_bank_update', [SettingController::class, 'get_bank_update'])->name('get_bank_update');
$router->get('template_add', [SettingController::class, 'template_add_index'])->name('template_add_index');
$router->post('template_add', [SettingController::class, 'template_add'])->name('template_add');
$router->get('template_edit-{id}', [SettingController::class, 'template_edit'])->name('template_edit');
$router->post('template_update', [SettingController::class, 'template_update'])->name('template_update');
$router->get('get_template_update', [SettingController::class, 'get_template_update'])->name('get_template_update');
$router->get('assign_leads', [SettingController::class, 'assign_leads_index'])->name('assign_leads_index');
$router->post('get_assign_leads', [SettingController::class, 'get_assign_leads'])->name('get_assign_leads');
$router->post('assign_leads', [SettingController::class, 'assign_leads'])->name('assign_leads');
$router->get('service_add', [SettingController::class, 'service_add_index'])->name('service_add_index');
$router->post('service_add', [SettingController::class, 'service_add'])->name('service_add');
$router->post('service_update', [SettingController::class, 'service_update'])->name('service_update');
$router->get('get_service_update', [SettingController::class, 'get_service_update'])->name('get_service_update');
$router->get('lead_type_add', [SettingController::class, 'lead_type_add_index'])->name('lead_type_add_index');
$router->post('lead_type_add', [SettingController::class, 'lead_type_add'])->name('lead_type_add');
$router->post('lead_type_update', [SettingController::class, 'lead_type_update'])->name('lead_type_update');
$router->get('get_lead_type_update', [SettingController::class, 'get_lead_type_update'])->name('get_lead_type_update');
$router->get('lead_data', [SettingController::class, 'lead_data_index'])->name('lead_data');
$router->get('upload_lead_data', [SettingController::class, 'upload_lead_data_index'])->name('upload_lead_data');
$router->post('upload_data', [SettingController::class, 'upload_data'])->name('upload_data');
$router->post('search_lead_data', [SettingController::class, 'search_lead_data'])->name('search_lead_data');


$router->get('add_office', [SettingController::class, 'add_office_index'])->name('add_office_index');
$router->post('add_office', [SettingController::class, 'add_office'])->name('add_office');
$router->post('office_update', [SettingController::class, 'office_update'])->name('office_update');
$router->get('get_office_update', [SettingController::class, 'get_office_update'])->name('get_office_update');

$router->get('staff_shift', [SettingController::class, 'staff_shift_index'])->name('staff_shift_index');
$router->post('staff_shift', [SettingController::class, 'staff_shift'])->name('staff_shift');
$router->get('get_staff_shift', [SettingController::class, 'get_staff_shift'])->name('get_staff_shift');
$router->post('update_staff_shift', [SettingController::class, 'update_staff_shift'])->name('update_staff_shift');
$router->get('leave-analytics', [SettingController::class, 'leave_analytics'])->name('leave_analytics');
$router->get('get_staff_leave', [SettingController::class, 'get_staff_leave'])->name('get_staff_leave');
$router->post('add_leave_analytics', [SettingController::class, 'add_leave_analytics'])->name('add_leave_analytics');
$router->post('update_leave_analytics', [SettingController::class, 'update_leave_analytics'])->name('update_leave_analytics');

$router->get('office-address', [SettingController::class, 'office_address'])->name('office_address');
$router->get('office-address-list', [SettingController::class, 'office_address_list'])->name('office_address_list');
$router->get('export-lead', [SettingController::class, 'export_lead'])->name('export_lead');
//-------------------------------------------------------------------------------------------------------------------//

//----------------------------------------ResetPasswordController------------------------------------------------//

Route::get('forgot_password', [ResetPasswordController::class, 'forgot_password'])->name('forgot_password');
Route::post('send_otp', [ResetPasswordController::class, 'send_otp'])->name('send_otp');
Route::post('forgot_password', [ResetPasswordController::class, 'reset_password'])->name('reset_password');
Route::post('reset_password', [ResetPasswordController::class, 'reset_password_submit'])->name('reset_password_submit');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------RefundController------------------------------------------------//
$router->get('refund_list', [RefundController::class, 'refund_list'])->name('refund_list');
$router->get('refund-add', [RefundController::class, 'refund_add_index'])->name('refund_add_index');
$router->post('refund-add', [RefundController::class, 'refund_add'])->name('refund_add');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------ReportController------------------------------------------------//
$router->get('report', [ReportController::class, 'report'])->name('report');
$router->post('get_report', [ReportController::class, 'get_report'])->name('get_report');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------QuotationReportController------------------------------------------------//
$router->get('quotation_sent_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_sent_excel'])->name('quotation_sent_excel');
$router->get('quotation_finalized_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_finalized_excel'])->name('quotation_finalized_excel');
$router->get('quotation_by_sales_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_by_sales_excel'])->name('quotation_by_sales_excel');
$router->get('quotation_by_office_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_by_office_excel'])->name('quotation_by_office_excel');
$router->get('clientwise_quotation_finalized_excel', [QuotationReportController::class, 'clientwise_quotation_finalized_excel'])->name('clientwise_quotation_finalized_excel');
$router->get('servicewise_quotation_sent_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'servicewise_quotation_sent_excel'])->name('servicewise_quotation_sent_excel');
$router->get('servicewise_quotation_finalized_excel/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'servicewise_quotation_finalized_excel'])->name('servicewise_quotation_finalized_excel');
$router->get('quotation_sent_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_sent_pdf'])->name('quotation_sent_pdf');
$router->get('quotation_finalized_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_finalized_pdf'])->name('quotation_finalized_pdf');
$router->get('quotation_by_sales_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_by_sales_pdf'])->name('quotation_by_sales_pdf');
$router->get('quotation_by_office_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'quotation_by_office_pdf'])->name('quotation_by_office_pdf');
$router->get('servicewise_quotation_sent_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'servicewise_quotation_sent_pdf'])->name('servicewise_quotation_sent_pdf');
$router->get('servicewise_quotation_finalized_pdf/{year}/{quarter}/{month}/{daily}', [QuotationReportController::class, 'servicewise_quotation_finalized_pdf'])->name('servicewise_quotation_finalized_pdf');
$router->get('clientwise_quotation_finalized_pdf', [QuotationReportController::class, 'clientwise_quotation_finalized_pdf'])->name('clientwise_quotation_finalized_pdf');

$router->get('quotation_sent_print', [QuotationReportController::class, 'quotation_sent_print'])->name('quotation_sent_print');
$router->get('quotation_finalized_print', [QuotationReportController::class, 'quotation_finalized_print'])->name('quotation_finalized_print');
$router->get('quotation_by_sales_print', [QuotationReportController::class, 'quotation_by_sales_print'])->name('quotation_by_sales_print');
$router->get('quotation_by_office_print', [QuotationReportController::class, 'quotation_by_office_print'])->name('quotation_by_office_print');
$router->get('servicewise_quotation_sent_print', [QuotationReportController::class, 'servicewise_quotation_sent_print'])->name('servicewise_quotation_sent_print');
$router->get('servicewise_quotation_finalized_print', [QuotationReportController::class, 'servicewise_quotation_finalized_print'])->name('servicewise_quotation_finalized_print');

$router->get('clientwise_quotation_finalized_print', [QuotationReportController::class, 'clientwise_quotation_finalized_print'])->name('clientwise_quotation_finalized_print');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------ExpenseReportController------------------------------------------------//
$router->get('expense_report_staffwise_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_staffwise_excel'])->name('expense_report_staffwise_excel');
$router->get('expense_report_ledgerwise_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_ledgerwise_excel'])->name('expense_report_ledgerwise_excel');
$router->get('expense_report_clientwise_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_clientwise_excel'])->name('expense_report_clientwise_excel');
$router->get('expense_report_reimbursement_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_reimbursement_excel'])->name('expense_report_reimbursement_excel');
$router->get('expense_report_client_ledgerwise_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_client_ledgerwise_excel'])->name('expense_report_client_ledgerwise_excel');
$router->get('expense_report_staff_ledgerwise_excel/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_staff_ledgerwise_excel'])->name('expense_report_staff_ledgerwise_excel');
$router->get('daily_expense_report_excel', [ExpenseReportController::class, 'daily_expense_report_excel'])->name('daily_expense_report_excel');

$router->get('expense_report_staffwise_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_staffwise_pdf'])->name('expense_report_staffwise_pdf');
$router->get('expense_report_ledgerwise_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_ledgerwise_pdf'])->name('expense_report_ledgerwise_pdf');
$router->get('expense_report_clientwise_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_clientwise_pdf'])->name('expense_report_clientwise_pdf');
$router->get('expense_report_reimbursement_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_reimbursement_pdf'])->name('expense_report_reimbursement_pdf');
$router->get('expense_report_client_ledgerwise_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_client_ledgerwise_pdf'])->name('expense_report_client_ledgerwise_pdf');
$router->get('expense_report_staff_ledgerwise_pdf/{year}/{quarter}/{month}/{daily}', [ExpenseReportController::class, 'expense_report_staff_ledgerwise_pdf'])->name('expense_report_staff_ledgerwise_pdf');
$router->get('daily_expense_report_pdf', [ExpenseReportController::class, 'daily_expense_report_pdf'])->name('daily_expense_report_pdf');

$router->get('expense_report_staffwise_print', [ExpenseReportController::class, 'expense_report_staffwise_print'])->name('expense_report_staffwise_print');
$router->get('expense_report_ledgerwise_print', [ExpenseReportController::class, 'expense_report_ledgerwise_print'])->name('expense_report_ledgerwise_print');
$router->get('expense_report_clientwise_print', [ExpenseReportController::class, 'expense_report_clientwise_print'])->name('expense_report_clientwise_print');
$router->get('expense_report_reimbursement_print', [ExpenseReportController::class, 'expense_report_reimbursement_print'])->name('expense_report_reimbursement_print');
$router->get('expense_report_client_ledgerwise_print', [ExpenseReportController::class, 'expense_report_client_ledgerwise_print'])->name('expense_report_client_ledgerwise_print');
$router->get('expense_report_staff_ledgerwise_print', [ExpenseReportController::class, 'expense_report_staff_ledgerwise_print'])->name('expense_report_staff_ledgerwise_print');
$router->get('daily_expense_report_print', [ExpenseReportController::class, 'daily_expense_report_print'])->name('daily_expense_report_print');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------ClientReportController------------------------------------------------//
$router->get('all_clients_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'all_clients_excel'])->name('all_clients_excel');
$router->get('all_leads_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'all_leads_excel'])->name('all_leads_excel');
$router->get('leads_by_sales_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'leads_by_sales_excel'])->name('leads_by_sales_excel');
$router->get('other_leads_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'other_leads_excel'])->name('other_leads_excel');
$router->get('client_followup_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_followup_excel'])->name('client_followup_excel');
$router->get('client_not_followup_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_not_followup_excel'])->name('client_not_followup_excel');
$router->get('client_no_email_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_no_email_excel'])->name('client_no_email_excel');
$router->get('client_no_contact_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_no_contact_excel'])->name('client_no_contact_excel');
$router->get('daily_sales_excel', [ClientReportController::class, 'daily_sales_excel'])->name('daily_sales_excel');
$router->get('assigned_leads_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'assigned_leads_excel'])->name('assigned_leads_excel');
$router->get('unassigned_leads_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'unassigned_leads_excel'])->name('unassigned_leads_excel');
$router->get('companywise_lead_contacts_excel', [ClientReportController::class, 'companywise_lead_contacts_excel'])->name('companywise_lead_contacts_excel');
$router->get('companywise_client_contacts_excel', [ClientReportController::class, 'companywise_client_contacts_excel'])->name('companywise_client_contacts_excel');
$router->get('leads_services_excel/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'leads_services_excel'])->name('leads_services_excel');

$router->get('all_clients_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'all_clients_pdf'])->name('all_clients_pdf');
$router->get('all_leads_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'all_leads_pdf'])->name('all_leads_pdf');
$router->get('leads_by_sales_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'leads_by_sales_pdf'])->name('leads_by_sales_pdf');
$router->get('other_leads_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'other_leads_pdf'])->name('other_leads_pdf');
$router->get('client_followup_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_followup_pdf'])->name('client_followup_pdf');
$router->get('client_not_followup_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_not_followup_pdf'])->name('client_not_followup_pdf');
$router->get('client_no_email_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_no_email_pdf'])->name('client_no_email_pdf');
$router->get('client_no_contact_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'client_no_contact_pdf'])->name('client_no_contact_pdf');
$router->get('daily_sales_pdf', [ClientReportController::class, 'daily_sales_pdf'])->name('daily_sales_pdf');
$router->get('assigned_leads_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'assigned_leads_pdf'])->name('assigned_leads_pdf');
$router->get('unassigned_leads_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'unassigned_leads_pdf'])->name('unassigned_leads_pdf');
$router->get('companywise_client_contacts_pdf', [ClientReportController::class, 'companywise_client_contacts_pdf'])->name('companywise_client_contacts_pdf');
$router->get('companywise_lead_contacts_pdf', [ClientReportController::class, 'companywise_lead_contacts_pdf'])->name('companywise_lead_contacts_pdf');
$router->get('leads_services_pdf/{year}/{quarter}/{month}/{daily}', [ClientReportController::class, 'leads_services_pdf'])->name('leads_services_pdf');

$router->get('all_clients_print', [ClientReportController::class, 'all_clients_print'])->name('all_clients_print');
$router->get('all_leads_print', [ClientReportController::class, 'all_leads_print'])->name('all_leads_print');
$router->get('leads_by_sales_print', [ClientReportController::class, 'leads_by_sales_print'])->name('leads_by_sales_print');
$router->get('other_leads_print', [ClientReportController::class, 'other_leads_print'])->name('other_leads_print');
$router->get('client_followup_print', [ClientReportController::class, 'client_followup_print'])->name('client_followup_print');
$router->get('client_not_followup_print', [ClientReportController::class, 'client_not_followup_print'])->name('client_not_followup_print');
$router->get('client_no_email_print', [ClientReportController::class, 'client_no_email_print'])->name('client_no_email_print');
$router->get('client_no_contact_print', [ClientReportController::class, 'client_no_contact_print'])->name('client_no_contact_print');
$router->get('daily_sales_print', [ClientReportController::class, 'daily_sales_print'])->name('daily_sales_print');
$router->get('assigned_leads_print', [ClientReportController::class, 'assigned_leads_print'])->name('assigned_leads_print');
$router->get('unassigned_leads_print', [ClientReportController::class, 'unassigned_leads_print'])->name('unassigned_leads_print');
$router->get('companywise_client_contacts_print', [ClientReportController::class, 'companywise_client_contacts_print'])->name('companywise_client_contacts_print');
$router->get('companywise_lead_contacts_print', [ClientReportController::class, 'companywise_lead_contacts_print'])->name('companywise_lead_contacts_print');
$router->get('leads_services_print', [ClientReportController::class, 'leads_services_print'])->name('leads_services_print');

$router->get('companywise_lead_contacts_and_quotation_excel', [ClientReportController::class, 'companywise_lead_contacts_and_quotation_excel'])->name('companywise_lead_contacts_and_quotation_excel');
$router->get('companywise_client_contacts_and_quotation_excel', [ClientReportController::class, 'companywise_client_contacts_and_quotation_excel'])->name('companywise_client_contacts_and_quotation_excel');
$router->get('companywise_client_contacts_and_quotation_pdf', [ClientReportController::class, 'companywise_client_contacts_and_quotation_pdf'])->name('companywise_client_contacts_and_quotation_pdf');
$router->get('companywise_lead_contacts_and_quotation_pdf', [ClientReportController::class, 'companywise_lead_contacts_and_quotation_pdf'])->name('companywise_lead_contacts_and_quotation_pdf');
$router->get('companywise_lead_contacts_and_quotation_print', [ClientReportController::class, 'companywise_lead_contacts_and_quotation_print'])->name('companywise_lead_contacts_and_quotation_print');
$router->get('companywise_client_contacts_and_quotation_print', [ClientReportController::class, 'companywise_client_contacts_and_quotation_print'])->name('companywise_client_contacts_and_quotation_print');

//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------AttendanceReportController------------------------------------------------//
$router->get('staff_attendance_excel/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'staff_attendance_excel'])->name('staff_attendance_excel');
$router->get('staff_attendance_pdf/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'staff_attendance_pdf'])->name('staff_attendance_pdf');
$router->get('staff_attendance_print', [AttendanceReportController::class, 'staff_attendance_print'])->name('staff_attendance_print');

$router->get('salary_attendance_excel/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'salary_attendance_excel'])->name('salary_attendance_excel');
$router->get('salary_attendance_pdf/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'salary_attendance_pdf'])->name('salary_attendance_pdf');
$router->get('salary_attendance_print', [AttendanceReportController::class, 'salary_attendance_print'])->name('salary_attendance_print');

$router->get('staff_work_excel/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'staff_work_excel'])->name('staff_work_excel');
$router->get('staff_work_pdf/{year}/{quarter}/{month}/{daily}', [AttendanceReportController::class, 'staff_work_pdf'])->name('staff_work_pdf');
$router->get('staff_work_print', [AttendanceReportController::class, 'staff_work_print'])->name('staff_work_print');
//---------------------------------------------------------------------------------------------------------------//

//----------------------------------------AccountingReportController------------------------------------------------//

$router->get('invoice_against_quotation_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'invoice_against_quotation_excel'])->name('invoice_against_quotation_excel');
$router->get('additional_invoices_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'additional_invoices_excel'])->name('additional_invoices_excel');
$router->get('cancelled_invoice_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'cancelled_invoice_excel'])->name('cancelled_invoice_excel');
$router->get('consultation_fee_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'consultation_fee_excel'])->name('consultation_fee_excel');
$router->get('billwise_payment_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'billwise_payment_excel'])->name('billwise_payment_excel');
$router->get('clientwise_tds_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'clientwise_tds_excel'])->name('clientwise_tds_excel');
$router->get('sales_target_excel/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'sales_target_excel'])->name('sales_target_excel');

$router->get('invoice_against_quotation_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'invoice_against_quotation_pdf'])->name('invoice_against_quotation_pdf');
$router->get('additional_invoices_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'additional_invoices_pdf'])->name('additional_invoices_pdf');
$router->get('cancelled_invoice_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'cancelled_invoice_pdf'])->name('cancelled_invoice_pdf');
$router->get('consultation_fee_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'consultation_fee_pdf'])->name('consultation_fee_pdf');
$router->get('billwise_payment_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'billwise_payment_pdf'])->name('billwise_payment_pdf');
$router->get('clientwise_tds_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'clientwise_tds_pdf'])->name('clientwise_tds_pdf');
$router->get('sales_target_pdf/{year}/{quarter}/{month}/{daily}', [AccountingReportController::class, 'sales_target_pdf'])->name('sales_target_pdf');

$router->get('invoice_against_quotation_print', [AccountingReportController::class, 'invoice_against_quotation_print'])->name('invoice_against_quotation_print');
$router->get('additional_invoices_print', [AccountingReportController::class, 'additional_invoices_print'])->name('additional_invoices_print');
$router->get('cancelled_invoice_print', [AccountingReportController::class, 'cancelled_invoice_print'])->name('cancelled_invoice_print');
$router->get('consultation_fee_print', [AccountingReportController::class, 'consultation_fee_print'])->name('consultation_fee_print');
$router->get('billwise_payment_print', [AccountingReportController::class, 'billwise_payment_print'])->name('billwise_payment_print');
$router->get('clientwise_tds_print', [AccountingReportController::class, 'clientwise_tds_print'])->name('clientwise_tds_print');
$router->get('sales_target_print', [AccountingReportController::class, 'sales_target_print'])->name('sales_target_print');
//---------------------------------------------------------------------------------------------------------------//

//------------------------------------------------PrettyCashReportController---------------------------------------------------//
$router->get('pretty_cash_excel/{year}/{quarter}/{month}/{daily}', [PrettyCashReportController::class, 'pretty_cash_excel'])->name('pretty_cash_excel');
$router->get('pretty_cash_pdf/{year}/{quarter}/{month}/{daily}', [PrettyCashReportController::class, 'pretty_cash_pdf'])->name('pretty_cash_pdf');
$router->get('pretty_cash_print', [PrettyCashReportController::class, 'pretty_cash_print'])->name('pretty_cash_print');
//----------------------------------------------------------------------------------------------------------------------------//

//-------------------------------------------------AdminReportController---------------------------------------------------//
$router->get('admin_quotation_finalized_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'admin_quotation_finalized_excel'])->name('admin_quotation_finalized_excel');
$router->get('admin_quotation_finalized_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'admin_quotation_finalized_pdf'])->name('admin_quotation_finalized_pdf');
$router->get('admin_quotation_finalized_print', [AdminReportController::class, 'admin_quotation_finalized_print'])->name('admin_quotation_finalized_print');
$router->get('Admin_servicewise_quotation_finalized_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_servicewise_quotation_finalized_excel'])->name('Admin_servicewise_quotation_finalized_excel');
$router->get('Admin_servicewise_quotation_finalized_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_servicewise_quotation_finalized_pdf'])->name('Admin_servicewise_quotation_finalized_pdf');
$router->get('Admin_servicewise_quotation_finalized_print', [AdminReportController::class, 'Admin_servicewise_quotation_finalized_print'])->name('Admin_servicewise_quotation_finalized_print');
$router->get('Admin_leads_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_leads_excel'])->name('Admin_leads_excel');
$router->get('Admin_leads_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_leads_pdf'])->name('Admin_leads_pdf');
$router->get('Admin_leads_print', [AdminReportController::class, 'Admin_leads_print'])->name('Admin_leads_print');
$router->get('Admin_assigned_leads_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_assigned_leads_excel'])->name('Admin_assigned_leads_excel');
$router->get('Admin_assigned_leads_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_assigned_leads_pdf'])->name('Admin_assigned_leads_pdf');
$router->get('Admin_assigned_leads_print', [AdminReportController::class, 'Admin_assigned_leads_print'])->name('Admin_assigned_leads_print');
$router->get('Admin_unassigned_leads_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_unassigned_leads_excel'])->name('Admin_unassigned_leads_excel');
$router->get('Admin_unassigned_leads_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_unassigned_leads_pdf'])->name('Admin_unassigned_leads_pdf');
$router->get('Admin_unassigned_leads_print', [AdminReportController::class, 'Admin_unassigned_leads_print'])->name('Admin_unassigned_leads_print');
$router->get('Admin_invoice_against_quotation_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_invoice_against_quotation_excel'])->name('Admin_invoice_against_quotation_excel');
$router->get('Admin_invoice_against_quotation_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_invoice_against_quotation_pdf'])->name('Admin_invoice_against_quotation_pdf');
$router->get('Admin_invoice_against_quotation_print', [AdminReportController::class, 'Admin_invoice_against_quotation_print'])->name('Admin_invoice_against_quotation_print');
$router->get('Admin_additional_invoices_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_additional_invoices_excel'])->name('Admin_additional_invoices_excel');
$router->get('Admin_additional_invoices_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_additional_invoices_pdf'])->name('Admin_additional_invoices_pdf');
$router->get('Admin_additional_invoices_print', [AdminReportController::class, 'Admin_additional_invoices_print'])->name('Admin_additional_invoices_print');
$router->get('Admin_cancelled_invoice_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_cancelled_invoice_excel'])->name('Admin_cancelled_invoice_excel');
$router->get('Admin_cancelled_invoice_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_cancelled_invoice_pdf'])->name('Admin_cancelled_invoice_pdf');
$router->get('Admin_cancelled_invoice_print', [AdminReportController::class, 'Admin_cancelled_invoice_print'])->name('Admin_cancelled_invoice_print');
$router->get('Admin_consultation_fee_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_consultation_fee_excel'])->name('Admin_consultation_fee_excel');
$router->get('Admin_consultation_fee_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_consultation_fee_pdf'])->name('Admin_consultation_fee_pdf');
$router->get('Admin_consultation_fee_print', [AdminReportController::class, 'Admin_consultation_fee_print'])->name('Admin_consultation_fee_print');
$router->get('Admin_daily_visit_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_daily_visit_excel'])->name('Admin_daily_visit_excel');
$router->get('Admin_daily_visit_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'Admin_daily_visit_pdf'])->name('Admin_daily_visit_pdf');
$router->get('Admin_daily_visit_print', [AdminReportController::class, 'Admin_daily_visit_print'])->name('Admin_daily_visit_print');
$router->get('checkIn_staff_pdf/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'checkIn_staff_pdf'])->name('checkIn_staff_pdf');
$router->get('checkIn_staff_excel/{year}/{quarter}/{month}/{daily}', [AdminReportController::class, 'checkIn_staff_excel'])->name('checkIn_staff_excel');
$router->get('checkIn_staff_print', [AdminReportController::class, 'checkIn_staff_print'])->name('checkIn_staff_print');

//-------------------------------------------------------------------------------------------------------------------------//


//----------------------------------------------------------------SalesReportController--------------------------------------------------------------------//
$router->get('sales_assigned_leads_pdf/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_assigned_leads_pdf'])->name('sales_assigned_leads_pdf');
$router->get('sales_assigned_leads_excel/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_assigned_leads_excel'])->name('sales_assigned_leads_excel');
$router->get('sales_assigned_leads_print', [SalesReportController::class, 'sales_assigned_leads_print'])->name('sales_assigned_leads_print');
$router->get('sales_quotation_sent_pdf/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_quotation_sent_pdf'])->name('sales_quotation_sent_pdf');
$router->get('sales_quotation_sent_excel/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_quotation_sent_excel'])->name('sales_quotation_sent_excel');
$router->get('sales_quotation_sent_print', [SalesReportController::class, 'sales_quotation_sent_print'])->name('sales_quotation_sent_print');
$router->get('sales_quotation_finalized_pdf/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_quotation_finalized_pdf'])->name('sales_quotation_finalized_pdf');
$router->get('sales_quotation_finalized_excel/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_quotation_finalized_excel'])->name('sales_quotation_finalized_excel');
$router->get('sales_quotation_finalized_print', [SalesReportController::class, 'sales_quotation_finalized_print'])->name('sales_quotation_finalized_print');
$router->get('sales_invoice_against_quotation_pdf/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_invoice_against_quotation_pdf'])->name('sales_invoice_against_quotation_pdf');
$router->get('sales_invoice_against_quotation_excel/{year}/{quarter}/{month}/{daily}', [SalesReportController::class, 'sales_invoice_against_quotation_excel'])->name('sales_invoice_against_quotation_excel');
$router->get('sales_invoice_against_quotation_print', [SalesReportController::class, 'sales_invoice_against_quotation_print'])->name('sales_invoice_against_quotation_print');
//--------------------------------------------------------------------------------------------------------------------------------------------------------//


//------------------------------------------KnowledgeBaseController---------------------------------------------//
$router->get('upload_document', [KnowledgeBaseController::class, 'upload_document_index'])->name('upload_document');
$router->post('upload_document', [KnowledgeBaseController::class, 'upload_document'])->name('upload_document');
$router->get('autocomplete_tags', [KnowledgeBaseController::class, 'autocomplete_tags'])->name('autocomplete_tags');
$router->get('autocomplete_title', [KnowledgeBaseController::class, 'autocomplete_title'])->name('autocomplete_title');
$router->post('search_tags', [KnowledgeBaseController::class, 'search_tags'])->name('search_tags');
$router->get('search_document', [KnowledgeBaseController::class, 'search_document_index'])->name('search_document');
$router->get('download_document', [KnowledgeBaseController::class, 'download_document'])->name('download_document');
//-----------------------------------------------------------------------------------------------------------------//
//---------------------MyCasesController-------------------//
Route::get('chat', [MyCasesController::class, 'chat'])->name('chat');
Route::get('get_mycases_list', [MyCasesController::class, 'getMyCases'])->name('get_mycases_list');
Route::get('get_chat_list', [MyCasesController::class, 'chat_list'])->name('get_chat_list');
Route::get('get_contacts', [MyCasesController::class, 'getContacts'])->name('getContacts');
Route::post('add_participate', [MyCasesController::class, 'add_participate'])->name('add_participate');
Route::post('remove_participate', [MyCasesController::class, 'remove_participate'])->name('remove_participate');
Route::post('add_staff', [MyCasesController::class, 'add_staff'])->name('add_staff');
Route::post('remove_staff', [MyCasesController::class, 'remove_staff'])->name('remove_staff');
Route::post('upload_mycases_doc', [MyCasesController::class, 'upload_mycases_doc'])->name('upload_mycases_doc');
Route::post('send_chats', [MyCasesController::class, 'send_chats'])->name('send_chats');
Route::post('get_next_msg', [MyCasesController::class, 'get_next_msg'])->name('get_next_msg');
Route::post('case_invoice', [MyCasesController::class, 'case_invoice'])->name('case_invoice');
Route::post('case_document', [MyCasesController::class, 'case_document'])->name('case_document');

Route::post('save_firebase_token', [DashboardController::class, 'save_firebase_token'])->name('save_firebase_token');
//---------------------------------------------------------------------------------------------------------------//
Route::get('task', [TaskManagementController::class, 'index'])->name('task');

Route::get('projects', [TaskManagementController::class, 'projects'])->name('projects');
Route::post('project_filter', [TaskManagementController::class, 'project_filter'])->name('project_filter');
Route::post('get_task_list', [TaskManagementController::class, 'get_task_list'])->name('get_task_list');
Route::post('update_task', [TaskManagementController::class, 'update_task'])->name('update_task');


Route::post('add_task', [TaskManagementController::class, 'add_task'])->name('add_task');
Route::post('get_task_data', [TaskManagementController::class, 'get_task_data'])->name('get_task_data');
Route::post('delete_task', [TaskManagementController::class, 'delete_task'])->name('delete_task');
Route::post('task_filter', [TaskManagementController::class, 'task_filter'])->name('task_filter');
// Project 
Route::post('new_project', [TaskManagementController::class, 'new_project'])->name('new_project');
Route::get('projects_table', [TaskManagementController::class, 'projects_table'])->name('projects_table');
Route::post('get_project_data', [TaskManagementController::class, 'get_project_data'])->name('get_project_data');
Route::post('update_project', [TaskManagementController::class, 'update_project'])->name('update_project');
Route::post('delete_project', [TaskManagementController::class, 'delete_project'])->name('delete_project');

Route::post('client_project_list', [TaskManagementController::class, 'client_project_list'])->name('client_project_list');
Route::get('projects_task-{project_id}', [TaskManagementController::class, 'projects_task'])->name('projects_task');

Route::get('assignee_list', [TaskManagementController::class, 'web_assignee_list'])->name('web_assignee_list');
//Template
Route::post('add_template', [TaskManagementController::class, 'add_template'])->name('add_template');
Route::post('update_template', [TaskManagementController::class, 'update_template'])->name('update_template');
Route::post('delete_template', [TaskManagementController::class, 'delete_template'])->name('delete_template');
Route::post('get_template_data', [TaskManagementController::class, 'get_template_data'])->name('get_template_data');
Route::post('add_task_template', [TaskManagementController::class, 'add_task_template'])->name('add_task_template');
Route::post('update_task_template', [TaskManagementController::class, 'update_task_template'])->name('update_task_template');
Route::get('template_task-{main_template_id}', [TaskManagementController::class, 'template_task'])->name('template_task');
Route::post('template_task_list', [TaskManagementController::class, 'template_task_list'])->name('template_task_list');
//Duplicate template
Route::post('duplicate_template', [TaskManagementController::class, 'duplicate_template'])->name('duplicate_template');
Route::post('add_subtask', [TaskManagementController::class, 'add_subtask'])->name('add_subtask');
Route::post('update_subtask', [TaskManagementController::class, 'update_subtask'])->name('update_subtask');
Route::post('get_task_chart', [TaskManagementController::class, 'get_task_chart'])->name('get_task_chart');
Route::get('task_status_details-{task_status_id}-{month}', [TaskManagementController::class, 'task_status_details'])->name('task_status_details');
Route::post('get_task_status_list', [TaskManagementController::class, 'get_task_status_list'])->name('get_task_status_list');
Route::get('get_type_chart', [TaskManagementController::class, 'get_type_chart'])->name('get_type_chart');
Route::get('task_type_details-{type_name}-{month}', [TaskManagementController::class, 'task_type_details'])->name('task_type_details');
Route::post('get_task_type_list', [TaskManagementController::class, 'get_task_type_list'])->name('get_task_type_list');

$router->get('get_task_on_status', [TaskManagementController::class, 'get_task_on_status'])->name('get_task_on_status');
$router->get('task_comment_inbox', [TaskManagementController::class, 'task_comment_inbox'])->name('task_comment_inbox');
$router->get('task_analytics', [TaskManagementController::class, 'task_analytics'])->name('task_analytics');
$router->get('staff_wise_task', [TaskManagementController::class, 'staff_wise_task'])->name('staff_wise_task');
$router->post('get_staff_wise_task', [TaskManagementController::class, 'get_staff_wise_task'])->name('get_staff_wise_task');

$router->get('overdue_task', [TaskManagementController::class, 'overdue_task'])->name('overdue_task');
$router->get('overdue_task_grid', [TaskManagementController::class, 'overdue_task_grid'])->name('overdue_task_grid');
$router->get('get_overdue_grid_task', [TaskManagementController::class, 'get_overdue_grid_task'])->name('get_overdue_grid_task');
$router->post('get_over_due_task', [TaskManagementController::class, 'get_over_due_task'])->name('get_over_due_task');
Route::post('overdue_task_filter', [TaskManagementController::class, 'overdue_task_filter'])->name('overdue_task_filter');
$router->get('my_task', [TaskManagementController::class, 'my_task'])->name('my_task');
$router->post('get_my_task', [TaskManagementController::class, 'get_my_task'])->name('get_my_task');
$router->get('my_task_grid', [TaskManagementController::class, 'my_task_grid'])->name('my_task_grid');
$router->get('get_my_task_grid', [TaskManagementController::class, 'get_my_task_grid'])->name('get_my_task_grid');
//comment///
$router->get('comment_inbox', [TaskManagementController::class, 'comment_inbox'])->name('comment_inbox');
$router->post('get_mention_assignee', [TaskManagementController::class, 'get_mention_assignee'])->name('get_mention_assignee');
$router->post('save_task_comment', [TaskManagementController::class, 'save_task_comment'])->name('save_task_comment');
$router->post('get_task_comment', [TaskManagementController::class, 'get_task_comment'])->name('get_task_comment');
//inoutBox
Route::get('inbox', [TaskManagementController::class, 'inbox'])->name('inbox');
Route::get('outbox', [TaskManagementController::class, 'outbox'])->name('outbox');
$router->post('task_comment_inbox', [TaskManagementController::class, 'task_comment_inbox'])->name('task_comment_inbox');
$router->post('task_comment_outbox', [TaskManagementController::class, 'task_comment_outbox'])->name('task_comment_outbox');

//task_hearing
Route::get('task_hearing', [TaskManagementController::class, 'task_hearing'])->name('task_hearing');
$router->post('get_hearing_task', [TaskManagementController::class, 'get_hearing_task'])->name('get_hearing_task');
Route::post('getprojectstatus', [TaskManagementController::class, 'getprojectstatus'])->name('getprojectstatus');
Route::get('generate_short_name', [TaskManagementController::class, 'generate_short_name'])->name('generate_short_name');
Route::get('task_hearing_excel', [TaskManagementController::class, 'task_hearing_excel'])->name('task_hearing_excel');
Route::post('raise_overdue', [TaskManagementController::class, 'raise_overdue'])->name('raise_overdue');
Route::get('raised_overdue_task', [TaskManagementController::class, 'raised_overdue_task'])->name('raised_overdue_task');
Route::get('get_raised_overdue_task', [TaskManagementController::class, 'get_raised_overdue_task'])->name('get_raised_overdue_task');
Route::post('reject_overdue_task', [TaskManagementController::class, 'reject_overdue_task'])->name('reject_overdue_task');
Route::post('approve_overdue', [TaskManagementController::class, 'approve_overdue'])->name('approve_overdue');
Route::get('get_my_overdue', [TaskManagementController::class, 'get_my_overdue'])->name('get_my_overdue');
Route::get('project_timeline-{project_id}', [TaskManagementController::class, 'project_timeline'])->name('project_timeline');

///task report
Route::get('task-report', [TaskManagementReportController::class, 'task_report'])->name('task_report');
Route::get('get-task-report', [TaskManagementReportController::class, 'get_task_report'])->name('get_task_report');
Route::get('onhold_task_pdf', [TaskManagementReportController::class, 'onhold_task_pdf'])->name('onhold_task_pdf');
Route::get('onhold_task_excel', [TaskManagementReportController::class, 'onhold_task_excel'])->name('onhold_task_excel');
Route::get('onhold_task_print', [TaskManagementReportController::class, 'onhold_task_print'])->name('onhold_task_print');
Route::get('hearing_task_excel', [TaskManagementReportController::class, 'hearing_task_excel'])->name('hearing_task_excel');

$router->post('save_department', [DepartmentController::class, 'save_department'])->name('save_department');
$router->post('update_department', [DepartmentController::class, 'update_department'])->name('update_department');
$router->get('testing_mail', [DepartmentController::class, 'testing_mail'])->name('testing_mail');
