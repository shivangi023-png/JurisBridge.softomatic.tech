<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\InvoiceTraits;
use Illuminate\Support\Facades\Log;
use App\Traits\ClientTraits;
use App\Helpers\AppHelper;
use App\Traits\NotificationTraits;
use App\Traits\StaffTraits;
use App\Traits\DashboardTraits;

use Illuminate\Support\Facades\Validator;
class InvoiceController extends Controller
{

    use InvoiceTraits;
    use ClientTraits;
    use NotificationTraits;
    use StaffTraits;
     use DashboardTraits;
    public function invoice_list()
    {
        if (session('username') == "") {
            return redirect('/')->with('status', "Please login First");
        }

        $company = session('company_id');
        $clients = $this->get_clients_leads_by_status($company, 'client', 'active');
        $bank_detail = DB::table('bank_detailes')->get();
        $staffs = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.*', 'users.id as user_id')
            ->get();
        $services = DB::table('services')->get(['id', 'name']);
        $data = DB::table('bill')
            ->join('clients', 'clients.id', 'bill.client')
            ->join('staff', 'staff.sid', 'bill.sign')
            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.company', session('company_id'))
            ->where('bill.status', '!=', 'paid')
            ->where('bill.active', 'yes')
            ->orderBy('bill.bill_date', 'desc')->get();

        foreach ($data as $row) {
            $row->client_case_no = $this->get_client_case_no_by_id($row->client);
            $services_arr = json_decode($row->service);
            $amount_arr = json_decode($row->amount);
            $quotation_array = json_decode($row->quotation);
            $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
            $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
            $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
            $discount_arr=json_decode($row->discount, true);
            $discount_arr = $discount_arr ?? [];

            $total_discount = array_sum(array_map('intval', (array) $discount_arr));
            
          
            
            $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note+$total_discount);
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
        $tds_applicable = DB::table('company')->where('id', session('company_id'))->value('tds_applicable');

        $banks = DB::table('bank_detailes')->get();
        $companies = DB::table('company')->get();
        return view('pages.invoice-list', compact('staffs', 'clients', 'services', 'data', 'tds_applicable'));
    }

