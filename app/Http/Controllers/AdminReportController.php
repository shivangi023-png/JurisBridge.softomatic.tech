<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppHelper;

class AdminReportController extends Controller
{

    use ExpenseTraits;
    use StaffTraits;

    public function admin_quotation_finalized_excel(Request $request)
    {
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


        $company = DB::table('company')->get();
        $out1 = '';
        $export_data = "Admin Quotation Finalize Report -\n\n";

        foreach ($company as $comp) {

            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
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
                ->where('quotation.company', $comp->id)
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get();

            $comp->quotation_list = $quotation_list;
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

            $comp->grand_total =
                $grand_total->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', $comp->id)
                ->sum('quotation_details.amount');
            if ($comp->quotation_list != '[]') {
                $i = 1;
                $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\t\t\t\tTotal Amount\t(" .  AppHelper::moneyFormatIndia($comp->grand_total) . ")\n";
                $export_data .= "\n";
                $export_data .= "Sr. No.\tClient\tAssign to\tAssigned dt\tSource\tFollow Up\tServices\tUnits\tAmount/Unit\tTotal Amt\tSend Dt\tFinalized Dt\n";
                foreach ($comp->quotation_list as $row) {
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    if ($row->assigned_at != '') {
                        $row->assigned_at = date('d-M-Y', strtotime($row->assigned_at));
                    }
                    $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                    $lineData = array($i++, $row->case_no, $row->client_name, $row->assign_to_name, $row->assigned_at, $row->total_followup, $row->service_name, $row->no_of_units, $row->units_per_amount, AppHelper::moneyFormatIndia($row->amount), $row->send_date, $row->finalize_date);
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
            ->header("Content-Disposition", "attachment;filename=\"Quotation_Finalized_Report.xls\"");
    }

    public function admin_quotation_finalized_pdf(Request $request)
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


            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
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
                    ->where('quotation.company', $comp->id)
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    //->groupBy('quotation.company', 'clients.assign_to')
                    ->get();

                $comp->quotation_list = $quotation_list;
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

                $comp->grand_total =
                    $grand_total->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', $comp->id)
                    ->sum('quotation_details.amount');

                foreach ($comp->quotation_list as $row) {
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    if ($row->assigned_at != '') {
                        $row->assigned_at = date('d-M-Y', strtotime($row->assigned_at));
                    }
                    $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                }
            }


            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_quotation_finalized_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function admin_quotation_finalized_print(Request $request)
    {
        try {
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {

                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
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
                    ->where('quotation.company', $comp->id)
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $comp->quotation_list = $quotation_list;
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

                $comp->grand_total =
                    $grand_total->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', $comp->id)
                    ->sum('quotation_details.amount');

                foreach ($comp->quotation_list as $row) {
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    if ($row->assigned_at != '') {
                        $row->assigned_at = date('d-M-Y', strtotime($row->assigned_at));
                    }
                    $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                }
            }
            return view('pages.reports.super_admin.get_quotation_finalized_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_servicewise_quotation_finalized_excel(Request $request)
    {
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

        $company = DB::table('company')->get();

        $out1 = '';
        $export_data = "Admin Servicewise Quotation Finalized Report -\n\n";

        foreach ($company as $comp) {
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
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                $total = $total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereMonth('quotation_details.finalize_date', $month)
                    ->whereYear('quotation_details.finalize_date', $curr_year);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $total = $total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }
            $total = $total->where('quotation.company', $comp->id)
                ->where('quotation_details.finalize', 'yes')
                ->sum('quotation_details.amount');

            $comp->total = $total;

            $comp->task_id = $task_id;
            foreach ($comp->task_id as $row) {
                $row->quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
                    ->where('quotation_details.task_id', $row->id)
                    ->where('quotation_details.finalize', 'yes');



                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
                }
                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->quotation_list = $row->quotation_list->whereMonth('quotation_details.finalize_date', $month)
                        ->whereYear('quotation_details.finalize_date', $curr_year);
                }
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $row->quotation_list = $row->quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $row->quotation_list = $row->quotation_list->where('quotation.company', $comp->id)
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
                    }

                    if (
                        $quarter_filter == 'First Quarter'
                    ) {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }

                    if (
                        $quarter_filter == 'Second Quarter'
                    ) {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }

                    if (
                        $quarter_filter == 'Third Quarter'
                    ) {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    }
                    $row->grand_total = $row->grand_total->whereBetween('quotation_details.finalize_date', [$start_quarter, $end_quarter]);
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
                $row->grand_total = $row->grand_total->where('quotation.company', $comp->id)
                    ->sum('quotation_details.amount');

                if ($row->quotation_list != '[]') {
                    $i = 1;
                    $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\tStaff -\t(" . $row->name . "):\t\t\t\tTotal Amount -\t(" .  AppHelper::moneyFormatIndia($comp->total) . ")\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tClient\tAssign_to\tAssigned At\tSource\tFollow Up\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";

                    foreach ($row->quotation_list as $quot) {
                        $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                        $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');
                        if ($quot->assigned_at != '') {
                            $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                        }
                        $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();

                        $lineData = array($i++, $quot->case_no . '(' . $quot->client_name . ')', $quot->assign_to_name, $quot->assigned_at, $quot->source_name, $quot->total_followup, $quot->no_of_units, $quot->units_per_amount, AppHelper::moneyFormatIndia($quot->amount),  $quot->finalize_date, date('d-M-Y', strtotime($quot->send_date)));
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\t\t\tGrand Total\t" . $row->grand_total;
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Servicewise_quotation_finalized_report.xls\"");
    }


    public function Admin_servicewise_quotation_finalized_pdf(Request $request)
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
            $company = DB::table('company')->get();

            foreach ($company as $comp) {
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
                $total = $total->where('quotation.company', $comp->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->sum('quotation_details.amount');

                $comp->total = $total;

                $comp->task_id = $task_id;
                foreach ($comp->task_id as $row) {
                    $row->quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
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
                    $row->quotation_list = $row->quotation_list->where('quotation.company', $comp->id)
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
                    $row->grand_total = $row->grand_total->where('quotation.company', $comp->id)
                        ->sum('quotation_details.amount');

                    foreach ($row->quotation_list as $quot) {
                        $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                        $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');
                        if ($quot->assigned_at != '') {
                            $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                        }
                        $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                    }
                }
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->simpleTables = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_servicewise_quotation_finalized_report', compact('company', 'FilterDate')));
            return ($mpdf->Output('Servicewise_Quotation_Finalized_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_servicewise_quotation_finalized_print(Request $request)
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
            $company = DB::table('company')->get();

            foreach ($company as $comp) {
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
                $total = $total->where('quotation.company', $comp->id)
                    ->where('quotation_details.finalize', 'yes')
                    ->sum('quotation_details.amount');

                $comp->total = $total;

                $comp->task_id = $task_id;
                foreach ($comp->task_id as $row) {
                    $row->quotation_list = DB::table('quotation')
                        ->join('clients', 'clients.id', '=', 'quotation.client_id')
                        ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                        ->join('services', 'services.id', '=', 'quotation_details.task_id')
                        ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.finalize_date',  'quotation_details.reference_no')
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
                    $row->quotation_list = $row->quotation_list->where('quotation.company', $comp->id)
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
                    $row->grand_total = $row->grand_total->where('quotation.company', $comp->id)
                        ->sum('quotation_details.amount');

                    foreach ($row->quotation_list as $quot) {
                        $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                        $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');
                        if ($quot->assigned_at != '') {
                            $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                        }
                        $quot->total_followup = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                    }
                }
            }
            return view('pages.reports.super_admin.get_servicewise_quotation_finalized_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_leads_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);


        $start_year = $year[0] . '-04-01';
        $end_year = $year[1] . '-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter = array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
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
        $company = DB::table('company')->get();
        $out1 = '';
        $export_data = "All Leads Report -\n\n";
        foreach ($company as $comp) {

            $comp->client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.case_no', 'asc')
                ->get();


            if ($comp->client_list != '[]') {
                $i = 1;
                $export_data .= "Company - (" . $comp->company_name . ")\n";
                $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }

                    $lineData = array($i++, $row->case_no, $row->client_name, $row->no_of_units, $row->property_type_name, $row->source_name, $row->latitude, $row->longitude, $row->city_name, $row->created_by_name, $row->address);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"All_Leads_Report.xls\"");
    }


    public function Admin_leads_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);


            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $comp->client_list = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('client_company_mapping.company', $comp->id)
                    ->whereBetween('clients.date', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->orderBy('clients.case_no', 'asc')
                    ->get();


                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_all_leads_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('All_Leads_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_leads_print(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);


            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {

                $comp->client_list = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('client_company_mapping.company', $comp->id)
                    ->whereBetween('clients.date', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->orderBy('clients.case_no', 'asc')
                    ->get();


                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }
                }
            }


            return view('pages.reports.super_admin.get_all_leads_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_assigned_leads_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_year = $year[0] . '-04-01';
        $end_year = $year[1] . '-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter = array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
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

        $company1 = DB::table('company')->get();
        $staff1 = DB::table('staff')->get();
        $out1 = '';
        $export_data = "Assigned Leads Report -\n\n";

        foreach ($company1 as $comp) {
            $StaffId = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == $comp->id) {
                        $StaffId[] = $stf->sid;
                    }
                }
            }

            $comp->staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $StaffId)
                ->orderBy('staff.sid', 'asc')
                ->get();
            foreach ($comp->staff as $stf) {
                $staff_id = $stf->sid;
                $stf->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                    ->where('clients.default_company', $comp->id)
                    ->whereBetween('clients.assigned_at', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('clients.id', 'desc')
                    ->get();

                if ($stf->client_list != '[]') {
                    $i = 1;
                    $export_data .= "Company - (" . $comp->company_name . "):\t\t\tStaff - \t(" .  $stf->name . ")\n";
                    $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tCity\tCretaed By\tAssign To\tAssigned At\tAddress\n";
                    foreach ($stf->client_list as $row) {
                        $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                        $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                        $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                        $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');

                        $lineData = array($i++, $row->case_no, $row->client_name, $row->no_of_units, $row->property_type_name, $row->source_name, $row->city_name, $row->created_by_name, $row->assign_to_name, $row->assigned_at, $row->address);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                }
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Assigned_leads_Report.xls\"");
    }


    public function Admin_assigned_leads_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);


            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company1 = DB::table('company')->get();
            $staff1 = DB::table('staff')->get();


            foreach ($company1 as $comp) {
                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == $comp->id) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $comp->staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                foreach ($comp->staff as $stf) {
                    $staff_id = $stf->sid;
                    $stf->client_list = DB::table('clients')
                        ->join('city', 'city.id', 'clients.city')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                        ->where('clients.default_company', $comp->id)
                        ->whereBetween('clients.assigned_at', $filter)
                        ->where('clients.status', 'active')
                        ->where('clients.client_leads', 'leads')
                        ->where('clients.assign_to', $staff_id)
                        ->orderBy('clients.assigned_at')
                        ->get();

                    if ($stf->client_list != '[]') {
                        foreach ($stf->client_list as $row1) {
                            $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                            $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                            $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                            $row1->assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
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
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_assigned_leads_report', compact('company1', 'FilterDate')));

            return ($mpdf->Output('Staffwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_assigned_leads_print(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);


            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }

            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company1 = DB::table('company')->get();
            $staff1 = DB::table('staff')->get();


            foreach ($company1 as $comp) {
                $StaffId = array();
                foreach ($staff1 as $stf) {
                    $company = json_decode($stf->company);
                    for ($i = 0; $i < sizeof($company); $i++) {
                        if ($company[$i] == $comp->id) {
                            $StaffId[] = $stf->sid;
                        }
                    }
                }

                $comp->staff = DB::table('staff')
                    ->join('users', 'users.user_id', 'staff.sid')
                    ->select('staff.sid', 'staff.name')
                    ->where('users.status', 'active')
                    ->whereIn('staff.sid', $StaffId)
                    ->orderBy('staff.sid', 'asc')
                    ->get();

                foreach ($comp->staff as $stf) {
                    $staff_id = $stf->sid;
                    $stf->client_list = DB::table('clients')
                        ->join('city', 'city.id', 'clients.city')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                        ->where('clients.default_company', $comp->id)
                        ->whereBetween('clients.assigned_at', $filter)
                        ->where('clients.status', 'active')
                        ->where('clients.client_leads', 'leads')
                        ->where('clients.assign_to', $staff_id)
                        ->orderBy('clients.assigned_at')
                        ->get();

                    if ($stf->client_list != '[]') {
                        foreach ($stf->client_list as $row1) {
                            $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                            $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                            $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                            $row1->assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
                        }
                    }
                }
            }

            return view('pages.reports.super_admin.get_assigned_leads_report', compact('company1', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_unassigned_leads_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));
        $year = explode('-', $year_filter);

        $start_year = $year[0] . '-04-01';
        $end_year = $year[1] . '-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter = array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
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

        $company = DB::table('company')->get();
        $out1 = '';
        $export_data = "Unassigned Leads Report -\n\n";
        foreach ($company as $comp) {
            $comp->client_list = DB::table('clients')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('clients.default_company', $comp->id)
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->whereNull('clients.assign_to')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.id', 'asc')
                ->get();


            if ($comp->client_list != '[]') {
                $i = 1;
                $export_data .= "Company - (" . $comp->company_name . ")\n";
                $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }

                    $lineData = array($i++, $row->case_no, $row->client_name, $row->no_of_units, $row->property_type_name, $row->source_name, $row->latitude, $row->longitude, $row->city_name, $row->created_by_name, $row->address);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Unassigned_leads_report.xls\"");
    }

    public function Admin_unassigned_leads_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $comp->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('clients.default_company', $comp->id)
                    ->whereBetween('clients.date', $filter)
                    ->whereNull('clients.assign_to')
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->orderBy('clients.id', 'asc')
                    ->get();

                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_unassigned_leads_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('unassigned_leads_report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_unassigned_leads_print(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            $start_year = $year[0] . '-04-01';
            $end_year = $year[1] . '-03-31';

            if ($month > 03) {
                $curr_year = $year[0];
            } else {
                $curr_year = $year[1];
            }
            $filter = array();
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                $FilterDate = $quarter_filter;
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $comp->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('clients.default_company', $comp->id)
                    ->whereBetween('clients.date', $filter)
                    ->whereNull('clients.assign_to')
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->orderBy('clients.id', 'asc')
                    ->get();

                foreach ($comp->client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $location = json_decode($row->location);
                    if ($location != '') {

                        $row->longitude = $location[0];
                        $row->latitude = $location[1];
                    } else {
                        $row->longitude = "";
                        $row->latitude = "";
                    }
                }
            }
            return view('pages.reports.super_admin.get_unassigned_leads_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_invoice_against_quotation_excel(Request $request)
    {
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

        $company = DB::table('company')->get();

        $out1 = '';
        $export_data = "Daily Invoice Against Quotation Report -\n\n";

        foreach ($company as $comp) {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('company', 'company.id', 'bill.company')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                ->where('bill.active', 'yes')
                ->where('bill.company', $comp->id)
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
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }
            $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')
                ->where('active', 'yes')
                ->where('company', $comp->id)
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
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
            }
            $comp->grand_total = $grand_total->sum('total_amount');
            if ($comp->invoice_list != '[]') {
                $i = 1;
                $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\t\tTotal Amount\t(" .  AppHelper::moneyFormatIndia($comp->grand_total) . ")\n";
                $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tClient\tDiscount\tTotal Amount\n";
                foreach ($comp->invoice_list as $inv) {
                    $client = $inv->case_no . '(' . $inv->client_name . ')';
                    if ($inv->bill_date != '') {
                        $inv->bill_date = date('d-M-Y', strtotime($inv->bill_date));
                    } else {
                        $inv->bill_date = '';
                    }
                    $inv->invoice_no = $inv->short_code . '-' . str_pad($inv->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($inv->bill_date));
                    $lineData = array($i++, $inv->invoice_no, $inv->bill_date, $client, $inv->discount, AppHelper::moneyFormatIndia($inv->total_amount));
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }

            $export_data .= "\n";
            $export_data .= "\n";
            $export_data .= "\n";
        }
        $out1 .= $export_data;
        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Invoices_Against_Quotation_Report.xls\"");
    }

    public function Admin_invoice_against_quotation_pdf(Request $request)
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->where('company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $comp->grand_total = $grand_total->sum('total_amount');
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_invoice_against_quotation_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('Invoices_Against_Quotation_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_invoice_against_quotation_print(Request $request)
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->where('company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $comp->grand_total = $grand_total->sum('total_amount');
            }

            return view('pages.reports.super_admin.get_invoice_against_quotation_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_additional_invoices_excel(Request $request)
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
        $company = DB::table('company')->get();

        $out1 = '';
        $export_data = "Additional Invoices Report -\n\n";

        foreach ($company as $comp) {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('company', 'company.id', 'bill.company')
                ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                ->where('bill.active', 'yes')
                ->where('bill.company', $comp->id)
                ->where('bill.quotation', 'null');

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year);
                    $end_date = strtotime('31-December-' . $year);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                $invoice_list == $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list == $invoice_list->whereYear('bill.bill_date', $curr_year)->whereMonth('bill.bill_date', $month);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list == $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }

            $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', $comp->id)->where('quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year);
                    $end_date = strtotime('31-December-' . $year);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total == $grand_total->whereYear('bill.bill_date', $curr_year)
                    ->whereMonth('bill.bill_date', $month);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total == $grand_total->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }

            $comp->grand_total = $grand_total->sum('total_amount');

            if ($comp->invoice_list != '[]') {
                $i = 1;
                $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\t\tTotal Amount\t(" .  AppHelper::moneyFormatIndia($comp->grand_total) . ")\n";
                $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tClient\tDiscount\tTotal Amount\n";
                foreach ($comp->invoice_list as $inv) {
                    $client = $inv->case_no . '(' . $inv->client_name . ')';
                    if ($inv->bill_date != '') {
                        $inv->bill_date = date('d-M-Y', strtotime($inv->bill_date));
                    } else {
                        $inv->bill_date = '';
                    }

                    $inv->invoice_no = $inv->short_code . '-' . str_pad($inv->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($inv->bill_date));

                    $lineData = array($i++, $inv->invoice_no, $inv->bill_date, $client, $inv->discount, AppHelper::moneyFormatIndia($inv->total_amount));
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }

            $export_data .= "\n";
            $export_data .= "\n";
            $export_data .= "\n";
        }
        $out1 .= $export_data;
        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Admin_additional_Invoices_Report.xls\"");
    }

    public function Admin_additional_invoices_pdf(Request $request)
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->where('company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $comp->grand_total = $grand_total->sum('total_amount');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_additional_invoices_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('Additional_Invoices_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_additional_invoices_print(Request $request)
    {
        try {
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount', 'company.short_code')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->where('company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $comp->grand_total = $grand_total->sum('total_amount');
            }
            return view('pages.reports.super_admin.get_additional_invoices_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_cancelled_invoice_excel(Request $request)
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

        $company = DB::table('company')->get();

        $out1 = '';
        $export_data = "Cancelled Invoice Report -\n\n";

        foreach ($company as $comp) {
            $invoice_list = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('company', 'company.id', 'bill.company')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name', 'company.short_code')
                ->where('bill.company', $comp->id)
                ->where('bill.active', 'no');


            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {

                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                $invoice_list == $invoice_list->whereBetween('bill.bill_date', [$start_quarter, $end_quarter]);
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list == $invoice_list->whereYear('bill.bill_date', $curr_year)->whereMonth('bill.bill_date', $month);
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $invoice_list == $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }

            $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                ->get();

            $grand_total = DB::table('bill')->where('active', 'yes')->where('company', $comp->id)->where('quotation', 'null');
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year);
                    $end_date = strtotime('31-December-' . $year);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                }
                $grand_total = $grand_total->whereBetween('bill_date', [$start_quarter, $end_quarter]);
            }
            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total == $grand_total->whereYear('bill.bill_date', $curr_year)
                    ->whereMonth('bill.bill_date', $month);
            }
            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $grand_total == $grand_total->whereBetween('bill.bill_date', [$start_year, $end_year]);
            }

            $comp->grand_total = $grand_total->sum('total_amount');


            if ($comp->invoice_list != '[]') {
                $a = 1;
                $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\t\tTotal Amount\t(" .  AppHelper::moneyFormatIndia($comp->grand_total) . ")\n";
                $export_data .= "Sr. No.\tInvoice No.\tClient Name\tService\tAmount\tInvoice Date\tDue Date\n";
                $comp->grand_total = 0;
                foreach ($comp->invoice_list as $row) {
                    $services_arr = json_decode($row->service);
                    $amount_arr = json_decode($row->amount);
                    $quotation_array = json_decode($row->quotation);
                    $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                    $row->payable = $row->total_amount - $paid_amt;
                    $comp->grand_total += $row->payable;

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

                    $row->invoice_no = $row->short_code . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));

                    $lineData = array($a++, $row->invoice_no, $client, $row->service, $amount, $invoice_date, $due_date);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;
        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Cancelled_Invoice_Report.xls\"");
    }

    public function Admin_cancelled_invoice_pdf(Request $request)
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
            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name', 'company.short_code')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();
                $comp->grand_total = 0;
                if ($comp->invoice_list != '[]') {
                    foreach ($comp->invoice_list as $row) {
                        $services_arr = json_decode($row->service);
                        $amount_arr = json_decode($row->amount);
                        $quotation_array = json_decode($row->quotation);
                        $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                        $row->payable = $row->total_amount - $paid_amt;
                        $comp->grand_total += $row->payable;
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
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            //$mpdf->AddPage('p', '', '', '', '', 5, 5, 10, 10, 10);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_cancelled_invoices_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('Cancelled_Invoice_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_cancelled_invoice_print(Request $request)
    {
        try {
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
            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->join('company', 'company.id', 'bill.company')
                    ->join('staff', 'staff.sid', 'bill.sign')
                    ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name', 'company.short_code')
                    ->where('bill.company', $comp->id)
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $comp->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->get();
                $comp->grand_total = 0;
                if ($comp->invoice_list != '[]') {
                    foreach ($comp->invoice_list as $row) {
                        $services_arr = json_decode($row->service);
                        $amount_arr = json_decode($row->amount);
                        $quotation_array = json_decode($row->quotation);
                        $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                        $row->payable = $row->total_amount - $paid_amt;
                        $comp->grand_total += $row->payable;
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
            }
            return view('pages.reports.super_admin.get_cancelled_invoices_report', compact('company', 'FilterDate'));

            return ($mpdf->Output('Cancelled_Invoice_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_consultation_fee_excel(Request $request)
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

        $out1 = '';
        $export_data = "Consultation Fee Report \n";

        $company = DB::table('company')->get();
        foreach ($company as $comp) {
            $comp->clients = DB::table('appointment')
                ->join('clients', 'clients.id', 'appointment.client')
                ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', $comp->id)
                ->distinct()
                ->orderBy('appointment.client', 'asc')
                ->get();

            $client_id = array_column(json_decode($comp->clients), 'client');

            $total_fees = DB::table('consulting_fee')
                ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                ->join('clients', 'clients.id', 'appointment.client')
                ->where('clients.default_company', $comp->id);
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
                    ->whereYear('appointment.meeting_date', $curr_year);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
            }
            $comp->total_fees = $total_fees->sum('consulting_fee.fees');

            if ($comp->total_fees > 0) {
                $export_data .= "Company - (" . $comp->company_name . "):\t\t\t\t\tTotal Amount\t(" . AppHelper::moneyFormatIndia($comp->total_fees) . ")\n";
            }
            foreach ($comp->clients as $val) {
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
                    ->where('clients.default_company', $comp->id);
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
                    ->where('clients.default_company', $comp->id);
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
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $val->grand_total = $val->grand_total->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $val->grand_total = $val->grand_total->sum('consulting_fee.fees');

                if ($val->consultation_fee_list != '[]') {
                    $i = 1;
                    $export_data .= "Client -" . $val->case_no . "(" . $val->client_name . "):\n";
                    $export_data .= "\n";
                    $export_data .= "Sr. No.\tReceipt No\tVisit Type\tFee\tMeeting Date\tMeeting Time\tAttanded By\n";
                    foreach ($val->consultation_fee_list as $row) {
                        $row->place_name = DB::table('appointment_places')->where('id', $row->place)->value('name');
                        $row->receipt_no = 'RC' . '-' . str_pad($row->consulting_fee_id, 5, '0', STR_PAD_LEFT) . '/' . date('Y');
                        $lineData = array($i++, $row->receipt_no, $row->place_name, AppHelper::moneyFormatIndia($row->fees), date("d-M-Y", strtotime($row->meeting_date)), $row->meeting_time, $row->meetname);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                    }
                    $export_data .= "\t\tGrand Total\t" . AppHelper::moneyFormatIndia($val->grand_total) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }
        }
        $out1 .= $export_data;
        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Consultation_Fee_Report.xls\"");
    }

    public function Admin_consultation_fee_pdf(Request $request)
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

            $company = DB::table('company')->get();

            foreach ($company as $comp) {
                $comp->clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('clients.default_company', $comp->id)
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array_column(json_decode($comp->clients), 'client');

                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('clients.default_company', $comp->id);
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
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $comp->total_fees = $total_fees->sum('consulting_fee.fees');
                foreach ($comp->clients as $val) {
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
                        ->where('clients.default_company', $comp->id);
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
                        ->where('clients.default_company', $comp->id);
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
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_consultation_fees_report', compact('company', 'FilterDate')));

            return ($mpdf->Output('Consultation_Fee_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_consultation_fee_print(Request $request)
    {
        try {
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

            $company = DB::table('company')->get();
            foreach ($company as $comp) {
                $comp->clients = DB::table('appointment')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->select('appointment.client', 'clients.client_name', 'clients.case_no')
                    ->where('clients.default_company', $comp->id)
                    ->distinct()
                    ->orderBy('appointment.client', 'asc')
                    ->get();

                $client_id = array_column(json_decode($comp->clients), 'client');

                $total_fees = DB::table('consulting_fee')
                    ->join('appointment', 'appointment.id', 'consulting_fee.appointment_id')
                    ->join('clients', 'clients.id', 'appointment.client')
                    ->where('clients.default_company', $comp->id);
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
                        ->whereYear('appointment.meeting_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $total_fees = $total_fees->whereBetween('appointment.meeting_date', [$start_year, $end_year]);
                }
                $comp->total_fees = $total_fees->sum('consulting_fee.fees');
                foreach ($comp->clients as $val) {
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
                        ->where('clients.default_company', $comp->id);
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
                        ->where('clients.default_company', $comp->id);
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
            }
            return view('pages.reports.super_admin.get_consultation_fees_report', compact('company', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_daily_visit_pdf(Request $request)
    {
        try {
            $daily_filter = $request->daily;
            // new code for pdf
            require_once base_path('vendor/autoload.php');

           
            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $stf->daily_leave = DB::table('leave_table')
                    ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                    ->select('leave_table.*', 'leave_type.type')
                    ->where('staff_id', $stf->sid)
                    ->where('leave_table.start_date','>=',$daily_filter)
                    ->where('leave_table.end_date','<=',$daily_filter)
                    ->get();

                $stf->office_visit = DB::table('office_visit')->leftJoin('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.*', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->where('visit_date', $daily_filter)->get();
                foreach ($stf->office_visit as $row) 
                    {
                        $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                    }
            }
            $style="<style>
            body, div {
                                 font-family: freeserif;
                                 font-size:16px;
                             }
                             li
                             {
                               font-family: freeserif;
                             }
                             
       </style>";
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">'.date('d-m-Y',strtotime($daily_filter)).'</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($style);
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_daily_visit_report', compact('staff','daily_filter')));

            return ($mpdf->Output('Additional_Invoices_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function Admin_daily_visit_excel(Request $request)
    {
        require_once base_path('vendor/autoload.php');
        $daily_filter = $request->daily;
        
       
        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
             ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Daily Visit/Leave Report -\n\n";

        foreach ($staff as $stf) {
            $stf->daily_leave = DB::table('leave_table')
                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                ->select(
                    'leave_table.*',
                    'leave_type.type'
                )
                ->where('staff_id', $stf->sid)
                ->where('leave_table.start_date','>=',$daily_filter)
                ->where('leave_table.end_date','<=',$daily_filter)
                ->get();

            $i = 1;
            if (sizeof($stf->daily_leave)) {
                foreach ($stf->daily_leave as $row) {
                    $lineData = array($i++, $stf->name, $row->type, 'on leave');
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                    $export_data .= "\n\n";
                }
            }

            $stf->office_visit = DB::table('office_visit')->leftJoin('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.*', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->where('visit_date', $daily_filter)->get();
            foreach ($stf->office_visit as $row) {
                $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
            }

            $i = 1;
            if (sizeof($stf->office_visit)) {
                foreach ($stf->office_visit as $row) {
                    $lineData = array($i++, $stf->name, $row->location, $row->discussion, $row->department_name, $row->client_name, $row->address, $row->created_at, 'on visit');
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                    $export_data .= "\n\n";
                }
            }
        }
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Daily_visit_Report.xls\"");
    }


    public function Admin_daily_visit_print(Request $request)
    {
        try {
            $daily_filter = $request->date;
            require_once base_path('vendor/autoload.php');

            $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->orderBy('staff.sid', 'asc')
            ->get();

        foreach ($staff as $stf) {
            $stf->daily_leave = DB::table('leave_table')
                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                ->select('leave_table.*', 'leave_type.type')
                ->where('staff_id', $stf->sid)
                ->where('leave_table.start_date','>=',$daily_filter)
                ->where('leave_table.end_date','<=',$daily_filter)
                ->get();


                $stf->office_visit = DB::table('office_visit')->leftJoin('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.*', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->where('visit_date', $daily_filter)->get();
                foreach ($stf->office_visit as $row) {
                    $row->client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
                }
            }
            
            return view('pages.reports.super_admin.get_daily_visit_report', compact('staff','daily_filter'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
    public function checkIn_staff_pdf(Request $request)
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

            $staff1 = DB::table('staff')->get();
            $staff_id = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $staff_id[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $staff_id)
                ->orderBy('staff.sid', 'asc')
                ->get();


            foreach ($staff as $stf) {
                $c = 1;
                if ($year_filter != 'none' && $daily_filter == 'none' && $month_filter == 'none' && $quarter_filter == 'none') {
                    $start_fiscal_year = strtotime('1-April-' . $year[0]);
                    $end_fiscal_year = strtotime('31-March-' . $year[1]);
                    $start_year = date('Y-m-d', $start_fiscal_year);
                    $end_year = date('Y-m-d', $end_fiscal_year);
                    $date_diff = strtotime($end_year) - strtotime($start_year);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_year) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();
                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }
                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }
                        $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                    }
                }

                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none' && $daily_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = !empty($start_date) ? date('Y-m-d', $start_date) : null;
                        $end_quarter = !empty($end_date) ?  date('Y-m-d', $end_date) : null;
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);

                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                }

                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
                    // log::info('-------staff id------ ' . $stf->sid);
                    $start_date = date('Y-m-d', strtotime($curr_year . '-' . $month . '-01'));
                    $end_date = date('Y-m-t', strtotime($curr_year . '-' . $month . '-01'));
                    $date_diff = strtotime($end_date) - strtotime($start_date);
                    $count_date = $date_diff / (3600 * 24);
                    $checkinArr = [];
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_date) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }
                        $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                    }
                }

                if ($daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none') {
                    $leave_type = 'NULL';
                    $signin_time = 'NULL';
                    $signout_time = 'NULL';
                    $location = $address = $department_name = 'NULL';
                    $leave_type = DB::table('leave_table')
                        ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                        ->select('leave_type.type')
                        ->where('leave_table.staff_id', $stf->sid)
                        ->where('leave_table.start_date', '<=', $daily_filter)
                        ->where('leave_table.end_date', '>=', $daily_filter)
                        ->value('leave_type.type');

                    $att_date = DB::table('attendance')
                        ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                        ->where('attendance.staff_id', $stf->sid)
                        ->where('attendance.signin_date', $daily_filter)
                        ->first();
                    $check_in = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                    if (!empty($check_in->location)) {
                        $location = implode(",", json_decode($check_in->location));
                    }
                    if (!empty($check_in->address)) {
                        $address = $check_in->address;
                    }
                    if (!empty($check_in->department_name)) {
                        $department_name = $check_in->department_name;
                    }

                    if (!empty($att_date->signin_time) || !empty($att_date->signout_time)) {
                        $signin_time = $att_date->signin_time;
                        $signout_time  = $att_date->signout_time;
                    }
                    if ($leave_type != '') {
                        $leave_type = $leave_type;
                    }
                    $date = $daily_filter;
                    $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time, 'signout_time' => $signout_time, 'leave_type' => $leave_type, 'department_name' => $department_name, 'location' => $location, 'address' => $address);
                }
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.super_admin.get_checkin_staff_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('CheckIn_Staff_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function checkIn_staff_excel(Request $request)
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


        $staff1 = DB::table('staff')->get();
        $staff_id = array();
        foreach ($staff1 as $stf) {
            $company = json_decode($stf->company);
            for ($i = 0; $i < sizeof($company); $i++) {
                if ($company[$i] == session('company_id')) {
                    $staff_id[] = $stf->sid;
                }
            }
        }

        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Daily CheckIn Staff Report -\n\n";
        foreach ($staff as $stf) {
            $c = 1;
            $export_data .= "staff - (" . $stf->name . ' - ' . $stf->sid . ")\n";
            $export_data .= "Sr. No.\tDate\tSignIn Time\tSignOut Time\tLeave\tDepartment\tLocation\tAddress\n";

            if ($year_filter != 'none' && $daily_filter == 'none' && $month_filter == 'none' && $quarter_filter == 'none') {
                $start_fiscal_year = strtotime('1-April-' . $year[0]);
                $end_fiscal_year = strtotime('31-March-' . $year[1]);
                $start_year = date('Y-m-d', $start_fiscal_year);
                $end_year = date('Y-m-d', $end_fiscal_year);
                $date_diff = strtotime($end_year) - strtotime($start_year);
                $count_date = $date_diff / (3600 * 24);
                for ($i = $count_date; $i >= 0; $i--) {
                    $loop_date_str = strtotime($start_year) + ($i * 3600 * 24);
                    $date = date('Y-m-d', $loop_date_str);
                    $att_date[$i] = DB::table('attendance')
                        ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                        ->where('attendance.staff_id', $stf->sid)
                        ->where('attendance.signin_date', $date)
                        ->first();
                    $leave_type[$i] = 'NULL';

                    $leave_type[$i] = DB::table('leave_table')
                        ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                        ->select('leave_type.type')
                        ->where('leave_table.staff_id', $stf->sid)
                        ->where('leave_table.start_date', '<=', $date)
                        ->where('leave_table.end_date', '>=', $date)
                        ->value('leave_type.type');

                    $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();
                    $signin_time[$i] = 'NULL';
                    $signout_time[$i] = 'NULL';
                    $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                    if (!empty($check_in[$i]->location)) {
                        $location[$i] = implode(",", json_decode($check_in[$i]->location));
                    }
                    if (!empty($check_in[$i]->address)) {
                        $address[$i] = $check_in[$i]->address;
                    }
                    if (!empty($check_in[$i]->department_name)) {
                        $department_name[$i] = $check_in[$i]->department_name;
                    }
                    if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                        $signin_time[$i] = $att_date[$i]->signin_time;
                        $signout_time[$i] = $att_date[$i]->signout_time;
                    }

                    $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }

            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none' && $daily_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = !empty($start_date) ? date('Y-m-d', $start_date) : null;
                    $end_quarter = !empty($end_date) ?  date('Y-m-d', $end_date) : null;
                    $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }

                        $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }

                        $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }

                        $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }

                        $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                        $export_data .= implode("\t", array_values($lineData)) . "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                        $export_data .= "\n";
                    }
                }
            }



            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
                // log::info('-------staff id------ ' . $stf->sid);
                $start_date = date('Y-m-d', strtotime($curr_year . '-' . $month . '-01'));
                $end_date = date('Y-m-t', strtotime($curr_year . '-' . $month . '-01'));
                $date_diff = strtotime($end_date) - strtotime($start_date);
                $count_date = $date_diff / (3600 * 24);
                for ($i = $count_date; $i >= 0; $i--) {
                    $loop_date_str = strtotime($start_date) + ($i * 3600 * 24);
                    $date = date('Y-m-d', $loop_date_str);
                    $att_date[$i] = DB::table('attendance')
                        ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                        ->where('attendance.staff_id', $stf->sid)
                        ->where('attendance.signin_date', $date)
                        ->first();
                    $leave_type[$i] = 'NULL';

                    $leave_type[$i] = DB::table('leave_table')
                        ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                        ->select('leave_type.type')
                        ->where('leave_table.staff_id', $stf->sid)
                        ->where('leave_table.start_date', '<=', $date)
                        ->where('leave_table.end_date', '>=', $date)
                        ->value('leave_type.type');

                    $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                    $signin_time[$i] = 'NULL';
                    $signout_time[$i] = 'NULL';
                    $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                    if (!empty($check_in[$i]->location)) {
                        $location[$i] = implode(",", json_decode($check_in[$i]->location));
                    }
                    if (!empty($check_in[$i]->address)) {
                        $address[$i] = $check_in[$i]->address;
                    }
                    if (!empty($check_in[$i]->department_name)) {
                        $department_name[$i] = $check_in[$i]->department_name;
                    }

                    if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                        $signin_time[$i] = $att_date[$i]->signin_time;
                        $signout_time[$i] = $att_date[$i]->signout_time;
                    }

                    $lineData = array($c++, $date, $signin_time[$i], $signout_time[$i], $leave_type[$i], $department_name[$i], $location[$i], $address[$i]);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                    $export_data .= "\n";
                }
            }

            if ($daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none') {
                $leave_type = 'NULL';
                $signin_time = 'NULL';
                $signout_time = 'NULL';
                $location = $address = $department_name = 'NULL';
                $leave_type = DB::table('leave_table')
                    ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                    ->select('leave_type.type')
                    ->where('leave_table.staff_id', $stf->sid)
                    ->where('leave_table.start_date', '<=', $daily_filter)
                    ->where('leave_table.end_date', '>=', $daily_filter)
                    ->value('leave_type.type');

                $att_date = DB::table('attendance')
                    ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                    ->where('attendance.staff_id', $stf->sid)
                    ->where('attendance.signin_date', $daily_filter)
                    ->first();
                $check_in = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                if (!empty($check_in->location)) {
                    $location = implode(",", json_decode($check_in->location));
                }
                if (!empty($check_in->address)) {
                    $address = $check_in->address;
                }
                if (!empty($check_in->department_name)) {
                    $department_name = $check_in->department_name;
                }

                if (!empty($att_date->signin_time) || !empty($att_date->signout_time)) {
                    $signin_time = $att_date->signin_time;
                    $signout_time = $att_date->signout_time;
                }
                if ($leave_type != '') {
                    $leave_type = $leave_type;
                }
                $lineData = array($c++, $daily_filter, $signin_time, $signout_time, $leave_type, $department_name, $location, $address);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
                $export_data .= "\n";
                $export_data .= "\n";
                $export_data .= "\n";
            }
        }

        $out1 .= $export_data;
        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"CheckIn_staff_report.xls\"");
    }

    public function checkIn_staff_print(Request $request)
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

            $staff1 = DB::table('staff')->get();
            $staff_id = array();
            foreach ($staff1 as $stf) {
                $company = json_decode($stf->company);
                for ($i = 0; $i < sizeof($company); $i++) {
                    if ($company[$i] == session('company_id')) {
                        $staff_id[] = $stf->sid;
                    }
                }
            }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->whereIn('staff.sid', $staff_id)
                ->orderBy('staff.sid', 'asc')
                ->get();


            foreach ($staff as $stf) {
                $c = 1;
                if ($year_filter != 'none' && $daily_filter == 'none' && $month_filter == 'none' && $quarter_filter == 'none') {
                    $start_fiscal_year = strtotime('1-April-' . $year[0]);
                    $end_fiscal_year = strtotime('31-March-' . $year[1]);
                    $start_year = date('Y-m-d', $start_fiscal_year);
                    $end_year = date('Y-m-d', $end_fiscal_year);
                    $date_diff = strtotime($end_year) - strtotime($start_year);
                    $count_date = $date_diff / (3600 * 24);
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_year) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();
                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }
                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }
                        $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                    }
                }

                if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none' && $daily_filter == 'none') {
                    if ($quarter_filter == 'Fourth Quarter') {
                        $start_date = strtotime('1-January-' . $year[1]);
                        $end_date = strtotime('31-March-' . $year[1]);
                        $start_quarter = !empty($start_date) ? date('Y-m-d', $start_date) : null;
                        $end_quarter = !empty($end_date) ?  date('Y-m-d', $end_date) : null;
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);

                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'First Quarter') {
                        $start_date = strtotime('1-April-' . $year[0]);
                        $end_date = strtotime('30-June-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'Second Quarter') {
                        $start_date = strtotime('1-July-' . $year[0]);
                        $end_date = strtotime('30-September-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                    if ($quarter_filter == 'Third Quarter') {
                        $start_date = strtotime('1-October-' . $year[0]);
                        $end_date = strtotime('31-December-' . $year[0]);
                        $start_quarter = date('Y-m-d 00:00:00', $start_date);
                        $end_quarter = date('Y-m-d 23:59:59', $end_date);
                        $date_diff = strtotime($end_quarter) - strtotime($start_quarter);
                        $count_date = $date_diff / (3600 * 24);
                        for ($i = $count_date; $i >= 0; $i--) {
                            $loop_date_str = strtotime($start_quarter) + ($i * 3600 * 24);
                            $date = date('Y-m-d', $loop_date_str);
                            $att_date[$i] = DB::table('attendance')
                                ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                                ->where('attendance.staff_id', $stf->sid)
                                ->where('attendance.signin_date', $date)
                                ->first();
                            $leave_type[$i] = 'NULL';

                            $leave_type[$i] = DB::table('leave_table')
                                ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                                ->select('leave_type.type')
                                ->where('leave_table.staff_id', $stf->sid)
                                ->where('leave_table.start_date', '<=', $date)
                                ->where('leave_table.end_date', '>=', $date)
                                ->value('leave_type.type');

                            $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                            $signin_time[$i] = 'NULL';
                            $signout_time[$i] = 'NULL';
                            $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                            if (!empty($check_in[$i]->location)) {
                                $location[$i] = implode(",", json_decode($check_in[$i]->location));
                            }
                            if (!empty($check_in[$i]->address)) {
                                $address[$i] = $check_in[$i]->address;
                            }
                            if (!empty($check_in[$i]->department_name)) {
                                $department_name[$i] = $check_in[$i]->department_name;
                            }

                            if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                                $signin_time[$i] = $att_date[$i]->signin_time;
                                $signout_time[$i] = $att_date[$i]->signout_time;
                            }
                            $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                        }
                    }
                }

                if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none' && $daily_filter == 'none') {
                    // log::info('-------staff id------ ' . $stf->sid);
                    $start_date = date('Y-m-d', strtotime($curr_year . '-' . $month . '-01'));
                    $end_date = date('Y-m-t', strtotime($curr_year . '-' . $month . '-01'));
                   
                    $count_date = cal_days_in_month(CAL_GREGORIAN,$month,$curr_year);;
                    
                    $checkinArr =array();$leave_type=array();$check_in=array();$signin_time=array();$signout_time=array();$location=array();$address=array();$department_name=array();
                    for ($i = $count_date; $i >= 0; $i--) {
                        $loop_date_str = strtotime($start_date) + ($i * 3600 * 24);
                        $date = date('Y-m-d', $loop_date_str);
                        $att_date[$i] = DB::table('attendance')
                            ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                            ->where('attendance.staff_id', $stf->sid)
                            ->where('attendance.signin_date', $date)
                            ->first();
                        $leave_type[$i] = 'NULL';

                        $leave_type[$i] = DB::table('leave_table')
                            ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                            ->select('leave_type.type')
                            ->where('leave_table.staff_id', $stf->sid)
                            ->where('leave_table.start_date', '<=', $date)
                            ->where('leave_table.end_date', '>=', $date)
                            ->value('leave_type.type');

                        $check_in[$i] = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->where('visit_date',$date)->first();
                        
                        $signin_time[$i] = 'NULL';
                        $signout_time[$i] = 'NULL';
                        $location[$i] = $address[$i] = $department_name[$i] = 'NULL';
                        if (!empty($check_in[$i]->location)) {
                            $location[$i] = implode(",", json_decode($check_in[$i]->location));
                        }
                        if (!empty($check_in[$i]->address)) {
                            $address[$i] = $check_in[$i]->address;
                        }
                        if (!empty($check_in[$i]->department_name)) {
                            $department_name[$i] = $check_in[$i]->department_name;
                        }

                        if (!empty($att_date[$i]->signin_time) || !empty($att_date[$i]->signout_time)) {
                            $signin_time[$i] = $att_date[$i]->signin_time;
                            $signout_time[$i] = $att_date[$i]->signout_time;
                        }
                        $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time[$i], 'signout_time' => $signout_time[$i], 'leave_type' => $leave_type[$i], 'department_name' => $department_name[$i], 'location' => $location[$i], 'address' => $address[$i]);
                    }
                }

