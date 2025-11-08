@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Payment List')
{{-- vendor style --}}
@section('vendor-styles')
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-payment.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }

    .body {
        float: right;
        position: absolute;
        right: 0;
        margin-top: 5px;
        margin-right: 230px;
    }


    .table-condensed {
        border-collapse: initial;
    }

    .datepicker-days {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker-years {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker thead {
        background-color: #e9edf1;
        color: #2454b1;
    }

    .dow {
        color: #2454b1;
    }
</style>

@endsection

@section('content')
<!-- invoice list -->
<section class="payment-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>

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
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="received-tab" data-toggle="tab" href="#received" aria-controls="received" role="tab" aria-selected="true">
                            <span class="align-middle">Received</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="deposited-tab" data-toggle="tab" href="#deposited" aria-controls="deposited" role="tab" aria-selected="false">
                            <span class="align-middle">Deposited</span>
                        </a>
                    </li>
                    @if(session('role_id')==1 || session('role_id')==3)
                    <li class="nav-item">
                        <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" aria-controls="approved" role="tab" aria-selected="false">
                            <span class="align-middle">Approved</span>
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="received" aria-labelledby="received-tab" role="tabpanel">
                        <div class="receive_body">
                            <div class="body">
                                <h5><b>Total received payment : <span class="total_rc_h4">{{number_format($total_received,2)}}</span></b></h5>
                            </div>

                            <div class="table-responsive">
                                <table class="table payment-data-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>
                                            <th>Cheque No</th>
                                            <th>Deposite Date</th>
                                            <th>Deposite Bank</th>
                                            <th>Deposite By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($received as $rc)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                    @if($rc->mode_of_payment=='cash' || $rc->mode_of_payment=='cheque')

                                                    <button data-id="{{$rc->id}}" class="payment-action-edit btn btn-icon rounded-circle glow btn-success mr-1 mb-1 create_deposit_btn" data-tooltip="Deposite">
                                                        <i class="bx bxs-receipt"></i>
                                                    </button>

                                                    <button data-id="{{$rc->id}}" class="payment-action-done btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 deposit_btn" style="display:none;" data-tooltip="Done">
                                                        <i class="bx bx-check"></i>
                                                    </button>

                                                    <button data-id="{{$rc->id}}" class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_deposit_btn" style="display:none;" data-tooltip="Close">
                                                        <i class="bx bx-x"></i>
                                                    </button>

                                                    @elseif($rc->mode_of_payment=='online')
                                                    @if(session('role_id')==1 || session('role_id')==3)

                                                    <button data-id="{{$rc->id}}" class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                        <i class="bx bx-money"></i>
                                                    </button>

                                                    <button data-id="{{$rc->id}}" class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn" style="display:none;" data-tooltip="Done">
                                                        <i class="bx bx-check"></i>
                                                    </button>

                                                    <button data-id="{{$rc->id}}" class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn" style="display:none;" data-tooltip="Close">
                                                        <i class="bx bx-window-close"></i>
                                                    </button>

                                                    @endif
                                                    @endif
                                                    <button data-id="{{$rc->id}}" class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>{{ $rc->client_case_no }}
                                                </span></td>
                                            <td>{{number_format($rc->payment,2)}}</td>
                                            <td>{{$rc->tds}}</td>
                                            <td data-sort="{{strtotime($rc->payment_date)}}">
                                                {{date('d-m-Y',strtotime($rc->payment_date))}}
                                            </td>
                                            <td>{{$rc->mode_of_payment}}</td>
                                            <td>{{$rc->cheque_no}}</td>
                                            <td>
                                                <div class="depo_dt_data">
                                                    @if($rc->deposit_date!='')
                                                    {{date('d-m-Y',strtotime($rc->deposit_date))}}
                                                    @endif
                                                </div>
                                                <div class="depo_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px" class="form-control datepicker deposit_date" placeholder="Deposit date">
                                                    <span class="valid_err deposit_date_err"></span>
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px" class="form-control datepicker approve_date" placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="depo_bank_data">{{$rc->deposit_bank}}</div>
                                                <div class="depo_bank_ui" style="display:none">
                                                    <select class="form-control required deposit_bank" name="deposit_bank" id="deposit_bank">
                                                        <option value="">---Select deposit bank---</option>
                                                        @foreach($bank_detail as $bank)
                                                        <option value="{{$bank->id}}" <?php if ($bank->default_bank_account == 'yes') echo "selected"; ?>>
                                                            {{$bank->bankname}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="valid_err deposit_bank_err"></span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="depo_by_data">{{$rc->deposited_by}}</div>
                                                <div class="depo_by_ui" style="display:none">

                                                    <select class="form-control required deposit_by" name="deposit_by" style="width:100%">
                                                        @foreach($staff as $stf)
                                                        @if(session('role_id')!=1 &&
                                                        (session('user_id')==$stf->user_id))
                                                        <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                                        @endif
                                                        @endforeach
                                                        @if(session('role_id')==1)
                                                        <option value="">---Select deposit by---</option>
                                                        @foreach($staff as $stf)
                                                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="valid_err deposit_by_err"></span>
                                                </div>
                                                <div class="apr_by_ui" style="display:none">

                                                    <select class="form-control required approve_by" name="approve_by" style="width:100%">



                                                        @foreach($staff as $stf)
                                                        @if(session('role_id')!=1 &&
                                                        (session('user_id')==$stf->user_id))
                                                        <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                                        @endif
                                                        @endforeach


                                                        @if(session('role_id')==1)
                                                        <option value="">---Select approve by---</option>
                                                        @foreach($staff as $stf)
                                                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="deposited" aria-labelledby="deposited-tab" role="tabpanel">
                        <div class="deposite_body">
                            <div class="body">
                                <h5><b>Total deposited payment: <span class="total_dp_h4">{{number_format($total_deposited,2)}}</span></b></h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table payment-data-table dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>Payment Date</th>
                                            <th>Mode of Payment</th>

                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Approve date</th>
                                            <th>Approve by</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach($deposited as $dp)
                                        <tr>
                                            <td></td>
                                            <td>
                                                @if(session('role_id')==1 || session('role_id')==3)
                                                <div class="payment-action">

                                                    <button data-id="{{$dp->id}}" class="payment-action-edit btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn" data-tooltip="Approve">
                                                        <i class="bx bx-money"></i>
                                                    </button>

                                                    <button data-id="{{$dp->id}}" class="payment-action-done btn btn-icon rounded-circle glow btn-success mr-1 mb-1 approve_btn" style="display:none;" data-tooltip="Done">
                                                        <i class="bx bx-check"></i>
                                                    </button>

                                                    <button data-id="{{$dp->id}}" class="payment-action-close btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 close_approve_btn" style="display:none;" data-tooltip="Close">
                                                        <i class="bx bx-window-close"></i>
                                                    </button>

                                                    @endif
                                                    <button data-id="{{$dp->id}}" class="payment-action-delete btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_payment" data-dp_id="{{$dp->id}}" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span>{{ $dp->client_case_no }}
                                                </span></td>
                                            <td>{{number_format($dp->payment,2)}}</td>
                                            <td>{{$dp->tds}}</td>
                                            <td data-sort="{{strtotime($dp->payment_date)}}">
                                                {{date('d-m-Y',strtotime($dp->payment_date))}}
                                            </td>
                                            <td>{{$dp->mode_of_payment}}</td>
                                            <td>{{$dp->cheque_no}}</td>
                                            <td>{{$dp->reference_no}}</td>
                                            <td>
                                                <div class="apr_dt_data">
                                                    @if($dp->deposit_date!='')
                                                    {{date('d-m-Y',strtotime($dp->deposit_date))}}
                                                    @endif
                                                </div>
                                                <div class="apr_dt_ui" style="display:none">
                                                    <input type="text" style="top: 461.4px" class="form-control datepicker approve_date" placeholder="Approve Date">
                                                    <span class="valid_err approve_date_err"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="apr_by_data">{{$dp->approved_by}}</div>
                                                <div class="apr_by_ui" style="display:none">
                                                    <select class="form-control required approve_by" name="approve_by" style="width:100%">
                                                        @foreach($staff as $stf)
                                                        @if(session('role_id')!=1 &&
                                                        (session('user_id')==$stf->user_id))
                                                        <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                                        @endif
                                                        @endforeach

                                                        @if(session('role_id')==1)
                                                        <option value="">---Select approve by---</option>
                                                        @foreach($staff as $stf)
                                                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="valid_err approve_by_err"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @if(session('role_id')==1 || session('role_id')==3)
                    <div class="tab-pane" id="approved" aria-labelledby="approved-tab" role="tabpanel">
                        <div class="approve_body">
                            <div class="body">
                                <h5><b>Total approved payment : <span class="total_ap_h4">{{number_format($total_approved,2)}}</span></b></h5>
                            </div>
                            <div class="action-dropdown-btn d-none">
                                <div class="dropdown payment-filter-action">
                                    <button class="btn border dropdown-toggle mr-1" type="button" id="payment-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="selection">Filter Payment</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="payment-filter-btn">
                                        <a type="button" href="javascript:void(0);" class="dropdown-item filter_approve_btn" data-value="today">Today</a>
                                        <a class="dropdown-item filter_approve_btn" href="javascript:void(0);" data-value="next_day">Next
                                            Day</a>
                                        <a class="dropdown-item filter_approve_btn" href="javascript:void(0);" data-value="this_week">This
                                            Week</a>
                                        <a class="dropdown-item filter_approve_btn" href="javascript:void(0);" data-value="this_month">This Month</a>
                                        <a class="dropdown-item filter_approve_btn" href="javascript:void(0);" data-value="this_year">This
                                            Year</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table payment-data-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Client Name</th>
                                            <th>Payment</th>
                                            <th>TDS</th>
                                            <th>payment Date</th>
                                            <th>Mode of payment</th>
                                            <th>Cheque No</th>
                                            <th>Reference No</th>
                                            <th>Narration</th>
                                            <th>Deposite Bank</th>
                                            <th>Approved By</th>
                                            <th>Approved Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach($approved as $ap)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="payment-action">
                                                    <a href="payment_reciept-{{$ap->id}}" class="payment-action-receipt btn btn-icon rounded-circle btn-warning mr-1 mb-1 " data-tooltip="Payment Receipt">
                                                        <i class="bx bx-printer"></i>
                                                    </a>

                                                    <a href="#" class="payment-action-delete btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_payment" data-id="{{$ap->id}}" data-tooltip="Delete">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><span>{{ $ap->client_case_no }}
                                                </span></td>
                                            <td>{{number_format($ap->payment,2)}}</td>
                                            <td>{{$ap->tds}}</td>
                                            <td data-sort="{{strtotime($ap->payment_date)}}">
                                                {{date('d-m-Y',strtotime($ap->payment_date))}}
                                            </td>
                                            <td>{{$ap->mode_of_payment}}</td>
                                            <td>{{$ap->cheque_no}}</td>
                                            <td>{{$ap->reference_no}}</td>
                                            <td>{{$ap->narration}}</td>
                                            <td>{{$ap->deposite_bank_name}}</td>
                                            <td>{{$ap->approved_by_name}}</td>
                                            <td>{{date('d-m-Y',strtotime($ap->approve_date))}}</td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script> -->
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script> -->
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
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/payment_list.js')}}"></script>
<script>
    function get_payment_by_status() {
        $.ajax({
            type: "get",
            url: "get_payment_by_status",

            success: function(data) {
                console.log(data);
                // $(".receive_body").empty().html(rc_out);
                // $(".deposite_body").empty().html(dp_out);
                // $(".approve_body").empty().html(ap_out);

                $(".tab-content").empty().html(data);
            },
            error: function(data) {
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    }
</script>
@endsection