    public function proforma_invoice_list()
    {
        if (session('username') == "") {
            return redirect('/')->with('status', "Please login First");
        }

        $company = session('company_id');
        $clients = $this->get_clients_leads_by_status($company, 'client', 'active');
        $bank_detail = DB::table('bank_detailes')->get();
        $staffs = DB::table('staff')
            ->join('users', 'users.user_id', 'staff.sid')
            ->select('staff.*', 'users.id as user_id')
            ->get();
        $services = DB::table('services')->get(['id', 'name']);
        $data = DB::table('proforma_invoice')
            ->join('clients', 'clients.id', 'proforma_invoice.client')
            ->join('staff', 'staff.sid', 'proforma_invoice.sign')
            ->select('proforma_invoice.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('proforma_invoice.company', session('company_id'))
            ->where('proforma_invoice.status', '!=', 'paid')
            ->where('proforma_invoice.active', 'yes')
            ->where('proforma_invoice.convert_tax','!=', 'yes')
            ->orderBy('proforma_invoice.bill_date', 'desc')->get();

        foreach ($data as $row) {
            $row->client_case_no = $this->get_client_case_no_by_id($row->client);
            $services_arr = json_decode($row->service);
            $amount_arr = json_decode($row->amount);
            $quotation_array = json_decode($row->quotation);
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
        $tds_applicable = DB::table('company')->where('id', session('company_id'))->value('tds_applicable');

        $banks = DB::table('bank_detailes')->get();
        $companies = DB::table('company')->get();
        return view('pages.proforma-invoice-list', compact('staffs', 'clients', 'services', 'data', 'tds_applicable'));
    }

    public function add_invoice_index(Request $request)
    {
        try {
            $client_id = $request->id;
            if ($client_id == '') {
                $client_id = '';
            }
            $clients = $this->get_clients_leads_by_status(0, 'client', 'active');
            $staffs = DB::table("staff")->select("sid", "name")->get();
            $services = DB::table("services")->select("id", "name")->get();
            $banks = DB::table("bank_detailes")->select("id", "bankname")->get();
            $companies = DB::table("company")->select("id", "company_name")->get();
            foreach ($companies as $com) {
                $company = strtolower($com->company_name);
                $seal = str_replace(' ', '_', $company);
                $com->seal=$seal.$com->id;
            }

            $taxes = DB::table('tax')->where('company', session('company_id'))->get();
            $tax_applicable = DB::table("company")->where('id', session('company_id'))->value('tax_applicable');
            return view('pages.invoice-add', compact('client_id', "clients", "staffs", "services", "banks", "companies", "taxes", "tax_applicable"));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function invoice_edit_index($id)
    {
        $clients = $this->get_clients_leads_by_status(0, 'client', 'active');
        $staffs = DB::table("staff")->select("sid", "name")->get();
        $services = DB::table("services")->select("id", "name")->get();
        $banks = DB::table("bank_detailes")->select("id", "bankname")->get();
        $companies = DB::table("company")->select("id", "company_name")->get();
        foreach ($companies as $com) {
            $company = strtolower($com->company_name);
             $seal = str_replace(' ', '_', $company);
                $com->seal=$seal.$com->id;
        }
        $paid_amount = DB::table('bill_payment_mapping')->where('bill_id', $id)->where('active', 'yes')->sum('paid_amount');

        $invoices = DB::table('bill')
            ->join('clients', 'clients.id', 'bill.client')
            ->join('staff', 'staff.sid', 'bill.sign')
            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'clients.address', 'staff.name', 'clients.id as client_id')->where('bill.id', $id)->where('bill.company', session('company_id'))->get();

        foreach ($invoices as $inv) {
            $inv->quo_of_client = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
                ->join('services', 'services.id', 'quotation_details.task_id')
                ->select('quotation.*', 'services.name', 'quotation_details.amount', 'quotation_details.id as quotation_details_id')
                ->where('quotation.client_id', $inv->client_id)
                ->where('quotation_details.finalize', 'yes')->get();
            $inv->paid_amount = $inv->total_amount - $paid_amount;

            $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
        }
        $taxes = DB::table('tax')->where('company', session('company_id'))->get();
        $tax_applicable = DB::table("company")->where('id', session('company_id'))->value('tax_applicable');
        return view('pages.invoice-edit', compact("clients", "staffs", "services", "banks", "companies", "invoices", "taxes", "tax_applicable"));
    }

     public function proforma_invoice_edit_index($id)
    {
        $clients = $this->get_clients_leads_by_status(0, 'client', 'active');
        $staffs = DB::table("staff")->select("sid", "name")->get();
        $services = DB::table("services")->select("id", "name")->get();
        $banks = DB::table("bank_detailes")->select("id", "bankname")->get();
        $companies = DB::table("company")->select("id", "company_name")->get();
        foreach ($companies as $com) {
            $company = strtolower($com->company_name);
             $seal = str_replace(' ', '_', $company);
                $com->seal=$seal.$com->id;
        }
        $invoices = DB::table('proforma_invoice')
            ->join('clients', 'clients.id', 'proforma_invoice.client')
            ->join('staff', 'staff.sid', 'proforma_invoice.sign')
            ->select('proforma_invoice.*', 'clients.client_name', 'clients.case_no', 'clients.address', 'staff.name', 'clients.id as client_id')->where('proforma_invoice.id', $id)->where('proforma_invoice.company', session('company_id'))->get();

        foreach ($invoices as $inv) {
            $inv->quo_of_client = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
                ->join('services', 'services.id', 'quotation_details.task_id')
                ->select('quotation.*', 'services.name', 'quotation_details.amount', 'quotation_details.id as quotation_details_id')
                ->where('quotation.client_id', $inv->client_id)
                ->where('quotation_details.finalize', 'yes')->get();
            $inv->tds_applicable = DB::table('company')->where('id', $inv->company)->value('tds_applicable');
        }
        $taxes = DB::table('tax')->where('company', session('company_id'))->get();
        $tax_applicable = DB::table("company")->where('id', session('company_id'))->value('tax_applicable');
        return view('pages.proforma_invoice_edit', compact("clients", "staffs", "services", "banks", "companies", "invoices", "taxes", "tax_applicable"));
    }

    public function delete_invoice(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            $check_payment_id = DB::table('bill_payment_mapping')->where('bill_id', $id)->where('active', 'yes')->get('payment_id');
            if ($check_payment_id != "[]") {
                $msg = '';
                foreach ($check_payment_id as $row) {
                    $msg .= $row->payment_id . ', ';
                }
                return json_encode(array('status' => 'error', 'msg' => 'Please first delete payment of this bill. Payment id is ' . $msg));
            }
            $delete = DB::table('bill')->where('id', $id)->update(['active' => 'no', 'updated_at' => now()]);

            if ($delete) {
                if ($status != 'Filter Invoice') {
                    $data = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->join('staff', 'staff.sid', 'bill.sign')
                        ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.status', $status)
                        ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->orderBy('bill.bill_date', 'desc')->get();
                } else {
                    $data = DB::table('bill')
                        ->join('clients', 'clients.id', 'bill.client')
                        ->join('staff', 'staff.sid', 'bill.sign')
                        ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                        ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->where('bill.status', '!=', 'paid')->orderBy('bill.bill_date', 'desc')->get();
                }


                foreach ($data as $row) {
                    $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                    $services_arr = json_decode($row->service);
                    $amount_arr = json_decode($row->amount);
                    $quotation_array = json_decode($row->quotation);
                    $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                    $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
                    $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
                    $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note);
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
                $out = '<div class="action-dropdown-btn d-none">
    <div class="dropdown invoice-filter-action">
      <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="selection">Filter Invoice</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
        <a class="dropdown-item statusbtn"  data-value="partial">Partial Payment</a>
        <a class="dropdown-item statusbtn"  data-value="unpaid">Unpaid</a>
        <a class="dropdown-item statusbtn"  data-value="paid">Paid</a>
      </div>
    </div>
    <div class=" invoice-options">
      <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
      <i class="bx bx-plus"></i>Add Invoice</a>

    </div>
  </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table invoice-data-table dt-responsive wrap" >
                    <thead>
                        <tr>
                        <th></th>
                        <th></th>
                        <th>
                            <span class="align-middle">Invoice#</span>
                        </th>
                        <th>Action</th>
                        
                        <th>Client</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th>Seal</th>
                        <th>Sign</th>   
                        </tr>
                    </thead>
                
                    <tbody>';
                foreach ($data as $row) {
                    $invoice_no=session('short_code'). '-' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT) . '/' .$row->year;
                    $out .= '<tr>
                    <td></td>
                    <td></td>
                    <td>
                    <a href="generate_invoice-' . $row->id . '-tax">' . $invoice_no . '</a>
                    </td>
                    <td>
                        <div class="invoice-action">
                            <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                            <i class="bx bx-show-alt"></i>
                            </a> -->
                            <a href="generate_invoice-' . $row->id . '-tax" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                            <i class="bx bx-printer"></i>
                            </a>
                            <a href="invoice_edit-' . $row->id . '" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-id=' . $row->id . '  data-tooltip="Edit">
                            <i class="bx bx-edit"></i></a>
                              <a href="refund_list" class="invoice-action-edit btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1"
                                         data-tooltip="Refund">
                                        <i class="bx bx-wallet-alt"></i>
                                    </a>
                            <a  class="delete_invoice btn btn-icon rounded-circle glow btn-info mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                            </a>
                            <a data-toggle="modal" data-target="#default" class="invoice_payment_btn btn btn-icon rounded-circle glow btn-primary mr-1 mb-1" data-id="' . $row->id . '" data-amount="' . $row->payable . '" data-client_id="' . $row->client . '" data-tooltip="Payment">
                            <i class="bx bx-money"></i>
                            </a>
                            <a href="" class="invoice-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Send Mail">
                            <i class="bx bx-send"></i>
                            </a>
                            <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id="'.$row->id.'" data-payable="'.$row->payable.'" data-client_id="'.$row->client.'" data-invoice_no="'.$invoice_no.'" data-tooltip="write off">
                            <i class="bx bxs-credit-card-alt"></i>
                            </a>
                            <a type="button" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="'.$row->id.'" data-tooltip="Credit note">
                                <i class="bx bxs-credit-card"></i>
                            </a>

                        </div>
                        </td>
                  
                    <td><span class="invoice-customer">' . $row->client_case_no . ' </span></td>
                    <td>
                    
                    <small class="text-muted">' . nl2br($row->service) . '</small>
                    </td> 
                    <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
                    if ($row->status == 'unpaid') {

                        $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
                    } else if ($row->status == 'paid') {
                        $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
                    } else {
                        $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
                    }




                    $out .= '<td data-sort="' . strtotime($row->bill_date) . '">' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                    <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                    <td>' . $row->seal . '</td>
                    <td>' . $row->name . '</td>  
                </tr>';
                }
                $out .= '</tbody>
                </table>
            </div>
        </div>
    </div>';

                return json_encode(array('status' => 'success', 'msg' => 'Bill deleted successfully', 'out' => $out));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Bill can`t be deleted '));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }

    public function print_invoice()
    {
    }

    public function get_bill_quotation(Request $request)

    {

        try {

            $client_id = $request->client_id;
            $quotation = DB::table('quotation')
                ->join('quotation_details', 'quotation_details.quotation_id', 'quotation.id')
                ->join('services', 'services.id', 'quotation_details.task_id')
                ->select('quotation.*', 'services.name', 'quotation_details.amount', 'quotation_details.id as quotation_details_id')
                ->where('quotation.client_id', $client_id)
                ->where('quotation_details.finalize', 'yes')
                ->where('quotation.company',session('company_id'))->get();
            $taxes = DB::table('tax')->where('company', session('company_id'))->get();
            $out = '
                  <div>
                      <center><b>OR</b></center>
                      <br>
                  </div>
                  <div class="row">
                  <h5>Quotation Details</h5>
                  </div>';
            if ($quotation != '[]') {
                $i = 1;
                foreach ($quotation as $quo) {
                    $out .= '<div class="row quo_main_row">
                   
                        <div class="col-sm-1 col-12 order-2 order-sm-1" style="padding-top:3px;border:1px solid #DFE3E7">
                        <fieldset class="form-label-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="colorCheckbox' . $i . '" value="' . $quo->quotation_details_id . '" class="quotation_check">
                                <label for="colorCheckbox' . $i . '"></label>
                            </div>
                            </fieldset>
                        </div>
                        <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7;">
                        <fieldset class="form-label-group" style="padding-top:4px">
                        <div class="input-group">
                            <b>' . $quo->name . '</b>
                            </div>
                            </fieldset>
                        </div>
                        <div class="col-sm-6 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                            <div class="row">
                                    <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                    <fieldset class="form-label-group" style="padding-top:4px">
                                    
                                    <input type="text" value="' . $quo->amount . '"  class="form-control quo_amount">
                                        </fieldset>
                                    </div>
                                
                                <div class="col-sm-4 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset>
                                <div class="form-label-group" style="padding-top:4px">
                                    <input type="text" class="form-control quo_discount"  name="quo_discount" placeholder="Discount">
                                    <label for="first-name-column">Discount</label>
                                </div>
                            </fieldset>
                            <span class="discount_err valid_err"></span>
                        </div>
                                <div class="col-sm-3 col-12 order-12 order-sm-2" >
                                <fieldset class="form-label-group" style="padding-top:4px">
                                    <div class="form-label-group">
                                    <div class="custom-control custom-switch custom-control-inline mb-1">
                                        <input type="checkbox" class="custom-control-input quo_round_check"  id="quo_round_check' . $i . '" value="no">
                                        <label class="custom-control-label mr-1" for="quo_round_check' . $i . '">
                                        </label>
                                        <span>Round</span>
                                    </div>
                                    </div>
                                    </fieldset>
                                <span class="discount_err valid_err"></span>
                                </div>
                            </div>
                        </div>
                       
              ';
                    foreach ($taxes as $tax) {
                        if ($tax->status == 'active') {
                            $out .= '<div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                <label for="">' . $tax->tax . '%</label>
                <fieldset class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control quo_' . $tax->tax . '_percent"  name="quo_' . $tax->tax . '_percent">
                      </div>
                  </fieldset>
                  <span class="valid_err ' . $tax->tax . '_percent_err"></span>
              </div>
              <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                <label for="">' . $tax->tax . ' amount</label>
                <fieldset class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control quo_' . $tax->tax . '_amount "  name="quo_' . $tax->tax . '_amount" >
                      </div>
                      <span class="valid_err ' . $tax->tax . '_amount_err"></span>
                  </fieldset>
                  
              </div>';
                        } else {
                            $out .= '<div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                <label for="">' . $tax->tax . '%</label>
                <fieldset class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control quo_' . $tax->tax . '_percent"  name="quo_' . $tax->tax . '_percent" disabled>
                      </div>
                  </fieldset>
                  <span class="valid_err ' . $tax->tax . '_percent_err"></span>
              </div>
              <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                <label for="">' . $tax->tax . ' amount</label>
                <fieldset class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control quo_' . $tax->tax . '_amount "  name="quo_' . $tax->tax . '_amount" disabled>
                      </div>
                      <span class="valid_err ' . $tax->tax . '_amount_err"></span>
                  </fieldset>
                  
              </div>';
                        }
                    }
                    $out .= ' <div class="col-sm-3 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
              <label for="">Total</label>
              <fieldset class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control quo_total_amount"  name="quo_total_amount" value="' . $quo->amount . '" placeholder="total">
                      </div>
                  </fieldset>
                  <span class="total_err valid_err"></span>
              </div>
             
              </div><hr>
                     ';
                    $i++;
                }
            } else {
                $out .= ' <div>
                          <center><b>!!!!!.......Quotation not found........!!!!!</b></center>
                          <br>
                      </div> 
                      </div><hr>';
            }

            return $out;
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }
    public function invoice_submit(Request $request)
    {

        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }

            $quotation = $request->quotation;
            $client = $request->client;
            $bill_date = $request->bill_date;
            $bill_date = str_replace('/', '-', $bill_date);
            $bill_date = date('Y-m-d', strtotime($bill_date));
            $due_date = $request->due_date;
            $due_date = str_replace('/', '-', $due_date);
            $due_date = date('Y-m-d', strtotime($due_date));
            $seal = $request->seal;
            $sign = $request->sign;
            $service = $request->service;
            $amount = $request->amount;
            $total = $request->total_amount;

            $total_amount = array_sum($total);
            $bank = $request->bank;
            $company_id = $request->company;
            $round_check = $request->round_check;
            $gst_percent = $request->gst_percent;
            $gst_amount = $request->gst_amount;
            $cgst_percent = $request->cgst_percent;
            $cgst_amount = $request->cgst_amount;
            $igst_percent = $request->igst_percent;
            $igst_amount = $request->igst_amount;
            $discount = $request->discount;
            $invoice_type = $request->invoice_type;
            $description = $request->description;
            // $total_discount=array_sum($discount);
            // $total_gst_amount=array_sum($gst_amount);
            // $total_cgst_amount=array_sum($cgst_amount);
            // $total_igst_amount=array_sum($igst_amount);
            //$total_amount=($total_amount-$total_discount)+$total_gst_amount+$total_cgst_amount+$total_igst_amount;

            
            $sess = '';
            $prev_yr=substr (date('Y')-1,-2);
            $cur_yr=substr (date('Y'),2);
            $nxt_yr=substr (date('Y')+1,-2);
            if(date('m')<=3)
            {
               $sess=$prev_yr.'-'.$cur_yr;
            }
            else
            {
               $sess=$cur_yr.'-'.$nxt_yr;
            }
            $invoice_no  = '';
            if($invoice_type == 'tax'){
                $invoice_no = (DB::table('bill')->where('company', $company_id)->where('year',$sess)->orderBy('id', 'desc')->value('invoice_no')) + 1;
             }else{
                $invoice_no = (DB::table('proforma_invoice')->where('company', $company_id)->where('year',$sess)->orderBy('id', 'desc')->value('invoice_no')) + 1;
             } 
            $data = [
                        'year'=>$sess,
                        'invoice_no' => $invoice_no,
                        'client' => $client,
                        'company' => $company_id,
                        'bill_date' => $bill_date,
                        'due_date' => $due_date,
                        'seal' => $seal,
                        'sign' => $sign,
                        'quotation' => json_encode($quotation),
                        'service' => json_encode($service),
                        'amount' => json_encode($amount),
                        'total_amount' => $total_amount,
                        'bank' => $bank,
                        'company' => $company_id,
                        'discount' => json_encode($discount),
                        'gst_per' => json_encode($gst_percent),
                        'gst_amount' => json_encode($gst_amount),
                        'cgst_per' => json_encode($cgst_percent),
                        'cgst_amount' => json_encode($cgst_amount),
                        'igst_per' => json_encode($igst_percent),
                        'igst_amount' => json_encode($igst_amount),
                        'total' => json_encode($total),
                        'round_check' => json_encode($round_check),
                        'description' => $description
                    ];
                    $msg = '';
                    if($invoice_type == 'tax'){
                        $insert = DB::table('bill')->insert($data);
                        $msg = 'Tax Invoice';
                     }else{
                        $insert = DB::table('proforma_invoice')->insert($data);
                        $msg = 'Proforma Invoice';
                     }   

            if ($insert) {
                  Log::info('-------Push Notification: Invoice Raised----');
                  $notification_data = $this->push_notification_list('Invoice_Raised');
                  $title = $notification_data['title'];
                  $body = $notification_data['body'];
                  $icon = $notification_data['icon'];
                  $click_action = $notification_data['click_action'];
                  $module = $notification_data['module'];


                  $TotalAmount = AppHelper::moneyFormatWithoutZeroIndia($total_amount);

                  $data1 = DB::table('clients')->where('id', $client)->first(['case_no','client_name','assign_to']);
                  $case_no1 = $data1->case_no;
                  $client_name = $data1->client_name;
                  $staff_id=$this->admin_id();
                  array_push($staff_id,(string)$data1->assign_to);
                  log::info('staff_id='.json_encode($staff_id));
                  log::info('assign_to='.$data1->assign_to);
                  $body = str_replace(['{amount}','{case_no}','{client_name}'],[$TotalAmount,$case_no1,$client_name],$body);
                  $this->send_push_notification($title,$body,$staff_id,$click_action,$icon,$module);

                return json_encode(array('status' => 'success', 'msg' => $msg.' created successfully'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => $msg.' can`t be created '));
            }
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }
    public function invoice_update(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }

            $id = $request->bill_id;
            $bill_date = $request->bill_date;
            $client = $request->client;
            $bill_date = str_replace('/', '-', $bill_date);
            $bill_date = date('Y-m-d', strtotime($bill_date));
            $due_date = $request->due_date;
            $due_date = str_replace('/', '-', $due_date);
            $due_date = date('Y-m-d', strtotime($due_date));
            $seal = $request->seal;
            $sign = $request->sign;
            $service = $request->service;
            $amount = $request->amount;
            $total = $request->total_amount;
            $total_amount = array_sum($total);
            $quotation = $request->quotation;
            $bank = $request->bank;
            $company = $request->company;
            $round_check = $request->round_check;
            $gst_percent = $request->gst_percent;
            $gst_amount = $request->gst_amount;
            $cgst_percent = $request->cgst_percent;
            $cgst_amount = $request->cgst_amount;
            $igst_percent = $request->igst_percent;
            $igst_amount = $request->igst_amount;
            $discount = $request->discount;
            $invoice_type = $request->invoice_type;
            $description = $request->description;
            // $total_discount=array_sum($discount);
            // $total_gst_amount=array_sum($gst_amount);
            // $total_cgst_amount=array_sum($cgst_amount);
            // $total_igst_amount=array_sum($igst_amount);
            // $total_amount=($total_amount-$total_discount)+$total_gst_amount+$total_cgst_amount+$total_igst_amount;
            $data = [
                'bill_date' => $bill_date,
                'client' => $client,
                'due_date' => $due_date,
                'seal' => $seal,
                'sign' => $sign,
                'service' => json_encode($service),
                'amount' => json_encode($amount),
                'total_amount' => $total_amount,
                'quotation' => json_encode($quotation),
                'bank' => $bank,
                'company' => $request->company,
                'discount' => json_encode($discount),
                'gst_per' => json_encode($gst_percent),
                'gst_amount' => json_encode($gst_amount),
                'cgst_per' => json_encode($cgst_percent),
                'cgst_amount' => json_encode($cgst_amount),
                'igst_per' => json_encode($igst_percent),
                'igst_amount' => json_encode($igst_amount),
                'total' => json_encode($total),
                'round_check' => json_encode($round_check),
                'description' =>$description,
                'updated_at' => now()
            ];
             $msg = '';
            if($invoice_type == 'tax'){
                $update = DB::table('bill')->where('id',$id)->update($data);
                $msg = 'Tax Invoice';
             }else{
                $update = DB::table('proforma_invoice')->where('id',$id)->update($data);
                $msg = 'Proforma Invoice';
             }
            if ($update) {
                return json_encode(array('status' => 'success', 'msg' =>$msg.' updated successfully'));
            } else {
                return json_encode(array('status' => 'error', 'msg' =>$msg.' can`t be updated '));
            }
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
        }
    }
    
    
    public function generate_invoice(Request $request)
    {
        try {
            $id=$request->id;
            $type=$request->type;
            $title = 'Invoice No';
            if($type == 'tax'){
                $title = 'Invoice No';
                $data = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('bill.id', $id)->get();
            }
            if($type == 'proforma'){
                $title = 'PI No';
                $data = DB::table('proforma_invoice')
                ->join('clients', 'clients.id', 'proforma_invoice.client')
                ->join('staff', 'staff.sid', 'proforma_invoice.sign')
                ->select('proforma_invoice.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('proforma_invoice.id', $id)->get();
            }
            require_once base_path('vendor/autoload.php');
            //return session('company_logo');
            $html = "<style>
                      body{
                          font-family: 'Ubuntu', sans-serif;
                      }
                      .main {
                      
                          margin:15;
                      
                      
                      }
                      .head{
                          background-color:#000;
                          padding:2px 10px 2px 10px;
                          overflow:hidden;
                      }
                      .logo{
                          float:left;
                      }
                      .logo img{
                          width: 80px;
                          margin-top: 0px;
                      }
                      .add{
                          float:right;
                      }
                  
                      .main h4{
                          text-align:center;
                          margin: 0px 0px 0px 0px;
                          font-size: 20px;
                      }
                      p.signtext{
                          text-align: right;
                          margin-left: 30px;
                          margin-bottom:0px;
                          margin-top:0px;
                      }
                      
                      .footer{
                          margin-top:20px;
                      }
                      .footer p{
                          font-size:13px;
                          text-align:center;
                          border-bottom: 1px solid #faa41a;
                          padding-bottom:5px;
                          margin:0px;
                      }
                      .footer ul{
                          margin: 6px 0px 0px 0px;
                      }
                      .footer ul li{
                          display:inline-block;
                          font-size:13px;
                      }
                      .footer li{
                          padding-right:24px;
                      }
                      .footer li i{
                          color:#d58504;
                      }
                      .abc {
                          border-collapse: collapse;
                      }
                      .abc th, td {
                          padding: 6px;
                          text-align: left;
                          border: 1px solid #ddd;
                      }
                      .footer-tbl
                      {
                          border-collapse: collapse;
                          margin-bottom:20;
                      }
                      #leftbox { 
                          float:left;  
                      
                          width:75%; 
                          font-size:14px
                      } 
                      
                      #rightbox{ 
                          float:right; 
                      
                          width:25%; 
                          
                      }
                      </style>";
            foreach ($data as $row) {
                $active = $row->active;
                $bank_id = $row->bank;
                $bank_name = DB::table('bank_detailes')->where('id', $bank_id)->value('bankname');
                $ifsc = DB::table('bank_detailes')->where('id', $bank_id)->value('ifsccode');
                $acno = DB::table('bank_detailes')->where('id', $bank_id)->value('accnumber');
                $company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                $company_name = ucwords($company_name);
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
                $jd = gregoriantojd(date('m', strtotime($row->bill_date)), date('d', strtotime($row->bill_date)), date('Y', strtotime($row->bill_date)));
                $month_name = jdmonthname($jd, 0);
                $jd1 = gregoriantojd(date('m', strtotime($row->due_date)), date('d', strtotime($row->due_date)), date('Y', strtotime($row->due_date)));
                $due_month_name = jdmonthname($jd1, 0);
                $city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                if ($city_name == '') {
                    $city_name = $row->city;
                }
                
                $total_amt = $row->total_amount;

            // $discount_arr=json_decode($row->discount, true);
            // $discount_arr = $discount_arr ?? [];
            // $total_discount = array_sum(array_map('intval', (array) $discount_arr));
            // if($type == 'tax')
            // {
            //      $total_amt = $total_amt-$total_discount;
            // }
           
                
                $seal = $row->seal;
                $sign = $row->sign;
                $sign_name = DB::table('staff')->where('sid', $sign)->value('name');
                $sign_name = str_replace(" ", "_", $sign_name);
                $image_path = 'images/invoice_img/sign/' . $row->seal . '_' . $sign_name . '.png';

                $sess = '';
                $prev_yr=substr (date('Y')-1,-2);
                $cur_yr=substr (date('Y'),2);
                $nxt_yr=substr (date('Y')+1,-2);
                // if(date('m')<=3)
                // {
                //   $sess=$prev_yr.'-'.$cur_yr;
                // }
                // else
                // {
                //   $sess=$cur_yr.'-'.$nxt_yr;
                // }
                $sess=$row->year;
               if($type == 'tax'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
               }
               if($type == 'proforma'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
               }
                $tax_applicable = DB::table('company')->where('id', $row->company)->value('tax_applicable');
                $total_discount = 0;
                $total_gst_amt = 0;
                $total_cgst_amt = 0;
                $total_igst_amt = 0;
                $desc=$row->description;
                if ($row->discount != "" && $row->discount != "null") {
                    $total_discount = array_sum(json_decode($row->discount));
                }

                if ($row->gst_amount != '' && $row->gst_amount != "null") {
                    $total_gst_amt = array_sum(json_decode($row->gst_amount));
                }
                if ($row->cgst_amount != '' && $row->cgst_amount != "null") {
                    $total_cgst_amt = array_sum(json_decode($row->cgst_amount));
                }
                if ($row->igst_amount != '' && $row->igst_amount != "null") {
                    $total_igst_amt = array_sum(json_decode($row->igst_amount));
                }
                $total_tax = $total_gst_amt + $total_cgst_amt + $total_igst_amt;
                $html .= "
                              <body>
                              <table class='head' width='100%'>
                              <tr>
                              <td class='logo' style='border:none' width='65%'>";
                if (session('company_id') == 3) {
                    $html .= "<img width='150px' src='" . session('company_logo') . "'>";
                } else {
                    $html .= "<img width='80px' src='" . session('company_logo') . "'>";
                }

                $html .= "</td>
                  <td  class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
                              <p style='color:#fff;
                              padding-left: 10px;
                              margin: 10px 0px 0px 0px;
                              font-size: 15px;'>
                                 " . session('company_address') . "
                              </p>
                          </td>
                              </tr>
                                  
                                  </table>
                              <div class='main'>";


                if ($tax_applicable == 'yes' || $type == 'tax') {
                    $html .= "<h4 style='margin-bottom:10px'><u>Tax Invoice</u></h4>";
                } else if($type == 'proforma'){
                    $html .= "<h4 style='margin-bottom:10px'><u>Proforma Invoice</u></h4>";
                }else{
                    $html .= "<h4 style='margin-bottom:10px'><u>Invoice</u></h4>";
                }

                $html .= "<table width='100%' style='border:none'>
                                  <tr>
                                  <td style='border:none;font-family: 'Ubuntu', sans-serif'>$title. <strong>$bill_no</strong></td>
                                  <td style='border:none' align='center'>Invoice Date: <strong>" . date('d', strtotime($row->bill_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->bill_date)) . "</strong></td>
                                  <td style='border:none' align='right'>Due Date: <strong>" . date('d', strtotime($row->due_date)) . '-' . $due_month_name . '-' . date('Y', strtotime($row->due_date)) . "</strong></td>
                                  
                                  </tr>
                              </table>
                              <table width='100%' style='border:none'>
                                  <tr>
                                      <td style='border:none'>Invoice To: <strong>$row->client_name</strong>, $row->address, $city_name</td>
                                  </tr>
                              </table>
                              
                              
                              <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                                  <tr>
                                  <th style='text-align:center;'>S. No.</th>
                                  <th style='text-align:center;'>Particulars</th>
                                  <th style='text-align:center;'>Amount (INR)</th>
                                  </tr>";
                $a = 1;


                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];

                        $html .= "<tr>
                                  <td style='text-align:center;'>" . $a++ . "</td>
                                  <td style='text-align:center;font-family: hindi'>" . ucwords($ser) . "<br>".$desc."</td>

                                  <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];



