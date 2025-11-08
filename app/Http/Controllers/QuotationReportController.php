<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppHelper;

class QuotationReportController extends Controller
{
    use ExpenseTraits;
    use StaffTraits;

    public function quotation_sent_excel(Request $request)
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation.send_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation.send_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation.send_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation.send_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                ->whereYear('quotation.send_date', $curr_year)
                ->whereMonth('quotation.send_date', $month)
                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation.send_date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereYear('quotation.send_date', $curr_year)
                ->whereMonth('quotation.send_date', $month)
                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount')
                ->whereBetween('quotation.send_date', [$start_year, $end_year])
                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation.send_date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereBetween('quotation.send_date', [$start_year, $end_year])
                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');
        }

        $export_data = "Quotation Sent Report -\n\n";
        if ($quotation_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
            foreach ($quotation_list as $quot) {
                $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                $assigned_at='';
                if($quot->assigned_at!='')
                {
                    $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                }
                $row['client']  = $quot->case_no . '(' . $quot->client_name . ')';
                $row['service_name']    = $quot->service_name;
                $row['no_of_units']    = $quot->no_of_units;
                $row['units_per_amount']    = $quot->units_per_amount;
                $row['total_amt']  = AppHelper::moneyFormatIndia($quot->amount);
                $row['finalize']  = $quot->finalize;
                $row['send_date']    = date('d-M-Y', strtotime($quot->send_date));

                $lineData = array($i++, $row['client'],$assign_to_name,$assigned_at,$source_name, $row['service_name'], $row['no_of_units'], $row['units_per_amount'], $row['total_amt'], $row['finalize'], $row['send_date']);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Quotation_Sent_Report.xls\"");
    }

    public function quotation_sent_pdf(Request $request)
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

            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }

            $quotation_list = $quotation_list->where('quotation.company', session('company_id'))
                ->orderBy('quotation.send_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }

            $grand_total = $grand_total->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');
            foreach($quotation_list as $quot)
            {
                $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
               
                if($quot->assigned_at!='')
                {
                    $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                }
            }
            
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_quotation_sent_report', compact('quotation_list', 'FilterDate', 'grand_total')));

            return ($mpdf->Output('Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function quotation_sent_print(Request $request)
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

            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }

            $quotation_list = $quotation_list->where('quotation.company', session('company_id'))
                ->orderBy('quotation.send_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }
            foreach($quotation_list as $quot)
            {
                $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
               
                if($quot->assigned_at!='')
                {
                    $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                }
            }
            $grand_total = $grand_total->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');

            return view('pages.reports.get_quotation_sent_report', compact('quotation_list', 'FilterDate', 'grand_total'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function quotation_finalized_excel(Request $request)
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'First Quarter') {
                $start_date = strtotime('1-April-' . $year[0]);
                $end_date = strtotime('30-June-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'Second Quarter') {
                $start_date = strtotime('1-July-' . $year[0]);
                $end_date = strtotime('30-September-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_date = strtotime('1-October-' . $year[0]);
                $end_date = strtotime('31-December-' . $year[0]);
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get()->toArray();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                    ->where('quotation_details.finalize', 'yes')

                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                ->whereYear('quotation_details.finalize_date', $curr_year)
                ->whereMonth('quotation_details.finalize_date', $month)
                ->where('quotation_details.finalize', 'yes')

                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereYear('quotation_details.finalize_date', $curr_year)
                ->whereMonth('quotation_details.finalize_date', $month)
                ->where('quotation_details.finalize', 'yes')

                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount')
                ->whereBetween('quotation_details.finalize_date', [$start_year, $end_year])
                ->where('quotation_details.finalize', 'yes')

                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get()->toArray();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereBetween('quotation_details.finalize_date', [$start_year, $end_year])
                ->where('quotation_details.finalize', 'yes')

                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');
        }

        $export_data = "Quotation Finalized Report\n\n";
        if ($quotation_list != '[]') {
            $i = 1;

            $export_data .= "Sr. No.\tClient\tAssign_to\tAssigned at\tSource\tFollow Up\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tSend Dt\tFinalized Dt\n";
            foreach ($quotation_list as $quot) {
                $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                $assigned_at='';
                if($quot->assigned_at!='')
                {
                    $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                }
                $row['total_followup'] = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                $row['client']  = $quot->case_no . '(' . $quot->client_name . ')';
                $row['service_name']    = $quot->service_name;
                $row['no_of_units']    = $quot->no_of_units;
                $row['units_per_amount']    = $quot->units_per_amount;
                $row['total_amt']  = AppHelper::moneyFormatIndia($quot->amount);
                $row['send_date']    = date('d-M-Y', strtotime($quot->send_date));
                $row['finalize_date']  = date('d-M-Y', strtotime($quot->finalize_date));

                $lineData = array($i++, $row['client'],$assign_to_name,$assigned_at,$source_name, $row['total_followup'], $row['service_name'], $row['no_of_units'], $row['units_per_amount'], $row['total_amt'], $row['send_date'], $row['finalize_date']);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\t\t\t\t\tGrand Total\t" . $grand_total;
        }


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Quotation_Finalized_Report.xls\"");
    }

    public function quotation_finalized_pdf(Request $request)
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

            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }

            $quotation_list = $quotation_list->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total =
                    $grand_total->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }

            $grand_total =
                $grand_total->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');

            foreach ($quotation_list as $row) {
                $row->assign_to_name=DB::table('staff')->where('sid',$row->assign_to)->value('name');
                $row->source_name=DB::table('source')->where('id',$row->source)->value('source');
                if($row->assigned_at!='')
                {
                    $row->assigned_at=date('d-M-Y',strtotime($row->assigned_at));
                }
                $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                
            }


            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_quotation_finalized_report', compact('quotation_list', 'FilterDate', 'grand_total')));

            return ($mpdf->Output('Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function quotation_finalized_print(Request $request)
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

            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }
            $quotation_list = $quotation_list->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }

            $grand_total = $grand_total->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');

            foreach ($quotation_list as $row) {
                $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                $row->assign_to_name=DB::table('staff')->where('sid',$row->assign_to)->value('name');
                $row->source_name=DB::table('source')->where('id',$row->source)->value('source');
                if($row->assigned_at!='')
                {
                    $row->assigned_at=date('d-M-Y',strtotime($row->assigned_at));
                }
            }
            
            return view('pages.reports.get_quotation_finalized_report', compact('quotation_list', 'FilterDate', 'grand_total'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    // public function quotation_by_sales_excel(Request $request)
    // {
    //     $month_filter = $request->month;
    //     $quarter_filter = $request->quarter;
    //     $year_filter = $request->year;

    //     $month = date("m", strtotime($month_filter));

    //     $year = explode('-', $year_filter);

    //     $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //     $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //     $start_year = date('Y-m-d', $start_fiscal_year);
    //     $end_year = date('Y-m-d', $end_fiscal_year);

    //     if ($month > 03) {
    //         $curr_year = $year[0];
    //     } else {
    //         $curr_year = $year[1];
    //     }


    //     if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //         if ($quarter_filter == 'Fourth Quarter') {
    //             $start_date = strtotime('1-January-' . $year[1]);
    //             $end_date = strtotime('31-March-' . $year[1]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);

    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Sales Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'First Quarter') {
    //             $start_date = strtotime('1-April-' . $year[0]);
    //             $end_date = strtotime('30-June-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Sales Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'Second Quarter') {
    //             $start_date = strtotime('1-July-' . $year[0]);
    //             $end_date = strtotime('30-September-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Sales Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'Third Quarter') {
    //             $start_date = strtotime('1-October-' . $year[0]);
    //             $end_date = strtotime('31-December-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Sales Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }
    //     }

    //     if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $out1 = '';
    //         $export_data = "Quotation By Sales Report - \n\n";
    //         foreach ($staff as $row) {
    //             $quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                 ->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year)
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year)
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');

    //             if ($quotation_list != '[]') {
    //                 $i = 1;
    //                 $export_data .= "Staff - (" . $row->name . "):\n";
    //                 $export_data .= "\n";
    //                 $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                 foreach ($quotation_list as $quot) {
    //                     $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                     $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                 }
    //                 $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                 $export_data .= "\n";
    //                 $export_data .= "\n";
    //             }
    //         }
    //         $out1 .= $export_data;
    //     }

    //     if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //         $staff1 = DB::table('staff')->get();
    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $out1 = '';
    //         $export_data = "Quotation By Sales Report - \n\n";
    //         foreach ($staff as $row) {
    //             $quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                 ->whereBetween('quotation.send_date', [$start_year, $end_year])
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();


    //             $grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->whereBetween('quotation.send_date', [$start_year, $end_year])
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');

    //             if ($quotation_list != '[]') {
    //                 $i = 1;
    //                 $export_data .= "Staff - (" . $row->name . "):\n";
    //                 $export_data .= "\n";
    //                 $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
    //                 foreach ($quotation_list as $quot) {
    //                     $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                     $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                 }
    //                 $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                 $export_data .= "\n";
    //                 $export_data .= "\n";
    //             }
    //         }
    //         $out1 .= $export_data;
    //     }

    //     return response($out1)
    //         ->header("Content-Type", "application/vnd.ms-excel")
    //         ->header("Content-Disposition", "attachment;filename=\"Quotation_By_Sales_Report.xls\"");
    // }

    // public function quotation_by_sales_pdf(Request $request)
    // {
    //     try {
    //         // new code for pdf
    //         require_once base_path('vendor/autoload.php');
    //         $month_filter = $request->month;
    //         $quarter_filter = $request->quarter;
    //         $year_filter = $request->year;

    //         $month = date("m", strtotime($month_filter));

    //         $year = explode('-', $year_filter);

    //         $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //         $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //         $start_year = date('Y-m-d', $start_fiscal_year);
    //         $end_year = date('Y-m-d', $end_fiscal_year);

    //         if ($month > 03) {
    //             $curr_year = $year[0];
    //         } else {
    //             $curr_year = $year[1];
    //         }

    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             $FilterDate = $quarter_filter;
    //         }

    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $month_filter . '/' . $curr_year;
    //         }

    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $year_filter;
    //         }

    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $StaffId = array_column(json_decode($staff), 'sid');

    //         $total = DB::table('quotation')
    //             ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //             ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             if ($quarter_filter == 'Fourth Quarter') {
    //                 $start_date = strtotime('1-January-' . $year[1]);
    //                 $end_date = strtotime('31-March-' . $year[1]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'First Quarter') {
    //                 $start_date = strtotime('1-April-' . $year[0]);
    //                 $end_date = strtotime('30-June-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Second Quarter') {
    //                 $start_date = strtotime('1-July-' . $year[0]);
    //                 $end_date = strtotime('30-September-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Third Quarter') {
    //                 $start_date = strtotime('1-October-' . $year[0]);
    //                 $end_date = strtotime('31-December-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }
    //         }
    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year);
    //         }
    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //         }
    //         $total = $total->where('quotation.company', session('company_id'))
    //             ->whereIn('clients.created_by', $StaffId)
    //             ->sum('quotation_details.amount');

    //         foreach ($staff as $row) {
    //             $row->quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }

    //             $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $row->grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }

    //             $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');
    //         }

    //         ini_set("pcre.backtrack_limit", "5000000");
    //         $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
    //         $mpdf->simpleTables = true;
    //         $mpdf->use_kwt = true;
    //         $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
    //         $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
    //         $mpdf->SetDisplayMode('fullpage');
    //         $mpdf->WriteHTML(view('pages.reports.get_quotation_by_sales_report', compact('staff', 'total', 'FilterDate')));
    //         return ($mpdf->Output('Quotation_By_Sales_Report.pdf', 'I'));
    //     } catch (QueryException $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('alert-danger', $e->getMessage());
    //     }
    // }

    // public function quotation_by_sales_print(Request $request)
    // {
    //     try {
    //         $month_filter = $request->month;
    //         $quarter_filter = $request->quarter;
    //         $year_filter = $request->year;

    //         $month = date("m", strtotime($month_filter));

    //         $year = explode('-', $year_filter);

    //         $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //         $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //         $start_year = date('Y-m-d', $start_fiscal_year);
    //         $end_year = date('Y-m-d', $end_fiscal_year);

    //         if ($month > 03) {
    //             $curr_year = $year[0];
    //         } else {
    //             $curr_year = $year[1];
    //         }


    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             $FilterDate = $quarter_filter;
    //         }

    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $month_filter . '/' . $curr_year;
    //         }

    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $year_filter;
    //         }

    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $StaffId = array_column(json_decode($staff), 'sid');

    //         $total = DB::table('quotation')
    //             ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //             ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             if ($quarter_filter == 'Fourth Quarter') {
    //                 $start_date = strtotime('1-January-' . $year[1]);
    //                 $end_date = strtotime('31-March-' . $year[1]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'First Quarter') {
    //                 $start_date = strtotime('1-April-' . $year[0]);
    //                 $end_date = strtotime('30-June-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Second Quarter') {
    //                 $start_date = strtotime('1-July-' . $year[0]);
    //                 $end_date = strtotime('30-September-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Third Quarter') {
    //                 $start_date = strtotime('1-October-' . $year[0]);
    //                 $end_date = strtotime('31-December-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }
    //         }
    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year);
    //         }
    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //         }
    //         $total = $total->where('quotation.company', session('company_id'))
    //             ->whereIn('clients.created_by', $StaffId)
    //             ->sum('quotation_details.amount');

    //         foreach ($staff as $row) {
    //             $row->quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }

    //             $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $row->grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }

    //             $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');
    //         }

    //         return view('pages.reports.get_quotation_by_sales_report', compact('staff', 'total', 'FilterDate'));
    //     } catch (QueryException $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('alert-danger', $e->getMessage());
    //     }
    // }

    // public function quotation_by_office_excel(Request $request)
    // {
    //     $month_filter = $request->month;
    //     $quarter_filter = $request->quarter;
    //     $year_filter = $request->year;
    //     $year = $request->year;

    //     $month_filter = $request->month;
    //     $quarter_filter = $request->quarter;
    //     $year_filter = $request->year;

    //     $month = date("m", strtotime($month_filter));

    //     $year = explode('-', $year_filter);

    //     $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //     $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //     $start_year = date('Y-m-d', $start_fiscal_year);
    //     $end_year = date('Y-m-d', $end_fiscal_year);

    //     if ($month > 03) {
    //         $curr_year = $year[0];
    //     } else {
    //         $curr_year = $year[1];
    //     }


    //     if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //         if ($quarter_filter == 'Fourth Quarter') {
    //             $start_date = strtotime('1-January-' . $year[1]);
    //             $end_date = strtotime('31-March-' . $year[1]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);

    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', "!=", 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Office Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'First Quarter') {
    //             $start_date = strtotime('1-April-' . $year[0]);
    //             $end_date = strtotime('30-June-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', "!=", 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Office Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'Second Quarter') {
    //             $start_date = strtotime('1-July-' . $year[0]);
    //             $end_date = strtotime('30-September-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', "!=", 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Office Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }

    //         if ($quarter_filter == 'Third Quarter') {
    //             $start_date = strtotime('1-October-' . $year[0]);
    //             $end_date = strtotime('31-December-' . $year[0]);
    //             $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //             $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //             $staff1 = DB::table('staff')->get();

    //             $staff_id = array();
    //             foreach ($staff1 as $stf) {
    //                 $company = json_decode($stf->company);
    //                 for ($i = 0; $i < sizeof($company); $i++) {
    //                     if ($company[$i] == session('company_id')) {
    //                         $staff_id[] = $stf->sid;
    //                     }
    //                 }
    //             }

    //             $staff = DB::table('staff')
    //                 ->join('users', 'users.user_id', 'staff.sid')
    //                 ->select('staff.sid', 'staff.name')
    //                 ->where('users.status', 'active')
    //                 ->where('users.role_id', "!=", 8)
    //                 ->whereIn('staff.sid', $staff_id)
    //                 ->orderBy('staff.sid', 'asc')
    //                 ->get();

    //             $out1 = '';
    //             $export_data = "Quotation By Office Report - \n\n";
    //             foreach ($staff as $row) {
    //                 $quotation_list = DB::table('quotation')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                     ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->orderBy('quotation.send_date', 'asc')
    //                     ->get();

    //                 $grand_total = DB::table('quotation')
    //                     ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                     ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                     ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
    //                     ->where('quotation.company', session('company_id'))
    //                     ->where('clients.created_by', $row->sid)
    //                     ->sum('quotation_details.amount');

    //                 if ($quotation_list != '[]') {
    //                     $i = 1;
    //                     $export_data .= "Staff - (" . $row->name . "):\n";
    //                     $export_data .= "\n";
    //                     $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                     foreach ($quotation_list as $quot) {
    //                         $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                         $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                     }
    //                     $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                     $export_data .= "\n";
    //                     $export_data .= "\n";
    //                 }
    //             }
    //             $out1 .= $export_data;
    //         }
    //     }

    //     if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', "!=", 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $out1 = '';
    //         $export_data = "Quotation By Office Report - \n\n";
    //         foreach ($staff as $row) {
    //             $quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                 ->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year)
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year)
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');

    //             if ($quotation_list != '[]') {
    //                 $i = 1;
    //                 $export_data .= "Staff - (" . $row->name . "):\n";
    //                 $export_data .= "\n";
    //                 $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                 foreach ($quotation_list as $quot) {
    //                     $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                     $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                 }
    //                 $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                 $export_data .= "\n";
    //                 $export_data .= "\n";
    //             }
    //         }
    //         $out1 .= $export_data;
    //     }

    //     if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //         $staff1 = DB::table('staff')->get();
    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', '!=', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $out1 = '';
    //         $export_data = "Quotation By Office Report - \n\n";
    //         foreach ($staff as $row) {
    //             $quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no')
    //                 ->whereBetween('quotation.send_date', [$start_year, $end_year])
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->whereBetween('quotation.send_date', [$start_year, $end_year])
    //                 ->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');

    //             if ($quotation_list != '[]') {
    //                 $i = 1;
    //                 $export_data .= "Staff - (" . $row->name . "):\n";
    //                 $export_data .= "\n";
    //                 $export_data .= "Sr. No.\tClient\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

    //                 foreach ($quotation_list as $quot) {
    //                     $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
    //                     $export_data .= implode("\t", array_values($lineData)) . "\n";
    //                 }
    //                 $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
    //                 $export_data .= "\n";
    //                 $export_data .= "\n";
    //             }
    //         }
    //         $out1 .= $export_data;
    //     }

    //     return response($out1)
    //         ->header("Content-Type", "application/vnd.ms-excel")
    //         ->header("Content-Disposition", "attachment;filename=\"Quotation_By_Office_Report.xls\"");
    // }

    // public function quotation_by_office_pdf(Request $request)
    // {
    //     try {
    //         // new code for pdf
    //         require_once base_path('vendor/autoload.php');
    //         $month_filter = $request->month;
    //         $quarter_filter = $request->quarter;
    //         $year_filter = $request->year;

    //         $month = date("m", strtotime($month_filter));

    //         $year = explode('-', $year_filter);

    //         $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //         $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //         $start_year = date('Y-m-d', $start_fiscal_year);
    //         $end_year = date('Y-m-d', $end_fiscal_year);

    //         if ($month > 03) {
    //             $curr_year = $year[0];
    //         } else {
    //             $curr_year = $year[1];
    //         }


    //         if (
    //             $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
    //         ) {
    //             $FilterDate = $quarter_filter;
    //         }

    //         if (
    //             $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //         ) {
    //             $FilterDate = $month_filter . '/' . $curr_year;
    //         }

    //         if (
    //             $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //         ) {
    //             $FilterDate = $year_filter;
    //         }

    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where(
    //                 'users.role_id',
    //                 '!=',
    //                 8
    //             )
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $StaffId = array_column(json_decode($staff), 'sid');

    //         $total = DB::table('quotation')
    //             ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //             ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             if ($quarter_filter == 'Fourth Quarter') {
    //                 $start_date = strtotime('1-January-' . $year[1]);
    //                 $end_date = strtotime('31-March-' . $year[1]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'First Quarter') {
    //                 $start_date = strtotime('1-April-' . $year[0]);
    //                 $end_date = strtotime('30-June-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Second Quarter') {
    //                 $start_date = strtotime('1-July-' . $year[0]);
    //                 $end_date = strtotime('30-September-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Third Quarter') {
    //                 $start_date = strtotime('1-October-' . $year[0]);
    //                 $end_date = strtotime('31-December-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }
    //         }
    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year);
    //         }
    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //         }
    //         $total = $total->where('quotation.company', session('company_id'))
    //             ->whereIn('clients.created_by', $StaffId)
    //             ->sum('quotation_details.amount');

    //         foreach ($staff as $row) {
    //             $row->quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no');
    //             if (
    //                 $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
    //             ) {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if (
    //                 $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //             ) {
    //                 $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if (
    //                 $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //             ) {
    //                 $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }
    //             $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $row->grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //             if (
    //                 $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
    //             ) {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if (
    //                 $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //             ) {
    //                 $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if (
    //                 $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
    //             ) {
    //                 $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }
    //             $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');
    //         }

    //         ini_set("pcre.backtrack_limit", "5000000");
    //         $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
    //         $mpdf->use_kwt = true;
    //         $mpdf->simpleTables = true;
    //         $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
    //         $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
    //         $mpdf->SetDisplayMode('fullpage');
    //         $mpdf->WriteHTML(view('pages.reports.get_quotation_by_office_report', compact('staff', 'total', 'FilterDate')));

    //         return ($mpdf->Output('Quotation_By_Office_Report.pdf', 'I'));
    //     } catch (QueryException $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('alert-danger', $e->getMessage());
    //     }
    // }

    // public function quotation_by_office_print(Request $request)
    // {
    //     try {
    //         $month_filter = $request->month;
    //         $quarter_filter = $request->quarter;
    //         $year_filter = $request->year;

    //         $month = date("m", strtotime($month_filter));

    //         $year = explode('-', $year_filter);

    //         $start_fiscal_year = strtotime('1-April-' . $year[0]);
    //         $end_fiscal_year = strtotime('31-March-' . $year[1]);
    //         $start_year = date('Y-m-d', $start_fiscal_year);
    //         $end_year = date('Y-m-d', $end_fiscal_year);

    //         if ($month > 03) {
    //             $curr_year = $year[0];
    //         } else {
    //             $curr_year = $year[1];
    //         }

    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             $FilterDate = $quarter_filter;
    //         }

    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $month_filter . '/' . $curr_year;
    //         }

    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $FilterDate = $year_filter;
    //         }

    //         $staff1 = DB::table('staff')->get();

    //         $staff_id = array();
    //         foreach ($staff1 as $stf) {
    //             $company = json_decode($stf->company);
    //             for ($i = 0; $i < sizeof($company); $i++) {
    //                 if ($company[$i] == session('company_id')) {
    //                     $staff_id[] = $stf->sid;
    //                 }
    //             }
    //         }

    //         $staff = DB::table('staff')
    //             ->join('users', 'users.user_id', 'staff.sid')
    //             ->select('staff.sid', 'staff.name')
    //             ->where('users.status', 'active')
    //             ->where('users.role_id', '!=', 8)
    //             ->whereIn('staff.sid', $staff_id)
    //             ->orderBy('staff.sid', 'asc')
    //             ->get();

    //         $StaffId = array_column(json_decode($staff), 'sid');

    //         $total = DB::table('quotation')
    //             ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //             ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //         if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //             if ($quarter_filter == 'Fourth Quarter') {
    //                 $start_date = strtotime('1-January-' . $year[1]);
    //                 $end_date = strtotime('31-March-' . $year[1]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'First Quarter') {
    //                 $start_date = strtotime('1-April-' . $year[0]);
    //                 $end_date = strtotime('30-June-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Second Quarter') {
    //                 $start_date = strtotime('1-July-' . $year[0]);
    //                 $end_date = strtotime('30-September-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }

    //             if ($quarter_filter == 'Third Quarter') {
    //                 $start_date = strtotime('1-October-' . $year[0]);
    //                 $end_date = strtotime('31-December-' . $year[0]);
    //                 $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                 $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                 $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //             }
    //         }
    //         if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereMonth('quotation.send_date', $month)
    //                 ->whereYear('quotation.send_date', $curr_year);
    //         }
    //         if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //             $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //         }
    //         $total = $total->where('quotation.company', session('company_id'))
    //             ->whereIn('clients.created_by', $StaffId)
    //             ->sum('quotation_details.amount');

    //         foreach ($staff as $row) {
    //             $row->quotation_list = DB::table('quotation')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('services', 'services.id', '=', 'quotation_details.task_id')
    //                 ->select('clients.client_name', 'clients.case_no', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'clients.created_by', 'quotation_details.reference_no');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }
    //             $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->orderBy('quotation.send_date', 'asc')
    //                 ->get();

    //             $row->grand_total = DB::table('quotation')
    //                 ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
    //                 ->join('clients', 'clients.id', '=', 'quotation.client_id');
    //             if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
    //                 if ($quarter_filter == 'Fourth Quarter') {
    //                     $start_date = strtotime('1-January-' . $year[1]);
    //                     $end_date = strtotime('31-March-' . $year[1]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'First Quarter') {
    //                     $start_date = strtotime('1-April-' . $year[0]);
    //                     $end_date = strtotime('30-June-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Second Quarter') {
    //                     $start_date = strtotime('1-July-' . $year[0]);
    //                     $end_date = strtotime('30-September-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }

    //                 if ($quarter_filter == 'Third Quarter') {
    //                     $start_date = strtotime('1-October-' . $year[0]);
    //                     $end_date = strtotime('31-December-' . $year[0]);
    //                     $start_quarter = date('Y-m-d 00:00:00', $start_date);
    //                     $end_quarter = date('Y-m-d 23:59:59', $end_date);
    //                     $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
    //                 }
    //             }
    //             if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
    //                     ->whereYear('quotation.send_date', $curr_year);
    //             }
    //             if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
    //                 $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
    //             }
    //             $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
    //                 ->where('clients.created_by', $row->sid)
    //                 ->sum('quotation_details.amount');
    //         }

    //         return view('pages.reports.get_quotation_by_office_report', compact('staff', 'total', 'FilterDate'));
    //     } catch (QueryException $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return redirect()->back()->with('alert-danger', $e->getMessage());
    //     }
    // }

    public function servicewise_quotation_sent_excel(Request $request)
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Sent Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                        foreach ($quotation_list as $quot) {
                            $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            $assigned_at='';
                            if($quot->assigned_at!='')
                            {
                                $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
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
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Sent Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign_to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                        foreach ($quotation_list as $quot) {
                            $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            $assigned_at='';
                            if($quot->assigned_at!='')
                            {
                                $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
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
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Sent Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                        foreach ($quotation_list as $quot) {
                            $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            $assigned_at='';
                            if($quot->assigned_at!='')
                            {
                                $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
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

                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Sent Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->whereBetween('quotation.send_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                        foreach ($quotation_list as $quot) {
                            $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            $assigned_at='';
                            if($quot->assigned_at!='')
                            {
                                $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                        $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                $out1 .= $export_data;
            }
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $task_id = DB::table('services')->get();

            $out1 = '';
            $export_data = "Servicewise Quotation Sent Report - \n\n";
            foreach ($task_id as $row) {
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->whereMonth('quotation.send_date', $month)
                    ->whereYear('quotation.send_date', $curr_year)
                    ->where('quotation.company', session('company_id'))
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->whereMonth('quotation.send_date', $month)
                    ->whereYear('quotation.send_date', $curr_year)
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                if ($quotation_list != '[]') {
                    $i = 1;
                    $export_data .= "Service - (" . $row->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                    foreach ($quotation_list as $quot) {
                        $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        $assigned_at='';
                        if($quot->assigned_at!='')
                        {
                            $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                        $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $task_id = DB::table('services')->get();
            $out1 = '';
            $export_data = "Servicewise Quotation Sent Report - \n\n";
            foreach ($task_id as $row) {
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->whereBetween('quotation.send_date', [$start_year, $end_year])
                    ->where('quotation.company', session('company_id'))
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->whereBetween('quotation.send_date', [$start_year, $end_year])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                if ($quotation_list != '[]') {
                    $i = 1;
                    $export_data .= "Service - (" . $row->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                    foreach ($quotation_list as $quot) {
                        $assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        $assigned_at='';
                        if($quot->assigned_at!='')
                        {
                            $assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                        $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$assign_to_name,$assigned_at,$source_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize, date('d-M-Y', strtotime($quot->send_date)));
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . $grand_total;
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
            $out1 .= $export_data;
        }

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Servicewise_Quotation_Sent_Report.xls\"");
    }

    public function servicewise_quotation_sent_pdf(Request $request)
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

            $task_id = DB::table('quotation_details')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('quotation_details.task_id as id', 'services.name')
                ->whereNotNull('quotation_details.task_id')
                ->distinct()
                ->orderBy('quotation_details.task_id', 'asc')
                ->get();

            $TaskId = array_column(json_decode($task_id), 'id');
            $total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereIn('quotation_details.task_id', $TaskId);
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereMonth('quotation.send_date', $month)
                    ->whereYear('quotation.send_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }
            $total = $total->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');

            foreach ($task_id as $row) {
                $row->quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize')
                    ->where('quotation_details.task_id', $row->id);
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
                        ->whereYear('quotation.send_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }
                $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
                    ->get();

                $row->grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id);
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
                        ->whereYear('quotation.send_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }
                $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
                    foreach ($row->quotation_list as $quot) {
                        $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        if($quot->assigned_at!='')
                        {
                            $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                    }
            }
           
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_servicewise_quotation_sent_report', compact('task_id', 'total', 'FilterDate')));

            return ($mpdf->Output('Servicewise_Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function servicewise_quotation_sent_print(Request $request)
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

            $task_id = DB::table('quotation_details')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('quotation_details.task_id as id', 'services.name')
                ->whereNotNull('quotation_details.task_id')
                ->distinct()
                ->orderBy('quotation_details.task_id', 'asc')
                ->get();

            $TaskId = array_column(json_decode($task_id), 'id');
            $total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereIn('quotation_details.task_id', $TaskId);
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                }
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereMonth('quotation.send_date', $month)
                    ->whereYear('quotation.send_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }
            $total = $total->where('quotation.company', session('company_id'))
                ->sum('quotation_details.amount');

            foreach ($task_id as $row) {
                $row->quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize')
                    ->where('quotation_details.task_id', $row->id);
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->quotation_list = $row->quotation_list->whereMonth('quotation.send_date', $month)
                        ->whereYear('quotation.send_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }
                $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
                    ->get();

                $row->grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id);
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }

                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_quarter, $end_quarter]);
                    }
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->grand_total = $row->grand_total->whereMonth('quotation.send_date', $month)
                        ->whereYear('quotation.send_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->grand_total = $row->grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }
                $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');
                    foreach ($row->quotation_list as $quot) {
                        $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        if($quot->assigned_at!='')
                        {
                            $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                    }
            }
          
            return view('pages.reports.get_servicewise_quotation_sent_report', compact('task_id', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function servicewise_quotation_finalized_excel(Request $request)
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Finalized Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->orderBy('quotation_details.finalize_date', 'asc')
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                        foreach ($quotation_list as $quot) {
                            $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            if($quot->assigned_at!='')
                            {
                                $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Finalized Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->orderBy('quotation_details.finalize_date', 'asc')
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                        foreach ($quotation_list as $quot) {
                            $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            if($quot->assigned_at!='')
                            {
                                $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);
                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Finalized Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->orderBy('quotation_details.finalize_date', 'asc')
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                        foreach ($quotation_list as $quot) {
                            $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            if($quot->assigned_at!='')
                            {
                                $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
                $start_quarter = date('Y-m-d 00:00:00', $start_date);
                $end_quarter = date('Y-m-d 23:59:59', $end_date);

                $task_id = DB::table('services')->get();
                $out1 = '';
                $export_data = "Servicewise Quotation Finalized Report - \n\n";
                foreach ($task_id as $row) {
                    $quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->orderBy('quotation_details.finalize_date', 'asc')
                        ->get();

                    $grand_total = DB::table('quotation')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->where('quotation_details.task_id', $row->id)
                        ->where('quotation_details.finalize', 'yes')

                        ->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter])
                        ->where('quotation.company', session('company_id'))
                        ->sum('quotation_details.amount');

                    if ($quotation_list != '[]') {
                        $i = 1;
                        $export_data .= "Service - (" . $row->name . "):\n";
                        $export_data .= "\n";
                        $export_data .= "Sr. No.\tClient\tAssign to\tAssignd At\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                        foreach ($quotation_list as $quot) {
                            $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                            $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                            if($quot->assigned_at!='')
                            {
                                $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                            }
                            $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                            $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
            $task_id = DB::table('services')->get();

            $out1 = '';
            $export_data = "Servicewise Quotation Finalized Report - \n\n";
            foreach ($task_id as $row) {
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->whereMonth('quotation_details.finalize_date', $month)
                    ->whereYear('quotation_details.finalize_date', $curr_year)
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->whereMonth('quotation_details.finalize_date', $month)
                    ->whereYear('quotation_details.finalize_date', $curr_year)
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                if ($quotation_list != '[]') {
                    $i = 1;
                    $export_data .= "Service - (" . $row->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                    foreach ($quotation_list as $quot) {
                        $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        if($quot->assigned_at!='')
                        {
                            $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                        $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                        $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
            $task_id = DB::table('services')->get();
            $out1 = '';
            $export_data = "Servicewise Quotation Finalized Report - \n\n";
            foreach ($task_id as $row) {
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->whereBetween('quotation_details.finalize_date', [$start_year, $end_year])
                    ->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->whereBetween('quotation_details.finalize_date', [$start_year, $end_year])
                    ->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                if ($quotation_list != '[]') {
                    $i = 1;
                    $export_data .= "Service - (" . $row->name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tAssign To\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                    foreach ($quotation_list as $quot) {
                        $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                        $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                        if($quot->assigned_at!='')
                        {
                            $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                        }
                        $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                        $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')',$quot->assign_to_name,$quot->assigned_at,$quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
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
            ->header("Content-Disposition", "attachment;filename=\"Servicewise_Quotation_Finalized_Report.xls\"");
    }

    public function servicewise_quotation_finalized_pdf(Request $request)
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

            $task_id = DB::table('quotation_details')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('quotation_details.task_id as id', 'services.name')
                ->whereNotNull('quotation_details.task_id')
                ->distinct()
                ->orderBy('quotation_details.task_id', 'asc')
                ->get();
            $TaskId = array_column(json_decode($task_id), 'id');
            $total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereIn('quotation_details.task_id', $TaskId);
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('quotation_details.finalize_date', $month)
                    ->whereYear('quotation_details.finalize_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }
            $total = $total->where('quotation.company', session('company_id'))
                ->where('quotation_details.finalize', 'yes')
                ->sum('quotation_details.amount');

            foreach ($task_id as $row) {
                $row->quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes');

                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereMonth('quotation_details.finalize_date', $month)
                        ->whereYear('quotation_details.finalize_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $row->grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes');
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereMonth('quotation_details.finalize_date', $month)
                        ->whereYear('quotation_details.finalize_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                foreach ($row->quotation_list as $quot) {
                    $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                    $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                    if($quot->assigned_at!='')
                    {
                        $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                    }
                    $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                    
                }
            }
           
            
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->simpleTables = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_servicewise_quotation_finalized_report', compact('task_id', 'total', 'FilterDate')));
            return ($mpdf->Output('Servicewise_Quotation_Finalized_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function servicewise_quotation_finalized_print(Request $request)
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

            $task_id = DB::table('quotation_details')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('quotation_details.task_id as id', 'services.name')
                ->whereNotNull('quotation_details.task_id')
                ->distinct()
                ->orderBy('quotation_details.task_id', 'asc')
                ->get();
            $TaskId = array_column(json_decode($task_id), 'id');
            $total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->whereIn('quotation_details.task_id', $TaskId);
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
            }
            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereMonth('quotation_details.finalize_date', $month)
                    ->whereYear('quotation_details.finalize_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total = $total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }
            $total = $total->where('quotation.company', session('company_id'))
                ->where('quotation_details.finalize', 'yes')
                ->sum('quotation_details.amount');

            foreach ($task_id as $row) {
                $row->quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes');
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereMonth('quotation_details.finalize_date', $month)
                        ->whereYear('quotation_details.finalize_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $row->quotation_list = $row->quotation_list->where('quotation.company', session('company_id'))
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $row->grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes');
                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                    }
                }
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereMonth('quotation_details.finalize_date', $month)
                        ->whereYear('quotation_details.finalize_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $row->grand_total = $row->grand_total->where('quotation.company', session('company_id'))
                    ->sum('quotation_details.amount');

                foreach ($row->quotation_list as $quot) {
                    $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                    $quot->assign_to_name=DB::table('staff')->where('sid',$quot->assign_to)->value('name');
                    $quot->source_name=DB::table('source')->where('id',$quot->source)->value('source');
                    if($quot->assigned_at!='')
                    {
                        $quot->assigned_at=date('d-M-Y',strtotime($quot->assigned_at));
                    }
                }
            }

            return view('pages.reports.get_servicewise_quotation_finalized_report', compact('task_id', 'total', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function clientwise_quotation_finalized_excel(Request $request)
    {

        $clients = DB::table('quotation')
            ->join('clients', 'clients.id', 'quotation.client_id')
            ->select('quotation.client_id', 'clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source')
            ->where('quotation.company', session('default_company_id'))
            ->whereNotNull('quotation.client_id')
            ->distinct()
            ->orderBy('quotation.client_id', 'asc')
            ->get();
        $out1 = '';
        $export_data = "Clientwise Quotation Finalized Report - \n\n";
        foreach ($clients as $row) {
            $assign_to_name=DB::table('staff')->where('sid',$row->assign_to)->value('name');
            $source_name=DB::table('source')->where('id',$row->source)->value('source');
            $assigned_at="";
            if($row->assigned_at!='')
            {
                $assigned_at=date('d-M-Y',strtotime($row->assigned_at));
            }
            $quotations = DB::table('quotation')
                ->select('id')
                ->where('client_id', $row->client_id)
                ->where('company', session('company_id'))
                ->orderBy('id', 'asc')
                ->get();

            $quot_id = array();
            foreach ($quotations as $val) {
                $quot_id[] = $val->id;
            }

            $quotation_list = DB::table('quotation_details')
                ->join('services', 'services.id', 'quotation_details.task_id')
                ->select('services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.quotation_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                ->whereIn('quotation_details.quotation_id', $quot_id)
                ->where('quotation_details.finalize', 'yes')
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation_details')
                ->whereIn('quotation_id', $quot_id)
                ->where('finalize', 'yes')
                ->sum('amount');

            if ($quotation_list != '[]') {
                $i = 1;
                $export_data .= "Client - " . $row->case_no . "(" . $row->client_name . ")\tAssign to-".$assign_to_name."\tAssign Dt-".$assigned_at."\tSource-".$source_name."\n";
                $export_data .= "\n";
                $export_data .= "Sr. No.\tService\tNo of Units\tAmount/Unit\tTotal Amt\tSend Date\tFinalized Date\n";

                foreach ($quotation_list as $quot) {
                    $quot->send_date = DB::table('quotation')->where('id', $quot->quotation_id)->value('send_date');
                    $lineData = array($i++, $quot->service_name, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  date('d-M-Y', strtotime($quot->send_date)), date('d-M-Y', strtotime($quot->finalize_date)));
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
                $export_data .= "\t\t\tGrand Total\t" . AppHelper::moneyFormatIndia($grand_total) . "\n";
                $export_data .= "\n";
                $export_data .= "\n";
            }
        }
        $out1 .= $export_data;


        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Clientwise_Quotation_Finalized_Report.xls\"");
    }

    public function clientwise_quotation_finalized_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

            $clients = DB::table('quotation')
                ->join('clients', 'clients.id', 'quotation.client_id')
                ->select('quotation.client_id', 'clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'clients.created_at')
                ->where('quotation.company', session('default_company_id'))
                ->whereNotNull('quotation.client_id')
                ->distinct()
                ->orderBy('quotation.client_id', 'asc')
                ->get();

            foreach ($clients as $row) {
                $row->assign_to_name=DB::table('staff')->where('sid',$row->assign_to)->value('name');
                $row->source_name=DB::table('source')->where('id',$row->source)->value('source');
               
                if($row->assigned_at!='')
                {
                    $row->assigned_at=date('d-M-Y',strtotime($row->assigned_at));
                }
                $quotations = DB::table('quotation')
                    ->select('id')
                    ->where('client_id', $row->client_id)
                    ->where('company', session('company_id'))
                    ->orderBy('id', 'asc')
                    ->get();

                $quot_id = array_column(json_decode($quotations), 'id');

                $row->quotation_list = DB::table('quotation_details')
                    ->join('services', 'services.id', 'quotation_details.task_id')
                    ->select('services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.quotation_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->whereIn('quotation_details.quotation_id', $quot_id)
                    ->where('quotation_details.finalize', 'yes')
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $row->grand_total = DB::table('quotation_details')
                    ->whereIn(
                        'quotation_id',
                        $quot_id
                    )
                    ->where('finalize', 'yes')
                    ->sum('amount');

                foreach ($row->quotation_list as $quot) {
                    $quot->send_date = DB::table('quotation')->where('id', $quot->quotation_id)->value('send_date');
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->simpleTables = true;
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_clientwise_quotation_finalized_report', compact('clients')));
            return ($mpdf->Output('Clientwise_Quotation_Finalized_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function clientwise_quotation_finalized_print()
    {
        try {
            $clients = DB::table('quotation')
                ->join('clients', 'clients.id', 'quotation.client_id')
                ->select('quotation.client_id', 'clients.client_name', 'clients.case_no','clients.assign_to','clients.assigned_at','clients.source', 'clients.created_at')
                ->where('quotation.company', session('default_company_id'))
                ->whereNotNull('quotation.client_id')
                ->distinct()
                ->orderBy('quotation.client_id', 'asc')
                ->get();

            foreach ($clients as $row) {
                $row->assign_to_name=DB::table('staff')->where('sid',$row->assign_to)->value('name');
                
                $row->source_name=DB::table('source')->where('id',$row->source)->value('source');
               
                if($row->assigned_at!='')
                {
                    $row->assigned_at=date('d-M-Y',strtotime($row->assigned_at));
                }
                $quotations = DB::table('quotation')
                    ->select('id')
                    ->where('client_id', $row->client_id)
                    ->where('company', session('company_id'))
                    ->orderBy('id', 'asc')
                    ->get();

                $quot_id = array_column(json_decode($quotations), 'id');

                $row->quotation_list = DB::table('quotation_details')
                    ->join('services', 'services.id', 'quotation_details.task_id')
                    ->select('services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.quotation_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->whereIn('quotation_details.quotation_id', $quot_id)
                    ->where('quotation_details.finalize', 'yes')
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $row->grand_total = DB::table('quotation_details')
                    ->whereIn('quotation_id', $quot_id)
                    ->where('finalize', 'yes')
                    ->sum('amount');

                foreach ($row->quotation_list as $quot) {
                    $quot->send_date = DB::table('quotation')->where('id', $quot->quotation_id)->value('send_date');
                }
            }

            return view('pages.reports.get_clientwise_quotation_finalized_report', compact('clients'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
