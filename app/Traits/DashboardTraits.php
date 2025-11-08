<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait DashboardTraits
{
     public static function get_activity()
    {
        $month = date('m'); // THIS MONTH
        $year = date('Y');
        $quotations = count(DB::table('quotation_details')
            ->join('quotation', 'quotation.id', 'quotation_details.quotation_id')
            ->where('quotation_details.finalize', 'yes')->whereMonth('quotation_details.finalize_date', $month)
            ->whereYear('quotation_details.finalize_date', $year)
            ->where('quotation.company', session('company_id'))->get());
        $invoice = DB::table('bill')->where('active', 'yes')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereMonth('bill_date', $month)->whereYear('bill_date', $year)->sum('total_amount');
        $proforma_invoice = DB::table('proforma_invoice')->where('active', 'yes')->where('convert_tax','no')->where('status','unpaid')->where('company', session('company_id'))->where('quotation', '!=', 'null')->whereMonth('bill_date', $month)->whereYear('bill_date', $year)->sum('total_amount');
        $invoice+=$proforma_invoice;

        $additionalInvoice = DB::table('bill')->where('active', 'yes')->where('quotation', 'null')->where('company', session('company_id'))->whereMonth('bill_date', $month)->whereYear('bill_date', $year)->sum('total_amount');
        $additionalProformaInvoice = DB::table('proforma_invoice')->where('active', 'yes')->where('convert_tax','no')->where('status','unpaid')->where('quotation', 'null')->where('company', session('company_id'))->whereMonth('bill_date', $month)->whereYear('bill_date', $year)->sum('total_amount');
        $additionalInvoice+=$additionalProformaInvoice;
        
        $payments = DB::table('payment')->whereMonth('approve_date',$month)->whereYear('approve_date',$year)->where('status', 'approved')->where('active', 'yes')->where('company', session('company_id'))->sum('payment');
        $tds_payments = DB::table('payment')->whereMonth('payment_date', $month)->whereYear('payment_date', $year)->where('status', 'approved')->where('active', 'yes')->where('company', session('company_id'))->sum('tds');
        $payments+=$tds_payments;
        
        $duePayments = 0;
       
        $bill_id=DB::table('bill')->where('status', '!=', 'paid')->where('active', 'yes')->where('company', session('company_id'))->pluck('id');
        $payment_amt = DB::table('bill_payment_mapping')->whereIn('bill_id',$bill_id)->where('active','yes')->sum('paid_amount');
        $tds_amt = DB::table('bill_payment_mapping')->whereIn('bill_id',$bill_id)->where('active','yes')->sum('tds_amount');
        $due_bill_amount = DB::table('bill')->whereIn('id',$bill_id)->sum('total_amount');
        $proforma_invoice_due=DB::table('proforma_invoice')->where('status','unpaid')->where('convert_tax','no')->where('company', session('company_id'))->where('active','yes')->sum('total_amount');
        $duePayments=($proforma_invoice_due+$due_bill_amount)-($payment_amt+$tds_amt);
        
        
        $appointments = count(DB::table('appointment')->whereMonth('meeting_date', $month)->whereYear('meeting_date', $year)->where('company', session('company_id'))->get());
        $consultationFees = DB::table('consulting_fee')->join('appointment','consulting_fee.appointment_id','appointment.id')->where('appointment.status','finalize')
        ->where('appointment.company',session('company_id'))
        ->whereMonth('consulting_fee.payment_date',$month)
        ->whereYear('consulting_fee.payment_date',$year)->sum('consulting_fee.fees');
        $newClients = DB::table('clients')->where('status', 'active')->whereMonth('date', $month)->whereYear('date', $year)->where('default_company', session('company_id'))->count();
        $clientsContacted = count(DB::table('follow_up')->whereMonth('followup_date', $month)->whereYear('followup_date', $year)->where('company', session('company_id'))->get());

        $data = array(
            'Quotations' => $quotations, 'Invoice' => $invoice, 'Additional Invoice' => $additionalInvoice, 'Payments' => $payments,
            'Due Payments' => $duePayments, 'Appointments' => $appointments, 'Consultation Fees' => $consultationFees,
            'New Clients' => $newClients, 'Clients Contacted' => $clientsContacted
        );

        return $data;
    }


    public static function get_sales_activity()
    {
        $month = date('m');
        $year = date('Y');
        $call = '["call"]';
        $whatsapp = '["whatsapp"]';
        $visit = '["visit"]';

        $lead_created = DB::table('clients')->where('status', 'active')->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('default_company', session('company_id'))->where('created_by', session('staff_id'))->count();
        $lead_assigned = DB::table('clients')->where('status', 'active')->whereMonth('assigned_at', $month)->whereYear('assigned_at', $year)->where('default_company', session('company_id'))->where('assign_to', session('staff_id'))->count();

        $follow_up_call = DB::table('follow_up')->where('method', $call)
            ->whereMonth('followup_date', $month)
            ->whereYear('followup_date', $year)
            ->where('follow_up.company', session('company_id'))->where('contact_by', session('staff_id'))->count();

        $follow_up_whatsapp =
            DB::table('follow_up')->where('method', $whatsapp)
            ->whereMonth('followup_date', $month)
            ->whereYear('followup_date', $year)
            ->where('follow_up.company', session('company_id'))->where('contact_by', session('staff_id'))->count();

        $follow_up_visit =
            DB::table('follow_up')->where('method', $visit)
            ->whereMonth('followup_date', $month)
            ->whereYear('followup_date', $year)
            ->where('follow_up.company', session('company_id'))->where('contact_by', session('staff_id'))->count();

        $follow_up_pending =
            DB::table('follow_up')->where('finalized', 'no')
            ->whereMonth('followup_date', $month)
            ->whereYear('followup_date', $year)
            ->where('follow_up.company', session('company_id'))->where('contact_by', session('staff_id'))->count();

        $clients = DB::table('clients')->select('id')->where('status', 'active')->where('default_company', session('company_id'))->where('assign_to', session('staff_id'))->get();
        $clients_count = DB::table('clients')->select('id')->where('status', 'active')->where('default_company', session('company_id'))->where('assign_to', session('staff_id'))->count();

        $client_ids = array_column(json_decode($clients), 'id');

        $quotations_send = DB::table('quotation')
            ->whereMonth('send_date', $month)
            ->whereYear('send_date', $year)
            ->whereIn('client_id', $client_ids)
            ->count();

        $quotations_finalized = count(DB::table('quotation_details')
            ->join('quotation', 'quotation.id', 'quotation_details.quotation_id')
            ->where('quotation_details.finalize', 'yes')
            ->whereMonth('quotation_details.finalize_date', '05')
            ->whereYear('quotation_details.finalize_date', $year)
            ->whereIn('quotation.client_id', $client_ids)
            ->where('quotation.company', session('company_id'))->get());

        $appointment = DB::table('appointment')->whereMonth('meeting_date', $month)
            ->whereYear('meeting_date', $year)
            ->where('company', session('company_id'))->count();

        $leave = DB::table('leave_table')->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->where('staff_id', session('staff_id'))->count();

        $data = array('lead_created' => $lead_created, 'lead_assigned' => $lead_assigned, 'follow_up_call' => $follow_up_call, 'follow_up_whatsapp' => $follow_up_whatsapp, 'follow_up_visit' => $follow_up_visit, 'follow_up_pending' => $follow_up_pending, 'quotations_send' => $quotations_send, 'quotations_finalized' => $quotations_finalized, 'appointment' => $appointment, 'leave' => $leave);
        return $data;
    }
      public static function get_income($month,$year)
    {
        $Payment=DB::table('payment')->where('status','approved')->where('active','yes')->whereMonth('approve_date',$month)->whereYear('approve_date',$year)->where('company',session('company_id'))->sum('payment');
        $tds_payments = DB::table('payment')->whereMonth('payment_date', $month)->whereYear('payment_date', $year)->where('status', 'approved')->where('active', 'yes')->where('company', session('company_id'))->sum('tds');
        $Payment+=$tds_payments;
        $consulting_fee=DB::table('consulting_fee')->join('appointment','consulting_fee.appointment_id','appointment.id')->where('appointment.status','finalize')->where('appointment.company',session('company_id'))->whereMonth('consulting_fee.payment_date',$month)->whereYear('consulting_fee.payment_date',$year)->sum('consulting_fee.fees');
        return $total_income=$Payment+$consulting_fee;
    }
    public static function get_expenses($month,$year)
    {
        return $expenses=DB::table('expense')->where('status','approved')->whereMonth('approve_date',$month)->whereYear('approve_date',$year)->where('company',session('company_id'))->sum('amount');
    }
    public static function IND_money_format($number){
        $decimal = (string)($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for($i=0;$i<$length;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                $delimiter .=',';
            }
            $delimiter .=$money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if( $decimal != '0'){
            $result = $result.$decimal;
        }

        return $result;
    }
    public static function get_finalize_quotation($month,$year)
    {
        $data=DB::table('quotation')
        ->join('quotation_details','quotation.id','quotation_details.quotation_id')
        ->where('quotation.company',session('company_id'))->where('quotation_details.finalize','yes')
        ->whereMonth('quotation_details.finalize_date',$month)->whereYear('quotation_details.finalize_date',$year)->sum('amount');
        return $data;
    }
}
