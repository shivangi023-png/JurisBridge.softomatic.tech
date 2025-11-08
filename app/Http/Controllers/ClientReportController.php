<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExpenseTraits;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientReportController extends Controller
{
    use ExpenseTraits;
    use StaffTraits;

    public function all_clients_excel(Request $request)
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

        $client_list = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->join('city', 'city.id', 'clients.city')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
            ->where('client_company_mapping.company', session('company_id'))
            ->whereBetween('clients.date', $filter)
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'client')
            ->orderBy('clients.case_no', 'asc')
            ->get();


        if ($client_list != '[]') {
            $i = 1;
            $export_data = "All Clients Report -\n\n";
            $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
            foreach ($client_list as $row) {
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


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"All_Clients_Report.xls\"");
    }

    public function all_clients_pdf(Request $request)
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

            $client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->orderBy('clients.case_no', 'asc')
                ->get();



            foreach ($client_list as $row) {
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
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_all_clients_report', compact('client_list', 'FilterDate')));

            return ($mpdf->Output('All_Client_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function all_clients_print(Request $request)
    {
        try {
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
            $client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->orderBy('clients.case_no', 'asc')
                ->get();



            foreach ($client_list as $row) {
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

            return view('pages.reports.get_all_clients_report', compact('client_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }
    public function all_leads_excel(Request $request)
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


        $client_list = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->join('city', 'city.id', 'clients.city')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
            ->where('client_company_mapping.company', session('company_id'))
            ->whereBetween('clients.date', $filter)
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'leads')
            ->orderBy('clients.case_no', 'asc')
            ->get();


        if ($client_list != '[]') {
            $i = 1;
            $export_data = "All Leads Report -\n\n";
            $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
            foreach ($client_list as $row) {
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


        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"All_Leads_Report.xls\"");
    }

    public function all_leads_pdf(Request $request)
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
            $client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.case_no', 'asc')
                ->get();


            foreach ($client_list as $row) {
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
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_all_leads_report', compact('client_list', 'FilterDate')));

            return ($mpdf->Output('All_Leads_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function all_leads_print(Request $request)
    {
        try {
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


            $client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.case_no', 'asc')
                ->get();


            foreach ($client_list as $row) {
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


            return view('pages.reports.get_all_leads_report', compact('client_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }



    public function client_followup_excel(Request $request)
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
        $follow_up_list = DB::table('follow_up')
            ->join('clients', 'clients.id', 'follow_up.client_id')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'follow_up.followup_date')
            ->where('follow_up.company', session('company_id'))
            // ->where('clients.status', 'active')
            ->whereBetween('follow_up.followup_date', $filter)
            ->orderBy('followup_date', 'desc')
            // ->distinct()
            ->get();


        foreach ($follow_up_list as $row) {
            if ($row->followup_date != '' || $row->followup_date != null) {
                $row->followup_date = date('d-M-Y', strtotime($row->followup_date));
            }
            // $row->last_followup_date = DB::table('follow_up')->where('client_id', $row->id)->orderBy('followup_date', 'desc')->value('followup_date');
            $row->total_followup = DB::table('follow_up')->where('client_id', $row->id)->count();
        }

        $follow_up_list = json_decode($follow_up_list, true);
        // usort($follow_up_list, function ($a, $b) {

        //     return strtotime($a['last_followup_date']) - strtotime($b['last_followup_date']);
        // });


        $export_data = "Client Follow-Up Report -\n\n";
        if ($follow_up_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tClient Name\tCase No\tTotal Follow Up\tLast Follow Up Date\n";
            foreach ($follow_up_list as $row) {
                $client_name = $row['client_name'];
                $case_no = $row['case_no'];
                $total_followup = $row['total_followup'];
                $last_followup_date = date("d-M-Y", strtotime($row['followup_date']));

                $lineData = array($i++, $client_name, $case_no, $total_followup, $last_followup_date);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Client_FollowUp_Report.xls\"");
    }

    public function client_followup_pdf(Request $request)
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

            $follow_up_list = DB::table('follow_up')
                ->join('clients', 'clients.id', 'follow_up.client_id')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'follow_up.followup_date')
                ->where('follow_up.company', session('company_id'))
                // ->where('clients.status', 'active')
                ->whereBetween('follow_up.followup_date', $filter)
                ->orderBy('followup_date', 'desc')
                // ->distinct()
                ->get();

            if ($follow_up_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            foreach ($follow_up_list as $row) {
                if ($row->followup_date != '' || $row->followup_date != null) {
                    $row->followup_date = date('d-M-Y', strtotime($row->followup_date));
                }
                // $row->last_followup_date = DB::table('follow_up')->where('client_id', $row->id)->orderBy('followup_date', 'desc')->value('followup_date');
                $row->total_followup = DB::table('follow_up')->where('client_id', $row->id)->count();
            }
            $follow_up_list = json_decode($follow_up_list, true);
            // usort($follow_up_list, function ($a, $b) {

            //     return strtotime($a['last_followup_date']) - strtotime($b['last_followup_date']);
            // });
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_client_followup_report', compact('follow_up_list', 'FilterDate')));

            return ($mpdf->Output('Client_FollowUp_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_followup_print(Request $request)
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

            $follow_up_list = DB::table('follow_up')
                ->join('clients', 'clients.id', 'follow_up.client_id')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'follow_up.followup_date')
                ->where('follow_up.company', session('company_id'))
                // ->where('clients.status', 'active')
                ->whereBetween('follow_up.followup_date', $filter)
                ->orderBy('followup_date', 'desc')
                // ->distinct()
                ->get();


            foreach ($follow_up_list as $row) {
                if ($row->followup_date != '' || $row->followup_date != null) {
                    $row->followup_date = date('d-M-Y', strtotime($row->followup_date));
                }
                // $row->last_followup_date = DB::table('follow_up')->where('client_id', $row->id)->orderBy('followup_date', 'desc')->value('followup_date');
                $row->total_followup = DB::table('follow_up')->where('client_id', $row->id)->count();
            }

            $follow_up_list = json_decode($follow_up_list, true);

            return view('pages.reports.get_client_followup_report', compact('follow_up_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_not_followup_excel(Request $request)
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

        $follow_up = DB::table('follow_up')
            ->select('client_id')
            ->distinct()
            ->orderBy('client_id', 'asc')
            ->get();

        $followupID = array();
        foreach ($follow_up as $val) {
            $followupID[] = $val->client_id;
        }

        $client = DB::table('clients')
            ->select('id')
            ->where('default_company', session('company_id'))
            ->orderBy('id', 'asc')
            ->get();

        $clientID = array();
        foreach ($client as $val1) {
            $clientID[] = $val1->id;
        }

        $result = array_diff($clientID, $followupID);

        $client_list = DB::table('clients')
            ->select('id', 'client_name', 'case_no')
            ->where('default_company', session('company_id'))
            ->whereBetween('date', $filter)
            ->whereIn('id', $result)
            ->get();


        $export_data = "Client Not Follow Up Report -\n\n";
        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tClient Name\tCase No\n";
            foreach ($client_list as $row) {

                $lineData = array($i++, $row->client_name, $row->case_no);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Client_Not_Followup_Report.xls\"");
    }

    public function client_not_followup_pdf(Request $request)
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


            $follow_up = DB::table('follow_up')
                ->select('client_id')
                ->distinct()
                ->orderBy('client_id', 'asc')
                ->get();

            $followupID = array();
            foreach ($follow_up as $val) {
                $followupID[] = $val->client_id;
            }

            $client = DB::table('clients')
                ->select('id')
                ->where('default_company', session('company_id'))
                ->orderBy('id', 'asc')
                ->get();

            $clientID = array();
            foreach ($client as $val1) {
                $clientID[] = $val1->id;
            }

            $result = array_diff(
                $clientID,
                $followupID
            );

            $follow_up_list = DB::table('clients')
                ->select(
                    'id',
                    'client_name',
                    'case_no'
                )
                ->where('default_company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->whereIn('id', $result)
                ->get();
            if ($follow_up_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_client_not_followup_report', compact('follow_up_list', 'FilterDate')));

            return ($mpdf->Output('Client_Not_Follow_Up_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_not_followup_print(Request $request)
    {
        try {
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



            $follow_up = DB::table('follow_up')
                ->select('client_id')
                ->distinct()
                ->orderBy('client_id', 'asc')
                ->get();

            $followupID = array();
            foreach ($follow_up as $val) {
                $followupID[] = $val->client_id;
            }

            $client = DB::table('clients')
                ->select('id')
                ->where('default_company', session('company_id'))
                ->orderBy('id', 'asc')
                ->get();

            $clientID = array();
            foreach ($client as $val1) {
                $clientID[] = $val1->id;
            }

            $result = array_diff($clientID, $followupID);

            $follow_up_list = DB::table('clients')
                ->select('id', 'client_name', 'case_no')
                ->where('default_company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->whereIn('id', $result)
                ->get();



            return view('pages.reports.get_client_not_followup_report', compact('follow_up_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_no_email_excel(Request $request)
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


        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR '|') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.name SEPARATOR '|') as `contact_name`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR '|') as `client_whatsapp`"), 'client_contacts.client_id', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->whereNull('client_contacts.email')
            ->whereBetween('clients.date', $filter)
            ->groupBY('client_contacts.client_id', 'clients.client_name', 'clients.case_no')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();


        $export_data = "Client With No Email Report -\n\n";
        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tClient Name\tCase No\tContact Name\tContact Number\tWhatsapp Number\n";
            foreach ($client_list as $row) {

                $lineData = array($i++, $row->client_name, $row->case_no, $row->contact_name, $row->client_contact, $row->client_whatsapp);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Client_With_No_Email_Report.xls\"");
    }

    public function client_no_email_pdf(Request $request)
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



            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->whereNull('client_contacts.email')
                ->whereBetween('clients.date', $filter)
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_client_no_email_report', compact('client_list', 'FilterDate')));

            return ($mpdf->Output('Client_With_No_Email_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_no_email_print(Request $request)
    {
        try {
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


            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->whereNull('client_contacts.email')
                ->whereBetween('clients.date', $filter)
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();


            return view('pages.reports.get_client_no_email_report', compact('client_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_no_contact_excel(Request $request)
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

        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR '|') as `client_email`"), DB::raw("GROUP_CONCAT(client_contacts.name SEPARATOR '|') as `contact_name`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR '|') as `client_whatsapp`"), 'client_contacts.client_id', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->where('client_contacts.contact', ' ')
            ->whereBetween('clients.date', $filter)
            ->groupBY('client_contacts.client_id', 'clients.client_name', 'clients.case_no')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();


        $export_data = "Client With No Contact Report -\n\n";
        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tClient Name\tCase No\tContact Name\tEmail\tWhatsapp Number\n";
            foreach ($client_list as $row) {

                $lineData = array($i++, $row->client_name, $row->case_no, $row->contact_name, $row->client_email, $row->client_whatsapp);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
        }

        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Client_With_No_Contact_Report.xls\"");
    }

    public function client_no_contact_pdf(Request $request)
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


            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR '<br>') as `client_whatsapp`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('client_contacts.contact', ' ')
                ->whereBetween('clients.date', $filter)
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_client_no_contact_report', compact('client_list', 'FilterDate')));

            return ($mpdf->Output('Client_With_No_Email_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function client_no_contact_print(Request $request)
    {
        try {
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



            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR '<br>') as `client_whatsapp`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('client_contacts.contact', ' ')
                ->whereBetween('clients.date', $filter)
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();

            return view('pages.reports.get_client_no_contact_report', compact('client_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function daily_sales_excel(Request $request)
    {

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
            ->where('users.role_id', 8)
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Daily Sales Report -\n\n";
        foreach ($staff as $row) {

            $client_list = DB::table('clients')
                ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('client_company_mapping.company', session('company_id'))
                ->whereDay('clients.created_at', '=', date('d'))
                ->whereMonth('clients.created_at', '=', date('m'))
                ->whereYear('clients.created_at', '=', date('Y'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->where('clients.created_by', $row->sid)
                ->orderBy('clients.case_no', 'asc')
                ->get();
            if ($client_list != '[]') {
                $i = 1;
                $export_data .= "Staff - (" . $row->name . "):\n";
                $export_data .= "\n";
                $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
                foreach ($client_list as $row1) {
                    $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                    $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                    $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                    $location = json_decode($row1->location);
                    if ($location != '') {

                        $row1->longitude = $location[0];
                        $row1->latitude = $location[1];
                    } else {
                        $row1->longitude = "";
                        $row1->latitude = "";
                    }

                    $lineData = array($i++, $row1->case_no, $row1->client_name, $row1->no_of_units, $row1->property_type_name, $row1->source_name, $row1->latitude, $row1->longitude, $row1->city_name, $row1->created_by_name, $row1->address);
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
            ->header("Content-Disposition", "attachment;filename=\"Leads_By_Sales_Report.xls\"");
    }

    public function daily_sales_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

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
                ->where('users.role_id', 8)
                ->whereIn('staff.sid', $staff_id)
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $row) {
                $row->client_list = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join(
                        'city',
                        'city.id',
                        'clients.city'
                    )
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('client_company_mapping.company', session('company_id'))
                    ->whereDay('clients.created_at', '=', date('d'))
                    ->whereMonth('clients.created_at', '=', date('m'))
                    ->whereYear('clients.created_at', '=', date('Y'))
                    ->where(
                        'clients.status',
                        'active'
                    )
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.created_by', $row->sid)
                    ->orderBy('clients.case_no', 'asc')
                    ->get();
                if ($row->client_list->isEmpty()) {
                    return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
                }
                if ($row->client_list != '[]') {
                    foreach ($row->client_list as $row1) {
                        $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                        $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                        $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                        $location = json_decode($row1->location);
                        if ($location != '') {

                            $row1->longitude = $location[0];
                            $row1->latitude = $location[1];
                        } else {
                            $row1->longitude = "";
                            $row1->latitude = "";
                        }
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_daily_sales_report', compact('staff')));

            return ($mpdf->Output('Daily_Sales_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function daily_sales_print(Request $request)
    {
        try {

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
                ->where('users.role_id', 8)
                ->whereIn('staff.sid', $staff_id)
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $row) {
                $row->client_list = DB::table('clients')
                    ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                    ->where('client_company_mapping.company', session('company_id'))
                    ->whereDay('clients.created_at', '=', date('d'))
                    ->whereMonth('clients.created_at', '=', date('m'))
                    ->whereYear('clients.created_at', '=', date('Y'))
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.created_by', $row->sid)
                    ->orderBy('clients.case_no', 'asc')
                    ->get();

                if ($row->client_list != '[]') {
                    foreach ($row->client_list as $row1) {
                        $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                        $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                        $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                        $location = json_decode($row1->location);
                        if ($location != '') {

                            $row1->longitude = $location[0];
                            $row1->latitude = $location[1];
                        } else {
                            $row1->longitude = "";
                            $row1->latitude = "";
                        }
                    }
                }
            }

            return view('pages.reports.get_daily_sales_report', compact('staff'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function assigned_leads_excel(Request $request)
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




        $staff1 = DB::table('staff')->get();
        $out1 = '';
        $export_data = "Assigned Leads Report -\n\n";
        foreach ($staff1 as $stf) {
            $company = json_decode($stf->company);
            for ($i = 0; $i < sizeof($company); $i++) {
                if ($company[$i] == session('company_id')) {
                    $staff_id = $stf->sid;
                    $client_list = DB::table('clients')
                        ->join('city', 'city.id', 'clients.city')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('clients.assigned_at', $filter)
                        ->where('clients.status', 'active')
                        ->where('clients.client_leads', 'leads')
                        ->where('clients.assign_to', $staff_id)
                        ->orderBy('clients.id', 'desc')
                        ->get();

                    if ($client_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n\n";
                        $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tCity\tCretaed By\tAssign To\tAssigned At\tAddress\n";
                        foreach ($client_list as $row) {
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
        }
        $out1 .= $export_data;


        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Assigned_leads_Report.xls\"");
    }

    public function assigned_leads_pdf(Request $request)
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
                $stf->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('clients.assigned_at', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('clients.assigned_at')
                    ->get();
                if ($stf->client_list->isEmpty()) {
                    return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
                }
                if ($stf->client_list != '[]') {
                    foreach ($stf->client_list as $row1) {
                        $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                        $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                        $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                        $row1->assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_assigned_leads_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Staffwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function assigned_leads_print(Request $request)
    {
        try {
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
            $out = '<style>
                body {
                    font-family: sans-serif;
                }
            
                table {
                    font-family: calibri;
                    font-size: 12px;
                }
            
                #logo {
                    float: right;
                    margin-bottom: 0px;
                    margin-top: 0px;
                }
            </style>
            <body>
              <img width="50px" id="logo" src="images/invoice_img/logo.png">
              <h4 style="text-align:center;">Assigned Leads Report ' . $FilterDate . '</h4>
            ';
            foreach ($staff as $stf) {


                $staff_id = $stf->sid;
                $query = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('clients.assigned_at', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.assign_to', $staff_id);
                $num = $query->count();
                $i = 1;
                $property_type_name = '';
                $staff_name = $stf->name;

                $query->orderBy('clients.assigned_at')->chunk($num, function ($data) use (&$out, $i, &$staff_name) {

                    if ($data != '') {
                        $out .= '<h4 style="text-align:left;">Staff - ' . $staff_name . '</h4> ';
                        $out .= '<table width="100%" border="1" cellspacing="0" cellpadding="3">
                            <tr>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Case No </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Lead</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">No of Units</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Properity Type</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">City </th>
                                
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assign To </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assigned Dt</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Address </th>
                            </tr>';
                        foreach ($data as $row1) {
                            if ($row1->assigned_at != "") {
                                $row1->assigned_at = date('d-M-Y', strtotime($row1->assigned_at));
                            }
                            $property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                            $source_name = DB::table('source')->where('id', $row1->source)->value('source');
                            $created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                            $assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
                            $out .= ' <tr>
                               <td style="text-align:right;width:5%;">' . $i++ . '</td>
                               <td style="width:10%;">' . $row1->case_no . '</td>
                               <td style="width:10%;">' . $row1->client_name . '</td>
                               <td style="text-align:center;width:5%;">' . $row1->no_of_units . '</td>
                               <td style="width:10%;">' . $property_type_name . '</td>
                               <td style="text-align:center;width:10%;">';
                            if ($source_name == 'Facebook') {
                                $out .= '<img src="' . asset('images/source_icons/facebook.png') . '" alt="Facebook">';
                            } else if ($source_name == 'Whatsapp group') {
                                $out .= '<img src="' . asset('images/source_icons/whatsApp-group.png') . '" alt="Whatsapp group">';
                            } else if ($source_name == 'Active Sales') {
                                $out .= '<img src="' . asset('images/source_icons/active-sales.png') . '" alt="Active Sales">';
                            } else if ($source_name == 'Client ref') {
                                $out .= '<img src="' . asset('images/source_icons/client-ref.png') . '" alt="Client ref">';
                            } else if ($source_name == 'Newspaper') {
                                $out .= '<img src="' . asset('images/source_icons/newspaper.png') . '" alt="Newspaper">';
                            } else if ($source_name == 'Franchise') {
                                $out .= '<img src="' . asset('images/source_icons/franchise.png') . '" alt="Franchise">';
                            } else if ($source_name == 'LinkedIn') {
                                $out .= '<img src="' . asset('images/source_icons/linkedin.png') . '" alt="LinkedIn">';
                            } else if ($source_name == 'Quora') {
                                $out .= '<img src="' . asset('images/source_icons/quora.png') . '" alt="Quora">';
                            } else if ($source_name == 'YouTube') {
                                $out .= '<img src="' . asset('images/source_icons/youtube.png') . '" alt="YouTube">';
                            } else if ($source_name == 'Google ads') {
                                $out .= '<img src="' . asset('images/source_icons/googleAds.png') . '" alt="Google ads">';
                            } else if ($source_name == 'Walk-in') {
                                $out .= '<img src="' . asset('images/source_icons/walk-in.png') . '" alt="Walk-in">';
                            }
                            $out .= '</td>
                               <td style="width:10%;">' . $row1->city_name . '</td>
                              
                               <td style="width:10%;">' . $assign_to_name . '</td>
                               <td style="width:10%;">' . $row1->assigned_at . '</td>
                               <td style="width:10%;">' . $row1->address . '</td>
                           </tr>';
                        }
                    }
                });


                $out .= '</table>';
            }
            $out .= '</body>';

            return $out;;
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function unassigned_leads_pdf(Request $request)
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



            $client_list = DB::table('clients')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('clients.default_company', session('company_id'))
                ->whereBetween('clients.date', $filter)
                ->whereNull('clients.assign_to')
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.id', 'asc')
                ->get();

            foreach ($client_list as $row) {
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
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_unassigned_leads_report', compact('client_list', 'FilterDate')));

            return ($mpdf->Output('Unassigned_Leads_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function unassigned_leads_excel(Request $request)
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


        $client_list = DB::table('clients')
            ->join('city', 'city.id', 'clients.city')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
            ->where('clients.default_company', session('company_id'))
            ->whereBetween('clients.date', $filter)
            ->where('clients.status', 'active')
            ->whereNull('clients.assign_to')
            ->where('clients.client_leads', 'leads')
            ->orderBy('clients.id', 'asc')
            ->get();


        $out1 = '';
        $export_data = "Unassigned Leads Report -\n\n";

        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperity Type\tSource\tLatitude\tLongitude\tCity\tCretaed By\tAddress\n";
            foreach ($client_list as $row) {
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
        $out1 .= $export_data;

        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"unassigned_leads_report.xls\"");
    }

    public function unassigned_leads_print(Request $request)
    {
        try {
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


            $client_list = DB::table('clients')
                ->join('city', 'city.id', 'clients.city')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.created_at')
                ->where('clients.default_company', session('company_id'))
                ->whereBetween('clients.created_at', [$start_year, $end_year])
                ->whereNull('clients.assign_to')
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->orderBy('clients.id', 'asc')
                ->get();

            foreach ($client_list as $row) {
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

            return view('pages.reports.get_unassigned_leads_report', compact('client_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_lead_contacts_excel(Request $request)
    {
        $out = '';
        $export_data = "Company-wise Lead Contacts Report -\n\n";
        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'leads')
            ->groupBY('client_contacts.client_id')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();

        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tCase No\tClient Name\tContact Name\tContact Number\tWhatsapp Number\tEmail Id\n";
            foreach ($client_list as $row) {
                $lineData = array($i++, $row->case_no, $row->client_name, $row->contact_name, $row->client_contact, $row->client_whatsapp, $row->client_email);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\n";
        }
        $out .= $export_data;


        return response($out)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Companywise_Lead_Contacts_Report.xls\"");
    }

    public function companywise_lead_contacts_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_companywise_lead_contacts_report', compact('client_list')));
            return ($mpdf->Output('Get_Companywise_Lead_Contacts_report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_lead_contacts_print(Request $request)
    {
        try {

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();


            return view('pages.reports.get_companywise_lead_contacts_report', compact('client_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_client_contacts_excel(Request $request)
    {
        $out = '';
        $export_data = "Company-wise Lead Contacts Report -\n\n";
        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'client')
            ->groupBY('client_contacts.client_id')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();

        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tCase No\tClient Name\tContact Name\tContact Number\tWhatsapp Number\tEmail Id\n";
            foreach ($client_list as $row) {
                $lineData = array($i++, $row->case_no, $row->client_name, $row->contact_name, $row->client_contact, $row->client_whatsapp, $row->client_email);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\n";
        }
        $out .= $export_data;


        return response($out)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Companywise_client_Contacts_Report.xls\"");
    }

    public function companywise_client_contacts_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_companywise_client_contacts_report', compact('client_list')));

            return ($mpdf->Output('Get_Companywise_Client_Contacts_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_client_contacts_print(Request $request)
    {
        try {

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();


            return view('pages.reports.get_companywise_client_contacts_report', compact('client_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }


    public function leads_services_excel(Request $request)
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




        $staff1 = DB::table('staff')->get();
        $out1 = '';
        $export_data = "Leads Services Report -\n\n";
        foreach ($staff1 as $stf) {
            $company = json_decode($stf->company);
            for ($i = 0; $i < sizeof($company); $i++) {
                if ($company[$i] == session('company_id')) {
                    $staff_id = $stf->sid;
                    $client_list = DB::table('clients')
                        ->join('city', 'city.id', 'clients.city')
                        ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                        ->where('clients.default_company', session('company_id'))
                        ->whereBetween('clients.assigned_at', $filter)
                        ->where('clients.status', 'active')
                        ->where('clients.client_leads', 'leads')
                        ->where('clients.assign_to', $staff_id)
                        ->orderBy('clients.id', 'desc')
                        ->get();

                    if ($client_list != '[]') {
                        $i = 1;
                        $export_data .= "Staff - (" . $stf->name . "):\n\n";
                        $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tServices\tProperity Type\tSource\tAssign To\tAssigned At\tAddress\n";
                        foreach ($client_list as $row) {
                            $services_id = DB::table('quotation')
                                ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')->where('quotation.client_id', $row->id)->distinct()->get(['task_id']);
                            $services = '';

                            foreach ($services_id as $si) {
                                $service = DB::table('services')->where('id', $si->task_id)->value('name');
                                $services .= $service . ', ';
                            }
                            $services = rtrim($services, ',');
                            $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                            $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                            $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                            $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');

                            $lineData = array($i++, $row->case_no, $row->client_name, $row->no_of_units, $services, $row->property_type_name, $row->source_name, $row->assign_to_name, $row->assigned_at, $row->address);
                            $export_data .= implode("\t", array_values($lineData)) . "\n";
                        }
                    }
                }
            }
        }
        $out1 .= $export_data;


        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Assigned_leads_Report.xls\"");
    }

    public function leads_services_pdf(Request $request)
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
                $stf->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('clients.assigned_at', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('clients.assigned_at')
                    ->get();
                if ($stf->client_list->isEmpty()) {
                    return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
                }
                if ($stf->client_list != '[]') {
                    foreach ($stf->client_list as $row1) {
                        $services_id = DB::table('quotation')
                            ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')->where('quotation.client_id', $row1->id)->distinct()->get(['task_id']);
                        $services = '';

                        foreach ($services_id as $si) {
                            $service = DB::table('services')->where('id', $si->task_id)->value('name');
                            $services .= $service . ', ';
                        }
                        $row1->services = rtrim($services, ',');
                        $row1->property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                        $row1->source_name = DB::table('source')->where('id', $row1->source)->value('source');
                        $row1->created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                        $row1->assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_leads_services_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Staffwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function leads_services_print(Request $request)
    {
        try {
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
            $out = '<style>
                body {
                    font-family: sans-serif;
                }
            
                table {
                    font-family: calibri;
                    font-size: 12px;
                }
            
                #logo {
                    float: right;
                    margin-bottom: 0px;
                    margin-top: 0px;
                }
            </style>
            <body>
              <img width="50px" id="logo" src="images/invoice_img/logo.png">
              <h4 style="text-align:center;">Leads Services Report ' . $FilterDate . '</h4>
            ';
            foreach ($staff as $stf) {


                $staff_id = $stf->sid;
                $query = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at')
                    ->where('clients.default_company', session('company_id'))
                    ->whereBetween('clients.assigned_at', $filter)
                    ->where('clients.status', 'active')
                    ->where('clients.client_leads', 'leads')
                    ->where('clients.assign_to', $staff_id);
                $num = $query->count();
                $i = 1;
                $property_type_name = '';
                $staff_name = $stf->name;

                $query->orderBy('clients.assigned_at')->chunk($num, function ($data) use (&$out, $i, &$staff_name) {

                    if ($data != '') {
                        $out .= '<h4 style="text-align:left;">Staff - ' . $staff_name . '</h4> ';
                        $out .= '<table width="100%" border="1" cellspacing="0" cellpadding="3">
                            <tr>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Case No </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Lead</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">No of Units</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Services</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Properity Type</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">City </th>
                                
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assign To </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assigned Dt</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Address </th>
                            </tr>';
                        foreach ($data as $row1) {
                            if ($row1->assigned_at != "") {
                                $row1->assigned_at = date('d-M-Y', strtotime($row1->assigned_at));
                            }
                            $property_type_name = DB::table('property_type')->where('id', $row1->property_type)->value('type');
                            $source_name = DB::table('source')->where('id', $row1->source)->value('source');
                            $created_by_name = DB::table('staff')->where('sid', $row1->created_by)->value('name');
                            $assign_to_name = DB::table('staff')->where('sid', $row1->assign_to)->value('name');
                            $services_id = DB::table('quotation')
                                ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')->where('quotation.client_id', $row1->id)->distinct()->get(['task_id']);
                            $services = '';

                            foreach ($services_id as $si) {
                                $service = DB::table('services')->where('id', $si->task_id)->value('name');
                                $services .= $service . ', ';
                            }
                            $services = rtrim($services, ',');
                            $out .= ' <tr>
                               <td style="text-align:right;width:5%;">' . $i++ . '</td>
                               <td style="width:10%;">' . $row1->case_no . '</td>
                               <td style="width:10%;">' . $row1->client_name . '</td>
                               <td style="text-align:center;width:5%;">' . $row1->no_of_units . '</td>
                               <td style="text-align:center;width:5%;">' . $services . '</td>
                               <td style="width:10%;">' . $property_type_name . '</td>
                               <td style="text-align:center;width:10%;">';
                            if ($source_name == 'Facebook') {
                                $out .= '<img src="' . asset('images/source_icons/facebook.png') . '" alt="Facebook">';
                            } else if ($source_name == 'Whatsapp group') {
                                $out .= '<img src="' . asset('images/source_icons/whatsApp-group.png') . '" alt="Whatsapp group">';
                            } else if ($source_name == 'Active Sales') {
                                $out .= '<img src="' . asset('images/source_icons/active-sales.png') . '" alt="Active Sales">';
                            } else if ($source_name == 'Client ref') {
                                $out .= '<img src="' . asset('images/source_icons/client-ref.png') . '" alt="Client ref">';
                            } else if ($source_name == 'Newspaper') {
                                $out .= '<img src="' . asset('images/source_icons/newspaper.png') . '" alt="Newspaper">';
                            } else if ($source_name == 'Franchise') {
                                $out .= '<img src="' . asset('images/source_icons/franchise.png') . '" alt="Franchise">';
                            } else if ($source_name == 'LinkedIn') {
                                $out .= '<img src="' . asset('images/source_icons/linkedin.png') . '" alt="LinkedIn">';
                            } else if ($source_name == 'Quora') {
                                $out .= '<img src="' . asset('images/source_icons/quora.png') . '" alt="Quora">';
                            } else if ($source_name == 'YouTube') {
                                $out .= '<img src="' . asset('images/source_icons/youtube.png') . '" alt="YouTube">';
                            } else if ($source_name == 'Google ads') {
                                $out .= '<img src="' . asset('images/source_icons/googleAds.png') . '" alt="Google ads">';
                            } else if ($source_name == 'Walk-in') {
                                $out .= '<img src="' . asset('images/source_icons/walk-in.png') . '" alt="Walk-in">';
                            }
                            $out .= '</td>
                               <td style="width:10%;">' . $row1->city_name . '</td>
                              
                               <td style="width:10%;">' . $assign_to_name . '</td>
                               <td style="width:10%;">' . $row1->assigned_at . '</td>
                               <td style="width:10%;">' . $row1->address . '</td>
                           </tr>';
                        }
                    }
                });


                $out .= '</table>';
            }
            $out .= '</body>';

            return $out;;
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_lead_contacts_and_quotation_excel(Request $request)
    {
        $out = '';
        $export_data = "Company-wise Lead Contacts and Quotation Report -\n\n";
        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no',DB::raw('0 as total_quotations'))
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'leads')
            ->groupBY('client_contacts.client_id')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();

        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tCase No\tClient Name\tContact Name\tContact Number\tWhatsapp Number\tEmail Id\tQuotation\n";
            foreach ($client_list as $row) {
                $lineData = array($i++, $row->case_no, $row->client_name, $row->contact_name, $row->client_contact, $row->client_whatsapp, $row->client_email,$row->total_quotations);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\n";
        }
        $out .= $export_data;


        return response($out)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Companywise_Lead_Contacts_and_Quotation_Report.xls\"");
    }

    public function companywise_lead_contacts_and_quotation_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no',DB::raw('0 as total_quotations'))
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_companywise_lead_contacts_and_quotation_report', compact('client_list')));
            return ($mpdf->Output('Get_Companywise_Lead_Contacts_and_Quotation_report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_lead_contacts_and_quotation_print(Request $request)
    {
        try {

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no',DB::raw('0 as total_quotations'))
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'leads')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();


            return view('pages.reports.get_companywise_lead_contacts_and_quotation_report', compact('client_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_client_contacts_and_quotation_excel(Request $request)
    {
        $out = '';
        $export_data = "Company-wise Lead Contacts and Quotation Report -\n\n";
        $client_list = DB::table('client_contacts')
            ->join('clients', 'clients.id', 'client_contacts.client_id')
            ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' , ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' , ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' , ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
            ->where('clients.default_company', session('company_id'))
            ->where('clients.status', 'active')
            ->where('clients.client_leads', 'client')
            ->groupBY('client_contacts.client_id')
            ->orderBy('client_contacts.client_id', 'asc')
            ->get();

        if ($client_list != '[]') {
            $i = 1;
            $export_data .= "Sr. No.\tCase No\tClient Name\tContact Name\tContact Number\tWhatsapp Number\tEmail Id\tQuotation\n";
            foreach ($client_list as $row) {
                $row->total_quotations=DB::table('quotation')->where('client_id',$row->client_id)->count();
                $lineData = array($i++, $row->case_no, $row->client_name, $row->contact_name, $row->client_contact, $row->client_whatsapp, $row->client_email,$row->total_quotations);
                $export_data .= implode("\t", array_values($lineData)) . "\n";
            }
            $export_data .= "\n";
        }
        $out .= $export_data;


        return response($out)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Companywise_Client_Contacts_and_Quotation_Report.xls\"");
    }

    public function companywise_client_contacts_and_quotation_pdf(Request $request)
    {
        try {
            // new code for pdf
            require_once base_path('vendor/autoload.php');

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();
            if ($client_list->isEmpty()) {
                return redirect()->back()->with('alert-danger', 'No data available for the selected filter');
            }
            foreach ($client_list as $row) {
                $row->total_quotations=DB::table('quotation')->where('client_id',$row->client_id)->count();
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.get_companywise_client_contacts_and_quotation_report', compact('client_list')));

            return ($mpdf->Output('Get_Companywise_Client_Contacts_and_Quotation_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }

    public function companywise_client_contacts_and_quotation_print(Request $request)
    {
        try {

            $client_list = DB::table('client_contacts')
                ->join('clients', 'clients.id', 'client_contacts.client_id')
                ->select(DB::raw("GROUP_CONCAT(client_contacts.contact SEPARATOR ' \n ') as `client_contact`"), DB::raw("GROUP_CONCAT(client_contacts.whatsapp SEPARATOR ' \n ') as `client_whatsapp`"), DB::raw("GROUP_CONCAT(client_contacts.email SEPARATOR ' \n ') as `client_email`"), 'client_contacts.client_id', 'client_contacts.name as contact_name', 'clients.client_name', 'clients.case_no')
                ->where('clients.default_company', session('company_id'))
                ->where('clients.status', 'active')
                ->where('clients.client_leads', 'client')
                ->groupBY('client_contacts.client_id')
                ->orderBy('client_contacts.client_id', 'asc')
                ->get();

            foreach ($client_list as $row) {
                $row->total_quotations=DB::table('quotation')->where('client_id',$row->client_id)->count();
            }
            return view('pages.reports.get_companywise_client_contacts_and_quotation_report', compact('client_list'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Something went wrong , please contact to support team!');
        }
    }
}