                if ($daily_filter != 'none' && $month_filter == 'none' && $year_filter == 'none' && $quarter_filter == 'none') {
                    $leave_type = 'NULL';
                    $signin_time = 'NULL';
                    $signout_time = 'NULL';
                    $location = $address = $department_name = 'NULL';
                    $leave_type = DB::table('leave_table')
                        ->join('leave_type', 'leave_type.id', 'leave_table.leave_id')
                        ->select('leave_type.type')
                        ->where('leave_table.staff_id', $stf->sid)
                        ->where('leave_table.start_date', '<=', $daily_filter)
                        ->where('leave_table.end_date', '>=', $daily_filter)
                        ->value('leave_type.type');

                    $att_date = DB::table('attendance')
                        ->select('attendance.signin_date', 'attendance.signin_time', 'attendance.signout_time')
                        ->where('attendance.staff_id', $stf->sid)
                        ->where('attendance.signin_date', $daily_filter)
                        ->first();
                    $check_in = DB::table('office_visit')->join('dept_address', 'dept_address.id', 'office_visit.dept_address_id')->select('office_visit.location', 'dept_address.address', 'dept_address.department_name')->where('visit_by', $stf->sid)->first();

                    if (!empty($check_in->location)) {
                        $location = implode(",", json_decode($check_in->location));
                    }
                    if (!empty($check_in->address)) {
                        $address = $check_in->address;
                    }
                    if (!empty($check_in->department_name)) {
                        $department_name = $check_in->department_name;
                    }

                    if (!empty($att_date->signin_time) || !empty($att_date->signout_time)) {
                        $signin_time = $att_date->signin_time;
                        $signout_time  = $att_date->signout_time;
                    }
                    if ($leave_type != '') {
                        $leave_type = $leave_type;
                    }
                    $date = $daily_filter;
                    $stf->checkin[] = array('date' => $date, 'signin_time' => $signin_time, 'signout_time' => $signout_time, 'leave_type' => $leave_type, 'department_name' => $department_name, 'location' => $location, 'address' => $address);
                }
            }

            return view('pages.reports.super_admin.get_checkin_staff_report', compact('FilterDate', 'staff'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
