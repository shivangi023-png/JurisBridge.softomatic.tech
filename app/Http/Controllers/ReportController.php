<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
  use ExpenseTraits;
  use StaffTraits;
  public function report()
  {
    if (session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 5 || session('role_id') == 8 || session('role_id') == 10) {
      return view('pages.reports.report');
    } else {
      return redirect()->back()->with('alert-danger', 'You are not authorize to view this menu');
    }
  }

  public function get_report(Request $request)
  {
    $id = $request->id;
    if ($id == 'quotation_report') {
      return view('pages.reports.quotation_report');
    }
    if ($id == 'expense_report') {
      return view('pages.reports.expense_report');
    }

    if ($id == 'client_report') {
      return view('pages.reports.client_report');
    }

    if ($id == 'attendance_report') {
      return view('pages.reports.attendance_report');
    }

    if ($id == 'accounting_report') {
      return view('pages.reports.accounting_report');
    }

    if ($id == 'pretty_cash_report') {
      return view('pages.reports.pretty_cash_report');
    }

    if ($id == 'admin_report') {
      return view('pages.reports.admin_report');
    }

    if ($id == 'sales_report') {
      return view('pages.reports.sales_report');
    }
  }
}
