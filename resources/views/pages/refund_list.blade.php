@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Payment List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/refund.css')}}">
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
<section>
    <div class="card">
        <div class="card-body">
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
            <div class="refund-list-wrapper">
                <div class="data_div">
                    <div class="action-dropdown-btn d-none">
                        <div class="dropdown refund-filter-action">
                            <button class="btn border dropdown-toggle mr-1" type="button" id="refund-filter-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="selection">Filter Refund</span>
                            </button>
                            <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="refund-filter-btn">
                                <a type="button" class="dropdown-item active_btn" data-value="open">Open</a>
                                <a type="button" class="dropdown-item active_btn" data-value="approved">Approved</a>
                            </div> -->
                        </div>
                        <div class="dropdown refund-options">
                            <a href="{{asset('refund-add')}}" class="refund-action-view mr-1">
                                <button type="button" id="" class="btn mr-2 btn-primary">Add
                                    Refund</button>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table refund-data-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Client Name</th>
                                    <th>Bank Name</th>
                                    <th>Amount</th>
                                    <th>Mode of Payment</th>
                                    <th>Cheque No</th>
                                    <th>Reference No</th>
                                    <th>Deposite Bank</th>
                                    <th>Deposite Date</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refund as $row)
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:void(0);"
                                            class="cursor-pointer btn btn-icon rounded-circle glow btn-warning mr-1 mb-1"
                                            id="update_refund" data-tooltip="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="cursor-pointer btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_refund"
                                            data-expense_id="{{$row->id}}" data-tooltip="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                        </a>
                                    </td>
                                    <td><span>{{ $row->case_no }}
                                            <small>({{ $row->client_name }})</small></span></td>
                                    <td>{{$row->bankname}}</td>
                                    <td>{{$row->amount}}</td>
                                    <td>{{$row->mode_of_payment}}</td>
                                    <td>{{$row->cheque_no}}</td>
                                    <td>{{$row->ref_no}}</td>
                                    <td>{{$row->deposite_bank}}</td>
                                    <td>{{date('d-m-Y',strtotime($row->deposite_date))}}</td>
                                    <td>{{$row->remark}}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/refund_list.js')}}"></script>

@endsection