                        $html .= "<tr>
                                      <td style='text-align:center;'>" . $a++ . "</td>
                                      <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>
                                      <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                }

              if($total_discount > 0){
                $html .= "<tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Discount</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_discount, 2) . "</strong></td>
                                  </tr>";
                }                  
                if($total_tax >0){
                    $html .= "<tr>
                    <td>&nbsp;</td>
                    <td style='text-align:right;'><strong>Tax</strong></td>
                    <td style='text-align:right;'><strong>" . number_format($total_tax, 2) . "</strong></td>
                    </tr>";
               }


                                 
               $html .=" <tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Total</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_amt, 2) . "</strong></td>
                                  </tr>
                                  <tr>
                                  <td style='text-align:center;'><strong>In  Words</strong></td>
                                  <td colspan='2'><strong>" . $this->displaywords($total_amt) . "</strong></td>
                                  </tr>
                              </table>
                              
                              <br>
                              
                              
                              <div id='leftbox'>
                              
                              <div style='padding-top:3px'><b>Note : </b>1). This is the Computer generated Invoice.</div>
                              <div style='margin-left:49px;padding-top:3px'>2). Cheque Should be Drawn in the Name of '" . $company_name . "' .</div>
                              
                              
                              <div style='margin-left:50px;'>Bank Name - <b>" . $bank_name . "</b>, Account No. <b>" . $acno . "</b>,  IFSC Code- <b>" . $ifsc . "</b>.</div>
                              <div style='margin-left:49px;padding-top:3px'>3).PAN No: " . session('pan_no') . ".</div>";
                              if(session('gst_no')!=NULL || session('gst_no')!='')
                              {
                                   $html .= "<div style='margin-left:49px;padding-top:3px'>4).GSTIN No: " . session('gst_no') . ".</div>";
                              }
                              $html.="</div>";


                $html .= "<div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='150px'></p>
                              <p class='signtext'>Authorised Signature</p></div>
                              </div>
                              </div>
                              </body>
                              ";
            }
            if ($a > 3) {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 195]]);
            } else {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 170]]);
            }


            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);

            if ($row->company != 3) {
                $ofc = "";
                if (session('head_office') == "") {
                    $ofc .= "";
                } else {
                    $ofc .= "Head Office : " . session('head_office');
                }
                if ($ofc != "") {
                    $ofc .= ", ";
                }
                if (session('company_branch') == "" || session('company_branch') == 'N/A') {
                    $ofc .= "";
                } else {
                    $ofc .= "Our Branches: " . session('company_branch');
                }
                $mpdf->SetHTMLFooter("<div class='footer'>
                          <p>" . $ofc . "</p>
                              <table class='footer-tbl'>
                                  <tr>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>
                                  </tr>
                              </table>
                      </div>");
            }
            if ($active == 'no') {
                $mpdf->SetWatermarkText('CANCELLED');
                $mpdf->showWatermarkText = true;
            }

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);

            $mpdf->Output();
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }
    public function download_invoice($id,$type)
    {
        try {
            $title = 'Invoice No';
            if($type == 'tax'){
            $title = 'Invoice No';
            $data = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('bill.id', $id)->where('clients.default_company', session('company_id'))->get();
            }else if($type == 'proforma'){
                $title = 'PI No';
                $data = DB::table('proforma_invoice')
                ->join('clients', 'clients.id', 'proforma_invoice.client')
                ->join('staff', 'staff.sid', 'proforma_invoice.sign')
                ->select('proforma_invoice.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('proforma_invoice.id', $id)->where('clients.default_company', session('company_id'))->get();
            }


            require_once base_path('vendor/autoload.php');

            $html = "<style>
                    body{
                        font-family: 'Ubuntu', sans-serif;
                    }
                    .main {
                    
                        margin:15;
                    
                    
                    }
                    .head{
                        background-color:#000;
                        padding:2px 10px 2px 10px;
                        overflow:hidden;
                    }
                    .logo{
                        float:left;
                    }
                    .logo img{
                        width: 80px;
                        margin-top: 0px;
                    }
                    .add{
                        float:right;
                    }
                
                    .main h4{
                        text-align:center;
                        margin: 0px 0px 0px 0px;
                        font-size: 20px;
                    }
                    p.signtext{
                        text-align: right;
                        margin-left: 30px;
                        margin-bottom:0px;
                        margin-top:0px;
                    }
                    
                    .footer{
                        margin-top:20px;
                    }
                    .footer p{
                        font-size:13px;
                        text-align:center;
                        border-bottom: 1px solid #faa41a;
                        padding-bottom:5px;
                        margin:0px;
                    }
                    .footer ul{
                        margin: 6px 0px 0px 0px;
                    }
                    .footer ul li{
                        display:inline-block;
                        font-size:13px;
                    }
                    .footer li{
                        padding-right:24px;
                    }
                    .footer li i{
                        color:#d58504;
                    }
                    .abc {
                        border-collapse: collapse;
                    }
                    .abc th, td {
                        padding: 6px;
                        text-align: left;
                        border: 1px solid #ddd;
                    }
                    .footer-tbl
                    {
                        border-collapse: collapse;
                        margin-bottom:20;
                    }
                    #leftbox { 
                        float:left;  
                    
                        width:75%; 
                        font-size:14px
                    } 
                    
                    #rightbox{ 
                        float:right; 
                    
                        width:25%; 
                        
                    }
                    </style>";
            foreach ($data as $row) {
                $bank_id = $row->bank;
                $bank_name = DB::table('bank_detailes')->where('id', $bank_id)->value('bankname');
                $ifsc = DB::table('bank_detailes')->where('id', $bank_id)->value('ifsccode');
                $acno = DB::table('bank_detailes')->where('id', $bank_id)->value('accnumber');
                $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
                $company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                $company_name = ucwords($company_name);
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $jd = gregoriantojd(date('m', strtotime($row->bill_date)), date('d', strtotime($row->bill_date)), date('Y', strtotime($row->bill_date)));
                $month_name = jdmonthname($jd, 0);
                $jd1 = gregoriantojd(date('m', strtotime($row->due_date)), date('d', strtotime($row->due_date)), date('Y', strtotime($row->due_date)));
                $due_month_name = jdmonthname($jd1, 0);
                $city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                if ($city_name == '') {
                    $city_name = $row->city;
                }
                $total_amt = 0;

                $seal = $row->seal;
                $sign = $row->sign;
                $sign_name = DB::table('staff')->where('sid', $sign)->value('name');
                $sign_name = str_replace(" ", "_", $sign_name);
                $image_path = 'images/invoice_img/sign/' . $row->seal . '_' . $sign_name . '.png';
                $sess = '';
                $prev_yr=substr (date('Y')-1,-2);
                $cur_yr=substr (date('Y'),2);
                $nxt_yr=substr (date('Y')+1,-2);
                $desc=$row->description;
                if(date('m')<=3)
                {
                 $sess=$prev_yr.'-'.$cur_yr;
                }
                else
                {
                 $sess=$cur_yr.'-'.$nxt_yr;
                }
                if($type == 'tax'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
                }
                if($type == 'proforma'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
                }
                $heading_title = "Invoice";
                if ($type == 'tax') {
                    $heading_title = "Tax Invoice";
                } else if($type == 'proforma'){
                     $heading_title = "Proforma Invoice";
                }

                $html .= "
                            <body>
                            <table class='head' width='100%'>
                            <tr>
                            <td class='logo' style='border:none' width='65%'>
                            <img width='80px' src='" . session('company_logo') . "'>
                        </td>
                <td  class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
                            <p style='color:#fff;
                            
                            padding-left: 10px;
                            margin: 10px 0px 0px 0px;
                            font-size: 15px;'>
                            " . session('company_address') . "
                            </p>
                        </td>
                            </tr>
                                
                                </table>
                            <div class='main'>
                            
                        
                            
                            <h4 style='margin-bottom:10px'><u>".$heading_title."</u></h4>
                            <table width='100%' style='border:none'>
                                <tr>
                                <td style='border:none;font-family: 'Ubuntu', sans-serif'>".$title." <strong>$bill_no</strong></td>
                                <td style='border:none' align='center'>Bill Date: <strong>" . date('d', strtotime($row->bill_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->bill_date)) . "</strong></td>
                                <td style='border:none' align='right'>Due Date: <strong>" . date('d', strtotime($row->due_date)) . '-' . $due_month_name . '-' . date('Y', strtotime($row->due_date)) . "</strong></td>
                                
                                </tr>
                            </table>
                            <table width='100%' style='border:none'>
                                <tr>
                                    <td style='border:none'>Received From: <strong>$row->client_name</strong>, $row->address, $city_name</td>
                                </tr>
                            </table>
                            
                            
                            <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                                <tr>
                                <th style='text-align:center;'>S. No.</th>
                                <th style='text-align:center;'>Particulars</th>
                                <th style='text-align:center;'>Amount(INR)</th>
                                </tr>";
                $a = 1;


                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $total_amt += $amt;
                        $html .= "<tr>
                                <td style='text-align:center;'>" . $a++ . "</td>
                                <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>
                                <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                </tr>";
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];


                        $total_amt += $amt;
                        $html .= "<tr>
                                    <td style='text-align:center;'>" . $a++ . "</td>
                                    <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>
                                    <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                </tr>";
                    }
                }
                $html .= "<tr>
                                <td>&nbsp;</td>
                                <td style='text-align:right;'><strong>Total</strong></td>
                                <td style='text-align:right;'><strong>" . number_format($total_amt, 2) . "</strong></td>
                                </tr>
                                <tr>
                                <td style='text-align:center;'><strong>In  Words</strong></td>
                                <td colspan='2'><strong>" . $this->displaywords($total_amt) . "</strong></td>
                                </tr>
                            </table>
                            
                            <br>
                            
                            
                            <div id='leftbox'>
                            
                            <div style='padding-top:3px'><b>Note : </b>1). This is the Computer generated Invoice.</div>
                            <div style='margin-left:50px;padding-top:3px'>2). Cheque Should be Drawn in the Name of '" . $company_name . "' .</div>
                            
                            
                            <div style='margin-left:50px;'>Bank Name - " . $bank_name . ", Account No. " . $acno . ",  IFSC Code- " . $ifsc . ".</div>
                            </div>";
                //return $image_path;
                $html .= "<div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='150px'></p>
                            <p class='signtext'>Authorised Signature</p></div>
                            </div>
                            </div>
                            </body>
                            ";
            }

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 160]]);

            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);
            $mpdf->SetDisplayMode('fullpage');
            $footer = "<div class='footer'>";
            if (session('head_office') != "") {
                $footer .= "<p>Head Office : " . session('head_office') . ", Our Branches:  " . session('company_branch') . "</p>";
            }


            $footer .= "<table class='footer-tbl'>
                  <tr>";
            if (session('company_contact') != "") {
                $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>";
            }
            if (session('company_email') != "") {
                $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>";
            }
            if (session('website_url') != "") {
                $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>";
            }
            if (session('facebook_url') != "") {
                $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>";
            }
            if (session('youtube_url') != "") {
                $footer .= "<td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>";
            }
            $footer .= "</tr>
             </table>
               </div>";
            $mpdf->SetHTMLFooter($footer);
            $mpdf->WriteHTML($html);

            $mpdf->Output('INV-' . $id . '.pdf', 'D');
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }
    public function filter_invoice(Request $request)
    {
        try {
            $status = $request->value;
            $data = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.active', 'yes')->where('bill.status', $status)
                ->where('bill.company', session('company_id'))->orderBy('bill.bill_date', 'desc')->get();

            foreach ($data as $row) {
                $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
                $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
                 $discount_arr=json_decode($row->discount, true);
                $discount_arr = $discount_arr ?? [];
    
                $total_discount = array_sum(array_map('intval', (array) $discount_arr));
            
          
            
            $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note+$total_discount);
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
            $out = '<div class="action-dropdown-btn d-none">
    <div class="dropdown invoice-filter-action">
      <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="selection">Filter Invoice</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
        <a class="dropdown-item statusbtn"  data-value="partial">Partial Payment</a>
        <a class="dropdown-item statusbtn"  data-value="unpaid">Unpaid</a>
        <a class="dropdown-item statusbtn"  data-value="paid">Paid</a>
      </div>
    </div>
    <div class=" invoice-options">
      <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
      <i class="bx bx-plus"></i>Add Invoice</a>

    </div>
  </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table invoice-data-table dt-responsive wrap" >
                    <thead>
                        <tr>
                        <th></th>
                        <th></th>
                        <th>
                            <span class="align-middle">Invoice#</span>
                        </th>
                        <th>Action</th>
                        
                        <th>Client</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th>Seal</th>
                        <th>Sign</th>   
                        </tr>
                    </thead>
                
                    <tbody>';
            foreach ($data as $row) {
                $invoice_no=session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$row->year;
                $out .= '<tr>
                    <td></td>
                    <td></td>
                    <td>';
                     if(session('header_footer')=='no')
                     {
                        $out .= '<a href="generate_invoice-' . $row->id . '-tax">' . $invoice_no . '</a>';
                     }
                    else
                    {
                         $out .= '<a href="generate_invoice_UT-' . $row->id . '-tax">' . $invoice_no . '</a>';
                    }
                    $out .= '</td>
                    <td>
                        <div class="invoice-action">
                            <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                            <i class="bx bx-show-alt"></i>
                            </a> -->
                            ';
                           if(session('header_footer')=='no')
                            {
                            $out.= '<a href="generate_invoice-' . $row->id . '-tax" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                            <i class="bx bx-printer"></i>
                            </a>';
                            }
                            else
                            {
                                $out.= '<a href="generate_invoice_UT-' . $row->id . '-tax" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                    </a>'; 
                            }
                           $out.= '<a href="invoice_edit-' . $row->id . '" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Edit">
                            <i class="bx bx-edit"></i><a>
                              <a href="refund_list" class="invoice-action-edit btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1"
                                         data-tooltip="Refund">
                                        <i class="bx bx-wallet-alt"></i>
                                    </a>

                            <a  class="delete_invoice btn btn-icon rounded-circle glow btn-info mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                            </a>
                            <a data-toggle="modal" data-target="#default" class="invoice_payment_btn btn btn-icon rounded-circle glow btn-primary mr-1 mb-1" data-id="' . $row->id . '" data-amount="' . $row->payable . '" data-client_id="' . $row->client . '" data-tooltip="Payment">
                            <i class="bx bx-money"></i>
                            </a>
                            <a href="" class="invoice-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Send Mail">
                            <i class="bx bx-send"></i>
                            </a>
                            <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id="'.$row->id.'" data-payable="'.$row->payable.'" data-client_id="'.$row->client.'" data-invoice_no="'.$invoice_no.'" data-tooltip="write off">
                            <i class="bx bxs-credit-card-alt"></i>
                            </a>
                            <a type="button" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="'.$row->id.'" data-tooltip="Credit note">
                                <i class="bx bxs-credit-card"></i>
                            </a>

                        </div>
                        </td>
                  
                    <td><span class="invoice-customer">' . $row->client_case_no . ' </span></td>
                    <td>
                    
                    <small class="text-muted">' . nl2br($row->service) . '</small>
                    </td> 
                    <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
                if ($row->status == 'unpaid') {

                    $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
                } else if ($row->status == 'paid') {
                    $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
                } else {
                    $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
                }




                $out .= '<td data-sort="' . strtotime($row->bill_date) . '">' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                    <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                    <td>' . $row->seal . '</td>
                    <td>' . $row->name . '</td>  
                </tr>';
            }
            $out .= '</tbody>
                </table>
            </div>
        </div>
    </div>';

            return $out;
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function writeoff(Request $request)
    {
        try {
           
            $amount=$request->amount;
            $invoice_id=$request->invoice_id;
            $client_id=$request->client_id;
            $payable=$request->payable;
            $payment_date=$request->date;
            $payment_date = str_replace('/', '-', $payment_date);
            $payment_date = date('Y-m-d', strtotime($payment_date));
            $remark=$request->remark;
            $selected=$request->status;
            //$mode_of_payment=$request->mode_of_payment;
            $mode_of_payment='cash';
            $ref_no=$request->ref_no;
            $bank_name=$request->bank_name;
            $cheque_no=$request->cheque_no;
            $approval_status='approved';
            $tds=0;
            $bill_amt=DB::table('bill')->where('id',$invoice_id)->value('amount');
            $company=session('company_id');
            $insert=DB::table('payment')->insertGetId([
                'client_id' => $client_id,
                'bill_id' => json_encode(array($invoice_id)),
                'receipt_no' => (DB::table('payment')->where('company', $company)->orderBy('id', 'desc')->value('receipt_no')) + 1,
                'bill_amt' => $bill_amt,
                'payment_date' => $payment_date,
                'payment' => $amount,
                'tds' => $tds,
                'mode_of_payment' =>$mode_of_payment,
                'cheque_no' => $cheque_no,
                'reference_no' => $ref_no,
                'bank_name' =>$bank_name,
                'narration' => $remark,
                'status' => $approval_status,
                'company' => $company,
                'created_by' => session('user_id'),
                'created_at' => now(),
                'payment_source'=>'write_off'
            ]);

            if ($insert) {
                $find_prev_payment = DB::table('bill_payment_mapping')->where('bill_id', $invoice_id)->where('active', 'yes')->sum('paid_amount');
                $find_tds = DB::table('bill_payment_mapping')->where('bill_id',$invoice_id)->where('active', 'yes')->sum('tds_amount');
                $bill_actual_amt = DB::table('bill')->where('id',$invoice_id)->value('total_amount');
                $bill_actual_amt = $bill_actual_amt - ($find_prev_payment + $find_tds);
                log::info('bill_acual_amt ' . $bill_actual_amt);
                log::info('bill_amt ' . $invoice_id);
                if ($invoice_id < $bill_actual_amt) {
                  $status = 'partial';
                } else {
                  $status = 'paid';
                }
                $update = DB::table('bill')->where('id', $invoice_id)->update(['status' => $status, 'updated_at' => now()]);
                if($update)
                {
                    $insert1 = DB::table('bill_payment_mapping')->insert([
                        'bill_id' => $invoice_id,
                        'payment_id' => $insert,
                        'paid_amount' => $amount,
                        'tds_amount' => $tds
                      ]);
                }
                else
                {
                    return json_encode(array('status' => 'error', 'msg' => 'Bill status can`t be updated'));
                }
                if($insert1)
                {
                    if ($selected != 'Filter Invoice') 
                    {
                        $data = DB::table('bill')
                            ->join('clients', 'clients.id', 'bill.client')
                            ->join('staff', 'staff.sid', 'bill.sign')
                            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.status', $status)
                            ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->orderBy('bill.bill_date', 'desc')->get();
                    } else {
                        $data = DB::table('bill')
                            ->join('clients', 'clients.id', 'bill.client')
                            ->join('staff', 'staff.sid', 'bill.sign')
                            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                            ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->where('bill.status', '!=', 'paid')->orderBy('bill.bill_date', 'desc')->get();
                    }
                    foreach ($data as $row) {
                        $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                        $services_arr = json_decode($row->service);
                        $amount_arr = json_decode($row->amount);
                        $quotation_array = json_decode($row->quotation);
                        $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                        $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
                        $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
                        $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note);
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
                    $out = '<div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action">
                      <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="selection">Filter Invoice</span>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                        <a class="dropdown-item statusbtn"  data-value="partial">Partial Payment</a>
                        <a class="dropdown-item statusbtn"  data-value="unpaid">Unpaid</a>
                        <a class="dropdown-item statusbtn"  data-value="paid">Paid</a>
                      </div>
                    </div>
                    <div class=" invoice-options">
                      <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                      <i class="bx bx-plus"></i>Add Invoice</a>
                
                    </div>
                  </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table invoice-data-table dt-responsive wrap" >
                                    <thead>
                                        <tr>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <span class="align-middle">Invoice#</span>
                                        </th>
                                        <th>Action</th>
                                        
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Bill Date</th>
                                        <th>Due Date</th>
                                        <th>Seal</th>
                                        <th>Sign</th>   
                                        </tr>
                                    </thead>
                                
                                    <tbody>';
                                foreach ($data as $row) {
                                    $invoice_no=session('short_code') . '-' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT) . '/' .$row->year;
                                    $out .= '<tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                    <a href="generate_invoice-'.$invoice_no.'-tax">'.$invoice_no.'</a>
                                    </td>
                                    <td>
                                        <div class="invoice-action">
                                            <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                                            <i class="bx bx-show-alt"></i>
                                            </a> -->
                                            <a href="generate_invoice-' . $row->id . '-tax" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                                            <i class="bx bx-printer"></i>
                                            </a>
                                            <a href="invoice_edit-' . $row->id . '" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-id=' . $row->id . '  data-tooltip="Edit">
                                            <i class="bx bx-edit"></i></a>
                                              <a href="refund_list" class="invoice-action-edit btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1"
                                                         data-tooltip="Refund">
                                                        <i class="bx bx-wallet-alt"></i>
                                                    </a>
                                            <a  class="delete_invoice btn btn-icon rounded-circle glow btn-info mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                            </a>
                                            <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id="'.$row->id.'" data-payable="'.$row->payable.'" data-client_id="'.$row->client.'" data-invoice_no="'.$invoice_no.'" data-tooltip="write off">
                                        <i class="bx bxs-credit-card-alt"></i>
                                        </a>
                                        <a data-toggle="modal" data-target="#creditNoteModal" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="'.$row->id.'" data-payable="'.$row->payable.'" data-client_id="'.$row->client.'" data-invoice_no="'.$invoice_no.'" data-tooltip="Credit note">
                                            <i class="bx bxs-credit-card"></i>
                                        </a>

                                        </div>
                                        </td>
                                  
                                    <td><span class="invoice-customer">' . $row->client_case_no . ' </span></td>
                                    <td>
                                    
                                    <small class="text-muted">' . nl2br($row->service) . '</small>
                                    </td> 
                                    <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
                                    if ($row->status == 'unpaid') {
                
                                        $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
                                    } else if ($row->status == 'paid') {
                                        $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
                                    } else {
                                        $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
                                    }
                
                
                
                
                                    $out .= '<td data-sort="' . strtotime($row->bill_date) . '">' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                                    <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                                    <td>' . $row->seal . '</td>
                                    <td>' . $row->name . '</td>  
                                </tr>';
                                }
                                $out .= '</tbody>
                                </table>
                            </div>
                        </div>
                    </div>';
                
                                return json_encode(array('status' => 'success', 'msg' => 'Write off done successfully', 'out' => $out));
                }
                else
                {
                    return json_encode(array('status' => 'error', 'msg' => 'Bill mapping data can`t be inserted'));
                }
              
                        } else {
                            return json_encode(array('status' => 'error', 'msg' => 'write off can`t be done'));
                        }
            
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function credit_note(Request $request)
    {
        try {
             $request->all();
            $amount=$request->amount;
            $invoice_id=$request->invoice_id;
            $client_id=$request->client_id;
            $payable=$request->payable;
            $payment_date=$request->date;
            $payment_date = str_replace('/', '-', $payment_date);
            $payment_date = date('Y-m-d', strtotime($payment_date));
            $remark=$request->remark;
            $selected=$request->status;
            
            $tds=0;
            $bill_amt=DB::table('bill')->where('id',$invoice_id)->value('amount');
            $company=session('company_id');
            $insert=DB::table('credit_note')->insertGetId(['invoice_id'=>$invoice_id,'date'=>$payment_date,'amount'=>$amount,'active'=>'yes','remark'=>$remark]);

            if ($insert) {
                
                $update = DB::table('bill')->where('id', $invoice_id)->update(['credit_note' => $insert, 'updated_at' => now()]);
                if($update)
                {
                   
                    if ($selected != 'Filter Invoice') 
                    {
                        $data = DB::table('bill')
                            ->join('clients', 'clients.id', 'bill.client')
                            ->join('staff', 'staff.sid', 'bill.sign')
                            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('bill.status', $status)
                            ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->orderBy('bill.bill_date', 'desc')->get();
                    } else {
                        $data = DB::table('bill')
                            ->join('clients', 'clients.id', 'bill.client')
                            ->join('staff', 'staff.sid', 'bill.sign')
                            ->select('bill.*', 'clients.client_name', 'clients.case_no', 'staff.name')
                            ->where('bill.company', session('company_id'))->where('bill.active', 'yes')->where('bill.status', '!=', 'paid')->orderBy('bill.bill_date', 'desc')->get();
                    }
                    foreach ($data as $row) {
                        $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                        $services_arr = json_decode($row->service);
                        $amount_arr = json_decode($row->amount);
                        $quotation_array = json_decode($row->quotation);
                        $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                        $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
                        $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
                        $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note);
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
                    $out ='<div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action">
                      <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="selection">Filter Invoice</span>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                        <a class="dropdown-item statusbtn"  data-value="partial">Partial Payment</a>
                        <a class="dropdown-item statusbtn"  data-value="unpaid">Unpaid</a>
                        <a class="dropdown-item statusbtn"  data-value="paid">Paid</a>
                      </div>
                    </div>
                    <div class=" invoice-options">
                      <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                      <i class="bx bx-plus"></i>Add Invoice</a>
                
                    </div>
                  </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table invoice-data-table dt-responsive wrap" >
                                    <thead>
                                        <tr>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <span class="align-middle">Invoice#</span>
                                        </th>
                                        <th>Action</th>
                                        
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Bill Date</th>
                                        <th>Due Date</th>
                                        <th>Seal</th>
                                        <th>Sign</th>   
                                        </tr>
                                    </thead>
                                
                                    <tbody>';
                                foreach ($data as $row) {
                                    $invoice_no=session('short_code') . '-' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT) . '/' .$row->year;
                                    $out .= '<tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <a href="generate_invoice-'.$invoice_no.'-tax">'.$invoice_no.'</a>
                                    </td>
                                    <td>
                                        <div class="invoice-action">
                                            <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                                            <i class="bx bx-show-alt"></i>
                                            </a> -->
                                            <a href="generate_invoice-' . $row->id . '-tax" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                                            <i class="bx bx-printer"></i>
                                            </a>
                                            <a href="invoice_edit-' . $row->id . '" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-id=' . $row->id . '  data-tooltip="Edit">
                                            <i class="bx bx-edit"></i></a>
                                              <a href="refund_list" class="invoice-action-edit btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1"
                                                         data-tooltip="Refund">
                                                        <i class="bx bx-wallet-alt"></i>
                                                    </a>
                                            <a  class="delete_invoice btn btn-icon rounded-circle glow btn-info mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                            </a>
                                            <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id='.$row->id.' data-payable='.$row->payable.' data-client_id='.$row->client.' data-invoice_no='.$invoice_no.' data-tooltip="write off">
                                            <i class="bx bxs-credit-card-alt"></i>
                                            </a>
                                            <a data-toggle="modal" data-target="#creditNoteModal" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id='.$row->id.' data-payable='.$row->payable.' data-client_id='.$row->client.' data-invoice_no='.$invoice_no.' data-tooltip="Credit note">
                                                <i class="bx bxs-credit-card"></i>
                                            </a>
                                        </div>
                                        </td>
                                  
                                    <td><span class="invoice-customer">' . $row->client_case_no . ' </span></td>
                                    <td>
                                    
                                    <small class="text-muted">' . nl2br($row->service) . '</small>
                                    </td> 
                                    <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
                                    if ($row->status == 'unpaid') {
                
                                        $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
                                    } else if ($row->status == 'paid') {
                                        $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
                                    } else {
                                        $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
                                    }
                
                
                
                
                                    $out .= '<td data-sort="' . strtotime($row->bill_date) . '">' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                                    <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                                    <td>' . $row->seal . '</td>
                                    <td>' . $row->name . '</td>  
                                </tr>';
                                }
                                $out .= '</tbody>
                                </table>
                            </div>
                        </div>
                    </div>';
                
                                return json_encode(array('status' => 'success', 'msg' => 'Credit Note done successfully', 'out' => $out));
                
                }
                else
                {
                    return json_encode(array('status' => 'error', 'msg' => 'Bill credit note id can`t be updated'));
                }
                
              
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Credit Note can`t be done'));
            }
            
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function convert_to_tax_invoice(Request $request)
    {
        try {
             $data = DB::table('proforma_invoice')->where('id', $request->id)->first();
             $sess = '';
             $prev_yr=substr (date('Y')-1,-2);
             $cur_yr=substr (date('Y'),2);
             $nxt_yr=substr (date('Y')+1,-2);
             if(date('m')<=3)
             {
                $sess=$prev_yr.'-'.$cur_yr;
             }
             else
             {
                $sess=$cur_yr.'-'.$nxt_yr;
             }
            
            
             $invoice_no  = (DB::table('bill')->where('company', $data->company)->where('year',$sess)->orderBy('id', 'desc')->value('invoice_no')) + 1;
             $data1 =   ['invoice_no' => $invoice_no,
                         'proforma_invoice_id' => $data->id,
                        'client' =>$data->client,
                        'year'=>$sess,
                        'company' => $data->company,
                        'bill_date' => $data->bill_date,
                        'due_date' => $data->due_date,
                        'seal' => $data->seal,
                        'sign' => $data->sign,
                        'quotation' => $data->quotation,
                        'service' =>$data->service,
                        'amount' =>$data->amount,
                        'total_amount' => $data->total_amount,
                        'bank' => $data->bank,
                        'company' => $data->company,
                        'discount' =>$data->discount,
                        'gst_per' =>$data->gst_per,
                        'gst_amount' =>$data->gst_amount,
                        'cgst_per' =>$data->cgst_per,
                        'cgst_amount' =>$data->cgst_amount,
                        'igst_per' =>$data->igst_per,
                        'igst_amount' =>$data->igst_amount,
                        'total' =>$data->total,
                        'round_check' =>$data->round_check,
                        'credit_note'=>$data->credit_note,
                        'description'=>$data->description];
            $add_proforma_tax =  DB::table('bill')->insert($data1);
            if ($add_proforma_tax) {
                DB::table('proforma_invoice')->where('id', $request->id)->update(['convert_tax'=>'yes']);
                return json_encode(array('status' => 'success','msg' =>'Convert to Tax Invoice successfully'));
            }else {
                return json_encode(array('status' => 'error', 'msg' => 'Convert to Tax Invoice can`t be deleted'));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }

    public function delete_proforma_invoice(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            $delete = DB::table('proforma_invoice')->where('id', $id)->update(['active' => 'no','status' => 'cancel', 'updated_at' => now()]);
            if ($delete) {
                return json_encode(array('status' => 'success', 'msg' => 'Proforma Invoice deleted successfully'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Proforma Invoice can`t be deleted '));
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }

    public function filter_proforma_invoice(Request $request)
    {
        try {
            $status = $request->value;
            $data = DB::table('proforma_invoice')
                ->join('clients', 'clients.id', 'proforma_invoice.client')
                ->join('staff', 'staff.sid', 'proforma_invoice.sign')
                ->select('proforma_invoice.*', 'clients.client_name', 'clients.case_no', 'staff.name')->where('proforma_invoice.active', 'yes')->where('proforma_invoice.status', $status)
                ->where('proforma_invoice.company', session('company_id'))->orderBy('proforma_invoice.bill_date', 'desc')->get();

            foreach ($data as $row) {
                $row->client_case_no = $this->get_client_case_no_by_id($row->client);
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $paid_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('paid_amount');
                $tds_amt = DB::table('bill_payment_mapping')->where('bill_id', $row->id)->where('active', 'yes')->sum('tds_amount');
                $credit_note=DB::table('credit_note')->where('id',$row->credit_note)->where('active','yes')->value('amount');
                $row->payable = $row->total_amount - ($paid_amt + $tds_amt+$credit_note);
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
            $out = '<div class="action-dropdown-btn d-none">
    <div class="dropdown invoice-filter-action">
      <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="selection">Filter Invoice</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
        <a class="dropdown-item statusbtn"  data-value="partial">Partial Payment</a>
        <a class="dropdown-item statusbtn"  data-value="unpaid">Unpaid</a>
        <a class="dropdown-item statusbtn"  data-value="paid">Paid</a>
      </div>
    </div>
    <div class=" invoice-options">
      <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
      <i class="bx bx-plus"></i>Add Invoice</a>

    </div>
  </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table invoice-data-table dt-responsive wrap" >
                    <thead>
                        <tr>
                        <th></th>
                        <th></th>
                        <th>
                            <span class="align-middle">Invoice#</span>
                        </th>
                        <th>Action</th>
                        
                        <th>Client</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th>Seal</th>
                        <th>Sign</th>   
                        </tr>
                    </thead>
                
                    <tbody>';
            foreach ($data as $row) {
                $invoice_no=session('short_code') . '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' .$row->year;
                $out .= '<tr>
                    <td></td>
                    <td></td>
                    <td>';
                     if(session('header_footer')=='no')
                     {
                        $out .= '<a href="generate_invoice-' . $row->id . '-proforma">' . $invoice_no . '</a>';
                     }
                    else
                    {
                         $out .= '<a href="generate_invoice_UT-' . $row->id . '-proforma">' . $invoice_no . '</a>';
                    }
                    $out .= '</td>
                    <td>
                        <div class="invoice-action">
                            <!-- <a href="' . asset('app/invoice/view') . '" class="invoice-action-view mr-1">
                            <i class="bx bx-show-alt"></i>
                            </a> -->     ';
                           if(session('header_footer')=='no')
                            {
                            $out.= '<a href="generate_invoice-' . $row->id . '-proforma" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                            <i class="bx bx-printer"></i>
                            </a>';
                            }
                            else
                            {
                                $out.= '<a href="generate_invoice_UT-' . $row->id . '-proforma" class="invoice-action-view btn btn-icon rounded-circle glow btn-danger mr-1 mb-1" data-invoice_id=' . $row->id . ' data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                    </a>'; 
                            }
                            $out.='<a href="invoice_edit-' . $row->id . '" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Edit">
                            <i class="bx bx-edit"></i><a>
                            <a  class="delete_invoice btn btn-icon rounded-circle glow btn-info mr-1 mb-1" data-id=' . $row->id . ' data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                            </a>
                            <a type="button" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="'.$row->id.'" data-tooltip="Credit note">
                                <i class="bx bxs-credit-card"></i>
                            </a>

                        </div>
                        </td>
                  
                    <td><span class="invoice-customer">' . $row->client_case_no . ' </span></td>
                    <td>
                    
                    <small class="text-muted">' . nl2br($row->service) . '</small>
                    </td> 
                    <td><span class="invoice-amount">&#8377;' . number_format($row->payable, 2) . '</span></td>';
                if ($row->status == 'unpaid') {

                    $out .= '<td><span class="badge badge-light-danger badge-pill">' . $row->status . '</span></td>';
                } else if ($row->status == 'paid') {
                    $out .= '<td><span class="badge badge-light-success badge-pill">' . $row->status . '</span></td>';
                } else {
                    $out .= '<td><span class="badge badge-light-warning badge-pill">' . $row->status . '</span></td>';
                }
                $out .= '<td data-sort="' . strtotime($row->bill_date) . '">' . date('d-m-Y', strtotime($row->bill_date)) . '</td>
                    <td>' . date('d-m-Y', strtotime($row->due_date)) . '</td>
                    <td>' . $row->seal . '</td>
                    <td>' . $row->name . '</td>  
                </tr>';
            }
            $out .= '</tbody>
                </table>
            </div>
        </div>
    </div>';

            return $out;
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function invoice_preview(Request $request){
        $title = 'Invoice No';
        if($request->invoice_type == 'tax'){
            $title = 'Invoice No';
        }
        if($request->invoice_type == 'proforma'){
            $title = 'PI No';
        }
        require_once base_path('vendor/autoload.php');
        $html = "<style>
                      body{
                          font-family: 'Ubuntu', sans-serif;
                      }
                      .main {
                          margin:15;
                      }
                      .head{
                          background-color:#000;
                          padding:2px 10px 2px 10px;
                          overflow:hidden;
                      }
                      .logo{
                          float:left;
                      }
                      .logo img{
                          width: 80px;
                          margin-top: 0px;
                      }
                      .add{
                          float:right;
                      }
                  
                      .main h4{
                          text-align:center;
                          margin: 0px 0px 0px 0px;
                          font-size: 20px;
                      }
                      p.signtext{
                          text-align: right;
                          margin-left: 30px;
                          margin-bottom:0px;
                          margin-top:0px;
                      }
                      
                      .footer{
                          margin-top:20px;
                      }
                      .footer p{
                          font-size:13px;
                          text-align:center;
                          border-bottom: 1px solid #faa41a;
                          padding-bottom:5px;
                          margin:0px;
                      }
                      .footer ul{
                          margin: 6px 0px 0px 0px;
                      }
                      .footer ul li{
                          display:inline-block;
                          font-size:13px;
                      }
                      .footer li{
                          padding-right:24px;
                      }
                      .footer li i{
                          color:#d58504;
                      }
                      .abc {
                          border-collapse: collapse;
                      }
                      .abc th, td {
                          padding: 6px;
                          text-align: left;
                          border: 1px solid #ddd;
                      }
                      .footer-tbl
                      {
                          border-collapse: collapse;
                          margin-bottom:20;
                      }
                      #leftbox { 
                          float:left;  
                      
                          width:75%; 
                          font-size:14px
                      } 
                      
                      #rightbox{ 
                          float:right; 
                      
                          width:25%; 
                          
                      }
                      </style>";
                $bank_id = $request->bank;
                $bank_name = DB::table('bank_detailes')->where('id', $bank_id)->value('bankname');
                $ifsc = DB::table('bank_detailes')->where('id', $bank_id)->value('ifsccode');
                $acno = DB::table('bank_detailes')->where('id', $bank_id)->value('accnumber');
                $company_name = DB::table('company')->where('id', $request->company)->value('company_name');
                $company_name = ucwords($company_name);
                $client = DB::table('clients')->where('id', $request->client)->first();
                $client_name = $client->client_name;
                $address = $client->address;
                $services_arr = $request->service;
                $amount_arr = $request->amount;
                $quotation_array =$request->quotation;
                $short_code = DB::table('company')->where('id', $request->company)->value('short_code');
                $bill_date = str_replace("/","-",$request->bill_date);
                $due_date = str_replace("/","-",$request->due_date);
                $jd = gregoriantojd(date('m', strtotime($bill_date)), date('d', strtotime($bill_date)), date('Y', strtotime($bill_date)));
                $month_name = jdmonthname($jd, 0);
                $jd1 = gregoriantojd(date('m', strtotime($due_date)), date('d', strtotime($due_date)), date('Y', strtotime($due_date)));
                $due_month_name = jdmonthname($jd1, 0);
                $city_name = DB::table('city')->where('id', $request->city)->value('city_name');

                if ($city_name == '') {
                    $city_name = $request->city;
                }
                $total_amt = array_sum($request->total_amount);
                $seal = $request->seal;
                $sign = $request->sign;
                $sign_name = DB::table('staff')->where('sid', $sign)->value('name');
                $sign_name = str_replace(" ", "_", $sign_name);
                $image_path = 'images/invoice_img/sign/' . $request->seal . '_' . $sign_name . '.png';

                $sess = '';
                $prev_yr=substr (date('Y')-1,-2);
                $cur_yr=substr (date('Y'),2);
                $nxt_yr=substr (date('Y')+1,-2);
                if(date('m')<=3)
                {
                   $sess=$prev_yr.'-'.$cur_yr;
                }
                else
                {
                   $sess=$cur_yr.'-'.$nxt_yr;
                }
               if($request->invoice_type == 'tax'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($request->invoice_no, 4, '0', STR_PAD_LEFT);
               }
               if($request->invoice_type == 'proforma'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($request->invoice_no, 4, '0', STR_PAD_LEFT);
               }
                $tax_applicable = DB::table('company')->where('id', $request->company)->value('tax_applicable');
                $total_discount = 0;
                $total_gst_amt = 0;
                $total_cgst_amt = 0;
                $total_igst_amt = 0;
                if ($request->discount != "" && $request->discount != "null") {
                    $total_discount = array_sum($request->discount);
                }

                if ($request->gst_amount != '' && $request->gst_amount != "null") {
                    $total_gst_amt = array_sum($request->gst_amount);
                }
                if ($request->cgst_amount != '' && $request->cgst_amount != "null") {
                    $total_cgst_amt = array_sum($request->cgst_amount);
                }
                if ($request->igst_amount != '' && $request->igst_amount != "null") {
                    $total_igst_amt = array_sum($request->igst_amount);
                }
                $total_tax = $total_gst_amt + $total_cgst_amt + $total_igst_amt;

                       $html .= "
                              <body>
                              <table class='head' width='100%'>
                              <tr>
                              <td class='logo' style='border:none' width='65%'>";
                if (session('company_id') == 3) {
                    $html .= "<img width='150px' src='" . session('company_logo') . "'>";
                } else {
                    $html .= "<img width='80px' src='" . session('company_logo') . "'>";
                }

                $html .= "</td>
                  <td  class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
                              <p style='color:#fff;
                              padding-left: 10px;
                              margin: 10px 0px 0px 0px;
                              font-size: 15px;'>
                                 " . session('company_address') . "
                              </p>
                          </td>
                              </tr>
                                  
                                  </table>
                              <div class='main'>";


                if ($tax_applicable == 'yes' || $request->invoice_type == 'tax') {
                    $html .= "<h4 style='margin-bottom:10px'><u>Tax Invoice</u></h4>";
                } else if($request->invoice_type == 'proforma'){
                    $html .= "<h4 style='margin-bottom:10px'><u>Proforma Invoice</u></h4>";
                }else{
                    $html .= "<h4 style='margin-bottom:10px'><u>Invoice</u></h4>";
                }

                $html .= "<table width='100%' style='border:none'>
                                  <tr>
                                  <td style='border:none;font-family: 'Ubuntu', sans-serif'>$title. <strong>$bill_no</strong></td>
                                  <td style='border:none' align='center'>Invoice Date: <strong>" . date('d', strtotime($bill_date)) . '-' . $month_name . '-' . date('Y', strtotime($bill_date)) . "</strong></td>
                                  <td style='border:none' align='right'>Due Date: <strong>" . date('d', strtotime($due_date)) . '-' . $due_month_name . '-' . date('Y', strtotime($due_date)) . "</strong></td>
                                  
                                  </tr>
                              </table>
                             <table width='100%' style='border:none'>
                                  <tr>
                                      <td style='border:none'>Invoice To: <strong>$client_name</strong>, $address, $city_name</td>
                                  </tr>
                              </table>
                              
                              
                              <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                                  <tr>
                                  <th style='text-align:center;'>S. No.</th>
                                  <th style='text-align:center;'>Particulars</th>
                                  <th style='text-align:center;'>Amount (INR)</th>
                                  </tr>";
                $a = 1;
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];

                        $html .= "<tr>
                                  <td style='text-align:center;'>" . $a++ . "</td>
                                  <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>

                                  <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $html .= "<tr>
                                      <td style='text-align:center;'>" . $a++ . "</td>
                                      <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>
                                      <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                }

              if($total_discount > 0){
                $html .= "<tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Discount</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_discount, 2) . "</strong></td>
                                  </tr>";
                }                  
                if($total_tax >0){
                    $html .= "<tr>
                    <td>&nbsp;</td>
                    <td style='text-align:right;'><strong>Tax</strong></td>
                    <td style='text-align:right;'><strong>" . number_format($total_tax, 2) . "</strong></td>
                    </tr>";
               }
               $html .=" <tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Total</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_amt, 2) . "</strong></td>
                                  </tr>
                                  <tr>
                                  <td style='text-align:center;'><strong>In  Words</strong></td>
                                  <td colspan='2'><strong>" . $this->displaywords($total_amt) . "</strong></td>
                                  </tr>
                              </table>
                              
                              <br>
                              
                              
                              <div id='leftbox'>
                              
                              <div style='padding-top:3px'><b>Note : </b>1). This is the Computer generated Invoice.</div>
                              <div style='margin-left:49px;padding-top:3px'>2). Cheque Should be Drawn in the Name of '" . $company_name . "' .</div>
                              
                              
                              <div style='margin-left:50px;'>Bank Name - <b>" . $bank_name . "</b>, Account No. <b>" . $acno . "</b>,  IFSC Code- <b>" . $ifsc . "</b>.</div>
                              <div style='margin-left:49px;padding-top:3px'>3).PAN No: " . session('pan_no') . ".</div>";
                              
                             
                             $html .= " </div>";


                $html .= "<div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='150px'></p>
                              <p class='signtext'>Authorised Signature</p></div>
                              </div>
                              </div>
                              </body>
                              ";
            if ($a > 3) {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 195]]);
            } else {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 170]]);
            }
            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);

            if ($request->company != 3) {
                $ofc = "";
                if (session('head_office') == "") {
                    $ofc .= "";
                } else {
                    $ofc .= "Head Office : " . session('head_office');
                }
                if ($ofc != "") {
                    $ofc .= ", ";
                }
                if (session('company_branch') == "" || session('company_branch') == 'N/A') {
                    $ofc .= "";
                } else {
                    $ofc .= "Our Branches: " . session('company_branch');
                }
                $mpdf->SetHTMLFooter("<div class='footer'>
                          <p>" . $ofc . "</p>
                              <table class='footer-tbl'>
                                  <tr>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/call.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_contact') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'> <img src='images/invoice_img/mail.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('company_email') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/web.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('website_url') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/f.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('facebook_url') . "</td>
                                  <td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'><img src='images/invoice_img/y.jpg'></td><td style=' border:none;font-family: 'Ubuntu', sans-serif;font-size:13px;'>" . session('youtube_url') . "</td>
                                  </tr>
                              </table>
                      </div>");
            }
            
            $mpdf->SetWatermarkText('PREVIEW');
            $mpdf->showWatermarkText = true;

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);

            $mpdf->Output();
    }
   public function client_invoices(Request $request)
    {
          $v = Validator::make($request->all(), [
            "client_id" => "numeric|required",
            
        ]);
        if ($v->fails()) {
            return $v->errors();
        }
        try {
          
            $client_id = $request->client_id;
            $tax_inv=DB::table('bill')->where('client',$client_id)->where('active','yes')->get();
            foreach($tax_inv as $row)
            {
                $row->pdf=url('/').'/generate_invoice-'.$row->id.'-tax';
                $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
                $row->invoice_no=$short_code. '-' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT) . '/' .$row->year;
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $service='';
                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser. ',';
                        $service=rtrim($service,',');
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];
                        $service .= $ser.',';
                        $service=rtrim($service,',');
                    }
                }
                 $row->service= $service;
            }
            $data['tax_invoice']=$tax_inv;
           
            $pro_inv=DB::table('proforma_invoice')->where('client',$client_id)->where('active','yes')->where('convert_tax','no')->where('status','!=','cancel')->get();
            foreach($pro_inv as $row2)
            {
                $row2->pdf=url('/').'/generate_invoice-'.$row2->id.'-proforma';
                $short_code = DB::table('company')->where('id', $row2->company)->value('short_code');
                $row2->invoice_no=$short_code. '-' . str_pad($row2->invoice_no, 4, '0', STR_PAD_LEFT) . '/' .$row2->year;
                $services_arr = json_decode($row2->service);
                $amount_arr = json_decode($row2->amount);
                $quotation_array = json_decode($row2->quotation);
                $service='';
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
               $row2->service= $service;
            }
            $data['proforma_invoice']=$pro_inv;
             return response()->json(array('status' => 'success', 'data' =>$data));
            
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Error'));
        }
    }
    public function get_card_invoice(Request $request)
        {
            try {
                $v = Validator::make($request->all(), [
                    'month' => 'required|numeric',
                ]);
    
                if ($v->fails()) {
                    return $v->errors();
                }
                $month=$request->month;
                $year=date('Y');
                $tax=DB::table('bill')->whereMonth('bill_date',$month)->whereYear('bill_date',$year)->where('active','yes')->sum('total_amount');
                $proforma=DB::table('proforma_invoice')->whereMonth('bill_date',$month)->whereYear('bill_date',$year)->where('convert_tax','no')->where('active','yes')->sum('total_amount');
                $tax=$this->IND_money_format($tax);
                $proforma=$this->IND_money_format($proforma);
                $data=array('tax'=>$tax,'proforma'=>$proforma);
                return response()->json(array('status' => 'success', 'data' => $data));
            } catch (QueryException $e) {
                Log::error("Database error ! [" . $e->getMessage() . "]");
                return response()->json(array('error' => 'Database error'));
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return response()->json(array('error' => 'Error'));
            }
        }
         public function generate_invoice_UT(Request $request)
        {

        try {
            $id=$request->id;
            $type=$request->type;
            $title = 'Invoice No';
            if($type == 'tax'){
                $title = 'Invoice No';
                $data = DB::table('bill')
                ->join('clients', 'clients.id', 'bill.client')
                ->join('staff', 'staff.sid', 'bill.sign')
                ->select('bill.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('bill.id', $id)->get();
            }
            if($type == 'proforma'){
                $title = 'PI No';
                $data = DB::table('proforma_invoice')
                ->join('clients', 'clients.id', 'proforma_invoice.client')
                ->join('staff', 'staff.sid', 'proforma_invoice.sign')
                ->select('proforma_invoice.*', 'clients.client_name', 'clients.address', 'clients.city', 'staff.name')->where('proforma_invoice.id', $id)->get();
            }
            require_once base_path('vendor/autoload.php');
            //return session('company_logo');
            $html = "<style>
            .logo{
                float: left;
            }
            body
                {
                    font-family: 'Ubuntu', sans-serif;
                }
                .main 
                {
                    
                    margin:15;
                }
                
                
            .add{
                float:right;
            }
                
            .main h4{
                text-align:center;
                margin: 0px 0px 0px 0px;
                font-size: 20px;
            }
            p.signtext{
                text-align: right;
                margin-left: 30px;
                margin-bottom:0px;
                margin-top:0px;
            }


            .abc {
                border-collapse: collapse;
            }
            .abc th, td {
                padding: 6px;
                text-align: left;
                border: 1px solid #ddd;
            }
            .footer {
            width: 100%;
            padding: 14px 12px;
            background-color: #000;
            }
                    .footer_tbl {
                margin: auto;
                border-collapse: collapse;
                text-align: center;
                font-family: Georgia, serif;
                color: white;
                width: 100%;
                padding: 12px 10px;
            }

            .footer_tbl td {
                padding: 5px 20px;
                border: none;
                background:black;
                color:white;
                font-size:16px;
            }

            .footer_tbl a {
                color: #fff;
                
                font-weight: bold;
            }

            .footer_tbl a:hover {
                text-decoration: underline;
            }

            .footer_tbl img.icon {
                vertical-align: middle;
                width: 20px;
                height: 20px;
                margin-right: 5px;
            }

            .footer_address {
                padding-top: 20px;
                color: white;
            }
            

                #leftbox
                { 
                float:left;  
                width:50%; 
                } 
                            
                #rightbox{ 
                    float:right; 
                    
                    width:50%; 
                    
                    
                }
                .header-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 5px solid #d4ad7f;
                padding: 10px 0;
                }
                .header-top img.logo {
                height: 50px;
                }
                .header-contact {
                
                padding-left: 15px;
                font-size: 16px;
                }
                .header-contact div {
                margin-bottom: 5px;
                }
                .header-contact img.icon {
                height: 14px;
                vertical-align: middle;
                margin-right: 5px;
                }
                </style>";
            foreach ($data as $row) {
                $active = $row->active;
                $bank_id = $row->bank;
                $bank_name = DB::table('bank_detailes')->where('id', $bank_id)->value('bankname');
                $ifsc = DB::table('bank_detailes')->where('id', $bank_id)->value('ifsccode');
                $acno = DB::table('bank_detailes')->where('id', $bank_id)->value('accnumber');
                $company_name = DB::table('company')->where('id', $row->company)->value('company_name');
                $company_name = ucwords($company_name);
                $services_arr = json_decode($row->service);
                $amount_arr = json_decode($row->amount);
                $quotation_array = json_decode($row->quotation);
                $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
                $jd = gregoriantojd(date('m', strtotime($row->bill_date)), date('d', strtotime($row->bill_date)), date('Y', strtotime($row->bill_date)));
                $month_name = jdmonthname($jd, 0);
                $jd1 = gregoriantojd(date('m', strtotime($row->due_date)), date('d', strtotime($row->due_date)), date('Y', strtotime($row->due_date)));
                $due_month_name = jdmonthname($jd1, 0);
                $city_name = DB::table('city')->where('id', $row->city)->value('city_name');
                if ($city_name == '') {
                    $city_name = $row->city;
                }
                $total_amt = $row->total_amount;


                $seal = $row->seal;
                $sign = $row->sign;
                $sign_name = DB::table('staff')->where('sid', $sign)->value('name');
                $sign_name = str_replace(" ", "_", $sign_name);
                $image_path = 'images/invoice_img/sign/UT_uma_tripathi.png';

                $sess = '';
                $prev_yr=substr (date('Y')-1,-2);
                $cur_yr=substr (date('Y'),2);
                $nxt_yr=substr (date('Y')+1,-2);
                
                $sess=$row->year;
               if($type == 'tax'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
               }
               if($type == 'proforma'){
                $bill_no = $short_code . '/' . $sess . '/' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
               }
                $tax_applicable = DB::table('company')->where('id', $row->company)->value('tax_applicable');
                $total_discount = 0;
                $total_gst_amt = 0;
                $total_cgst_amt = 0;
                $total_igst_amt = 0;
                $desc=$row->description;
                if ($row->discount != "" && $row->discount != "null") {
                    $total_discount = array_sum(json_decode($row->discount));
                }

                if ($row->gst_amount != '' && $row->gst_amount != "null") {
                    $total_gst_amt = array_sum(json_decode($row->gst_amount));
                }
                if ($row->cgst_amount != '' && $row->cgst_amount != "null") {
                    $total_cgst_amt = array_sum(json_decode($row->cgst_amount));
                }
                if ($row->igst_amount != '' && $row->igst_amount != "null") {
                    $total_igst_amt = array_sum(json_decode($row->igst_amount));
                }
                $total_tax = $total_gst_amt + $total_cgst_amt + $total_igst_amt;
                $html .= "<div class='header-top'>
                            <table class='header-contact' width='100%'>
                                <tr>
                                    <td  style='border:none' width='75%'>
                                    <img src=".base_path(session('company_logo'))." data-holder-rendered='true' />
                                    </td>
                                    <td class='add' style='color:#fff;border:none;border-left: 2px solid #1e1d1cff' width='35%'>
                                        <p style='color:#000;padding-left: 10px;font-size: 15px;'>
                                            <img src=".base_path('images/invoice_img/mailicon.jpg')." data-holder-rendered='true' /> legal@dearsociety.in
                                        </p>
                                        <p style='color:#000;padding-left: 10px;margin: 10px 0px 0px 0px;font-size: 15px;'><img src=".url('images/invoice_img/callicon.jpg')." data-holder-rendered='true' /> +91 7020876285</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                              <div class='main'>";


                if ($tax_applicable == 'yes' || $type == 'tax') {
                    $html .= "<h4 style='margin-bottom:10px'><u>Tax Invoice</u></h4>";
                } else if($type == 'proforma'){
                    $html .= "<h4 style='margin-bottom:10px'><u>Proforma Invoice</u></h4>";
                }else{
                    $html .= "<h4 style='margin-bottom:10px'><u>Invoice</u></h4>";
                }

                $html .= "<table width='100%' style='border:none'>
                                  <tr>
                                  <td style='border:none;font-family: 'Ubuntu', sans-serif'>$title. <strong>$bill_no</strong></td>
                                  <td style='border:none' align='center'>Invoice Date: <strong>" . date('d', strtotime($row->bill_date)) . '-' . $month_name . '-' . date('Y', strtotime($row->bill_date)) . "</strong></td>
                                  <td style='border:none' align='right'>Due Date: <strong>" . date('d', strtotime($row->due_date)) . '-' . $due_month_name . '-' . date('Y', strtotime($row->due_date)) . "</strong></td>
                                  
                                  </tr>
                              </table>
                              <table width='100%' style='border:none'>
                                  <tr>
                                      <td style='border:none'>Invoice To: <strong>$row->client_name</strong>, $row->address, $city_name</td>
                                  </tr>
                              </table>
                              
                              
                              <table  class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                                  <tr>
                                  <th style='text-align:center;'>S. No.</th>
                                  <th style='text-align:center;'>Particulars</th>
                                  <th style='text-align:center;'>Amount (INR)</th>
                                  </tr>";
                $a = 1;


                if ($services_arr != '') {
                    for ($i = 0; $i < sizeof($services_arr); $i++) {

                        $ser = DB::table('services')->where('id', $services_arr[$i])->value('name');
                        $amt = $amount_arr[$i];

                        $html .= "<tr>
                                  <td style='text-align:center;'>" . $a++ . "</td>
                                  <td style='text-align:center;font-family: hindi'>" . ucwords($ser) . "<br>".$desc."</td>

                                  <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                } else {
                    for ($i = 0; $i < sizeof($quotation_array); $i++) {
                        $service_id = DB::table('quotation_details')->where('id', $quotation_array[$i])->value('task_id');
                        $ser = DB::table('services')->where('id', $service_id)->value('name');
                        $amt = $amount_arr[$i];



                        $html .= "<tr>
                                      <td style='text-align:center;'>" . $a++ . "</td>
                                      <td style='text-align:center;font-family: hindi'><small><b>" . ucwords($ser) . "</b></small><br>".$desc."</td>
                                      <td style='text-align:right;'>" . number_format($amt, 2) . "</td>
                                  </tr>";
                    }
                }

              if($total_discount > 0){
                $html .= "<tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Discount</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_discount, 2) . "</strong></td>
                                  </tr>";
                }                  
                if($total_tax >0){
                    $html .= "<tr>
                    <td>&nbsp;</td>
                    <td style='text-align:right;'><strong>Tax</strong></td>
                    <td style='text-align:right;'><strong>" . number_format($total_tax, 2) . "</strong></td>
                    </tr>";
               }


                                 
               $html .=" <tr>
                                  <td>&nbsp;</td>
                                  <td style='text-align:right;'><strong>Total</strong></td>
                                  <td style='text-align:right;'><strong>" . number_format($total_amt, 2) . "</strong></td>
                                  </tr>
                                  <tr>
                                  <td style='text-align:center;'><strong>In  Words</strong></td>
                                  <td colspan='2'><strong>" . $this->displaywords($total_amt) . "</strong></td>
                                  </tr>
                              </table>
                              
                              <br>
                              
                              
                              <div id='leftbox'>
                              
                              <div style='padding-top:3px'><b>Note : </b>1). This is the Computer generated Invoice.</div>
                              <div style='margin-left:49px;padding-top:3px'>2). Cheque Should be Drawn in the Name of '" . $company_name . "' .</div>
                              
                              
                              <div style='margin-left:50px;'>Bank Name - <b>" . $bank_name . "</b>, Account No. <b>" . $acno . "</b>,  IFSC Code- <b>" . $ifsc . "</b>.</div>
                              <div style='margin-left:49px;padding-top:3px'>3).PAN No: " . session('pan_no') . ".</div>
                              </div>";


                $html .= "<div id='rightbox'> <p class='signtext' ><img src='" . base_path($image_path) . "' width='150px'></p>
                              <p class='signtext'>Authorised Signature</p></div>
                              </div>
                              </div>
                              </body>
                              ";
            }
            if ($a > 3) {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 195]]);
            } else {
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 170]]);
            }


            $mpdf->AddPage('p', '', '', '', '', 0, 0, 0, 0, 0, 2);

            if ($row->company != 3) {
                $ofc = "";
                if (session('head_office') == "") {
                    $ofc .= "";
                } else {
                    $ofc .= "Head Office : " . session('head_office');
                }
                if ($ofc != "") {
                    $ofc .= ", ";
                }
                if (session('company_branch') == "" || session('company_branch') == 'N/A') {
                    $ofc .= "";
                } else {
                    $ofc .= "Our Branches: " . session('company_branch');
                }
                   $mpdf->setHTMLFooter("<div class='footer'>
            <table class='footer_tbl'>
            <tr>
                <td style='text-align:right'>
                
                    <img src=". base_path('images/invoice_img/insta.jpg')." class='icon' alt='Instagram' />
                    uma_tripa
                
                </td>
                <td style='text-align:left'>
                
                    <img src=". base_path('images/invoice_img/linkdin.jpg')." class='icon' alt='LinkedIn' />
                    Adv Uma Tripathi
                
                </td>
            </tr>
            <tr >
                <td colspan='2' class='footer_address' style='text-align:center'>
                Office No 213, City Avenue, Shankar Kalat Nagar, Bangalore - Mumbai Highway, Wakad, Pimpri - Chinchwad Maharastra - 411057
                </td>
            </tr>
            </table>
            </div>");
            }
            if ($active == 'no') {
                $mpdf->SetWatermarkText('CANCELLED');
                $mpdf->showWatermarkText = true;
            }

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);

            $mpdf->Output();
        } catch (QueryException $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later')->withInput($request->all);
        }
    }
}
