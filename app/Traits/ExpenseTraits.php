<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait ExpenseTraits
{




	public static function get_expense_list()
	{

		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->approved_by_name = $approved_by_name;
		}
		return $expense_entry;
	}
		public static function open_expenses($first_dt,$last_dt)
	{
		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->where('expense.status', 'open')
			->where('expense.company', session('default_company_id'))->whereBetween('expense.date',array($first_dt,$last_dt))->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->approved_by_name = $approved_by_name;
			$row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
		}
		return $expense_entry;
	}

	public static function approved_expenses($first_dt,$last_dt)
	{

		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->where('expense.status', 'approved')->where('expense.company', session('default_company_id'))->whereBetween('expense.date',array($first_dt,$last_dt))->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->approved_by_name = $approved_by_name;
			$row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
		}
		return $expense_entry;
	}
	public static function open_expenses_by_staff($staff_id,$company_id)
	{
		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->where('expense.status', 'open')
			->where('expense.by_whom', $staff_id)
			->where('expense.company', $company_id)
		    ->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->approved_by_name = $approved_by_name;
			$row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
		}
		return $expense_entry;
	}
	public static function approved_expenses_by_staff($staff_id, $company_id,$month,$year)
	{

		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->where('expense.by_whom', $staff_id)
			->where('expense.status', 'approved')
			->where('expense.company', $company_id)
			->whereMonth('expense.date',$month)
			->whereYear('expense.date',$year)
			->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->approved_by_name = $approved_by_name;
			$row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
		}
		return $expense_entry;
	}
	public static function rejected_expenses_by_staff($staff_id, $company_id,$month,$year)
	{

		$expense_entry = DB::table('expense')
			->join('accounting_sub_heads', 'accounting_sub_heads.id', 'expense.ledger')
			->select('expense.*', 'accounting_sub_heads.sub_heads')->where('expense.by_whom', $staff_id)
			->where('expense.status', 'rejected')
			->where('expense.company', $company_id)
			->whereMonth('expense.date',$month)
			->whereYear('expense.date',$year)
			->orderby('expense.id', 'desc')->get();
		foreach ($expense_entry as $row) {
			$client_name = DB::table('clients')->where('id', $row->client_id)->value('client_name');
			$row->case_no = DB::table('clients')->where('id', $row->client_id)->value('case_no');
			$staff_name = DB::table('staff')->where('sid', $row->by_whom)->value('name');
			$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
			$row->client_name = $client_name;
			$row->entry_by = $staff_name;
			$row->rejected_by_name = $approved_by_name;
			$row->company_name = DB::table('company')->where('id', $row->company)->value('company_name');
		}
		return $expense_entry;
	}

	function displaywords($number)
	{
		$no = (int)floor($number);
		$point = (int)round(($number - $no) * 100);
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array(
			'0' => '', '1' => 'one', '2' => 'two',
			'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
			'7' => 'seven', '8' => 'eight', '9' => 'nine',
			'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
			'13' => 'thirteen', '14' => 'fourteen',
			'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
			'18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
			'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
			'60' => 'sixty', '70' => 'seventy',
			'80' => 'eighty', '90' => 'ninety'
		);
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;

			Log::info('number ' . $number);
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);


		if ($point > 20) {
			$points = ($point) ?
				"" . $words[floor($point / 10) * 10] . " " .
				$words[$point = $point % 10] : '';
		} else {
			$points = $words[$point];
		}
		if ($points != '') {
			return ucwords($result . "Rupees  " . $points . " Paise Only");
		} else {

			return  ucwords($result . "Rupees Only");
		}
	}
    public static function get_company_staff()
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

        $data = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.name', 'asc')
            ->pluck('staff.sid');
            return $data;
    }
	public static function get_travelling_allowance()
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

        $data = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.sid','staff.name')
            ->where('users.status', 'active')
            ->whereIn('staff.sid', $staff_id)
            ->orderBy('staff.name', 'asc')
            ->get();
        foreach($data as $row) 
        {
            	$row->total_distance = DB::table('travelling_allowance')
    		    ->where('entry_by', $row->sid)
    			->where('company',session('default_company_id'))
    			->where('travelling_allowance.status','approved')
    			->sum('distance');
        }
	
	
		return $data;
	}
	public static function pending_travelling_allowance()
	{
	   
            
		if (session('role_id') == 1 || session('role_id') == 3) {
		    
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.status', 'pending')
				->where('travelling_allowance.company',session('company_id'))
				->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		} else {
			$staff_id = session('staff_id');
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.status', 'pending')
				->where('travelling_allowance.entry_by', $staff_id)
				->whereYear('travelling_allowance.date',date('Y'))
				->whereMonth('travelling_allowance.date',date('m'))
				->where('travelling_allowance.company',session('company_id'))
				->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		}
		return $data;
	}

	public static function approved_travelling_allowance($id)
	{
		if ($id == '') {
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.status','approved')
				->where('travelling_allowance.company',session('company_id'))
			    ->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		} else {
		    
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.entry_by', $id)
				->where('travelling_allowance.status','approved')
				->where('travelling_allowance.company',session('company_id'))
				->whereYear('travelling_allowance.date', '=', date('Y'))
				->whereMonth('travelling_allowance.date', '=', date('m'))
				->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		}
		return $data;
	}
	public static function rejected_travelling_allowance($id)
	{
		if ($id == '') {
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.status','rejected')
				->where('travelling_allowance.company',session('company_id'))
			    ->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		} else {
			$data = DB::table('travelling_allowance')
				->join('destination', 'destination.id', 'travelling_allowance.destination_id')
				->select('travelling_allowance.*', 'destination.place')
				->where('travelling_allowance.entry_by',$id)
				->where('travelling_allowance.status','rejected')
				->where('travelling_allowance.company',session('company_id'))
				->whereYear('travelling_allowance.date', '=', date('Y'))
				->whereMonth('travelling_allowance.date', '=', date('m'))
				->orderBy('travelling_allowance.date', 'DESC')
				->get();
			foreach ($data as $row) {
				$by_whom = DB::table('staff')->where('sid', $row->entry_by)->value('name');
				$approved_by_name = DB::table('staff')->where('sid', $row->approved_by)->value('name');
				$row->entry = $by_whom;
				$row->approved_by_name = $approved_by_name;
			}
		}
		return $data;
	}
}