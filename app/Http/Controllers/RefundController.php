<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function refund_list(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }

            $refund = DB::table('refund')
                ->join('clients', 'clients.id', 'refund.client_id')
                ->join('bank_detailes', 'bank_detailes.id', 'refund.bank_id')
                ->select('refund.*', 'clients.client_name', 'clients.case_no', 'bank_detailes.bankname')
                ->where('clients.default_company', session('company_id'))
                ->get();

            return view('pages.refund_list', compact('refund'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }

    public function refund_add_index()
    {
        try {
            $clients = DB::table('clients')->where('default_company', session('company_id'))->get();

            $bank = DB::table('bank_detailes')->where('company', session('company_id'))->get();

            return view('pages.refund-add', compact('clients', 'bank'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function refund_add(Request $request)
    {
        try {
            Log::Info($request);
            $staff_id = session('staff_id');
            $client_id = $request->client_id;
            $bank_id = $request->bank_id;
            $amount = $request->amount;
            $mode_of_payment = $request->mode_of_payment;
            $deposite_bank = $request->deposite_bank;
            $var = $request->deposite_date;
            $date = str_replace('/', '-', $var);
            $deposite_date = date('Y-m-d', strtotime($date));
            $cheque_no = $request->cheque_no;
            $ref_no = $request->ref_no;
            $remark = $request->remark;
            $created_by = $staff_id;

            $insert = DB::table('refund')->insert(['client_id' => $client_id, 'bank_id' => $bank_id, 'amount' => $amount, 'mode_of_payment' => $mode_of_payment, 'deposite_bank' => $deposite_bank, 'deposite_date' => $deposite_date, 'cheque_no' => $cheque_no, 'ref_no' => $ref_no, 'remark' => $remark, 'created_by' => $created_by]);

            if ($insert) {
                return json_encode(array('status' => 'success', 'msg' => 'Refund Detail Inserted'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Refund detail can`t be inserted'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'failure', 'error' => 'Database error'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }
}