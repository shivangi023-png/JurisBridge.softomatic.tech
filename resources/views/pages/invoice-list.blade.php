@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Invoice List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>

@endsection

@section('content')
<!-- invoice list -->
<section class="invoice-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
    <div class="card">
        <div class="card-body">
    @include('layouts.tabs')
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <div class="alert bg-rgba-{{ $msg }} alert-dismissible mb-2" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="d-flex align-items-center">
            @if(Session::has('alert-success'))
            <i class="bx bx-like"></i>
            @else
            <i class="bx bx-error"></i>
            @endif
            <span>
                {{ Session::get('alert-' . $msg) }}
            </span>
        </div>
    </div>
    @endif
    @endforeach
    <div id="alert">


    </div>
    <div class="data_div">
        <div class="action-dropdown-btn d-none">
            <div class="dropdown invoice-filter-action">
                <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="selection">Filter Invoice</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                    <a class="dropdown-item statusbtn" data-value="partial">Partial Payment</a>
                    <a class="dropdown-item statusbtn" data-value="unpaid">Unpaid</a>
                    <a class="dropdown-item statusbtn" data-value="paid">Paid</a>
                </div>
            </div>
            <div class="dropdown invoice-options">

                <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                    <i class="bx bx-plus"></i>Add Invoice</a>

            </div>
        </div>
        <div class="card">
            <div class="card-body card-dashboard">
                <div class="table-responsive">
                    <table class="table invoice-data-table" style="width:100%">
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
                </div>
                </tr>
                </thead>

                <tbody id="invoice_table">
                    @foreach($data as $row)
                    <?php  
                    $invoice_no=session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date));
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                         @if(session('header_footer')=='no')
                        <a href="generate_invoice-{{$row->id}}-tax">{{session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date))}}</a>
                        @else
                        <a href="generate_invoice_UT-{{$row->id}}-tax">{{session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date))}}</a>
                        @endif  
                     </td>
                        <td>
                            <div class="invoice-action">
                                <!-- <a href="{{asset('app/invoice/view')}}" class="invoice-action-view mr-1">
                <i class="bx bx-show-alt"></i>
              </a> -->
                                @if(session('header_footer')=='no')
                                <a href="generate_invoice-{{$row->id}}-tax" class="invoice-action-view btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-invoice_id="{{$row->id}}" data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                </a>
                                @else
                                <a href="generate_invoice_UT-{{$row->id}}-tax" class="invoice-action-view btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-invoice_id="{{$row->id}}" data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                </a>
                                @endif
                                <a href="invoice_edit-{{$row->id}}" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 " data-id="{{$row->id}}" data-tooltip="Edit">
                                    <i class="bx bx-edit"></i>
                                    <a href="refund_list" class="invoice-action-edit btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1" data-id="{{$row->id}}" data-tooltip="Refund">
                                        <i class="bx bx-wallet-alt"></i>
                                    </a>
                                    <a class="btn btn-icon rounded-circle btn-info mr-1 mb-1 delete_invoice glow" data-id="{{$row->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
                                    <a data-toggle="modal" data-target="#default" class="invoice_payment_btn btn btn-icon rounded-circle glow btn-primary mr-1 mb-1" data-id="{{$row->id}}" data-amount="{{$row->payable}}" data-client_id="{{$row->client}}" data-tooltip="Payment">
                                        <i class="bx bx-money"></i>
                                    </a>
                                    <a type="button" class="invoice-action-edit btn btn-icon rounded-circle btn-success glow mr-1 mb-1" data-id="{{$row->id}}" data-tooltip="Send Mail">
                                        <i class="bx bx-send"></i>
                                    </a>
                                    <a data-toggle="modal" data-target="#writeoff" class="write_off_btn btn btn-icon rounded-circle glow btn-dark-red mr-1 mb-1" data-id="{{$row->id}}" data-payable="{{$row->payable}}" data-client_id="{{$row->client}}" data-invoice_no="{{$invoice_no}}" data-tooltip="write off">
                                        <i class="bx bxs-credit-card-alt"></i>
                                    </a>
                                    <a data-toggle="modal" data-target="#creditNoteModal" class="credit_note_btn btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="{{$row->id}}" data-payable="{{$row->payable}}" data-client_id="{{$row->client}}" data-invoice_no="{{$invoice_no}}"  data-tooltip="Credit note">
                                        <i class="bx bxs-credit-card"></i>
                                    </a>

                            </div>
                        </td>
                        <td><span class="invoice-customer">{{ $row->client_case_no }}
                            </span></td>
                        <td>
                            <!-- <span class="bullet bullet-success bullet-sm"></span> -->
                            <small class="text-muted"><?php echo nl2br($row->service) ?></small>
                        </td>
                        <td><span class="invoice-amount">&#8377;{{number_format($row->payable,2)}}</span></td>
                        @if($row->status == 'unpaid')
                        <td><span class="badge badge-light-danger badge-pill">{{ $row->status }}</span></td>

                        @elseif($row->status == 'paid')
                        <td><span class="badge badge-light-success badge-pill">{{ $row->status }}</span></td>

                        @else
                        <td><span class="badge badge-light-warning badge-pill">{{ $row->status }}</span></td>
                        @endif


                        <td data-sort="{{strtotime($row->bill_date)}}">{{ date('d-m-Y',strtotime($row->bill_date))}}
                        </td>
                        <td>{{ date('d-m-Y',strtotime($row->due_date))}}</td>
                        <td>{{ $row->seal}}</td>
                        <td>{{ $row->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Accept Payment</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
            <form id="modal_form">

                    <div class="row">
                        <div class="col">
                            <input type="hidden" class="form-control bill_id">
                            <input type="hidden" class="form-control client">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">

                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0 payment_date" placeholder="Payment Date">
                                <label for="payment_date">Payment Date</label>
                            </fieldset>
                            <span class="valid_err payment_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="Mode of payment">Mode of payment</label>
                        </div>
                    </div>
                    <div class="row py-50">
                        <div class="col">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment" value="cash" id="radio1" checked="">
                                            <label for="radio1">Cash</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment" value="cheque" id="radio2">
                                            <label for="radio2">Cheque</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment" value="online" id="radio3">
                                            <label for="radio3">Online</label>
                                        </div>
                                    </fieldset>
                                </li>

                            </ul>
                            <span class="valid_err mode_of_payment_err"></span>
                        </div>
                    </div>
                    <div class="row" id="ref_div" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control ref_no" id="ref_no" placeholder="Reference No">
                                <span class="valid_err ref_no_err"></span>
                                <label for="ref_no">Reference No</label>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row" id="cheque_div" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control cheque_no" id="cheque_no" placeholder="Cheque No">
                                <span class="valid_err cheque_no_err"></span>
                                <label for="cheque_no">Cheque No</label>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row" id="bank_div" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control bank_name" id="bank_name" placeholder="Bank Name">
                                <span class="valid_err bank_name_err"></span>
                                <label for="bank_name">Bank Name</label>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control payable" value="" id="payable" placeholder="payable" readonly>
                                <span class="valid_err payable_err"></span>
                                <label for="payable">Payable</label>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="company" class="company_id" value="{{session('company_id')}}" id="company_id">
                        </div>
                    </div>
                    @if($tds_applicable=='yes')
                    <div class="row mb-1">
                        <div class="col">
                            <label for="tds">TDS</label>
                            <input type="text" class="form-control tds" value="" id="tds" placeholder="TDS">
                            <span class="valid_err tds_err"></span>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col">
                            <label for="total">Total</label>
                            <input type="text" class="form-control total" value="" id="total" placeholder="Total" readonly>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control payment" value="" id="payment" placeholder="Payment">
                                <span class="valid_err payment_err"></span>
                                <label for="payment">Payment</label>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <textarea class="form-control narration" placeholder="Narration" autocomplete="off"></textarea>
                                <span class="valid_err narration_err"></span>
                                <label for="Narration">Narration</label>
                            </fieldset>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="submit_payment_btn" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="modal fade text-left" id="writeoff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Write Off</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
            <form id="modal_form">
                <div class="row">
                <input type="hidden" class="form-control writeoff_invoice_id">
                            <input type="hidden" class="form-control writeoff_client_id">
                        <div class="col">

                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0 writeoff_date" placeholder="Write Off Date">
                                <label for="write_off_date">Write Off Date</label>
                            </fieldset>
                            <span class="valid_err write_off_date_err"></span>
                        </div>
                    </div>
                <div class="row">
                        <div class="col">

                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 invoice_no" placeholder="Invoice No">
                                <label for="invoice_no">Invoice no</label>
                            </fieldset>
                            <span class="valid_err invoice_no_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="Mode of payment">Mode of payment</label>
                        </div>
                    </div>
                    <div class="row py-50">
                        <div class="col">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment1" value="cash" id="radio4" checked="">
                                            <label for="radio4">Cash</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment1" value="cheque" id="radio5">
                                            <label for="radio5">Cheque</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="bsradio" class="mode_of_payment1" value="online" id="radio6">
                                            <label for="radio6">Online</label>
                                        </div>
                                    </fieldset>
                                </li>

                            </ul>
                            <span class="valid_err mode_of_payment_err"></span>
                        </div>
                    </div>

                   <div class="row" id="ref_div1" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control ref_no" id="ref_no1" placeholder="Reference No">
                                <span class="valid_err ref_no_err"></span>
                                <label for="ref_no">Reference No</label>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row" id="cheque_div1" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control cheque_no" id="cheque_no1" placeholder="Cheque No">
                                <span class="valid_err cheque_no_err"></span>
                                <label for="cheque_no">Cheque No</label>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row" id="bank_div1" style="display:none">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control bank_name" id="bank_name1" placeholder="Bank Name">
                                <span class="valid_err bank_name_err"></span>
                                <label for="bank_name">Bank Name</label>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 writeoff_payable" placeholder="Payable">
                                <label for="payable">Payable</label>
                            </fieldset>
                            <span class="valid_err writeoff_payable_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 writeoff_amount" placeholder="Amount">
                                <label for="amount">Amount</label>
                            </fieldset>
                            <span class="valid_err writeoff_amount_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <textarea class="form-control mr-2 mb-50 mb-sm-0 writeoff_remark" placeholder="Remark"></textarea>
                                <label for="writeoff_remark">Remark</label>
                            </fieldset>
                            <span class="valid_err writeoff_remark_err"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="submit_writeoff_btn" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="modal fade text-left" id="creditNoteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Credit Note</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
            <form id="modal_form">
                <div class="row">
                            <input type="hidden" class="form-control credit_invoice_id">
                            <input type="hidden" class="form-control credit_client_id">
                        <div class="col">

                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0 credit_date" placeholder="Date">
                                <label for="credit_date">Date</label>
                            </fieldset>
                            <span class="valid_err credit_date_err"></span>
                        </div>
                    </div>
                <div class="row">
                        <div class="col">

                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 credit_invoice_no" placeholder="Invoice No">
                                <label for="invoice_no">Invoice no</label>
                            </fieldset>
                            <span class="valid_err credit_invoice_no_err"></span>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 credit_invoice_Payable" placeholder="Invoice Payable">
                                <label for="credit_invoice_Payable">Invoice Payable</label>
                            </fieldset>
                            <span class="valid_err credit_invoice_Payable_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 credit_amount" placeholder="Amount">
                                <label for="credit_amount">Amount</label>
                            </fieldset>
                            <span class="valid_err credit_amount_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                                <textarea class="form-control mr-2 mb-50 mb-sm-0 credit_remark" placeholder="Remark"></textarea>
                                <label for="credit_remark">Remark</label>
                            </fieldset>
                            <span class="valid_err credit_remark_err"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="submit_credit_btn" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>

<script>
    $(function() {

        $(".dropdown-menu a").click(function() {




        });

    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.invoice_payment_btn', function() {
            $('.valid_err').html('');
            $('#payable').val($(this).data('amount'));
            $('.bill_id').val($(this).data('id'));
            $('.client').val($(this).data('client_id'));

            $('.payment').val('');
            $('.narration').val('');
            $('.bank_name').val('');
            $('.cheque_no').val('');
            $('.ref_no').val('');
        });
        //Print Bill
        $(document).on('click', '.generate_bill_btn', function() {
            var bill_id = $('.bill_id').val();
            $.ajax({
                type: 'post',
                url: 'generate_bill',
                data: {
                    bill_id: bill_id
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#viewBill').modal('toggle');
                    if (res.status == 'success') {
                        $('.bill_view_body').empty().html(res.out);
                        $('.mytable1').DataTable({
                            "paging": true,
                            "bFilter": true,
                            "ordering": true,
                            "info": true
                        });
                        swal({
                            title: "Success!",
                            text: res.msg,
                            icon: "success",
                        });

                    } else {
                        swal({
                            title: "error!",
                            text: res.msg,
                            icon: "error",
                        });

                    }


                },
                error: function(data) {
                    console.log(data);
                }
            });


        });
        $(document).on('change', '.mode_of_payment', function() {

            var mode = $(this).val();
            if (mode == "cheque") {
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'block';
                document.getElementById("bank_div").style.display = 'block';
            } else if (mode == "online") {
                document.getElementById("cheque_div").style.display = 'none';
                document.getElementById("ref_div").style.display = 'block';
                document.getElementById("bank_div").style.display = 'block';
            } else if (mode == 'cash') {
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'none';
                document.getElementById("bank_div").style.display = 'none';
            } else {
                document.getElementById("bank_div").style.display = 'none';
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'none';
            }
        });
    });

     $(document).on('change', '.mode_of_payment1', function() {

            var mode = $(this).val();
            if (mode == "cheque") {
                document.getElementById("ref_div1").style.display = 'none';
                document.getElementById("cheque_div1").style.display = 'block';
                document.getElementById("bank_div1").style.display = 'block';
            } else if (mode == "online") {
                document.getElementById("cheque_div1").style.display = 'none';
                document.getElementById("ref_div1").style.display = 'block';
                document.getElementById("bank_div1").style.display = 'block';
            } else if (mode == 'cash') {
                document.getElementById("ref_div1").style.display = 'none';
                document.getElementById("cheque_div1").style.display = 'none';
                document.getElementById("bank_div1").style.display = 'none';
            } else {
                document.getElementById("bank_div1").style.display = 'none';
                document.getElementById("ref_div1").style.display = 'none';
                document.getElementById("cheque_div1").style.display = 'none';
            }
        });


    function isInt(value) {
        var er = /^-?[0-9]+$/;
        return er.test(value);
    }

    $(document).on("blur", ".tds", function() {
        var sum = 0;

        var tds1 = isInt(
            $(this).val(
                $(this)
                .val()
                .match(/^\d+\.?\d{0,2}/)
            )
        );
        if (tds1) {
            sum = parseInt($(".payable").val()) - parseInt($(this).val(), 10);
        } else {
            sum = parseInt($(".payable").val()) - parseFloat($(this).val());
        }

        $(".total").val(sum);
    });

    $(document).on('click', '#submit_payment_btn', function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.valid_err').html('');
        var client = $('.client').val();

        var payment_date = $('.payment_date').val();
        var payment = $('.payment').val();
        var tds = $('.tds').val();
        var company_id = $('.company_id').val();
        var mode_of_payment = $('.mode_of_payment:checked').val();
        var case_no = $('.case_no').val();
        var ref_no = $('.ref_no').val();
        var bank_name = $('.bank_name').val();
        var cheque_no = $('.cheque_no').val();
        var narration = $('.narration').val();
        var num = /^[0-9]+$/;
        var arr = [];
        var bill_array = [];
        var amount_array = [];
        var bill_id = $('.bill_id').val();
        bill_array.push(bill_id);
        amount_array.push($('.payment').val());



        if (payment_date == '') {
            arr.push('payment_date_err');
            arr.push('payment date required');
        }
        if (bill_array == '') {
            arr.push('bill_err');
            arr.push('Please check any bill');
        }
        if (payment == '') {

            arr.push('payment_err');
            arr.push('Payment amount required');
        }
        if (payment != '') {
            if (num.test(payment) == false) {
                arr.push('payment_err');
                arr.push('Valid amount required');
            }
        }
        if (mode_of_payment == '') {
            arr.push('mode_of_payment_err');
            arr.push('Mode of payment  required');
        }
        if (mode_of_payment != '') {
            if (mode_of_payment == 'online') {
                if (ref_no == '') {
                    arr.push('ref_no_err');
                    arr.push('Reference no required');
                }
                if (bank_name == '') {
                    arr.push('bank_name_err');
                    arr.push('Bank name required');
                }

            }
            if (mode_of_payment == 'cheque') {
                if (cheque_no == '' || num.test(cheque_no) == false) {
                    arr.push('cheque_no_err');
                    arr.push('Valid cheque no required');
                }
                if (bank_name == '') {
                    arr.push('bank_name_err');
                    arr.push('Bank name required');
                }

            }

        }

        if (narration == '') {
            arr.push('narration_err');
            arr.push('Narration  required');
        }

        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;


                $('.' + arr[i]).html(arr[j]).css('color', 'red');



                i = j;
            }
        } else {

            $.ajax({
                type: 'post',
                url: 'accept_payment',
                data: {
                    client: client,
                    bank_name: bank_name,
                    payment_date: payment_date,
                    payment: payment,
                    mode_of_payment: mode_of_payment,
                    ref_no: ref_no,
                    cheque_no: cheque_no,
                    bill_id: bill_array,
                    bill_amt: amount_array,
                    narration: narration,
                    case_no: case_no,
                    tds: tds,
                    company: company_id
                },

                success: function(data) {
                    console.log(data);
                    $("#form").trigger('reset');
                    $('#default').modal('hide');
                    var res = data;

                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    if (res.status == 'success') {
                        $('.data_div').empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();

                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });

                        if ($(".invoice-data-table").length) {
                            var dataListView = $(".invoice-data-table").DataTable({
                                columnDefs: [{
                                        targets: 0,
                                        className: "control"
                                    },
                                    {
                                        orderable: true,
                                        targets: 1,
                                        checkboxes: {
                                            selectRow: true
                                        }
                                    },
                                    {
                                        targets: [0, 1],
                                        orderable: false
                                    },
                                ],

                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                buttons: [
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5'
                                ],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search Invoice"
                                },
                                select: {
                                    style: "multi",
                                    selector: "td:first-child",
                                    items: "row"
                                },
                                responsive: {
                                    details: {
                                        type: "column",
                                        target: 0
                                    }
                                }
                            });
                        }

                        // To append actions dropdown inside action-btn div
                        var invoiceFilterAction = $(".invoice-filter-action");
                        var invoiceOptions = $(".invoice-options");
                        $(".action-btns").append(invoiceFilterAction, invoiceOptions);

                        // add class in row if checkbox checked
                        $(".dt-checkboxes-cell")
                            .find("input")
                            .on("change", function() {
                                var $this = $(this);
                                if ($this.is(":checked")) {
                                    $this.closest("tr").addClass("selected-row-bg");
                                } else {
                                    $this.closest("tr").removeClass("selected-row-bg");
                                }
                            });
                        // Select all checkbox
                        $(document).on("change", ".dt-checkboxes-select-all input", function() {
                            if ($(this).is(":checked")) {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", this.checked)
                                    .closest("tr")
                                    .addClass("selected-row-bg");
                            } else {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", "")
                                    .closest("tr")
                                    .removeClass("selected-row-bg");
                            }
                        });

                    } else {
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            res.msg + '</span></div></div>').focus();

                    }

                    if (res.status == 'validation_error') {
                        var errors = res.msg;
                        $.each(errors, function(key, val) {
                            $("." + key + "_err").html(val[0]);
                        });
                    }


                },
                error: function(data) {
                    console.log(data);
                    $('#default').modal('hide');
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    console.log(data);
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
                    ).focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            });
        }


    });
    $(document).on('click', '.statusbtn', function() {
        var value = $(this).data('value');
        $('.data_div').empty();
        $('.loader').css('display', 'block');
        var value = $(this).data('value');
        $.ajax({
            type: 'post',
            url: 'filter_invoice',
            data: {
                value: value
            },

            success: function(data) {
                $('.loader').css('display', 'none');



                $('.data_div').empty().html(data);
                let str = value;

                $(".selection").html(str.toUpperCase());
                if ($(".invoice-data-table").length) {
                    var dataListView = $(".invoice-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],

                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Invoice"
                        },
                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row"
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0
                            }
                        }
                    });
                }

                // To append actions dropdown inside action-btn div
                var invoiceFilterAction = $(".invoice-filter-action");
                var invoiceOptions = $(".invoice-options");
                $(".action-btns").append(invoiceFilterAction, invoiceOptions);

                // add class in row if checkbox checked
                $(".dt-checkboxes-cell")
                    .find("input")
                    .on("change", function() {
                        var $this = $(this);
                        if ($this.is(":checked")) {
                            $this.closest("tr").addClass("selected-row-bg");
                        } else {
                            $this.closest("tr").removeClass("selected-row-bg");
                        }
                    });
                // Select all checkbox
                $(document).on("change", ".dt-checkboxes-select-all input", function() {
                    if ($(this).is(":checked")) {
                        $(".dt-checkboxes-cell")
                            .find("input")
                            .prop("checked", this.checked)
                            .closest("tr")
                            .addClass("selected-row-bg");
                    } else {
                        $(".dt-checkboxes-cell")
                            .find("input")
                            .prop("checked", "")
                            .closest("tr")
                            .removeClass("selected-row-bg");
                    }
                });

            },
            error: function(data) {
                console.log(data);


            }


        });
    });
    $(document).on('click', '.delete_invoice', function() {
        var id = $(this).data('id');
        var status = $('.selection').html();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this Invocie",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: 'delete_invoice',
                    data: {
                        id: id,
                        status: status,
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        console.log(res.out);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $('.data_div').empty().html(res.out);
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            let str = status;

                            $(".selection").html(str.toUpperCase());
                            if ($(".invoice-data-table").length) {
                                var dataListView = $(".invoice-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control"
                                        },
                                        {
                                            orderable: true,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false
                                        },
                                    ],

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Invoice"
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row"
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0
                                        }
                                    }
                                });
                            }

                            // To append actions dropdown inside action-btn div
                            var invoiceFilterAction = $(".invoice-filter-action");
                            var invoiceOptions = $(".invoice-options");
                            $(".action-btns").append(invoiceFilterAction, invoiceOptions);

                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass("selected-row-bg");
                                    }
                                });
                            // Select all checkbox
                            $(document).on("change", ".dt-checkboxes-select-all input",
                                function() {
                                    if ($(this).is(":checked")) {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", this.checked)
                                            .closest("tr")
                                            .addClass("selected-row-bg");
                                    } else {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", "")
                                            .closest("tr")
                                            .removeClass("selected-row-bg");
                                    }
                                });
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }
                    },
                    error: function(data) {
                        console.log(data);

                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        ).focus();
                    }


                });
            }
        });
    });
    $(document).on('click','.write_off_btn',function(){
        $('.invoice_no').val($(this).data('invoice_no'));
        $('.writeoff_invoice_id').val($(this).data('id'));
        $('.writeoff_client_id').val($(this).data('client_id'));
        $('.writeoff_payable').val($(this).data('payable'));
      
        $('.writeoff_amount').val($(this).data('amount'));
    });
    $(document).on('click','.credit_note_btn',function(){
        $('.credit_invoice_no').val($(this).data('invoice_no'));
        $('.credit_invoice_id').val($(this).data('id'));
        $('.credit_client_id').val($(this).data('client_id'));
        $('.credit_invoice_Payable').val($(this).data('payable'));
      
        $('.credit_amount').val($(this).data('amount'));
    });
    $(document).on('click','#submit_writeoff_btn',function()
    {
        $('.valid_err').html('');
        var amount=$('.writeoff_amount').val();
        var invoice_id=$('.writeoff_invoice_id').val();
        var client_id=$('.writeoff_client_id').val();
        var payable=$('.writeoff_payable').val();
        var date=$('.writeoff_date').val();
        var remark=$('.writeoff_remark').val();
        var amount_exp=/^\d{0,9}(\.\d{0,9})?$/;
        var status = $('.selection').html();
      
        var num = /^[0-9]+$/;
        var arr=[];
        if (date == '') {
            arr.push('writeoff_date_err');
            arr.push('Date required');
        }
        if(amount_exp.test(amount)==false || amount=='')
        {
            arr.push('writeoff_amount_err');
            arr.push('Valid amount required');
        }
        if (remark == '') {
            arr.push('writeoff_remark_err');
            arr.push('Remark required');
        }
         if (parseInt(amount) > parseInt(payable)) {
            arr.push('writeoff_amount_err');
            arr.push('Amount must be less than payable');
        }
      
       
        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;


                $('.' + arr[i]).html(arr[j]).css('color', 'red');



                i = j;
            }
        } else {
        $.ajax({
            type: 'post',
            url: 'writeoff',
            data: {
                amount:amount,
                invoice_id:invoice_id,
                client_id:client_id,
                payable:payable,
                date:date,
                remark:remark,
                status:status,
               
            },

            success: function(data) {
                console.log(data);
                        var res = JSON.parse(data);
                        console.log(res.out);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $('#writeoff').modal('toggle');
                            $('.data_div').empty().html(res.out);
                            $('.writeoff_remark').val('');
                            $('.writeoff_payable').val('');
                            $('#bank_name1').val('');
                            $('#cheque_no1').val('');
                            $('#ref_no1').val('');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            let str = status;


                            $(".selection").html(str.toUpperCase());
                            if ($(".invoice-data-table").length) {
                                var dataListView = $(".invoice-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control"
                                        },
                                        {
                                            orderable: true,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false
                                        },
                                    ],

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Invoice"
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row"
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0
                                        }
                                    }
                                });
                            }

                            // To append actions dropdown inside action-btn div
                            var invoiceFilterAction = $(".invoice-filter-action");
                            var invoiceOptions = $(".invoice-options");
                            $(".action-btns").append(invoiceFilterAction, invoiceOptions);

                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass("selected-row-bg");
                                    }
                                });
                            // Select all checkbox
                            $(document).on("change", ".dt-checkboxes-select-all input",
                                function() {
                                    if ($(this).is(":checked")) {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", this.checked)
                                            .closest("tr")
                                            .addClass("selected-row-bg");
                                    } else {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", "")
                                            .closest("tr")
                                            .removeClass("selected-row-bg");
                                    }
                                });
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }

            },
            error: function(data) {
                console.log(data);


            }


        });
    }
    });
    $(document).on('click','#submit_credit_btn',function()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.valid_err').html('');
        var amount=$('.credit_amount').val();
        var invoice_id=$('.credit_invoice_id').val();
        var client_id=$('.credit_client_id').val();
        var payable=$('.credit_invoice_Payable').val();
        var date=$('.credit_date').val();
        var mode_of_payment = $('.mode_of_payment2:checked').val();
        var ref_no = $('#ref_no2').val();
        var bank_name = $('#bank_name2').val();
        var cheque_no = $('#cheque_no2').val();
        var remark=$('.credit_remark').val();
        var amount_exp=/^\d{0,9}(\.\d{0,9})?$/;
        var status = $('.selection').html();
        var num = /^[0-9]+$/;
        var arr=[];
        if (date == '') 
        {
            arr.push('credit_date_err');
            arr.push('Date required');
        }
        if(amount_exp.test(amount)==false || amount=='')
        {
            arr.push('credit_amount_err');
            arr.push('Valid amount required');
        }
        if (remark == '') 
        {
            arr.push('credit_remark_err');
            arr.push('Remark required');
        }
        if (parseInt(amount) > parseInt(payable)) 
        {
            arr.push('credit_amount_err');
            arr.push('Amount must be less than payable');
        }
        if (mode_of_payment != '') 
        {
            if (mode_of_payment == 'online') {
                if (ref_no == '') {
                    arr.push('ref_no_err');
                    arr.push('Reference no required');
                }
                if (bank_name == '') {
                    arr.push('bank_name_err');
                    arr.push('Bank name required');
                }

            }
            if (mode_of_payment == 'cheque') {
                if (cheque_no == '' || num.test(cheque_no) == false) {
                    arr.push('cheque_no_err');
                    arr.push('Valid cheque no required');
                }
                if (bank_name == '') {
                    arr.push('bank_name_err');
                    arr.push('Bank name required');
                }

            }

        }
        if (arr != '')
        {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;


                $('.' + arr[i]).html(arr[j]).css('color', 'red');



                i = j;
            }
        } else 
        {
        $.ajax({
            type: 'post',
            url: 'credit_note',
            data: {
                amount:amount,
                invoice_id:invoice_id,
                client_id:client_id,
                payable:payable,
                date:date,
                remark:remark,
                status:status,
                mode_of_payment:mode_of_payment,
                ref_no:ref_no,
                bank_name:bank_name,
                cheque_no:cheque_no
            },

            success: function(data) {
                console.log(data);
                        var res = JSON.parse(data);
                        console.log(res.out);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $('#creditNoteModal').modal('toggle');
                            $('.data_div').empty().html(res.out);
                            $('.credit_invoice_Payable').val('');
                            $('#ref_no2').val('');
                            $('#bank_name2').val('');
                            $('#cheque_no2').val('');
                            $('.credit_remark').val('');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            let str = status;

                            $(".selection").html(str.toUpperCase());
                            if ($(".invoice-data-table").length) {
                                var dataListView = $(".invoice-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control"
                                        },
                                        {
                                            orderable: true,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false
                                        },
                                    ],

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Invoice"
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row"
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0
                                        }
                                    }
                                });
                            }

                            // To append actions dropdown inside action-btn div
                            var invoiceFilterAction = $(".invoice-filter-action");
                            var invoiceOptions = $(".invoice-options");
                            $(".action-btns").append(invoiceFilterAction, invoiceOptions);

                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass("selected-row-bg");
                                    }
                                });
                            // Select all checkbox
                            $(document).on("change", ".dt-checkboxes-select-all input",
                                function() {
                                    if ($(this).is(":checked")) {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", this.checked)
                                            .closest("tr")
                                            .addClass("selected-row-bg");
                                    } else {
                                        $(".dt-checkboxes-cell")
                                            .find("input")
                                            .prop("checked", "")
                                            .closest("tr")
                                            .removeClass("selected-row-bg");
                                    }
                                });
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }

            },
            error: function(data) {
                console.log(data);


            }


        });
    }
    });

</script>
@endsection