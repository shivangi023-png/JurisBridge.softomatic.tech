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

class SalesReportController extends Controller
{

    use ExpenseTraits;
    use StaffTraits;

    public function sales_assigned_leads_pdf(Request $request)
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


            // $staff1 = DB::table('staff')->get();

            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $stf->client_list = DB::table('clients')
                    ->join('city', 'city.id', 'clients.city')
                    ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at', 'lead_type.type')
                    ->where('clients.default_company', session('company_id'))
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

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->use_kwt = true;
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.sales_report.get_assigned_leads_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Staffwise_Expense_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_assigned_leads_excel(Request $request)
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

        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->where('users.role_id', 8)
            ->where('staff.sid', session('staff_id'))
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Assigned Leads Report -\n\n";
        foreach ($staff as $stf) {
            $staff_id = $stf->sid;
            $client_list = DB::table('clients')
                ->join('city', 'city.id', 'clients.city')
                ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
                ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at', 'lead_type.type')
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
                $export_data .= "Sr. No.\tCase No\tClient Name\tNo of Units\tProperty Type\tSource\tLead Type\tLocation\tCity\tCreated By\tAssign To\tAssigned At\tAddress\n";
                foreach ($client_list as $row) {
                    $row->property_type_name = DB::table('property_type')->where('id', $row->property_type)->value('type');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    $row->created_by_name = DB::table('staff')->where('sid', $row->created_by)->value('name');
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $lineData = array($i++, $row->case_no, $row->client_name, $row->no_of_units, $row->property_type_name, $row->source_name, $row->type, $row->location,  $row->city_name, $row->created_by_name, $row->assign_to_name, $row->assigned_at, $row->address);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;


        return response($out1)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Assigned_leads_Report.xls\"");
    }

    public function sales_assigned_leads_print(Request $request)
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

            // $staff1 = DB::table('staff')->get();

            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
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
                    ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
                    ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.no_of_units', 'clients.city', 'clients.area', 'clients.location', 'city.city_name', 'clients.address', 'clients.property_type', 'clients.pincode', 'clients.source', 'clients.services', 'clients.created_by', 'clients.assign_to', 'clients.created_at', 'clients.assigned_at', 'lead_type.type')
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
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Property Type</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">City </th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Lead Type</th>
                                <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Location </th>
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
                                <td style="width:10%;">' . $row1->type . '</td>
                                <td style="width:10%;">' . $row1->location . '</td>
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

            return $out;
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_quotation_sent_excel(Request $request)
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


        // $staff1 = DB::table('staff')->get();
        // $StaffId = array();
        // foreach ($staff1 as $stf) {
        //     $company = json_decode($stf->company);
        //     for ($i = 0; $i < sizeof($company); $i++) {
        //         if ($company[$i] == session('company_id')) {
        //             $StaffId[] = $stf->sid;
        //         }
        //     }
        // }
        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->where('users.role_id', 8)
            ->where('staff.sid', session('staff_id'))
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Quotation Sent Report -\n\n";
        foreach ($staff as $stf) {
            $staff_id = $stf->sid;
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount');
            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
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

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }

