<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait StaffTraits
{
    public static function get_all_staff_list()
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
            ->join('role', 'role.id', 'users.role_id')
            ->select('staff.*', 'users.id as user_id', 'users.role_id', 'users.status', 'role.role')
            ->whereIn('staff.sid', $staff_id)
            ->get();
        return $staff;
    }

    public static function get_staff_list()
    {
        $staff = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->join('role', 'role.id', 'users.role_id')
            ->where('users.status', 'active')
            ->select('staff.*', 'users.role_id', 'role.role')
            ->orderBy('staff.name','ASC')
            ->get();
        return $staff;
    }
    public static function get_staff_list_userid()
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
            ->select('staff.*', 'users.id as user_id')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.name', 'desc')
            ->get();
        return $staff;
    }

    public static function get_staff_list_userid_company()
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
            ->select('staff.*', 'users.id as user_id')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.name', 'asc')
            ->get();
        return $staff;
    }
    public static function get_staff_id_list_userid_company($staff_id)
    {
        $staff1 = DB::table('staff')->where('sid', $staff_id)->get();

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
            ->select('staff.*', 'users.id as user_id')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.sid', 'desc')
            ->get();
        return $staff;
    }

    public static function get_sales_staff_list()
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
            ->select('staff.*', 'users.id as user_id')
            ->where('users.status', 'active')
            ->where('users.role_id', 8)
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.sid', 'desc')
            ->get();
        return $staff;
    }
    public static function get_leads_staff()
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
            ->join('clients','clients.assign_to','staff.sid')
            ->select('staff.*', 'users.id as user_id')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->groupBy('clients.assign_to')
            ->orderBy('staff.name', 'desc')
            ->get();
        return $staff;
    }
    public static function admin_id()
    {
        $admin = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->where('users.status', 'active')
            ->where('users.role_id',1)
             ->get(['staff.sid']);
             $admin_id=array();
        foreach($admin as $ad)
        {
            $admin_id[]=(string)$ad->sid;
        }
        return $admin_id;
    }
        function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
}
