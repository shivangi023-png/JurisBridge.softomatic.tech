<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppHelper;

class AccountingReportController extends Controller
{
    use ExpenseTraits;
    use StaffTraits;

    public function invoice_against_quotation_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }

        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none' && $daily_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', '!=', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', '!=', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', '!=', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', '!=', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', '!=', 'null')
                ->whereYear('bill.bill_date', $curr_year)
                ->whereMonth('bill.bill_date', $month)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereYear('bill_date', $curr_year)->whereMonth('bill_date', $month)->sum('total_amount');
        }

        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', '!=', 'null')
                ->where('bill.bill_date', $daily_filter)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->where('bill_date', $daily_filter)->sum('total_amount');
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', '!=', 'null')
                ->whereBetween('bill.bill_date', [$start_year, $end_year])
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_year, $end_year])->sum('total_amount');
        }

        $export_data = "Invoices Against Quotation Report -\n\n";
        if ($invoice_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tClient\tDiscount\tTotal Amount\n";
            foreach ($invoice_list as $inv) {
                $client = $inv->case_no . '(' . $inv->client_name . ')';
                if ($inv->bill_date != '') {
                    $inv->bill_date = date('d-M-Y', strtotime($inv->bill_date));
                } else {
                    $inv->bill_date = '';
                }

                $inv->invoice_no = session('short_code') . '-' . str_pad($inv->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($inv->bill_date));

                $lineData = array($i++, $inv->invoice_no, $inv->bill_date, $client, $inv->discount, AppHelper::moneyFormatIndia($inv->total_amount));
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total);
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Invoices_Against_Quotation_Report.xls\"");
    }

    public function invoice_against_quotation_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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

            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', '!=', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')
                ->where('active', 'yes')
                ->where('company', session('company_id'))
                ->where('quotation', '!=', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $curr_year);
            }

            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $grand_total = $grand_total->where('bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('total_amount');

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_invoice_against_quotation_report', compact('invoice_list', 'grand_total', 'FilterDate')));

            return ($mpdf->Output('Invoices_Against_Quotation_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function invoice_against_quotation_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none' && $daily_filter == 'none') {
                $FilterDate = $quarter_filter;
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
                $FilterDate = $month_filter;
            }

            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', '!=', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')
                ->where('active', 'yes')
                ->where('company', session('company_id'))
                ->where('quotation', '!=', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $grand_total = $grand_total->where('bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('total_amount');

            return view('pages.reports.get_invoice_against_quotation_report', compact('invoice_list', 'grand_total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function additional_invoices_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));


        if ($year_filter != 'none') {
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
        }



        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year);
                $end_date = strtotime('31-December-' . $year);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.quotation', 'null')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', 'null')
                ->whereYear('bill.bill_date', $curr_year)
                ->whereMonth('bill.bill_date', $month)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereYear('bill_date', $curr_year)->whereMonth('bill_date', $month)->sum('total_amount');
        }

        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', 'null')
                ->where('bill.bill_date', $daily_filter)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->where('bill_date', $daily_filter)->sum('total_amount');
        }
        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', 'null')
                ->whereBetween('bill.bill_date', [$start_year, $end_year])
                ->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', 'null')->whereBetween('bill_date', [$start_year, $end_year])->sum('total_amount');
        }

        $export_data = "Additional Invoices Report -\n\n";
        if ($invoice_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tClient\tDiscount\tTotal Amount\n";
            foreach ($invoice_list as $inv) {
                $client = $inv->case_no . '(' . $inv->client_name . ')';
                if ($inv->bill_date != '') {
                    $inv->bill_date = date('d-M-Y', strtotime($inv->bill_date));
                } else {
                    $inv->bill_date = '';
                }

                $inv->invoice_no = session('short_code') . '-' . str_pad($inv->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($inv->bill_date));

                $lineData = array($i++, $inv->invoice_no, $inv->bill_date, $client, $inv->discount, AppHelper::moneyFormatIndia($inv->total_amount));
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total);
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Additional_Invoices_Report.xls\"");
    }

    public function additional_invoices_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')
                ->where('active', 'yes')
                ->where('company', session('company_id'))
                ->where('quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $grand_total = $grand_total->where('bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('total_amount');

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_additional_invoices_report', compact('invoice_list', 'grand_total', 'FilterDate')));

            return ($mpdf->Output('Additional_Invoices_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function additional_invoices_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                ->where('bill.active', 'yes')
                ->where('bill.company', session('company_id'))
                ->where('bill.quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')
                ->where('active', 'yes')
                ->where('company', session('company_id'))
                ->where('quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereMonth('bill_date', $month)
                    ->whereYear('bill_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $grand_total = $grand_total->where('bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
            }
            $grand_total = $grand_total->sum('total_amount');

            return view('pages.reports.get_additional_invoices_report', compact('invoice_list', 'grand_total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function cancelled_invoice_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }

        $export_data = "Cancelled Invoice Report -\n\n";


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.active', 'no')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.active', 'no')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.active', 'no')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                    ->where('bill.company', session('company_id'))
                    ->where('bill.active', 'no')
                    ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                ->where('bill.company', session('company_id'))
                ->where('bill.active', 'no')
                ->whereMonth('bill.bill_date', $month)
                ->whereYear('bill.bill_date', $curr_year)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();
        }
        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                ->where('bill.company', session('company_id'))
                ->where('bill.active', 'no')
                ->where('bill.bill_date', $daily_filter)
                ->orderBy('bill.invoice_no', 'asc')
                ->get();
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                ->where('bill.company', session('company_id'))
                ->where('bill.active', 'no')
                ->whereBetween('bill.bill_date', [$start_year, $end_year])
                ->orderBy('bill.invoice_no', 'asc')
                ->get();
        }

        if ($invoice_list != '[]') {
            $a = 1;
            $export_data .= "Sr. No.\tInvoice No.\tClient Name\tService\tAmount\tInvoice Date\tDue Date\n";
            $grand_total = 0;
            foreach ($invoice_list as $row) {
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                $row->payable = $row->total_amount - $paid_amt;
                $grand_total += $row->payable;
                $service = '';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt . '/- <br>';
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser . ' : ' . $amt . '/-';
                    }
                }

                $row->service = $service;
                $client = $row->case_no . '(' . $row->client_name . ')';
                $invoice_date = date('d-M-Y', strtotime($row->bill_date));
                $due_date = date('d-M-Y', strtotime($row->due_date));
                $amount = AppHelper::moneyFormatIndia($row->payable);

                $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));

                $lineData = array($a++, $row->invoice_no, $client, $row->service, $amount, $invoice_date, $due_date);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total);
        }


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Cancelled_Invoice_Report.xls\"");
    }

    public function cancelled_invoice_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));


            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                ->where('bill.company', session('company_id'))
                ->where('bill.active', 'no');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();
            $grand_total = 0;
            if ($invoice_list != '[]') {
                foreach ($invoice_list as $row) {
                    $services_arr = json_decode($row->service);
                    $amount_arr = json_decode($row->amount);
                    $quotation_array = json_decode($row->quotation);
                    $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                    $row->payable = $row->total_amount - $paid_amt;
                    $grand_total += $row->payable;
                    $service = '';
                    if ($services_arr != '') {
                        for ($i = 0; $i < sizeof($services_arr); $i++) {

                            $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                            $amt = $amount_arr[$i];
                            $service .= $ser . ' : ' . $amt . '/- <br>';
                        }
                    } else {
                        for ($i = 0; $i < sizeof($quotation_array); $i++) {
                            $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                            $ser = DB::table('services')->where('id', $service_id)->value('name');
                            $amt = $amount_arr[$i];
                            $service .= $ser . ' : ' . $amt . '/- <br>';
                        }
                    }

                    $row->service = $service;
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            //$mpdf->AddPage('p', '', '', '', '', 5, 5, 10, 10, 10);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_cancelled_invoices_report', compact('invoice_list', 'grand_total', 'FilterDate')));

            return ($mpdf->Output('Cancelled_Invoice_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function cancelled_invoice_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));


            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                ->where('bill.company', session('company_id'))
                ->where('bill.active', 'no');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                    ->whereYear('bill.bill_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $invoice_list = $invoice_list->where('bill.bill_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();
            $grand_total = 0;
            if ($invoice_list != '[]') {
                foreach ($invoice_list as $row) {
                    $services_arr = json_decode($row->service);
                    $amount_arr = json_decode($row->amount);
                    $quotation_array = json_decode($row->quotation);
                    $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                    $row->payable = $row->total_amount - $paid_amt;
                    $grand_total += $row->payable;
                    $service = '';
                    if ($services_arr != '') {
                        for ($i = 0; $i < sizeof($services_arr); $i++) {

                            $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                            $amt = $amount_arr[$i];
                            $service .= $ser . ' : ' . $amt . '/- <br>';
                        }
                    } else {
                        for ($i = 0; $i < sizeof($quotation_array); $i++) {
                            $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                            $ser = DB::table('services')->where('id', $service_id)->value('name');
                            $amt = $amount_arr[$i];
                            $service .= $ser . ' : ' . $amt . '/- <br>';
                        }
                    }

                    $row->service = $service;
                }
            }

            return view('pages.reports.get_cancelled_invoices_report', compact('invoice_list', 'grand_total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function consultation_fee_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('appointment.company', session('default_company_id'))
                    ->where('appointment.status', 'finalize')
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array();
                foreach ($clients as $val) {
                    $client_id[] = $val->client;
                }
                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->whereIn('appointment.client',  $client_id)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                    ->sum('consulting_fee.fees');

                $out1 = '';
                $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
                foreach ($clients as $val) {
                    $Consultation_fee_list = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->join('staff', 'staff.sid', 'appointment.meeting_with')
                        ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->orderBy('appointment.meeting_date', 'asc')
                        ->get();

                    $grand_total = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->sum('consulting_fee.fees');

                    if ($Consultation_fee_list != '[]') {
                        $i = 1;
                        $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                        foreach ($Consultation_fee_list as $row) {
                            $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                            $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                            $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('appointment.company', session('default_company_id'))
                    ->where('appointment.status', 'finalize')
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array();
                foreach ($clients as $val) {
                    $client_id[] = $val->client;
                }
                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->whereIn('appointment.client',  $client_id)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                    ->sum('consulting_fee.fees');

                $out1 = '';

                $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
                foreach ($clients as $val) {
                    $Consultation_fee_list = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->join('staff', 'staff.sid', 'appointment.meeting_with')
                        ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->orderBy('appointment.meeting_date', 'asc')
                        ->get();

                    $grand_total = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->sum('consulting_fee.fees');

                    if ($Consultation_fee_list != '[]') {
                        $i = 1;
                        $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                        foreach ($Consultation_fee_list as $row) {
                            $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                            $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                            $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('appointment.company', session('default_company_id'))
                    ->where('appointment.status', 'finalize')
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array();
                foreach ($clients as $val) {
                    $client_id[] = $val->client;
                }
                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->whereIn('appointment.client',  $client_id)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                    ->sum('consulting_fee.fees');

                $out1 = '';

                $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
                foreach ($clients as $val) {
                    $Consultation_fee_list = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->join('staff', 'staff.sid', 'appointment.meeting_with')
                        ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->orderBy('appointment.meeting_date', 'asc')
                        ->get();

                    $grand_total = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->sum('consulting_fee.fees');

                    if ($Consultation_fee_list != '[]') {
                        $i = 1;
                        $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                        foreach ($Consultation_fee_list as $row) {
                            $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                            $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                            $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('appointment.company', session('default_company_id'))
                    ->where('appointment.status', 'finalize')
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array();
                foreach ($clients as $val) {
                    $client_id[] = $val->client;
                }
                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->whereIn('appointment.client',  $client_id)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                    ->sum('consulting_fee.fees');

                $out1 = '';

                $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
                foreach ($clients as $val) {
                    $Consultation_fee_list = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->join('staff', 'staff.sid', 'appointment.meeting_with')
                        ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->orderBy('appointment.meeting_date', 'asc')
                        ->get();

                    $grand_total = DB::table('consulting_fee')
                        ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                        ->join('clients', 'clients.id', 'appointment.client')
                        ->where('appointment.client',  $val->client)
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter])
                        ->sum('consulting_fee.fees');

                    if ($Consultation_fee_list != '[]') {
                        $i = 1;
                        $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                        foreach ($Consultation_fee_list as $row) {
                            $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                            $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                            $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('appointment.company', session('default_company_id'))
                ->where('appointment.status', 'finalize')
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array();
            foreach ($clients as $val) {
                $client_id[] = $val->client;
            }
            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->whereIn('appointment.client',  $client_id)
                ->where('clients.default_company', session('company_id'))
                ->whereMonth('appointment.meeting_date', $month)
                ->whereYear('appointment.meeting_date', $curr_year)
                ->sum('consulting_fee.fees');

            $out1 = '';
            $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
            foreach ($clients as $val) {
                $Consultation_fee_list = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->join('staff', 'staff.sid', 'appointment.meeting_with')
                    ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->whereMonth('appointment.meeting_date', $month)
                    ->whereYear('appointment.meeting_date', $curr_year)
                    ->orderBy('appointment.meeting_date', 'asc')
                    ->get();

                $grand_total = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->whereMonth('appointment.meeting_date', $month)
                    ->whereYear('appointment.meeting_date', $curr_year)
                    ->sum('consulting_fee.fees');

                if ($Consultation_fee_list != '[]') {
                    $i = 1;
                    $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                    foreach ($Consultation_fee_list as $row) {
                        $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                        $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('appointment.company', session('default_company_id'))
                ->where('appointment.status', 'finalize')
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array();
            foreach ($clients as $val) {
                $client_id[] = $val->client;
            }
            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->whereIn('appointment.client',  $client_id)
                ->where('clients.default_company', session('company_id'))
                ->where('appointment.meeting_date', $daily_filter)
                ->sum('consulting_fee.fees');

            $out1 = '';
            $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
            foreach ($clients as $val) {
                $Consultation_fee_list = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->join('staff', 'staff.sid', 'appointment.meeting_with')
                    ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->where('appointment.meeting_date', $daily_filter)
                    ->orderBy('appointment.meeting_date', 'asc')
                    ->get();

                $grand_total = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->where('appointment.meeting_date', $daily_filter)
                    ->sum('consulting_fee.fees');

                if ($Consultation_fee_list != '[]') {
                    $i = 1;
                    $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                    foreach ($Consultation_fee_list as $row) {
                        $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                        $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('appointment.company', session('default_company_id'))
                ->where('appointment.status', 'finalize')
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array();
            foreach ($clients as $val) {
                $client_id[] = $val->client;
            }
            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->whereIn('appointment.client',  $client_id)
                ->where('clients.default_company', session('company_id'))
                ->whereBetween('appointment.meeting_date', [$start_year, $end_year])
                ->sum('consulting_fee.fees');

            $out1 = '';
            $export_data = "Consultation Fee Report -\n
            Total Consultation Fees\t" . AppHelper::moneyFormatIndia($total_fees) . "\n";
            foreach ($clients as $val) {
                $Consultation_fee_list = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->join('staff', 'staff.sid', 'appointment.meeting_with')
                    ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_year, $end_year])
                    ->orderBy('appointment.meeting_date', 'asc')
                    ->get();

                $grand_total = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('appointment.meeting_date', [$start_year, $end_year])
                    ->sum('consulting_fee.fees');

                if ($Consultation_fee_list != '[]') {
                    $i = 1;
                    $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                    foreach ($Consultation_fee_list as $row) {
                        $place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');

                        $lineData = array($i++, $receipt_no, $place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Consultation_Fee_Report.xls\"");
    }

    public function consultation_fee_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('appointment.company', session('default_company_id'))
                ->where('appointment.status', 'finalize')
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array_column(json_decode($clients), 'client');

            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->whereIn('appointment.client',  $client_id)
                ->where('clients.default_company', session('company_id'));
            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total_fees = $total_fees->whereMonth('appointment.meeting_date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $total_fees = $total_fees->where('appointment.meeting_date', $daily_filter);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
            }
            $total_fees = $total_fees->sum('consulting_fee.fees');

            foreach ($clients as $val) {
                $val->consultation_fee_list = DB::table('consulting_fee')
                    ->join(
                        'appointment',
                        'appointment.id',
                        'consulting_fee.appointment_id'
                    )
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->join('staff', 'staff.sid', 'appointment.meeting_with')
                    ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if (
                        $quarter_filter == 'Fourth Quarter'
                    ) {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->whereMonth('appointment.meeting_date', $month)
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->where('appointment.meeting_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $val->consultation_fee_list = $val->consultation_fee_list->orderBy('appointment.meeting_date', 'asc')
                    ->get();

                $val->grand_total = DB::table('consulting_fee')
                    ->join(
                        'appointment',
                        'appointment.id',
                        'consulting_fee.appointment_id'
                    )
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if (
                        $quarter_filter == 'Fourth Quarter'
                    ) {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereMonth('appointment.meeting_date', $month)
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
                ) {
                    $val->grand_total = $val->grand_total->where('appointment.meeting_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->sum('consulting_fee.fees');

                if ($val->consultation_fee_list != '[]') {

                    foreach ($val->consultation_fee_list as $row) {
                        $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $row->receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
                    }
                }
            }


            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_consultation_fees_report', compact('clients', 'total_fees', 'FilterDate')));

            return ($mpdf->Output('Consultation_Fee_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function consultation_fee_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('appointment.company', session('default_company_id'))
                ->where('appointment.status', 'finalize')
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array_column(json_decode($clients), 'client');

            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->whereIn('appointment.client',  $client_id)
                ->where('clients.default_company', session('company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total_fees = $total_fees->whereMonth('appointment.meeting_date', $month)
                    ->whereYear('date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $total_fees = $total_fees->where('appointment.meeting_date', $daily_filter);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
            }
            $total_fees = $total_fees->sum('consulting_fee.fees');

            foreach ($clients as $val) {
                $val->consultation_fee_list = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->join('staff', 'staff.sid', 'appointment.meeting_with')
                    ->select('consulting_fee.*', 'consulting_fee.id as consulting_fee_id', 'appointment.place', 'appointment.meeting_date', 'appointment.meeting_time', 'appointment.meeting_with', 'clients.client_name', 'staff.name as meetname')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->whereMonth('appointment.meeting_date', $month)
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->where('appointment.meeting_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->consultation_fee_list = $val->consultation_fee_list->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $val->consultation_fee_list = $val->consultation_fee_list->orderBy('appointment.meeting_date', 'asc')
                    ->get();

                $val->grand_total = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('appointment.client',  $val->client)
                    ->where('clients.default_company', session('company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereMonth('appointment.meeting_date', $month)
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
                ) {
                    $val->grand_total = $val->grand_total->where('appointment.meeting_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->sum('consulting_fee.fees');

                if ($val->consultation_fee_list != '[]') {

                    foreach ($val->consultation_fee_list as $row) {
                        $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $row->receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
                    }
                }
            }


            return view('pages.reports.get_consultation_fees_report', compact('clients', 'total_fees', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function billwise_payment_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $payment = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                    ->orderBy('payment_date', 'asc')
                    ->get();


                $bill_payment = DB::table('bill_payment_mapping')
                    ->select('bill_id')
                    ->where('active', 'yes')
                    ->distinct()
                    ->orderBy('bill_id', 'asc')
                    ->get();

                $payment_id = array();
                foreach ($payment as $row) {
                    $payment_id[] = $row->id;
                }

                $out1 = '';
                $all_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.active', 'yes')
                    ->orderBy('payment.payment_date', 'asc')
                    ->sum('paid_amount');
                $export_data = "Invoice/Payment Report -\n\n";
                $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
                foreach ($bill_payment as $row1) {

                    $payment_list = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->select('bill_payment_mapping.payment_id', 'payment.*')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->where('bill_payment_mapping.active', 'yes')
                        ->orderBy('payment.payment_date', 'asc')
                        ->get();

                    $grand_total = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.active', 'yes')
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->sum('payment.payment');

                    if ($payment_list != '[]') {
                        $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                        $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                        $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                        $i = 1;
                        $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                        foreach ($payment_list as $val) {
                            $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                            $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                            $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                            $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                            if ($val->client_name != "") {
                                $client = $val->case_no . '(' . $val->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($val->approve_date != "") {
                                $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                            } else {
                                $val->approve_date = ' ';
                            }

                            $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $payment = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                    ->orderBy('payment_date', 'asc')
                    ->get();


                $bill_payment = DB::table('bill_payment_mapping')
                    ->select('bill_id')
                    ->where('active', 'yes')
                    ->distinct()
                    ->orderBy('bill_id', 'asc')
                    ->get();

                $payment_id = array();
                foreach ($payment as $row) {
                    $payment_id[] = $row->id;
                }

                $out1 = '';
                $all_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.active', 'yes')
                    ->orderBy('payment.payment_date', 'asc')
                    ->sum('paid_amount');
                $export_data = "Invoice/Payment Report -\n\n";
                $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";

                foreach ($bill_payment as $row1) {
                    $payment_list = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->select('bill_payment_mapping.payment_id', 'payment.*')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->orderBy('payment.payment_date', 'asc')
                        ->get();

                    $grand_total = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->sum('payment.payment');

                    if ($payment_list != '[]') {
                        $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                        $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                        $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                        $i = 1;
                        $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                        foreach ($payment_list as $val) {
                            $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                            $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                            $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                            $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                            if ($val->client_name != "") {
                                $client = $val->case_no . '(' . $val->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($val->approve_date != "") {
                                $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                            } else {
                                $val->approve_date = ' ';
                            }

                            $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $payment = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                    ->orderBy('payment_date', 'asc')
                    ->get();


                $bill_payment = DB::table('bill_payment_mapping')
                    ->select('bill_id')
                    ->where('active', 'yes')
                    ->distinct()
                    ->orderBy('bill_id', 'asc')
                    ->get();

                $payment_id = array();
                foreach ($payment as $row) {
                    $payment_id[] = $row->id;
                }

                $out1 = '';
                $all_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.active', 'yes')
                    ->orderBy('payment.payment_date', 'asc')
                    ->sum('paid_amount');
                $export_data = "Invoice/Payment Report -\n\n";
                $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
                foreach ($bill_payment as $row1) {
                    $payment_list = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->select('bill_payment_mapping.payment_id', 'payment.*')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->orderBy('payment.payment_date', 'asc')
                        ->get();

                    $grand_total = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->sum('payment.payment');

                    if ($payment_list != '[]') {
                        $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                        $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                        $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                        $i = 1;
                        $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                        foreach ($payment_list as $val) {
                            $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                            $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                            $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                            $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                            if ($val->client_name != "") {
                                $client = $val->case_no . '(' . $val->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($val->approve_date != "") {
                                $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                            } else {
                                $val->approve_date = ' ';
                            }

                            $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $payment = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                    ->orderBy('payment_date', 'asc')
                    ->get();


                $bill_payment = DB::table('bill_payment_mapping')
                    ->select('bill_id')
                    ->where('active', 'yes')
                    ->distinct()
                    ->orderBy('bill_id', 'asc')
                    ->get();

                $payment_id = array();
                foreach ($payment as $row) {
                    $payment_id[] = $row->id;
                }

                $out1 = '';
                $all_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.active', 'yes')
                    ->orderBy('payment.payment_date', 'asc')
                    ->sum('paid_amount');
                $export_data = "Invoice/Payment Report -\n\n";
                $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
                foreach ($bill_payment as $row1) {
                    $payment_list = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->select('bill_payment_mapping.payment_id', 'payment.*')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->orderBy('payment.payment_date', 'asc')
                        ->get();

                    $grand_total = DB::table('bill_payment_mapping')
                        ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                        ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                        ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                        ->sum('payment.payment');

                    if ($payment_list != '[]') {
                        $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                        $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                        $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                        $i = 1;
                        $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                        foreach ($payment_list as $val) {
                            $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                            $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                            $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                            $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                            if ($val->client_name != "") {
                                $client = $val->case_no . '(' . $val->client_name . ')';
                            } else {
                                $client = ' ';
                            }

                            if ($val->approve_date != "") {
                                $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                            } else {
                                $val->approve_date = ' ';
                            }

                            $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $payment = DB::table('payment')
                ->select('id')
                ->where('active', 'yes')
                ->where('company', session('default_company_id'))
                ->whereMonth('payment_date', $month)
                ->whereYear('payment_date', $curr_year)
                ->orderBy('payment_date', 'asc')
                ->get();


            $bill_payment = DB::table('bill_payment_mapping')
                ->select('bill_id')
                ->where('active', 'yes')
                ->distinct()
                ->orderBy('bill_id', 'asc')
                ->get();

            $payment_id = array();
            foreach ($payment as $row) {
                $payment_id[] = $row->id;
            }

            $out1 = '';
            $all_total = DB::table('bill_payment_mapping')
                ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                ->select('bill_payment_mapping.payment_id', 'payment.*')
                ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                ->where('bill_payment_mapping.active', 'yes')
                ->orderBy('payment.payment_date', 'asc')
                ->sum('paid_amount');
            $export_data = "Invoice/Payment Report -\n\n";
            $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
            foreach ($bill_payment as $row1) {
                $payment_list = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->orderBy('payment.payment_date', 'asc')
                    ->get();

                $grand_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->sum('payment.payment');

                if ($payment_list != '[]') {
                    $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                    $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                    $row1->bill_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $i = 1;
                    $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                    foreach ($payment_list as $val) {
                        $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                        $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                        $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                        $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                        if ($val->client_name != "") {
                            $client = $val->case_no . '(' . $val->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        if ($val->approve_date != "") {
                            $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                        } else {
                            $val->approve_date = ' ';
                        }

                        $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $payment = DB::table('payment')
                ->select('id')
                ->where('active', 'yes')
                ->where('company', session('default_company_id'))
                ->where('payment_date', $daily_filter)
                ->orderBy('payment_date', 'asc')
                ->get();


            $bill_payment = DB::table('bill_payment_mapping')
                ->select('bill_id')
                ->where('active', 'yes')
                ->distinct()
                ->orderBy('bill_id', 'asc')
                ->get();

            $payment_id = array();
            foreach ($payment as $row) {
                $payment_id[] = $row->id;
            }

            $out1 = '';
            $all_total = DB::table('bill_payment_mapping')
                ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                ->select('bill_payment_mapping.payment_id', 'payment.*')
                ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                ->where('bill_payment_mapping.active', 'yes')
                ->orderBy('payment.payment_date', 'asc')
                ->sum('paid_amount');
            $export_data = "Invoice/Payment Report -\n\n";
            $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
            foreach ($bill_payment as $row1) {
                $payment_list = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->orderBy('payment.payment_date', 'asc')
                    ->get();

                $grand_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->sum('payment.payment');

                if ($payment_list != '[]') {
                    $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                    $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                    $row1->bill_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $i = 1;
                    $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                    foreach ($payment_list as $val) {
                        $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                        $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                        $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                        $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                        if ($val->client_name != "") {
                            $client = $val->case_no . '(' . $val->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        if ($val->approve_date != "") {
                            $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                        } else {
                            $val->approve_date = ' ';
                        }

                        $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $payment = DB::table('payment')
                ->select('id')
                ->where('active', 'yes')
                ->where('company', session('default_company_id'))
                ->whereBetween('payment_date', [$start_year, $end_year])
                ->orderBy('payment_date', 'asc')
                ->get();


            $bill_payment = DB::table('bill_payment_mapping')
                ->select('bill_id')
                ->where('active', 'yes')
                ->distinct()
                ->orderBy('bill_id', 'asc')
                ->get();

            $payment_id = array();
            foreach ($payment as $row) {
                $payment_id[] = $row->id;
            }

            $out1 = '';
            $all_total = DB::table('bill_payment_mapping')
                ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                ->select('bill_payment_mapping.payment_id', 'payment.*')
                ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                ->where('bill_payment_mapping.active', 'yes')
                ->orderBy('payment.payment_date', 'asc')
                ->sum('paid_amount');
            $export_data = "Invoice/Payment Report -\n\n";
            $export_data .= "Grand Total=" . AppHelper::moneyFormatIndia($all_total) . " -\n\n";
            foreach ($bill_payment as $row1) {
                $payment_list = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->orderBy('payment.payment_date', 'asc')
                    ->get();

                $grand_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->sum('payment.payment');

                if ($payment_list != '[]') {
                    $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                    $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                    $i = 1;
                    $export_data .= "Invoice No - " . session('short_code') . '-' . str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row1->invoice_date)) . "\tInvoice Date - " . date('d-M-Y', strtotime($row1->invoice_date)) . "\tAmount - " . AppHelper::moneyFormatIndia($row1->invoice_amount) . "\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tMode of Payment\tCheque No\tReference No\tBank Name\tTDS\tPayment\tPayment Date\tApproved By\tApproved Date\n";
                    foreach ($payment_list as $val) {
                        $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                        $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                        $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                        $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');

                        if ($val->client_name != "") {
                            $client = $val->case_no . '(' . $val->client_name . ')';
                        } else {
                            $client = ' ';
                        }

                        if ($val->approve_date != "") {
                            $val->approve_date = date('d-M-Y', strtotime($val->approve_date));
                        } else {
                            $val->approve_date = ' ';
                        }

                        $lineData = array($i++, $client, $val->mode_of_payment, $val->cheque_no, $val->reference_no, $val->deposite_bank_name, $val->tds, AppHelper::moneyFormatIndia($val->payment), date('d-M-Y', strtotime($val->payment_date)), $val->approved_by_name, $val->approve_date);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Billwise_Payment_Report.xls\"");
    }

    public function billwise_payment_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));
            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $payment = DB::table('payment')
                ->select('id')
                ->where('active', 'yes')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $payment = $payment->whereMonth('payment_date', $month)
                    ->whereYear('payment_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none'
            ) {
                $payment = $payment->where('payment_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $payment = $payment->whereBetween('payment_date', [$start_year, $end_year]);
            }
            $payment = $payment->orderBy('payment_date', 'asc')
                ->get();

            $bill_payment = DB::table('bill_payment_mapping')
                ->select('bill_id')
                ->where('active', 'yes')
                ->distinct()
                ->orderBy('bill_id', 'asc')
                ->get();

            $payment_id = array();
            foreach ($payment as $row) {
                $payment_id[] = $row->id;
            }
            $all_total = 0;
            foreach ($bill_payment as $row1) {
                $row1->payment_list = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->orderBy('payment.payment_date', 'asc')
                    ->get();

                $row1->grand_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->sum('payment.payment');
                $all_total += $row1->grand_total;
                if ($row1->payment_list != '[]') {
                    $i = 1;
                    $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                    $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                    $row1->status = DB::table('bill')->where('id', $row1->bill_id)->value('status');

                    foreach ($row1->payment_list as $val) {
                        $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                        $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                        $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                        $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_invoice_payment_report', compact('bill_payment', 'FilterDate', 'all_total')));

            return ($mpdf->Output('Invoice/Payment_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function billwise_payment_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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

            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $payment = DB::table('payment')
                ->select('id')
                ->where('active', 'yes')
                ->where('company', session('default_company_id'));
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $payment = $payment->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $payment = $payment->whereMonth('payment_date', $month)
                    ->whereYear('payment_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $payment = $payment->where('payment_date', $daily_filter);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $payment = $payment->whereBetween('payment_date', [$start_year, $end_year]);
            }
            $payment = $payment->orderBy('payment_date', 'asc')
                ->get();

            $bill_payment = DB::table('bill_payment_mapping')
                ->select('bill_id')
                ->where('active', 'yes')
                ->distinct()
                ->orderBy('bill_id', 'asc')
                ->get();

            $payment_id = array();
            foreach ($payment as $row) {
                $payment_id[] = $row->id;
            }
            $all_total = 0;
            foreach ($bill_payment as $row1) {
                $row1->payment_list = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->select('bill_payment_mapping.payment_id', 'payment.*')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->orderBy('payment.payment_date', 'asc')
                    ->get();

                $row1->grand_total = DB::table('bill_payment_mapping')
                    ->join('payment', 'payment.id', 'bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.payment_id', $payment_id)
                    ->where('bill_payment_mapping.bill_id', $row1->bill_id)
                    ->sum('payment.payment');
                $all_total += $row1->grand_total;
                if ($row1->payment_list != '[]') {
                    $i = 1;
                    $row1->invoice_no = DB::table('bill')->where('id', $row1->bill_id)->value('invoice_no');
                    $row1->invoice_date = DB::table('bill')->where('id', $row1->bill_id)->value('bill_date');
                    $row1->invoice_amount = DB::table('bill')->where('id', $row1->bill_id)->value('total_amount');
                    $row1->status = DB::table('bill')->where('id', $row1->bill_id)->value('status');

                    foreach ($row1->payment_list as $val) {
                        $val->client_name = DB::table('clients')->where('id', $val->client_id)->value('client_name');
                        $val->case_no = DB::table('clients')->where('id', $val->client_id)->value('case_no');
                        $val->deposite_bank_name = DB::table('bank_detailes')->where('id', $val->deposit_bank)->value('bankname');
                        $val->approved_by_name = DB::table('staff')->where('sid', $val->approved_by)->value('name');
                    }
                }
            }

            return view('pages.reports.get_invoice_payment_report', compact('bill_payment', 'FilterDate', 'all_total'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function clientwise_tds_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;
        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }


        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            if ($quarter_filter == 'Fourth Quarter') {
                $start_date = strtotime('1-January-' . $year[1]);
                $end_date = strtotime('31-March-' . $year[1]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

                $out1 = '';
                $export_data = "Clientwise TDS Report -\n\n";
                foreach ($clients as $val) {
                    $client_id = $val->id;

                    $payments = DB::table('payment')
                        ->select('id')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->orderBy('id', 'asc')
                        ->get();

                    $pay_id = array();
                    foreach ($payments as $pay) {
                        $pay_id[] = $pay->id;
                    }

                    $tds_list = DB::table('bill_payment_mapping')
                        ->select('bill_id', 'payment_id')
                        ->whereIn('payment_id', $pay_id)
                        ->get();

                    $grand_total = DB::table('payment')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->sum('tds');

                    if ($tds_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                        foreach ($tds_list as $row) {
                            $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                            $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                            $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                            $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                            $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

                $out1 = '';
                $export_data = "Clientwise TDS Report -\n\n";
                foreach ($clients as $val) {
                    $client_id = $val->id;

                    $payments = DB::table('payment')
                        ->select('id')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->orderBy('id', 'asc')
                        ->get();

                    $pay_id = array();
                    foreach ($payments as $pay) {
                        $pay_id[] = $pay->id;
                    }

                    $tds_list = DB::table('bill_payment_mapping')
                        ->select('bill_id', 'payment_id')
                        ->whereIn('payment_id', $pay_id)
                        ->get();

                    $grand_total = DB::table('payment')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->sum('tds');

                    if ($tds_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                        foreach ($tds_list as $row) {
                            $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                            $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                            $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                            $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                            $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

                $out1 = '';
                $export_data = "Clientwise TDS Report -\n\n";
                foreach ($clients as $val) {
                    $client_id = $val->id;

                    $payments = DB::table('payment')
                        ->select('id')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->orderBy('id', 'asc')
                        ->get();

                    $pay_id = array();
                    foreach ($payments as $pay) {
                        $pay_id[] = $pay->id;
                    }

                    $tds_list = DB::table('bill_payment_mapping')
                        ->select('bill_id', 'payment_id')
                        ->whereIn('payment_id', $pay_id)
                        ->get();

                    $grand_total = DB::table('payment')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->sum('tds');

                    if ($tds_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                        foreach ($tds_list as $row) {
                            $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                            $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                            $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                            $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                            $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

                $out1 = '';
                $export_data = "Clientwise TDS Report -\n\n";
                foreach ($clients as $val) {
                    $client_id = $val->id;

                    $payments = DB::table('payment')
                        ->select('id')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->orderBy('id', 'asc')
                        ->get();

                    $pay_id = array();
                    foreach ($payments as $pay) {
                        $pay_id[] = $pay->id;
                    }

                    $tds_list = DB::table('bill_payment_mapping')
                        ->select('bill_id', 'payment_id')
                        ->whereIn('payment_id', $pay_id)
                        ->get();

                    $grand_total = DB::table('payment')
                        ->where('active', 'yes')
                        ->where('company', session('default_company_id'))
                        ->whereBetween('payment_date', [$start_quarter, $end_quarter])
                        ->whereNotNull('tds')
                        ->where('tds', '!=', 0)
                        ->where('client_id', $client_id)
                        ->sum('tds');

                    if ($tds_list != '[]') {
                        $i = 1;
                        $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                        foreach ($tds_list as $row) {
                            $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                            $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                            $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                            $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                            $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            $out1 = '';
            $export_data = "Clientwise TDS Report -\n\n";
            foreach ($clients as $val) {
                $client_id = $val->id;

                $payments = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereYear('payment_date', $curr_year)
                    ->whereMonth('payment_date', $month)
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();

                $pay_id = array();
                foreach ($payments as $pay) {
                    $pay_id[] = $pay->id;
                }

                $tds_list = DB::table('bill_payment_mapping')
                    ->select('bill_id', 'payment_id')
                    ->whereIn('payment_id', $pay_id)
                    ->get();

                $grand_total = DB::table('payment')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereYear('payment_date', $curr_year)
                    ->whereMonth('payment_date', $month)
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->sum('tds');

                if ($tds_list != '[]') {
                    $i = 1;
                    $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                    foreach ($tds_list as $row) {
                        $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                        $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                        $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                        $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                        $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none') {
            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            $out1 = '';
            $export_data = "Clientwise TDS Report -\n\n";
            foreach ($clients as $val) {
                $client_id = $val->id;

                $payments = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->where('payment_date', $daily_filter)
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();

                $pay_id = array();
                foreach ($payments as $pay) {
                    $pay_id[] = $pay->id;
                }

                $tds_list = DB::table('bill_payment_mapping')
                    ->select('bill_id', 'payment_id')
                    ->whereIn('payment_id', $pay_id)
                    ->get();

                $grand_total = DB::table('payment')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->where('payment_date', $daily_filter)
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->sum('tds');

                if ($tds_list != '[]') {
                    $i = 1;
                    $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                    foreach ($tds_list as $row) {
                        $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                        $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                        $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                        $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                        $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }


        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            $out1 = '';
            $export_data = "Clientwise TDS Report -\n\n";
            foreach ($clients as $val) {
                $client_id = $val->id;
                $payments = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_year, $end_year])
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();

                $pay_id = array();
                foreach ($payments as $pay) {
                    $pay_id[] = $pay->id;
                }

                $tds_list = DB::table('bill_payment_mapping')
                    ->select('bill_id', 'payment_id')
                    ->whereIn('payment_id', $pay_id)
                    ->get();

                $grand_total = DB::table('payment')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'))
                    ->whereBetween('payment_date', [$start_year, $end_year])
                    ->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->sum('tds');

                if ($tds_list != '[]') {
                    $i = 1;
                    $export_data .= "Client - " . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tTDS\n";
                    foreach ($tds_list as $row) {
                        $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                        $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                        $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                        $row->invoice_no = session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
                        $lineData = array($i++, $row->invoice_no, date('d-M-Y', strtotime($row->bill_date)), $row->tds);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
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

    public function clientwise_tds_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            foreach ($clients as $val) {
                $client_id = $val->id;
                $payments = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $payments = $payments->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                    $payments = $payments->where('payment_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $payments = $payments->whereBetween('payment_date', [$start_year, $end_year]);
                }
                $payments = $payments->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();

                $pay_id = array();
                foreach ($payments as $pay) {
                    $pay_id[] = $pay->id;
                }

                $val->tds_list = DB::table('bill_payment_mapping')
                    ->select('bill_id', 'payment_id')
                    ->whereIn('payment_id', $pay_id)
                    ->get();

                $val->grand_total = DB::table('payment')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'));
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                    $val->grand_total = $val->grand_total->where('payment_date', $daily_filter);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->sum('tds');

                if ($val->tds_list != '[]') {
                    foreach ($val->tds_list as $row) {
                        $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                        $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                        $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_clientwise_tds_report', compact('clients', 'FilterDate')));

            return ($mpdf->Output('Clientwise_TDS_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function clientwise_tds_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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

            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }


            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            foreach ($clients as $val) {

                $client_id = $val->id;

                $payments = DB::table('payment')
                    ->select('id')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $payments = $payments->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }
                }

                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $payments = $payments->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', $curr_year);
                }


                if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                    $payments = $payments->where('payment_date', $daily_filter);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $payments = $payments->whereBetween('payment_date', [$start_year, $end_year]);
                }
                $payments = $payments->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();


                $pay_id = array();
                foreach ($payments as $pay) {
                    $pay_id[] = $pay->id;
                }

                $val->tds_list = DB::table('bill_payment_mapping')
                    ->select('bill_id', 'payment_id')
                    ->whereIn('payment_id', $pay_id)
                    ->get();

                $val->grand_total = DB::table('payment')
                    ->where('active', 'yes')
                    ->where('company', session('default_company_id'));
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->grand_total = $val->grand_total->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                    $val->grand_total = $val->grand_total->where('payment_date', $daily_filter);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $val->grand_total = $val->grand_total->whereBetween('payment_date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->whereNotNull('tds')
                    ->where('tds', '!=', 0)
                    ->where('client_id', $client_id)
                    ->sum('tds');

                if ($val->tds_list != '[]') {
                    foreach ($val->tds_list as $row) {
                        $row->tds = DB::table('payment')->where('id', $row->payment_id)->value('tds');
                        $row->invoice_no = DB::table('bill')->where('id', $row->bill_id)->value('invoice_no');
                        $row->bill_date = DB::table('bill')->where('id', $row->bill_id)->value('bill_date');
                    }
                }
            }

            return view('pages.reports.get_clientwise_tds_report', compact('clients', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }


    public function sales_target_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $daily_filter = $request->daily;

        $month = date("m", strtotime($month_filter));

        if ($year_filter != 'none') {
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
        }

        if ($daily_filter != 'none') {
            $year[0] = date('Y', strtotime($daily_filter));
            $year[1] = $year[0] + 1;
        }
        $filter = array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter . '(' . $year[0] . '-' . $year[1] . ')';
            if ($quarter_filter == 'Fourth Quarter') {
                $start_quarter = $year[1] . '-01-01';
                $end_quarter = $year[1] . '-03-31';
            }

            if ($quarter_filter == 'First Quarter') {

                $start_quarter = $year[0] . '-04-01';
                $end_quarter = $year[0] . '-06-30';
            }

            if ($quarter_filter == 'Second Quarter') {

                $start_quarter = $year[0] . '-07-01';
                $end_quarter = $year[0] . '-09-30';
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_quarter = $year[0] . '-10-01';
                $end_quarter = $year[0] . '-12-31';
            }
            $filter = array($start_quarter, $end_quarter);
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $month_filter . '/' . $curr_year;
            $start_date = $curr_year . '-' . $month . '-01';
            $d = cal_days_in_month(CAL_GREGORIAN, $month, $curr_year);
            $end_date = $curr_year . '-' . $month . '-' . $d;
            $filter = array($start_date, $end_date);
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $year_filter;
            $filter = array($start_year, $end_year);
        }

        if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
            $FilterDate = $daily_filter;
            $filter = array($daily_filter, $daily_filter);
        }


        $staff1 = DB::table('staff')->get();
        $out1 = '';
        $export_data = "Daily Sales Report -\n\n";

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

        foreach ($staff1 as $stf) {
            $staff_id = $stf->sid;

            $stf->total_payment =
                DB::table('clients')
                ->join('lead_history', 'lead_history.client_id', 'clients.id')
                ->join('payment', 'payment.client_id', 'clients.id')
                ->whereBetween('payment.payment_date', $filter)
                ->where('lead_history.assign_to', $staff_id)
                ->sum('payment.payment');

            $stf->payment_details = DB::table('clients')
                ->join('lead_history', 'lead_history.client_id', 'clients.id')
                ->join('payment', 'payment.client_id', 'clients.id')
                ->select('lead_history.assign_to', 'clients.id', 'clients.client_name', 'payment.payment', 'payment.payment_date', 'payment.bill_id')
                ->whereBetween('payment.payment_date', $filter)
                ->where('lead_history.assign_to', $staff_id)
                ->get();
            if ($stf->payment_details != '[]') {
                $i = 1;
                $export_data .= "Staff - (" . $stf->name . "):\tTotal Amount\t(" .  AppHelper::moneyFormatIndia($stf->total_payment) . ")\n";
                $export_data .= "\n";
                $export_data .= "Sr. No.\tClient Name\tInvoice No\tAmount\tPayment Date\n";
                foreach ($stf->payment_details as $row) {
                    $short_code = DB::table('company')->where('id', $company)->value('short_code');
                    $bill_ids = json_decode($row->bill_id);
                    $invoice_no = array_column(json_decode(DB::table('bill')->where('id', $bill_ids)->select('invoice_no')->get(), true), 'invoice_no');
                    $row->invoice = implode(",", $invoice_no);
                    $no = str_pad($row->invoice, 5, '0', STR_PAD_LEFT);
                    $row->invoice_prefix = $short_code . '-' . $no . '/' . substr($year[0], -2) . '-' . substr($year[1], -2);

                    $lineData = array($i++, $row->client_name, $row->invoice_prefix, AppHelper::moneyFormatIndia($row->payment), $row->payment_date);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
                $export_data .= "\n";
                $export_data .= "\n";
                $export_data .= "\n";
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"sales_target_report_Report.xls\"");
    }



    public function sales_target_pdf(Request $request)
    {
        try {
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->daily;

            $month = date("m", strtotime($month_filter));

            if ($year_filter != 'none') {
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
            }

            if ($daily_filter != 'none') {
                $year[0] = date('Y', strtotime($daily_filter));
                $year[1] = $year[0] + 1;
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter . '(' . $year[0] . '-' . $year[1] . ')';
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_quarter = $year[1] . '-01-01';
                    $end_quarter = $year[1] . '-03-31';
                }

                if ($quarter_filter == 'First Quarter') {

                    $start_quarter = $year[0] . '-04-01';
                    $end_quarter = $year[0] . '-06-30';
                }

                if ($quarter_filter == 'Second Quarter') {

                    $start_quarter = $year[0] . '-07-01';
                    $end_quarter = $year[0] . '-09-30';
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_quarter = $year[0] . '-10-01';
                    $end_quarter = $year[0] . '-12-31';
                }
                $filter = array($start_quarter, $end_quarter);
            }


            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $start_date = $curr_year . '-' . $month . '-01';
                $d = cal_days_in_month(CAL_GREGORIAN, $month, $curr_year);
                $end_date = $curr_year . '-' . $month . '-' . $d;
                $filter = array($start_date, $end_date);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $filter = array($start_year, $end_year);
            }

            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $FilterDate = $daily_filter;
                $filter = array($daily_filter, $daily_filter);
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

            //$year = date("m") >= 4 ? date("y") . '-' . (date("y") + 1) : (date("y") - 1) . '-' . date("y");

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $stf->total_payment =
                    DB::table('clients')
                    ->join('lead_history', 'lead_history.client_id', 'clients.id')
                    ->join('payment', 'payment.client_id', 'clients.id')
                    ->whereBetween('payment.payment_date', $filter)
                    ->where('lead_history.assign_to', $staff_id)
                    ->sum('payment.payment');

                $stf->payment_details = DB::table('clients')
                    ->join('lead_history', 'lead_history.client_id', 'clients.id')
                    ->join('payment', 'payment.client_id', 'clients.id')
                    ->select('lead_history.assign_to', 'clients.id', 'clients.client_name', 'payment.payment', 'payment.payment_date', 'payment.bill_id')
                    ->whereBetween('payment.payment_date', $filter)
                    ->where('lead_history.assign_to', $staff_id)
                    ->get();

                foreach ($stf->payment_details as $row) {

                    $short_code = DB::table('company')->where('id', $company)->value('short_code');
                    $bill_ids = json_decode($row->bill_id);
                    $invoice_no = array_column(json_decode(DB::table('bill')->where('id', $bill_ids)->select('invoice_no')->get(), true), 'invoice_no');
                    $row->invoice = implode(",", $invoice_no);
                    $no = str_pad($row->invoice, 5, '0', STR_PAD_LEFT);
                    $row->invoice_prefix = $short_code . '-' . $no . '/' . substr($year[0], -2) . '-' . substr($year[1], -2);
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_sales_target_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Sales_Target_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }



    public function sales_target_print(Request $request)
    {
        try {
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;
            $daily_filter = $request->date;

            $month = date("m", strtotime($month_filter));


            if ($year_filter != 'none') {
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
            }

            if ($daily_filter != 'none') {
                $year[0] = date('Y', strtotime($daily_filter));
                $year[1] = $year[0] + 1;
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
            if (
                $daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $daily_filter;
            }

            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter . '(' . $year[0] . '-' . $year[1] . ')';
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_quarter = $year[1] . '-01-01';
                    $end_quarter = $year[1] . '-03-31';
                }

                if ($quarter_filter == 'First Quarter') {

                    $start_quarter = $year[0] . '-04-01';
                    $end_quarter = $year[0] . '-06-30';
                }

                if ($quarter_filter == 'Second Quarter') {

                    $start_quarter = $year[0] . '-07-01';
                    $end_quarter = $year[0] . '-09-30';
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_quarter = $year[0] . '-10-01';
                    $end_quarter = $year[0] . '-12-31';
                }
                $filter = array($start_quarter, $end_quarter);
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $start_date = $curr_year . '-' . $month . '-01';
                $d = cal_days_in_month(CAL_GREGORIAN, $month, $curr_year);
                $end_date = $curr_year . '-' . $month . '-' . $d;
                $filter = array($start_date, $end_date);
            }

            if ($month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none' && $daily_filter != 'none') {
                $FilterDate = $daily_filter;
                $filter = array($daily_filter, $daily_filter);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $filter = array($start_year, $end_year);
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

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $stf->total_payment =
                    DB::table('clients')
                    ->join('lead_history', 'lead_history.client_id', 'clients.id')
                    ->join('payment', 'payment.client_id', 'clients.id')
                    ->whereBetween('payment.payment_date', $filter)
                    ->where('lead_history.assign_to', $staff_id)
                    ->sum('payment.payment');

                $stf->payment_details = DB::table('clients')
                    ->join('lead_history', 'lead_history.client_id', 'clients.id')
                    ->join('payment', 'payment.client_id', 'clients.id')
                    ->select('lead_history.assign_to', 'clients.id', 'clients.client_name', 'payment.payment', 'payment.payment_date', 'payment.bill_id')
                    ->whereBetween('payment.payment_date', $filter)
                    ->where('lead_history.assign_to', $staff_id)
                    ->get();


                foreach ($stf->payment_details as $row) {
                    $short_code = DB::table('company')->where('id', $company)->value('short_code');
                    $bill_ids = json_decode($row->bill_id);
                    $invoice_no = array_column(json_decode(DB::table('bill')->where('id', $bill_ids)->select('invoice_no')->get(), true), 'invoice_no');
                    $row->invoice = implode(",", $invoice_no);
                    $no = str_pad($row->invoice, 5, '0', STR_PAD_LEFT);
                    $row->invoice_prefix = $short_code . '-' . $no . '/' . substr($year[0], -2) . '-' . substr($year[1], -2);
                }
            }
            return view('pages.reports.get_sales_target_report', compact('staff', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