            $stf->quotation_list = $quotation_list->where('quotation.company', session('company_id'))
                ->where('clients.assign_to', $staff_id)
                ->orderBy('quotation.send_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('clients', 'clients.id', '=', 'quotation.client_id');
            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
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

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $grand_total = $grand_total->whereYear('quotation.send_date', $curr_year)
                    ->whereMonth('quotation.send_date', $month);
            }

            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
            }
            foreach ($stf->quotation_list as $quot) {
                $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');

                if (
                    $quot->assigned_at != ''
                ) {
                    $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                }
            }
            $stf->grand_total = $grand_total->where('quotation.company', session('company_id'))
                ->where('clients.assign_to', $staff_id)
                ->sum('quotation_details.amount');

            if ($stf->quotation_list != '[]') {
                $i = 1;
                $export_data .= "Staff -\t(" . $stf->name . "):\t\t\t\tTotal Amount -\t(" .  AppHelper::moneyFormatIndia($stf->grand_total) . ")\n";

                $export_data .= "Sr. No.\tClient\tAssign to\tAssigned At\tSource\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tFinalized\tSend Date\n";
                foreach ($stf->quotation_list as $quot) {
                    $assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                    $source_name = DB::table('source')->where('id', $quot->source)->value('source');
                    $assigned_at = '';
                    if ($quot->assigned_at != '') {
                        $assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                    }
                    $row['client'] = $quot->case_no . '(' . $quot->client_name . ')';
                    $row['service_name'] = $quot->service_name;
                    $row['no_of_units'] = $quot->no_of_units;
                    $row['units_per_amount'] = $quot->units_per_amount;
                    $row['total_amt'] = AppHelper::moneyFormatIndia($quot->amount);
                    $row['finalize'] = $quot->finalize;
                    $row['send_date'] = date('d-M-Y', strtotime($quot->send_date));

                    $lineData = array($i++, $row['client'], $assign_to_name, $assigned_at, $source_name, $row['service_name'], $row['no_of_units'], $row['units_per_amount'], $row['total_amt'], $row['finalize'], $row['send_date']);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;
        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Quotation_Sent_Report.xls\"");
    }

    public function sales_quotation_sent_pdf(Request $request)
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


            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }
            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount');
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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

                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $FilterDate = $month_filter . '/' . $curr_year;
                    $quotation_list = $quotation_list->whereYear('quotation.send_date', $curr_year)
                        ->whereMonth('quotation.send_date', $month);
                }

                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $FilterDate = $year_filter;
                    $quotation_list = $quotation_list->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }

                $stf->quotation_list = $quotation_list->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('quotation.send_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id');
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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

                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereYear('quotation.send_date', $curr_year)
                        ->whereMonth('quotation.send_date', $month);
                }

                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereBetween('quotation.send_date', [$start_year, $end_year]);
                }
                foreach ($stf->quotation_list as $quot) {
                    $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                    $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');

                    if (
                        $quot->assigned_at != ''
                    ) {
                        $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                    }
                }
                $stf->grand_total = $grand_total->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->sum('quotation_details.amount');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.sales_report.get_quotation_sent_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_quotation_sent_print(Request $request)
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

            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }
            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize', 'quotation_details.amount');
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

                $stf->quotation_list = $quotation_list->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('quotation.send_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id');
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
                foreach ($stf->quotation_list as $quot) {
                    $quot->assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                    $quot->source_name = DB::table('source')->where('id', $quot->source)->value('source');

                    if ($quot->assigned_at != '') {
                        $quot->assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                    }
                }
                $stf->grand_total = $grand_total->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->sum('quotation_details.amount');
            }
            return view('pages.reports.sales_report.get_quotation_sent_report', compact('staff', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_quotation_finalized_excel(Request $request)
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


        // $staff1 = DB::table('staff')->get();
        // $StaffId = array();
        // foreach ($staff1 as $stf) {
        //     $company = json_decode($stf->company);
        //     for ($i = 0; $i < sizeof($company); $i++) {
        //         if ($company[$i] == session('company_id')) {
        //             $StaffId[] = $stf->sid;
        //         }
        //     }
        // }
        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->where('users.role_id', 8)
            ->where('staff.sid', session('staff_id'))
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Quotation Sent Report -\n\n";
        foreach ($staff as $stf) {
            $staff_id = $stf->sid;
            $quotation_list = DB::table('quotation')
                ->join('clients', 'clients.id', '=', 'quotation.client_id')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('services', 'services.id', '=', 'quotation_details.task_id')
                ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
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

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $month_filter . '/' . $curr_year;
                $quotation_list = $quotation_list->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $FilterDate = $year_filter;
                $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }
            $stf->quotation_list = $quotation_list->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->where('clients.assign_to', $staff_id)
                ->orderBy('quotation_details.finalize_date', 'asc')
                ->get();

            $grand_total = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                ->join('clients', 'clients.id', '=', 'quotation.client_id');
            if (
                $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
            ) {
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

            if (
                $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $grand_total = $grand_total->whereYear('quotation_details.finalize_date', $curr_year)
                    ->whereMonth('quotation_details.finalize_date', $month);
            }
            if (
                $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
            ) {
                $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
            }

            $stf->grand_total = $grand_total->where('quotation_details.finalize', 'yes')
                ->where('quotation.company', session('company_id'))
                ->where('clients.assign_to', $staff_id)
                ->sum('quotation_details.amount');

            if ($stf->quotation_list != '[]') {
                $i = 1;
                $export_data .= "Staff -\t(" . $stf->name . "):\t\t\t\tTotal Amount -\t(" .  AppHelper::moneyFormatIndia($stf->grand_total) . ")\n";
                $export_data .= "Sr. No.\tClient\tAssign_to\tAssigned at\tSource\tFollow Up\tServices\tNo of Units\tAmount/Unit\tTotal Amt\tSend Dt\tFinalized Dt\n";
                foreach ($stf->quotation_list as $quot) {
                    $assign_to_name = DB::table('staff')->where('sid', $quot->assign_to)->value('name');
                    $source_name = DB::table('source')->where('id', $quot->source)->value('source');
                    $assigned_at = '';
                    if ($quot->assigned_at != '') {
                        $assigned_at = date('d-M-Y', strtotime($quot->assigned_at));
                    }
                    $row['total_followup'] = DB::table('follow_up')->where('client_id', $quot->client_id)->count();
                    $row['client']  = $quot->case_no . '(' . $quot->client_name . ')';
                    $row['service_name']    = $quot->service_name;
                    $row['no_of_units']    = $quot->no_of_units;
                    $row['units_per_amount']    = $quot->units_per_amount;
                    $row['total_amt']  = AppHelper::moneyFormatIndia($quot->amount);
                    $row['send_date']    = date('d-M-Y', strtotime($quot->send_date));
                    $row['finalize_date']  = date('d-M-Y', strtotime($quot->finalize_date));

                    $lineData = array($i++, $row['client'], $assign_to_name, $assigned_at, $source_name, $row['total_followup'], $row['service_name'], $row['no_of_units'], $row['units_per_amount'], $row['total_amt'], $row['send_date'], $row['finalize_date']);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                }
            }
        }
        $out1 .= $export_data;
        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Quotation_Finalized_Report.xls\"");
    }

    public function sales_quotation_finalized_pdf(Request $request)
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

            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }
            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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

                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $FilterDate = $month_filter . '/' . $curr_year;
                    $quotation_list = $quotation_list->whereYear('quotation_details.finalize_date', $curr_year)
                        ->whereMonth('quotation_details.finalize_date', $month);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $FilterDate = $year_filter;
                    $quotation_list = $quotation_list->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }
                $stf->quotation_list = $quotation_list->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id');
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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

                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereYear('quotation_details.finalize_date', $curr_year)
                        ->whereMonth('quotation_details.finalize_date', $month);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereBetween('quotation_details.finalize_date', [$start_year, $end_year]);
                }

                $stf->grand_total = $grand_total->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->sum('quotation_details.amount');

                foreach ($stf->quotation_list as $row) {
                    $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    if ($row->assigned_at != '') {
                        $row->assigned_at = date('d-M-Y', strtotime($row->assigned_at));
                    }
                }
            }

            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.sales_report.get_quotation_finalized_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Quotation_Sent_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_quotation_finalized_print(Request $request)
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

            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $quotation_list = DB::table('quotation')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('services', 'services.id', '=', 'quotation_details.task_id')
                    ->select('clients.client_name', 'clients.case_no', 'clients.assign_to', 'clients.assigned_at', 'clients.source', 'services.name as service_name', 'services.id as service_id', 'quotation_details.id as quotation_details_id', 'quotation_details.amount', 'quotation_details.file', 'quotation_details.no_of_units', 'quotation_details.units_per_amount', 'quotation.*', 'quotation_details.finalize_date', 'quotation_details.amount');
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
                $stf->quotation_list = $quotation_list->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->orderBy('quotation_details.finalize_date', 'asc')
                    ->get();

                $grand_total = DB::table('quotation')
                    ->join('quotation_details', 'quotation_details.quotation_id', '=', 'quotation.id')
                    ->join('clients', 'clients.id', '=', 'quotation.client_id');
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

                $stf->grand_total = $grand_total->where('quotation_details.finalize', 'yes')
                    ->where('quotation.company', session('company_id'))
                    ->where('clients.assign_to', $staff_id)
                    ->sum('quotation_details.amount');

                foreach ($stf->quotation_list as $row) {
                    $row->total_followup = DB::table('follow_up')->where('client_id', $row->client_id)->count();
                    $row->assign_to_name = DB::table('staff')->where('sid', $row->assign_to)->value('name');
                    $row->source_name = DB::table('source')->where('id', $row->source)->value('source');
                    if ($row->assigned_at != '') {
                        $row->assigned_at = date('d-M-Y', strtotime($row->assigned_at));
                    }
                }
            }
            return view('pages.reports.sales_report.get_quotation_finalized_report', compact('staff', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_invoice_against_quotation_excel(Request $request)
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
        // $staff1 = DB::table('staff')->get();
        // $StaffId = array();
        // foreach ($staff1 as $stf) {
        //     $company = json_decode($stf->company);
        //     for ($i = 0; $i < sizeof($company); $i++) {
        //         if ($company[$i] == session('company_id')) {
        //             $StaffId[] = $stf->sid;
        //         }
        //     }
        // }
        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid', 'staff.name')
            ->where('users.status', 'active')
            ->where('users.role_id', 8)
            ->where('staff.sid', session('staff_id'))
            ->orderBy('staff.sid', 'asc')
            ->get();

        $out1 = '';
        $export_data = "Invoices Against Quotation Report -\n\n";
        foreach ($staff as $stf) {
            $staff_id = $stf->sid;
            if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
                if ($quarter_filter == 'Fourth Quarter') {
                    $start_date = strtotime('1-January-' . $year[1]);
                    $end_date = strtotime('31-March-' . $year[1]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);

                    $stf->invoice_list = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                        ->where('bill.active', 'yes')
                        ->where('bill.company', session('company_id'))
                        ->where('clients.assign_to', $staff_id)
                        ->where('bill.quotation', '!=', 'null')
                        ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                        ->orderBy('bill.invoice_no', 'asc')
                        ->get();

                    $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
                }

                if ($quarter_filter == 'First Quarter') {
                    $start_date = strtotime('1-April-' . $year[0]);
                    $end_date = strtotime('30-June-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $stf->invoice_list = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                        ->where('bill.active', 'yes')
                        ->where('bill.company', session('company_id'))->where('clients.assign_to', $staff_id)
                        ->where('bill.quotation', '!=', 'null')
                        ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                        ->orderBy('bill.invoice_no', 'asc')
                        ->get();

                    $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
                }

                if ($quarter_filter == 'Second Quarter') {
                    $start_date = strtotime('1-July-' . $year[0]);
                    $end_date = strtotime('30-September-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);
                    $stf->invoice_list = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                        ->where('bill.active', 'yes')
                        ->where('bill.company', session('company_id'))->where('clients.assign_to', $staff_id)
                        ->where('bill.quotation', '!=', 'null')
                        ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                        ->orderBy('bill.invoice_no', 'asc')
                        ->get();

                    $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
                }

                if ($quarter_filter == 'Third Quarter') {
                    $start_date = strtotime('1-October-' . $year[0]);
                    $end_date = strtotime('31-December-' . $year[0]);
                    $start_quarter = date('Y-m-d 00:00:00', $start_date);
                    $end_quarter = date('Y-m-d 23:59:59', $end_date);

                    $stf->invoice_list = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                        ->where('bill.active', 'yes')
                        ->where('bill.company', session('company_id'))->where('clients.assign_to', $staff_id)
                        ->where('bill.quotation', '!=', 'null')
                        ->whereBetween('bill.bill_date', [$start_quarter, $end_quarter])
                        ->orderBy('bill.invoice_no', 'asc')
                        ->get();

                    $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_quarter, $end_quarter])->sum('total_amount');
                }
            }

            if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $stf->invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))->where('clients.assign_to', $staff_id)
                    ->where('bill.quotation', '!=', 'null')
                    ->whereYear('bill.bill_date', $curr_year)
                    ->whereMonth('bill.bill_date', $month)
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereYear('bill_date', $curr_year)->whereMonth('bill_date', $month)->sum('total_amount');
            }

            if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                $stf->invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))->where('clients.assign_to', $staff_id)
                    ->where('bill.quotation', '!=', 'null')
                    ->whereBetween('bill.bill_date', [$start_year, $end_year])
                    ->orderBy('bill.invoice_no', 'asc')
                    ->get();

                $stf->grand_total = DB::table('bill')->join('clients', 'clients.id', '=', 'bill.client')->where('active', 'yes')->where('company', session('company_id'))->where('clients.assign_to', $staff_id)->where('quotation', '!=', 'null')->whereBetween('bill_date', [$start_year, $end_year])->sum('total_amount');
            }


            if ($stf->invoice_list != '[]') {
                $i = 1;
                $export_data .= "Staff -\t(" . $stf->name . "):\t\t\t\tTotal Amount -\t(" .  AppHelper::moneyFormatIndia($stf->grand_total) . ")\n";
                $export_data .= "Sr. No.\tInvoice No.\tInvoice Date\tClient\tDiscount\tTotal Amount\n";
                foreach ($stf->invoice_list as $inv) {
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
            }
        }
        $out1 .= $export_data;
        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Invoices_Against_Quotation_Report.xls\"");
    }

    public function sales_invoice_against_quotation_pdf(Request $request)
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

            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }

            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
                $invoice_list = DB::table('bill')
                    ->join('clients', 'clients.id', 'bill.client')
                    ->select('clients.client_name', 'clients.case_no', 'bill.invoice_no', 'bill.bill_date', 'bill.discount', 'bill.total_amount')
                    ->where('bill.active', 'yes')
                    ->where('bill.company', session('company_id'))
                    ->where(
                        'bill.quotation',
                        '!=',
                        'null'
                    );
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $invoice_list = $invoice_list->whereMonth('bill.bill_date', $month)
                        ->whereYear('bill.bill_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $stf->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->where('clients.assign_to', $staff_id)
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->join('clients', 'clients.id', '=', 'bill.client')
                    ->where('company', session('company_id'))
                    ->where(
                        'quotation',
                        '!=',
                        'null'
                    );
                if (
                    $quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none'
                ) {
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
                if (
                    $month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereMonth('bill_date', $month)
                        ->whereYear('bill_date', $curr_year);
                }
                if (
                    $month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none'
                ) {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $stf->grand_total = $grand_total->where('clients.assign_to', $staff_id)->sum('total_amount');
            }
            ini_set("pcre.backtrack_limit", "5000000");
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
            $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(view('pages.reports.sales_report.get_invoice_against_quotation_report', compact('staff', 'FilterDate')));

            return ($mpdf->Output('Invoices_Against_Quotation_Report.pdf', 'I'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function sales_invoice_against_quotation_print(Request $request)
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

            // $staff1 = DB::table('staff')->get();
            // $StaffId = array();
            // foreach ($staff1 as $stf) {
            //     $company = json_decode($stf->company);
            //     for ($i = 0; $i < sizeof($company); $i++) {
            //         if ($company[$i] == session('company_id')) {
            //             $StaffId[] = $stf->sid;
            //         }
            //     }
            // }


            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->select('staff.sid', 'staff.name')
                ->where('users.status', 'active')
                ->where('users.role_id', 8)
                ->where('staff.sid', session('staff_id'))
                ->orderBy('staff.sid', 'asc')
                ->get();

            foreach ($staff as $stf) {
                $staff_id = $stf->sid;
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $invoice_list = $invoice_list->whereBetween('bill.bill_date', [$start_year, $end_year]);
                }
                $stf->invoice_list = $invoice_list->orderBy('bill.invoice_no', 'asc')
                    ->where('clients.assign_to', $staff_id)
                    ->get();

                $grand_total = DB::table('bill')
                    ->where('active', 'yes')
                    ->join('clients', 'clients.id', '=', 'bill.client')
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
                if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
                    $grand_total = $grand_total->whereBetween('bill_date', [$start_year, $end_year]);
                }
                $stf->grand_total = $grand_total->where('clients.assign_to', $staff_id)->sum('total_amount');
            }
            return view('pages.reports.sales_report.get_invoice_against_quotation_report', compact('staff', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Database Query Error! [" . $e->getMessage() . " ]");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
