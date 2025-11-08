<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait clientTraits
{
    public static function get_clients_leads_list($company, $client_leads, $status, $assign_to, $client, $created_by, $created_date, $source, $from_date, $to_date, $address, $city, $lead_type, $appointment_count_show)
    {
        if ($company == 0) {
            $company = session('company_id');
        }

        $client_list = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->join('company', 'company.id', 'clients.default_company')
            ->leftJoin('city', 'city.id', 'clients.city')
            ->leftJoin('property_type', 'property_type.id', 'clients.property_type')
            ->leftJoin('source', 'source.id', 'clients.source')
            ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
            ->leftJoin('staff as assign_to_staff', 'assign_to_staff.sid', 'clients.assign_to')
            ->leftJoin('staff as created_by_staff', 'created_by_staff.sid', 'clients.created_by')
            ->select(DB::raw('CONCAT(case_no , " (" , client_name , ")") as client_case_no'), 'clients.id', 'clients.client_name', 'clients.case_no', 'clients.default_company', 'company.company_name', 'clients.no_of_units', 'city.city_name', 'clients.area', 'clients.remarks', 'clients.address', 'property_type.type as property_type_name', 'property_type.abbrev', 'clients.pincode', 'clients.lead_type', 'clients.source', 'source.source as source_name', 'clients.services', 'clients.assign_to', 'clients.created_by', 'clients.created_at', 'clients.assigned_at', 'clients.status', 'lead_type.type', 'assign_to_staff.name as assign_staff_name', 'created_by_staff.name as created_by_name')
            ->where('client_company_mapping.company', $company);
        if ($client_leads != '') {
            $client_list = $client_list->where('clients.client_leads', $client_leads);
        }

        if ($status != '') {
            $client_list = $client_list->where('clients.status', $status);
        }

        if ($assign_to != '') {
            $client_list = $client_list->where('clients.assign_to', $assign_to);
        }

        if ($client != '') {
            $client_list = $client_list->whereIn('clients.id', $client);
        }

        if ($created_by != '') {
            $client_list = $client_list->where('clients.created_by', $created_by);
        }

        if ($created_date != '') {
            $client_list = $client_list->whereDate('clients.created_at', '=', $created_date);
        }
        if ($source != '') {
            $client_list = $client_list->where('clients.source', $source);
        }
        if ($from_date != '' && $to_date != '') {
            $client_list = $client_list->whereBetween('clients.assigned_at', [$from_date, $to_date]);
        }
        if ($city != '') {
            $client_list = $client_list->where('clients.city', $city);
        }
        if ($address != '') {
            $client_list = $client_list->where('clients.address', 'LIKE', "%{$address}%");
        }
        if ($lead_type != '') {
            $client_list = $client_list->where('clients.lead_type', $lead_type);
        }

        $client_list = $client_list->orderBy('clients.created_at', 'desc')->get();

        if ($appointment_count_show != '') {
            foreach ($client_list as $row) {
                $row->appointments = DB::table('appointment')->where('client', $row->id)->count();
                $row->followups = DB::table('follow_up')->where('client_id', $row->id)->count();
                $row->quotations = DB::table('quotation')
                    ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')
                    ->where('quotation.client_id', $row->id)->count();
                $row->assigned_at = date('d-m-Y', strtotime($row->assigned_at));
            }
        }

        return $client_list;
    }

    public static function get_clients_leads_by_status($company, $client_leads, $status)
    {
        if ($company == 0) {
            $company = session('company_id');
        }

        $client_list = DB::table('clients')
            ->join('client_company_mapping', 'client_company_mapping.client_id', 'clients.id')
            ->join('company', 'company.id', 'clients.default_company')
            ->leftJoin('city', 'city.id', 'clients.city')
            ->leftJoin('property_type', 'property_type.id', 'clients.property_type')
            ->leftJoin('source', 'source.id', 'clients.source')
            ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
            ->leftJoin('staff as assign_to_staff', 'assign_to_staff.sid', 'clients.assign_to')
            ->leftJoin('staff as created_by_staff', 'created_by_staff.sid', 'clients.created_by')
            ->select(DB::raw('CONCAT(case_no , " (" , client_name , ")") as client_case_no'), 'clients.id', 'clients.client_name', 'clients.case_no', 'clients.default_company', 'company.company_name', 'clients.no_of_units', 'city.city_name', 'clients.area', 'clients.remarks', 'clients.address', 'property_type.type as property_type_name', 'property_type.abbrev', 'clients.pincode', 'clients.lead_type', 'clients.source', 'source.source as source_name', 'clients.services', 'clients.assign_to', 'clients.created_by', 'clients.created_at', 'clients.assigned_at', 'clients.status', 'lead_type.type', 'assign_to_staff.name as assign_staff_name', 'created_by_staff.name as created_by_name')
            ->where('client_company_mapping.company', $company);
        if ($client_leads != '') {
            $client_list = $client_list->where('clients.client_leads', $client_leads);
        }


        if (session('role_id') != '1') {
            $staff_id = session('staff_id');
            if ($staff_id != '') {
                $client_list = $client_list->where('clients.assign_to', $staff_id);
            }
        }

        
        if ($status != '') {
            $client_list = $client_list->where('clients.status', $status);
        }

        $client_list = $client_list->orderBy('clients.created_at', 'desc')->get();

        foreach ($client_list as $row) {
            $row->appointments = DB::table('appointment')->where('client', $row->id)->count();
            $row->followups = DB::table('follow_up')->where('client_id', $row->id)->count();
            $row->quotations = DB::table('quotation')
                ->join('quotation_details', 'quotation.id', 'quotation_details.quotation_id')
                ->where('quotation.client_id', $row->id)->count();
            $row->assigned_at = date('d-m-Y', strtotime($row->assigned_at));
        }

        return $client_list;
    }

    public static function get_client_case_no_by_id($id)
    {

        $case_no = DB::table('clients')->where('id', $id)->value('case_no');
        $client_name = DB::table('clients')->where('id', $id)->value('client_name');

        if (!empty($client_name)) {
            $client_case_no = $case_no . ' (' . $client_name . ')';
        } else {
            $client_case_no = $case_no;
        }

        return $client_case_no;
    }

    public static function get_client_case_no()
    {
        $company = session('company_id');
        $clients = DB::table('clients')->select('id', 'case_no', 'client_name')->where('default_company', $company)->where('client_leads', 'leads')->where('status', 'active')->orderBy('id', 'desc')->get();
        return $clients;
    }

    public static function get_client_by_id($id)
    {

        $client_list = DB::table('clients')
            ->join('client_contacts', 'clients.id', 'client_contacts.client_id')
            ->join('company', 'company.id', 'clients.default_company')
            ->leftJoin('city', 'city.id', 'clients.city')
            ->leftJoin('property_type', 'property_type.id', 'clients.property_type')
            ->leftJoin('source', 'source.id', 'clients.source')
            ->leftJoin('lead_type', 'lead_type.id', 'clients.lead_type')
            ->leftJoin('staff', 'staff.sid', 'clients.assign_to')
            ->select('clients.id', 'clients.client_name', 'clients.case_no', 'clients.default_company', 'company.company_name', 'clients.no_of_units', 'city.city_name', 'clients.area', 'clients.remarks', 'clients.address', 'property_type.type as property_type_name', 'clients.pincode', 'source.source as source_name', 'clients.services', 'clients.created_by', 'clients.status', 'lead_type.type', 'staff.name as assign_staff_name', 'client_contacts.contact', 'client_contacts.whatsapp', 'client_contacts.email')
            ->where('clients.id', $id)
            ->get();

        return $client_list;
    }
}
