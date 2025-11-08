<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppHelper;
class PrettyCashReportController extends Controller
{
    public function pretty_cash_excel(Request $request)
    {
        $month_filter = $request->month;
        $quarter_filter = $request->quarter;
        $year_filter = $request->year;

        $month = date("m", strtotime($month_filter));

        $year = explode('-', $year_filter);

        $start_year = $year[0].'-04-01';
        $end_year = $year[1].'-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter=array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
               if ($quarter_filter == 'Fourth Quarter') {
                $start_quarter = $year[1].'-01-01';
                $end_quarter = $year[1].'-03-31';
               
                
            }

            if ($quarter_filter == 'First Quarter') {
               
                $start_quarter = $year[0].'-04-01';
                $end_quarter = $year[0].'-06-30';
                
            }

            if ($quarter_filter == 'Second Quarter') {
               
                $start_quarter =$year[0].'-07-01';
                $end_quarter = $year[0].'-09-30';
               
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_quarter =$year[0].'-10-01';
                $end_quarter = $year[0].'-12-31';
               
                
            }
            $filter=array($start_quarter, $end_quarter);
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $month_filter . '/' . $curr_year;
            $start_date=$curr_year.'-'.$month.'-01';
            $d=cal_days_in_month(CAL_GREGORIAN,$month,$curr_year);
            $end_date=$curr_year.'-'.$month.'-'.$d;
            $filter=array($start_date, $end_date);
        }
        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $year_filter;
            $filter=array($start_year,$end_year);
        }
       
       $pretty_cash_list = DB::table('pretty_cash')
            ->join('staff','pretty_cash.staff_id','staff.sid')
            ->whereBetween('pretty_cash.date',$filter)
            ->select('pretty_cash.*','staff.name')
            ->where('pretty_cash.company',session('company_id'))
            ->orderBy('pretty_cash.id', 'asc')
            ->get();
        
        $export_data = "Pretty Cash Report -".$FilterDate."\n\n";
            if ($pretty_cash_list != '[]') {
                $i = 1;
                $prevBalance = 0;
                $export_data .= "Sr. No.\tStaff Name\tdate\tExpense\tReceipt\tRemark\tBalance Amount\n";
                foreach ($pretty_cash_list as $row) {
                    $expense = ($row->cash_type == 'expense') ? $row->amount : 0;
                    $receipt = ($row->cash_type == 'receipt') ? $row->amount : 0;
                    $balance = ($prevBalance + $receipt) - $expense;

                    $lineData = array($i++, $row->name,date('d-m-Y',strtotime($row->date)), ($expense == 0) ? '' : $expense, ($receipt == 0) ? '' : $receipt,str_replace(',',' | ',$row->remark), ($balance == 0) ? '' : $balance);
                    $export_data .= implode("\t", array_values($lineData)) . "\n";
                    $prevBalance = $balance;
                }
            }
        return response($export_data)
            ->header("Content-Type", "application/vnd.ms-excel")
            ->header("Content-Disposition", "attachment;filename=\"Pretty_cash_Report.xls\"");
    }
    public function pretty_cash_print(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            
        $start_year = $year[0].'-04-01';
        $end_year = $year[1].'-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter=array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
               if ($quarter_filter == 'Fourth Quarter') {
                $start_quarter = $year[1].'-01-01';
                $end_quarter = $year[1].'-03-31';
               
                
            }

            if ($quarter_filter == 'First Quarter') {
               
                $start_quarter = $year[0].'-04-01';
                $end_quarter = $year[0].'-06-30';
                
            }

            if ($quarter_filter == 'Second Quarter') {
               
                $start_quarter =$year[0].'-07-01';
                $end_quarter = $year[0].'-09-30';
               
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_quarter =$year[0].'-10-01';
                $end_quarter = $year[0].'-12-31';
               
                
            }
            $filter=array($start_quarter, $end_quarter);
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $month_filter . '/' . $curr_year;
            $start_date=$curr_year.'-'.$month.'-01';
            $d=cal_days_in_month(CAL_GREGORIAN,$month,$curr_year);
            $end_date=$curr_year.'-'.$month.'-'.$d;
            $filter=array($start_date, $end_date);
        }
        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $year_filter;
            $filter=array($start_year,$end_year);
        }
        $pretty_cash_list = DB::table('pretty_cash')
                ->join('staff','pretty_cash.staff_id','staff.sid')
                ->whereBetween('pretty_cash.date',$filter)
                ->select('pretty_cash.*','staff.name')
                ->where('pretty_cash.company',session('company_id'))
                ->orderBy('pretty_cash.id', 'asc')
                ->get();
        return view('pages.reports.get_pretty_cash_report', compact('pretty_cash_list', 'FilterDate'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Something went wrong , please contact to support team .");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
    public function pretty_cash_pdf(Request $request)
    {
        try {
            $month_filter = $request->month;
            $quarter_filter = $request->quarter;
            $year_filter = $request->year;

            $month = date("m", strtotime($month_filter));

            $year = explode('-', $year_filter);

            
        $start_year = $year[0].'-04-01';
        $end_year = $year[1].'-03-31';

        if ($month > 03) {
            $curr_year = $year[0];
        } else {
            $curr_year = $year[1];
        }
        $filter=array();
        if ($quarter_filter != 'none' && $year_filter != 'none' && $month_filter == 'none') {
            $FilterDate = $quarter_filter;
               if ($quarter_filter == 'Fourth Quarter') {
                $start_quarter = $year[1].'-01-01';
                $end_quarter = $year[1].'-03-31';
               
                
            }

            if ($quarter_filter == 'First Quarter') {
               
                $start_quarter = $year[0].'-04-01';
                $end_quarter = $year[0].'-06-30';
                
            }

            if ($quarter_filter == 'Second Quarter') {
               
                $start_quarter =$year[0].'-07-01';
                $end_quarter = $year[0].'-09-30';
               
            }

            if ($quarter_filter == 'Third Quarter') {
                $start_quarter =$year[0].'-10-01';
                $end_quarter = $year[0].'-12-31';
               
                
            }
            $filter=array($start_quarter, $end_quarter);
        }

        if ($month_filter != 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $month_filter . '/' . $curr_year;
            $start_date=$curr_year.'-'.$month.'-01';
            $d=cal_days_in_month(CAL_GREGORIAN,$month,$curr_year);
            $end_date=$curr_year.'-'.$month.'-'.$d;
            $filter=array($start_date, $end_date);
        }
        if ($month_filter == 'none' && $year_filter != 'none' && $quarter_filter == 'none') {
            $FilterDate = $year_filter;
            $filter=array($start_year,$end_year);
        }
        
       $pretty_cash_list = DB::table('pretty_cash')
            ->join('staff','pretty_cash.staff_id','staff.sid')
            ->whereBetween('pretty_cash.date',$filter)
            ->select('pretty_cash.*','staff.name')
            ->where('pretty_cash.company',session('company_id'))
            ->orderBy('pretty_cash.id', 'asc')
            ->get();
        ini_set("pcre.backtrack_limit", "5000000");
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
        $mpdf->SetHeader('<div style="font-size:16px;font-weight:700;">{DATE j-M-Y}</div>');
        $mpdf->setFooter('<div style="font-size:16px;font-weight:700;">{PAGENO}</div>');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML(view('pages.reports.get_pretty_cash_report',compact('pretty_cash_list', 'FilterDate')));
        return $mpdf->Output('pretty_cash_Report.pdf', 'I');
            
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('failure', "Something went wrong , please contact to support team .");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }
}
