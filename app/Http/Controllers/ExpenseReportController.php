<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppHelper;

class ExpenseReportController extends Controller
{
    use ExpenseTraits;
    use StaffTraits;

    public function expense_report_staffwise_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $staff1 = DB::table('staff')->get();

                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                $out1 = '';
                $export_data = "Staffwise Expense Report -\n\n";
                foreach ($staff1 as $stf) {
                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.by_whom', $stf->sid)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('by_whom', $stf->sid)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $staff1 = DB::table('staff')->get();

                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                $out1 = '';
                $export_data = "Staffwise Expense Report -\n\n";
                foreach ($staff1 as $stf) {
                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.by_whom', $stf->sid)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('by_whom', $stf->sid)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $staff1 = DB::table('staff')->get();

                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                $out1 = '';
                $export_data = "Staffwise Expense Report -\n\n";
                foreach ($staff1 as $stf) {
                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.by_whom', $stf->sid)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('by_whom', $stf->sid)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $staff1 = DB::table('staff')->get();

                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                $out1 = '';
                $export_data = "Staffwise Expense Report -\n\n";
                foreach ($staff1 as $stf) {
                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.by_whom', $stf->sid)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('by_whom', $stf->sid)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();
            $out1 = '';
            $export_data = "Staffwise Expense Report -\n\n";
            foreach ($staff1 as $stf) {
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->whereMonth('expense.date', $month)
                    ->whereYear('expense.date', $curr_year)
                    ->where('expense.by_whom', $stf->sid)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $curr_year)
                    ->where('by_whom', $stf->sid)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Staff - (" . $stf->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        if ($row->client_name != "") {
                            $client = $row->case_no . '(' . $row->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();
            $out1 = '';
            $export_data = "Staffwise Expense Report -\n\n";
            foreach ($staff1 as $stf) {
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->whereBetween('expense.date', [$start_year, $end_year])
                    ->where('expense.by_whom', $stf->sid)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('date', [$start_year, $end_year])
                    ->where('by_whom', $stf->sid)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Staff - (" . $stf->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        if ($row->client_name != "") {
                            $client = $row->case_no . '(' . $row->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Staffwise_Expense_Report.xls\"");
    }

    public function expense_report_staffwise_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('01-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('by_whom', $StaffId)
                ->sum('amount');

            foreach ($staff as $stf) {
                $stf->expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where(
                        'expense.status',
                        'approved'
                    )
                    ->where(
                        'expense.company',
                        session('default_company_id')
                    );
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $stf->expense_list = $stf->expense_list->whereMonth('expense.date', $month)
                        ->whereYear('expense.date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                }
                $stf->expense_list = $stf->expense_list->where('expense.by_whom', $stf->sid)
                    ->orderBy(
                        'expense.date',
                        'asc'
                    )
                    ->get();

                $stf->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $stf->grand_total = $stf->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $stf->grand_total = $stf->grand_total->where('by_whom', $stf->sid)
                    ->sum('amount');

                foreach ($stf->expense_list as $row) {
                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_staffwise_expense_report', compact('staff', 'total', 'FilterDate')));

            return ($mpdf->Output('Staffwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_staffwise_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('by_whom', $StaffId)
                ->sum('amount');

            foreach ($staff as $stf) {
                $stf->expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $stf->expense_list = $stf->expense_list->whereMonth('expense.date', $month)
                        ->whereYear('expense.date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $stf->expense_list = $stf->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                }
                $stf->expense_list = $stf->expense_list->where('expense.by_whom', $stf->sid)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $stf->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $stf->grand_total = $stf->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $stf->grand_total = $stf->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $stf->grand_total = $stf->grand_total->where('by_whom', $stf->sid)
                    ->sum('amount');

                foreach ($stf->expense_list as $row) {
                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }


            return view('pages.reports.get_staffwise_expense_report', compact('staff', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_ledgerwise_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                $out1 = '';
                $export_data = "Ledgerwise Expense Report -\n\n";
                foreach ($accounting_sub_heads as $shead) {
                    $ledger = $shead->id;

                    $expense_list = DB::table('expense')
                        ->select('*')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->orderBy('date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr.No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                $out1 = '';
                $export_data = "Ledgerwise Expense Report -\n\n";
                foreach ($accounting_sub_heads as $shead) {
                    $ledger = $shead->id;

                    $expense_list = DB::table('expense')
                        ->select('*')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->orderBy('date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr.No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                $out1 = '';
                $export_data = "Ledgerwise Expense Report -\n\n";
                foreach ($accounting_sub_heads as $shead) {
                    $ledger = $shead->id;

                    $expense_list = DB::table('expense')
                        ->select('*')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->orderBy('date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr.No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                $out1 = '';
                $export_data = "Ledgerwise Expense Report -\n\n";
                foreach ($accounting_sub_heads as $shead) {
                    $ledger = $shead->id;

                    $expense_list = DB::table('expense')
                        ->select('*')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->orderBy('date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_quarter, $end_quarter])
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr.No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->client_name != "") {
                                $client = $row->case_no . '(' . $row->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
            $out1 = '';
            $export_data = "Ledgerwise Expense Report -\n\n";
            foreach ($accounting_sub_heads as $shead) {
                $ledger = $shead->id;

                $expense_list = DB::table('expense')
                    ->select('*')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $curr_year)
                    ->where('ledger', $ledger)
                    ->orderBy('date', 'asc')
                    ->get();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $curr_year)
                    ->where('ledger', $ledger)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        if ($row->client_name != "") {
                            $client = $row->case_no . '(' . $row->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
            $out1 = '';
            $export_data = "Ledgerwise Expense Report -\n\n";
            foreach ($accounting_sub_heads as $shead) {
                $ledger = $shead->id;

                $expense_list = DB::table('expense')
                    ->select('*')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('date', [$start_year, $end_year])
                    ->where('ledger', $ledger)
                    ->orderBy('date', 'asc')
                    ->get();


                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('date', [$start_year, $end_year])
                    ->where('ledger', $ledger)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Ledger - (" . $shead->sub_heads . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        if ($row->client_name != "") {
                            $client = $row->case_no . '(' . $row->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Ledgerwise_Expense_Report.xls\"");
    }

    public function expense_report_ledgerwise_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }


            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();

            foreach ($accounting_sub_heads as $shead) {
                $ledger = $shead->id;
                $shead->expense_list = DB::table('expense')
                    ->select('*')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->expense_list = $shead->expense_list->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_year, $end_year]);
                }
                $shead->expense_list = $shead->expense_list->where('ledger', $ledger)
                    ->orderBy('date', 'asc')
                    ->get();

                $shead->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->grand_total = $shead->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $shead->grand_total = $shead->grand_total->where('ledger', $ledger)
                    ->sum('amount');
                if (sizeof($shead->expense_list)) {
                    foreach ($shead->expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_ledgerwise_expense_report', compact('accounting_sub_heads', 'FilterDate')));

            return ($mpdf->Output('Ledgerwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_ledgerwise_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();

            foreach ($accounting_sub_heads as $shead) {
                $ledger = $shead->id;
                $shead->expense_list = DB::table('expense')
                    ->select('*')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->expense_list = $shead->expense_list->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->expense_list = $shead->expense_list->whereBetween('date', [$start_year, $end_year]);
                }
                $shead->expense_list = $shead->expense_list->where('ledger', $ledger)
                    ->orderBy('date', 'asc')
                    ->get();

                $shead->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->grand_total = $shead->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $shead->grand_total = $shead->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $shead->grand_total = $shead->grand_total->where('ledger', $ledger)
                    ->sum('amount');
                if (sizeof($shead->expense_list)) {
                    foreach ($shead->expense_list as $row) {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                    }
                }
            }

            return view('pages.reports.get_ledgerwise_expense_report', compact('accounting_sub_heads', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_clientwise_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();
                $out1 = '';
                $export_data = "Clientwise Expense Report -\n\n";
                foreach ($clients as $val) {

                    $client_id = $val->client_id;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();
                $out1 = '';
                $export_data = "Clientwise Expense Report -\n\n";
                foreach ($clients as $val) {

                    $client_id = $val->client_id;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();
                $out1 = '';
                $export_data = "Clientwise Expense Report -\n\n";
                foreach ($clients as $val) {

                    $client_id = $val->client_id;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();
                $out1 = '';
                $export_data = "Clientwise Expense Report -\n\n";
                foreach ($clients as $val) {

                    $client_id = $val->client_id;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                        ->where('expense.client_id', $client_id)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                        foreach ($expense_list as $row) {
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy('expense.client_id', 'asc')
                ->get();
            $out1 = '';
            $export_data = "Clientwise Expense Report -\n\n";
            foreach ($clients as $val) {

                $client_id = $val->client_id;

                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->whereMonth('expense.date', $month)
                    ->whereYear('expense.date', $curr_year)
                    ->where('expense.client_id', $client_id)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $curr_year)
                    ->where('expense.client_id', $client_id)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy('expense.client_id', 'asc')
                ->get();
            $out1 = '';
            $export_data = "Clientwise Expense Report -\n\n";
            foreach ($clients as $val) {

                $client_id = $val->client_id;

                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->whereBetween('expense.date', [$start_year, $end_year])
                    ->where('expense.client_id', $client_id)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('date', [$start_year, $end_year])
                    ->where('expense.client_id', $client_id)
                    ->sum('amount');

                if ($expense_list != '[]') {
                    $i = 1;
                    $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                    foreach ($expense_list as $row) {
                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                        $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                        if ($row->bill != "") {
                            $bill = 'YES';
                        } else {
                            $bill = 'NO';
                        }

                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Clientwise_Expense_Report.xls\"");
    }

    public function expense_report_clientwise_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }



            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            //$clients = DB::table('clients')->where('default_company', session('company_id'))->get();
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select(
                    'expense.client_id',
                    'clients.client_name',
                    'clients.case_no'
                )
                ->distinct()
                ->orderBy('expense.id', 'asc')
                ->get();

            $ClientId = array_column(json_decode($clients), 'client_id');

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('client_id', $ClientId)
                ->sum('amount');

            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $val->expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.*', 'clients.client_name', 'clients.case_no', 'accounting_sub_heads.sub_heads')
                    ->where(
                        'expense.status',
                        'approved'
                    )
                    ->where(
                        'expense.company',
                        session('default_company_id')
                    );
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->expense_list = $val->expense_list->whereMonth('expense.date', $month)
                        ->whereYear('expense.date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                }
                $val->expense_list = $val->expense_list->where('expense.client_id', $client_id)
                    ->orderBy(
                        'expense.date',
                        'asc'
                    )
                    ->get();

                $val->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->where('client_id', $client_id)
                    ->sum('amount');

                foreach ($val->expense_list as $row) {
                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_clientwise_expense_report', compact('clients', 'total', 'FilterDate')));


            return ($mpdf->Output('Clientwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_clientwise_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            //$clients = DB::table('clients')->where('default_company', session('company_id'))->get();
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name', 'clients.case_no')
                ->distinct()
                ->orderBy('expense.id', 'asc')
                ->get();

            $ClientId = array_column(json_decode($clients), 'client_id');

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('client_id', $ClientId)
                ->sum('amount');

            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $val->expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.*', 'clients.client_name', 'clients.case_no', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->expense_list = $val->expense_list->whereMonth('expense.date', $month)
                        ->whereYear('expense.date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->expense_list = $val->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                }
                $val->expense_list = $val->expense_list->where('expense.client_id', $client_id)
                    ->orderBy('expense.date', 'asc')
                    ->get();

                $val->grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d', $start_date);
                        $end_quarter = date('Y-m-d', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->grand_total = $val->grand_total->whereMonth('date', $month)
                        ->whereYear('date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->grand_total = $val->grand_total->whereBetween('date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->where('client_id', $client_id)
                    ->sum('amount');

                foreach ($val->expense_list as $row) {
                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }

            return view('pages.reports.get_clientwise_expense_report', compact('clients', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_reimbursement_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }



        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->where('expense.self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->orderBy('expense.date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->where('self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->sum('amount');
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->where('expense.self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->orderBy('expense.date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->where('self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->sum('amount');
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->where('expense.self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->orderBy('expense.date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->where('self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->sum('amount');
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $expense_list = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                    ->where('expense.status', 'approved')
                    ->where('expense.company', session('default_company_id'))
                    ->where('expense.self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->orderBy('expense.date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('expense')
                    ->where('status', 'approved')
                    ->where('company', session('default_company_id'))
                    ->where('self', 'yes')
                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                    ->sum('amount');
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $expense_list = DB::table('expense')
                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.status', 'approved')
                ->where('expense.company', session('default_company_id'))
                ->where('expense.self', 'yes')
                ->whereMonth('expense.date', $month)
                ->whereYear('expense.date', $curr_year)
                ->orderBy('expense.date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'))
                ->where('self', 'yes')
                ->whereMonth('date', $month)
                ->whereYear('date', $curr_year)
                ->sum('amount');
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $expense_list = DB::table('expense')
                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.status', 'approved')
                ->where('expense.company', session('default_company_id'))
                ->where('expense.self', 'yes')
                ->whereBetween('expense.date', [$start_year, $end_year])
                ->orderBy('expense.date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'))
                ->where('self', 'yes')
                ->whereBetween('date', [$start_year, $end_year])
                ->sum('amount');
        }

        $export_data = "Reimbursement Expense Report -\n\n";
        if ($expense_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tExpenses#\tDate\tLedger\tEntry By\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
            foreach ($expense_list as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                $approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                $row->entry_by = $staff_name;
                $row->approved_by_name = $approved_by_name;
                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                if ($row->bill != "") {
                    $bill = 'YES';
                } else {
                    $bill = 'NO';
                }

                if ($row->client_name != "") {
                    $client = $row->case_no . '(' . $row->client_name . ')';
                } else {
                    $client = ' ';
                }

                $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
        }


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Reimbursement_Expense_Report.xls\"");
    }

    public function expense_report_reimbursement_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }


            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            $expense_list = DB::table('expense')
                ->join(
                    'accounting_sub_heads',
                    'accounting_sub_heads.id',
                    'expense.ledger'
                )
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.status', 'approved')
                ->where('expense.company', session('default_company_id'))
                ->where('expense.self', 'yes');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $expense_list = $expense_list->whereMonth('expense.date', $month)
                    ->whereYear('expense.date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $expense_list = $expense_list->whereBetween('expense.date', [$start_year, $end_year]);
            }
            $expense_list = $expense_list->orderBy('expense.date', 'asc')
                ->get();

            $grand_total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'))
                ->where('self', 'yes');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('amount');

            if ($expense_list != '[]') {
                foreach ($expense_list as $row) {
                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                    $row->approved_by_name  = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            //$mpdf->AddPage('p', '', '', '', '', 5, 5, 10, 10, 10);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_reimbursement_expense_report', compact('expense_list', 'grand_total', 'FilterDate')));

            return ($mpdf->Output('Reimbursement_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_reimbursement_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            $expense_list = DB::table('expense')
                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.status', 'approved')
                ->where('expense.company', session('default_company_id'))
                ->where('expense.self', 'yes');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $expense_list = $expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $expense_list = $expense_list->whereMonth('expense.date', $month)
                    ->whereYear('expense.date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $expense_list = $expense_list->whereBetween('expense.date', [$start_year, $end_year]);
            }
            $expense_list = $expense_list->orderBy('expense.date', 'asc')
                ->get();

            $grand_total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'))
                ->where('self', 'yes');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $grand_total = $grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('amount');

            if ($expense_list != '[]') {
                foreach ($expense_list as $row) {
                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                    $row->approved_by_name  = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                    $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                }
            }

            return view('pages.reports.get_reimbursement_expense_report', compact('expense_list', 'grand_total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_staff_ledgerwise_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $staff1 = DB::table('staff')->get();
                $out1 = '';

                foreach ($staff1 as $stf) {
                    $export_data = "Staff Ledgerwise Expense Report -\n\n";
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $staff_id = $stf->sid;
                            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                            foreach ($accounting_sub_heads as $heads) {
                                $ledger = $heads->id;

                                $expense_list = DB::table('expense')
                                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                    ->where('expense.status', 'approved')
                                    ->where('expense.company', session('default_company_id'))
                                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                                    ->where('expense.by_whom', $staff_id)
                                    ->where('expense.ledger', $ledger)
                                    ->orderBy('expense.date', 'asc')
                                    ->get();

                                $grand_total = DB::table('expense')
                                    ->where('status', 'approved')
                                    ->where('company', session('default_company_id'))
                                    ->whereBetween('date', [$start_quarter, $end_quarter])
                                    ->where('by_whom', $staff_id)
                                    ->where('ledger', $ledger)
                                    ->sum('amount');

                                if ($expense_list != '[]') {
                                    $i = 1;
                                    $export_data .= "Staff - (" . $stf->name . "):\n";
                                    $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                    $export_data .= "\n";
                                    $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                    foreach ($expense_list as $row) {
                                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                        if ($row->bill != "") {
                                            $bill = 'YES';
                                        } else {
                                            $bill = 'NO';
                                        }

                                        if ($row->client_name != "") {
                                            $client = $row->case_no . '(' . $row->client_name . ')';
                                        } else {
                                            $client = ' ';
                                        }

                                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                                    }
                                    $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                    $export_data .= "\n";
                                    $export_data .= "\n";
                                }
                            }
                            $out1 .= $export_data;
                        }
                    }
                }
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $staff1 = DB::table('staff')->get();
                $out1 = '';
                foreach ($staff1 as $stf) {
                    $export_data = "Staff Ledgerwise Expense Report -\n\n";
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $staff_id = $stf->sid;
                            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                            foreach ($accounting_sub_heads as $heads) {
                                $ledger = $heads->id;

                                $expense_list = DB::table('expense')
                                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                    ->where('expense.status', 'approved')
                                    ->where('expense.company', session('default_company_id'))
                                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                                    ->where('expense.by_whom', $staff_id)
                                    ->where('expense.ledger', $ledger)
                                    ->orderBy('expense.date', 'asc')
                                    ->get();

                                $grand_total = DB::table('expense')
                                    ->where('status', 'approved')
                                    ->where('company', session('default_company_id'))
                                    ->whereBetween('date', [$start_quarter, $end_quarter])
                                    ->where('by_whom', $staff_id)
                                    ->where('ledger', $ledger)
                                    ->sum('amount');

                                if ($expense_list != '[]') {
                                    $i = 1;
                                    $export_data .= "Staff - (" . $stf->name . "):\n";
                                    $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                    $export_data .= "\n";
                                    $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                    foreach ($expense_list as $row) {
                                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                        if ($row->bill != "") {
                                            $bill = 'YES';
                                        } else {
                                            $bill = 'NO';
                                        }

                                        if ($row->client_name != "") {
                                            $client = $row->case_no . '(' . $row->client_name . ')';
                                        } else {
                                            $client = ' ';
                                        }

                                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                                    }
                                    $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                    $export_data .= "\n";
                                    $export_data .= "\n";
                                }
                            }
                            $out1 .= $export_data;
                        }
                    }
                }
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $staff1 = DB::table('staff')->get();
                $out1 = '';
                foreach ($staff1 as $stf) {
                    $export_data = "Staff Ledgerwise Expense Report -\n\n";
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $staff_id = $stf->sid;
                            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                            foreach ($accounting_sub_heads as $heads) {
                                $ledger = $heads->id;

                                $expense_list = DB::table('expense')
                                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                    ->where('expense.status', 'approved')
                                    ->where('expense.company', session('default_company_id'))
                                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                                    ->where('expense.by_whom', $staff_id)
                                    ->where('expense.ledger', $ledger)
                                    ->orderBy('expense.date', 'asc')
                                    ->get();

                                $grand_total = DB::table('expense')
                                    ->where('status', 'approved')
                                    ->where('company', session('default_company_id'))
                                    ->whereBetween('date', [$start_quarter, $end_quarter])
                                    ->where('by_whom', $staff_id)
                                    ->where('ledger', $ledger)
                                    ->sum('amount');

                                if ($expense_list != '[]') {
                                    $i = 1;
                                    $export_data .= "Staff - (" . $stf->name . "):\n";
                                    $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                    $export_data .= "\n";
                                    $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                    foreach ($expense_list as $row) {
                                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                        if ($row->bill != "") {
                                            $bill = 'YES';
                                        } else {
                                            $bill = 'NO';
                                        }

                                        if ($row->client_name != "") {
                                            $client = $row->case_no . '(' . $row->client_name . ')';
                                        } else {
                                            $client = ' ';
                                        }

                                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                                    }
                                    $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                    $export_data .= "\n";
                                    $export_data .= "\n";
                                }
                            }
                            $out1 .= $export_data;
                        }
                    }
                }
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $staff1 = DB::table('staff')->get();
                $out1 = '';
                foreach ($staff1 as $stf) {
                    $export_data = "Staff Ledgerwise Expense Report -\n\n";
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == session('company_id')) {
                            $staff_id = $stf->sid;
                            $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                            foreach ($accounting_sub_heads as $heads) {
                                $ledger = $heads->id;

                                $expense_list = DB::table('expense')
                                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                    ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                    ->where('expense.status', 'approved')
                                    ->where('expense.company', session('default_company_id'))
                                    ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                                    ->where('expense.by_whom', $staff_id)
                                    ->where('expense.ledger', $ledger)
                                    ->orderBy('expense.date', 'asc')
                                    ->get();

                                $grand_total = DB::table('expense')
                                    ->where('status', 'approved')
                                    ->where('company', session('default_company_id'))
                                    ->whereBetween('date', [$start_quarter, $end_quarter])
                                    ->where('by_whom', $staff_id)
                                    ->where('ledger', $ledger)
                                    ->sum('amount');

                                if ($expense_list != '[]') {
                                    $i = 1;
                                    $export_data .= "Staff - (" . $stf->name . "):\n";
                                    $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                    $export_data .= "\n";
                                    $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                    foreach ($expense_list as $row) {
                                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                        $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                        $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                        $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                        if ($row->bill != "") {
                                            $bill = 'YES';
                                        } else {
                                            $bill = 'NO';
                                        }

                                        if ($row->client_name != "") {
                                            $client = $row->case_no . '(' . $row->client_name . ')';
                                        } else {
                                            $client = ' ';
                                        }

                                        $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                                    }
                                    $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                    $export_data .= "\n";
                                    $export_data .= "\n";
                                }
                            }
                            $out1 .= $export_data;
                        }
                    }
                }
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $staff1 = DB::table('staff')->get();
            $out1 = '';
            foreach ($staff1 as $stf) {
                $export_data = "Staff Ledgerwise Expense Report -\n\n";
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $staff_id = $stf->sid;
                        $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                        foreach ($accounting_sub_heads as $heads) {
                            $ledger = $heads->id;

                            $expense_list = DB::table('expense')
                                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                ->where('expense.status', 'approved')
                                ->where('expense.company', session('default_company_id'))
                                ->whereMonth('expense.date', $month)
                                ->whereYear('expense.date', $curr_year)
                                ->where('expense.by_whom', $staff_id)
                                ->where('expense.ledger', $ledger)
                                ->orderBy('expense.date', 'asc')
                                ->get();

                            $grand_total = DB::table('expense')
                                ->where('status', 'approved')
                                ->where('company', session('default_company_id'))
                                ->whereMonth('expense.date', $month)
                                ->whereYear('expense.date', $curr_year)
                                ->where('by_whom', $staff_id)
                                ->where('ledger', $ledger)
                                ->sum('amount');

                            if ($expense_list != '[]') {
                                $i = 1;
                                $export_data .= "Staff - (" . $stf->name . "):\n";
                                $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                $export_data .= "\n";
                                $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                foreach ($expense_list as $row) {
                                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                    if ($row->bill != "") {
                                        $bill = 'YES';
                                    } else {
                                        $bill = 'NO';
                                    }

                                    if ($row->client_name != "") {
                                        $client = $row->case_no . '(' . $row->client_name . ')';
                                    } else {
                                        $client = ' ';
                                    }

                                    $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                                }
                                $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                $export_data .= "\n";
                                $export_data .= "\n";
                            }
                        }
                        $out1 .= $export_data;
                    }
                }
            }
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $staff1 = DB::table('staff')->get();
            $out1 = '';
            foreach ($staff1 as $stf) {
                $export_data = "Staff Ledgerwise Expense Report -\n\n";
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $staff_id = $stf->sid;
                        $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                        foreach ($accounting_sub_heads as $heads) {
                            $ledger = $heads->id;

                            $expense_list = DB::table('expense')
                                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                                ->where('expense.status', 'approved')
                                ->where('expense.company', session('default_company_id'))
                                ->whereBetween('expense.date', [$start_year, $end_year])
                                ->where('expense.by_whom', $staff_id)
                                ->where('expense.ledger', $ledger)
                                ->orderBy('expense.date', 'asc')
                                ->get();

                            $grand_total = DB::table('expense')
                                ->where('status', 'approved')
                                ->where('company', session('default_company_id'))
                                ->whereBetween('expense.date', [$start_year, $end_year])
                                ->where('by_whom', $staff_id)
                                ->where('ledger', $ledger)
                                ->sum('amount');

                            if ($expense_list != '[]') {
                                $i = 1;
                                $export_data .= "Staff - (" . $stf->name . "):\n";
                                $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                                $export_data .= "\n";
                                $export_data .= "Sr. No.\tExpenses#\tDate\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";
                                foreach ($expense_list as $row) {
                                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                    $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                    $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                    $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                    if ($row->bill != "") {
                                        $bill = 'YES';
                                    } else {
                                        $bill = 'NO';
                                    }

                                    if ($row->client_name != "") {
                                        $client = $row->case_no . '(' . $row->client_name . ')';
                                    } else {
                                        $client = ' ';
                                    }

                                    $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                                }
                                $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                                $export_data .= "\n";
                                $export_data .= "\n";
                            }
                        }
                        $out1 .= $export_data;
                    }
                }
            }
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Staff_Ledgerwise_Expense_Report.xls\"");
    }

    public function expense_report_staff_ledgerwise_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }



            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            $LedgerId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                        $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                        foreach ($accounting_sub_heads as $heads) {
                            $LedgerId[] = $heads->id;
                        }
                    }
                }
            }

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('by_whom', $StaffId)
                ->whereIn('ledger', $LedgerId)
                ->sum('amount');

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $stf->accounting_sub_heads = DB::table('accounting_sub_heads')->get();

                foreach ($stf->accounting_sub_heads as $heads) {
                    $ledger = $heads->id;
                    $heads->expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select(
                            'expense.*',
                            'accounting_sub_heads.sub_heads'
                        )
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if (
                            $quarter_filter == 'Fourth Quarter'
                        ) {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'First Quarter'
                        ) {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Second Quarter'
                        ) {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Third Quarter'
                        ) {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                        $heads->expense_list = $heads->expense_list->whereMonth('expense.date', $month)
                            ->whereYear('expense.date', $curr_year);
                    }
                    if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                        $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                    }
                    $heads->expense_list = $heads->expense_list->where('expense.by_whom', $staff_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $heads->grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if (
                            $quarter_filter == 'Fourth Quarter'
                        ) {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'First Quarter'
                        ) {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Second Quarter'
                        ) {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Third Quarter'
                        ) {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereMonth('date', $month)
                            ->whereYear('date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_year, $end_year]);
                    }
                    $heads->grand_total = $heads->grand_total->where('by_whom', $staff_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if (
                        $heads->expense_list != '[]'
                    ) {
                        foreach ($heads->expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name  = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        }
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_staff_ledgerwise_expense_report', compact('staff', 'total', 'FilterDate')));

            return ($mpdf->Output('Staff_Ledgerwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_staff_ledgerwise_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            $staff1 = DB::table('staff')->get();

            $StaffId = array();
            $LedgerId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $StaffId[] = $stf->sid;
                        $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                        foreach ($accounting_sub_heads as $heads) {
                            $LedgerId[] = $heads->id;
                        }
                    }
                }
            }

            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('by_whom', $StaffId)
                ->whereIn('ledger', $LedgerId)
                ->sum('amount');

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $stf->accounting_sub_heads = DB::table('accounting_sub_heads')->get();

                foreach ($stf->accounting_sub_heads as $heads) {
                    $ledger = $heads->id;
                    $heads->expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if ($quarter_filter == 'Fourth Quarter') {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'First Quarter') {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Second Quarter') {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Third Quarter') {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                        $heads->expense_list = $heads->expense_list->whereMonth('expense.date', $month)
                            ->whereYear('expense.date', $curr_year);
                    }
                    if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                        $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                    }
                    $heads->expense_list = $heads->expense_list->where('expense.by_whom', $staff_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $heads->grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if ($quarter_filter == 'Fourth Quarter') {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'First Quarter'
                        ) {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Second Quarter'
                        ) {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Third Quarter'
                        ) {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereMonth('date', $month)
                            ->whereYear('date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_year, $end_year]);
                    }
                    $heads->grand_total = $heads->grand_total->where('by_whom', $staff_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($heads->expense_list != '[]') {
                        foreach ($heads->expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name  = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        }
                    }
                }
            }

            return view('pages.reports.get_staff_ledgerwise_expense_report', compact('staff', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_client_ledgerwise_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_fiscal_year = strtotime('1-April-' . $year[0]);
        $end_fiscal_year = strtotime('31-March-' . $year[1]);
        $start_year = date('Y-m-d', $start_fiscal_year);
        $end_year = date('Y-m-d', $end_fiscal_year);

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();

                $out1 = '';

                foreach ($clients as $val) {
                    $client_id = $val->client_id;
                    $export_data = "";
                    $accounting_sub_heads = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                        ->where('expense.company', session('default_company_id'))
                        ->whereNotNull('expense.ledger')
                        ->distinct()
                        ->orderBy('expense.ledger', 'asc')
                        ->get();
                    foreach ($accounting_sub_heads as $heads) {
                        $ledger = $heads->ledger;

                        $expense_list = DB::table('expense')
                            ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                            ->select('expense.*', 'accounting_sub_heads.sub_heads')
                            ->where('expense.status', 'approved')
                            ->where('expense.company', session('default_company_id'))
                            ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                            ->where('expense.client_id', $client_id)
                            ->where('expense.ledger', $ledger)
                            ->orderBy('expense.date', 'asc')
                            ->get();

                        $grand_total = DB::table('expense')
                            ->where('status', 'approved')
                            ->where('company', session('default_company_id'))
                            ->whereBetween('date', [$start_quarter, $end_quarter])
                            ->where('client_id', $client_id)
                            ->where('ledger', $ledger)
                            ->sum('amount');

                        if ($expense_list != '[]') {
                            $i = 1;
                            $export_data .= "Client Ledgerwise Expense Report -\n\n";
                            $export_data .= "Client - (" . $val->client_name . "):\n";
                            $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                            $export_data .= "\n";
                            $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                            foreach ($expense_list as $row) {
                                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                                $row->entry_by = $staff_name;
                                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                if ($row->bill != "") {
                                    $bill = 'YES';
                                } else {
                                    $bill = 'NO';
                                }

                                $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                                $export_data .= implode("\t", array_values($lineData)) . "\n";
                            }
                            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                            $export_data .= "\n";
                            $export_data .= "\n";
                        }
                    }
                    $out1 .= $export_data;
                }
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();

                $out1 = '';
                foreach ($clients as $val) {
                    $client_id = $val->client_id;
                    $export_data = "";
                    $accounting_sub_heads = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                        ->where('expense.company', session('default_company_id'))
                        ->whereNotNull('expense.ledger')
                        ->distinct()
                        ->orderBy('expense.ledger', 'asc')
                        ->get();
                    foreach ($accounting_sub_heads as $heads) {
                        $ledger = $heads->ledger;

                        $expense_list = DB::table('expense')
                            ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                            ->select('expense.*', 'accounting_sub_heads.sub_heads')
                            ->where('expense.status', 'approved')
                            ->where('expense.company', session('default_company_id'))
                            ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                            ->where('expense.client_id', $client_id)
                            ->where('expense.ledger', $ledger)
                            ->orderBy('expense.date', 'asc')
                            ->get();

                        $grand_total = DB::table('expense')
                            ->where('status', 'approved')
                            ->where('company', session('default_company_id'))
                            ->whereBetween('date', [$start_quarter, $end_quarter])
                            ->where('client_id', $client_id)
                            ->where('ledger', $ledger)
                            ->sum('amount');

                        if ($expense_list != '[]') {
                            $i = 1;
                            $export_data .= "Client Ledgerwise Expense Report -\n\n";
                            $export_data .= "Client - (" . $val->client_name . "):\n";
                            $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                            $export_data .= "\n";
                            $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                            foreach ($expense_list as $row) {
                                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                                $row->entry_by = $staff_name;
                                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                if ($row->bill != "") {
                                    $bill = 'YES';
                                } else {
                                    $bill = 'NO';
                                }

                                $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                                $export_data .= implode("\t", array_values($lineData)) . "\n";
                            }
                            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                            $export_data .= "\n";
                            $export_data .= "\n";
                        }
                    }
                    $out1 .= $export_data;
                }
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);
                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();

                $out1 = '';
                foreach ($clients as $val) {
                    $client_id = $val->client_id;
                    $export_data = "";
                    $accounting_sub_heads = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                        ->where('expense.company', session('default_company_id'))
                        ->whereNotNull('expense.ledger')
                        ->distinct()
                        ->orderBy('expense.ledger', 'asc')
                        ->get();
                    foreach ($accounting_sub_heads as $heads) {
                        $ledger = $heads->ledger;

                        $expense_list = DB::table('expense')
                            ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                            ->select('expense.*', 'accounting_sub_heads.sub_heads')
                            ->where('expense.status', 'approved')
                            ->where('expense.company', session('default_company_id'))
                            ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                            ->where('expense.client_id', $client_id)
                            ->where('expense.ledger', $ledger)
                            ->orderBy('expense.date', 'asc')
                            ->get();

                        $grand_total = DB::table('expense')
                            ->where('status', 'approved')
                            ->where('company', session('default_company_id'))
                            ->whereBetween('date', [$start_quarter, $end_quarter])
                            ->where('client_id', $client_id)
                            ->where('ledger', $ledger)
                            ->sum('amount');

                        if ($expense_list != '[]') {
                            $i = 1;
                            $export_data .= "Client Ledgerwise Expense Report -\n\n";
                            $export_data .= "Client - (" . $val->client_name . "):\n";
                            $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                            $export_data .= "\n";
                            $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                            foreach ($expense_list as $row) {
                                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                                $row->entry_by = $staff_name;
                                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                if ($row->bill != "") {
                                    $bill = 'YES';
                                } else {
                                    $bill = 'NO';
                                }

                                $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                                $export_data .= implode("\t", array_values($lineData)) . "\n";
                            }
                            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                            $export_data .= "\n";
                            $export_data .= "\n";
                        }
                    }
                    $out1 .= $export_data;
                }
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d', $start_date);
                $end_quarter = date('Y-m-d', $end_date);

                $clients = DB::table('expense')
                    ->join('clients', 'clients.id', 'expense.client_id')
                    ->select('expense.client_id', 'clients.client_name')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.client_id')
                    ->distinct()
                    ->orderBy('expense.client_id', 'asc')
                    ->get();

                $out1 = '';

                foreach ($clients as $val) {
                    $client_id = $val->client_id;
                    $export_data = "";
                    $accounting_sub_heads = DB::table('accounting_sub_heads')->get();
                    $accounting_sub_heads = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                        ->where('expense.company', session('default_company_id'))
                        ->whereNotNull('expense.ledger')
                        ->distinct()
                        ->orderBy('expense.ledger', 'asc')
                        ->get();
                    foreach ($accounting_sub_heads as $heads) {
                        $ledger = $heads->ledger;

                        $expense_list = DB::table('expense')
                            ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                            ->select('expense.*', 'accounting_sub_heads.sub_heads')
                            ->where('expense.status', 'approved')
                            ->where('expense.company', session('default_company_id'))
                            ->whereBetween('expense.date', [$start_quarter, $end_quarter])
                            ->where('expense.client_id', $client_id)
                            ->where('expense.ledger', $ledger)
                            ->orderBy('expense.date', 'asc')
                            ->get();

                        $grand_total = DB::table('expense')
                            ->where('status', 'approved')
                            ->where('company', session('default_company_id'))
                            ->whereBetween('date', [$start_quarter, $end_quarter])
                            ->where('client_id', $client_id)
                            ->where('ledger', $ledger)
                            ->sum('amount');

                        if ($expense_list != '[]') {
                            $i = 1;
                            $export_data .= "Client Ledgerwise Expense Report -\n\n";
                            $export_data .= "Client - (" . $val->client_name . "):\n";
                            $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                            $export_data .= "\n";
                            $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                            foreach ($expense_list as $row) {
                                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                                $row->entry_by = $staff_name;
                                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                                if ($row->bill != "") {
                                    $bill = 'YES';
                                } else {
                                    $bill = 'NO';
                                }

                                $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                                $export_data .= implode("\t", array_values($lineData)) . "\n";
                            }
                            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                            $export_data .= "\n";
                            $export_data .= "\n";
                        }
                    }
                    $out1 .= $export_data;
                }
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name')
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy('expense.client_id', 'asc')
                ->get();
            $out1 = '';
            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $export_data = "";
                $accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($accounting_sub_heads as $heads) {
                    $ledger = $heads->ledger;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereMonth('expense.date', $month)
                        ->whereYear('expense.date', $curr_year)
                        ->where('expense.client_id', $client_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereMonth('date', $month)
                        ->whereYear('date', $curr_year)
                        ->where('client_id', $client_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client Ledgerwise Expense Report -\n\n";
                        $export_data .= "Client - (" . $val->client_name . "):\n";
                        $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->entry_by = $staff_name;
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name')
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy('expense.client_id', 'asc')
                ->get();
            $out1 = '';
            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $export_data = "";
                $accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($accounting_sub_heads as $heads) {
                    $ledger = $heads->ledger;

                    $expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'))
                        ->whereBetween('expense.date', [$start_year, $end_year])
                        ->where('expense.client_id', $client_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('date', [$start_year, $end_year])
                        ->where('client_id', $client_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($expense_list != '[]') {
                        $i = 1;
                        $export_data .= "Client Ledgerwise Expense Report -\n\n";
                        $export_data .= "Client - (" . $val->client_name . "):\n";
                        $export_data .= "Ledger - (" . $heads->sub_heads . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tExpenses#\tDate\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\n";
                        foreach ($expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->entry_by = $staff_name;
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                            if ($row->bill != "") {
                                $bill = 'YES';
                            } else {
                                $bill = 'NO';
                            }

                            $lineData = array($i++, 'EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Client_Ledgerwise_Expense_Report.xls\"");
    }

    public function expense_report_client_ledgerwise_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }



            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                $FilterDate = $quarter_filter;
            }

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
            }

            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select(
                    'expense.client_id',
                    'clients.client_name'
                )
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy(
                    'expense.client_id',
                    'asc'
                )
                ->get();

            $ClientId = array();
            $LedgerId = array();
            foreach ($clients as $val) {
                $ClientId[] = $val->client_id;
                $accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select(
                        'expense.ledger',
                        'accounting_sub_heads.sub_heads'
                    )
                    ->where(
                        'expense.company',
                        session('default_company_id')
                    )
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($accounting_sub_heads as $heads) {
                    $LedgerId[] = $heads->ledger;
                }
            }
            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('client_id', $ClientId)
                ->whereIn('ledger', $LedgerId)
                ->sum('amount');

            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $val->accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select(
                        'expense.ledger',
                        'accounting_sub_heads.sub_heads'
                    )
                    ->where(
                        'expense.company',
                        session('default_company_id')
                    )
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($val->accounting_sub_heads as $heads) {
                    $ledger = $heads->ledger;
                    $heads->expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select(
                            'expense.*',
                            'accounting_sub_heads.sub_heads'
                        )
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if (
                            $quarter_filter == 'Fourth Quarter'
                        ) {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'First Quarter'
                        ) {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Second Quarter'
                        ) {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Third Quarter'
                        ) {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->expense_list = $heads->expense_list->whereMonth('expense.date', $month)
                            ->whereYear('expense.date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                    }
                    $heads->expense_list =  $heads->expense_list->where('expense.client_id', $client_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $heads->grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if (
                            $quarter_filter == 'Fourth Quarter'
                        ) {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'First Quarter'
                        ) {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Second Quarter'
                        ) {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if (
                            $quarter_filter == 'Third Quarter'
                        ) {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereMonth('date', $month)
                            ->whereYear('date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_year, $end_year]);
                    }
                    $heads->grand_total =  $heads->grand_total->where('client_id', $client_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if (
                        $heads->expense_list != '[]'
                    ) {
                        foreach ($heads->expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        }
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_client_ledgerwise_expense_report', compact('clients', 'total', 'FilterDate')));

            return ($mpdf->Output('Client_Ledgerwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function expense_report_client_ledgerwise_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_fiscal_year = strtotime('1-April-' . $year[0]);
            $end_fiscal_year = strtotime('31-March-' . $year[1]);
            $start_year = date('Y-m-d', $start_fiscal_year);
            $end_year = date('Y-m-d', $end_fiscal_year);

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
            }

            $clients = DB::table('expense')
                ->join('clients', 'clients.id', 'expense.client_id')
                ->select('expense.client_id', 'clients.client_name')
                ->where('expense.company', session('default_company_id'))
                ->whereNotNull('expense.client_id')
                ->distinct()
                ->orderBy('expense.client_id', 'asc')
                ->get();

            $ClientId = array();
            $LedgerId = array();
            foreach ($clients as $val) {
                $ClientId[] = $val->client_id;
                $accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($accounting_sub_heads as $heads) {
                    $LedgerId[] = $heads->ledger;
                }
            }
            $total = DB::table('expense')
                ->where('status', 'approved')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d', $start_date);
                    $end_quarter = date('Y-m-d', $end_date);
                    $total = $total->whereBetween('date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('date', [$start_year, $end_year]);
            }
            $total = $total->whereIn('client_id', $ClientId)
                ->whereIn('ledger', $LedgerId)
                ->sum('amount');

            foreach ($clients as $val) {
                $client_id = $val->client_id;
                $val->accounting_sub_heads = DB::table('expense')
                    ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                    ->select('expense.ledger', 'accounting_sub_heads.sub_heads')
                    ->where('expense.company', session('default_company_id'))
                    ->whereNotNull('expense.ledger')
                    ->distinct()
                    ->orderBy('expense.ledger', 'asc')
                    ->get();
                foreach ($val->accounting_sub_heads as $heads) {
                    $ledger = $heads->ledger;
                    $heads->expense_list = DB::table('expense')
                        ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                        ->select('expense.*', 'accounting_sub_heads.sub_heads')
                        ->where('expense.status', 'approved')
                        ->where('expense.company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if ($quarter_filter == 'Fourth Quarter') {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'First Quarter') {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Second Quarter') {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Third Quarter') {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->expense_list = $heads->expense_list->whereMonth('expense.date', $month)
                            ->whereYear('expense.date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->expense_list = $heads->expense_list->whereBetween('expense.date', [$start_year, $end_year]);
                    }
                    $heads->expense_list =  $heads->expense_list->where('expense.client_id', $client_id)
                        ->where('expense.ledger', $ledger)
                        ->orderBy('expense.date', 'asc')
                        ->get();

                    $heads->grand_total = DB::table('expense')
                        ->where('status', 'approved')
                        ->where('company', session('default_company_id'));
                    if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                        if ($quarter_filter == 'Fourth Quarter') {
                            $start_date = strtotime('1-January-' . $year[1]);
                            $end_date = strtotime('31-March-' . $year[1]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'First Quarter') {
                            $start_date = strtotime('1-April-' . $year[0]);
                            $end_date = strtotime('30-June-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Second Quarter') {
                            $start_date = strtotime('1-July-' . $year[0]);
                            $end_date = strtotime('30-September-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }

                        if ($quarter_filter == 'Third Quarter') {
                            $start_date = strtotime('1-October-' . $year[0]);
                            $end_date = strtotime('31-December-' . $year[0]);
                            $start_quarter = date('Y-m-d', $start_date);
                            $end_quarter = date('Y-m-d', $end_date);
                            $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_quarter, $end_quarter]);
                        }
                    }
                    if (
                        $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereMonth('date', $month)
                            ->whereYear('date', $curr_year);
                    }
                    if (
                        $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                    ) {
                        $heads->grand_total = $heads->grand_total->whereBetween('date', [$start_year, $end_year]);
                    }
                    $heads->grand_total =  $heads->grand_total->where('client_id', $client_id)
                        ->where('ledger', $ledger)
                        ->sum('amount');

                    if ($heads->expense_list != '[]') {
                        foreach ($heads->expense_list as $row) {
                            $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                            $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                            $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                            $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                            $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                        }
                    }
                }
            }

            return view('pages.reports.get_client_ledgerwise_expense_report', compact('clients', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function daily_expense_report_excel(Request $request)
    {

        $expense_list = DB::table('expense')
            ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
            ->select('expense.*', 'accounting_sub_heads.sub_heads')
            ->where('expense.company', session('default_company_id'))
            ->whereDay('expense.created_at', '=', date('d'))
            ->whereMonth('expense.created_at', '=', date('m'))
            ->whereYear('expense.created_at', '=', date('Y'))
            ->orderBy('expense.date', 'asc')
            ->get();

        $export_data = "Daily Expense Report -\n\n";
        if ($expense_list != '[]') {
            $export_data .= "Expenses#\tDate\tLedger\tEntry By\tReimbursement\tAmount\tIs Bill Attached\tMode of payment\tReference No\tApproval Date\tApproval By\tClient\n";

            foreach ($expense_list as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                $approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                $staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                $row->entry_by = $staff_name;
                $row->approved_by_name = $approved_by_name;
                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');

                if ($row->bill != "") {
                    $bill = 'YES';
                } else {
                    $bill = 'NO';
                }

                if ($row->client_name != "") {
                    $client = $row->case_no . '(' . $row->client_name . ')';
                } else {
                    $client = ' ';
                }

                $lineData = array('EXP' . $row->id,  date('d-M-Y', strtotime($row->date)), $row->sub_heads, $row->entry_by, $row->self, AppHelper::moneyFormatIndia($row->amount), $bill, $row->mode_of_payment, $row->ref_no, date('d-M-Y', strtotime($row->approve_date)), $row->approved_by_name, $client);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
        }


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Daily_Expense_Report.xls\"");
    }

    public function daily_expense_report_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');


            $expense_list = DB::table('expense')
                ->join(
                    'accounting_sub_heads',
                    'accounting_sub_heads.id',
                    'expense.ledger'
                )
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.company', session('default_company_id'))
                ->whereDay('expense.created_at', '=', date('d'))
                ->whereMonth('expense.created_at', '=', date('m'))
                ->whereYear('expense.created_at', '=', date('Y'))
                ->orderBy('expense.date', 'asc')
                ->get();

            foreach ($expense_list as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            //$mpdf->AddPage('p', '', '', '', '', 5, 5, 2, 10, 10, 10);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_daily_expense_report', compact('expense_list')));

            return ($mpdf->Output('Daily_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function daily_expense_report_print(Request $request)
    {
        try {

            $expense_list = DB::table('expense')
                ->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
                ->select('expense.*', 'accounting_sub_heads.sub_heads')
                ->where('expense.company', session('default_company_id'))
                ->whereDay('expense.created_at', '=', date('d'))
                ->whereMonth('expense.created_at', '=', date('m'))
                ->whereYear('expense.created_at', '=', date('Y'))
                ->orderBy('expense.date', 'asc')
                ->get();

            foreach ($expense_list as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                $row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
                $row->approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
                $row->entry_by = DB::table('staff')->where('sid', $row->by_whom)->value('name');
                $row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
            }

            return view('pages.reports.get_daily_expense_report', compact('expense_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
