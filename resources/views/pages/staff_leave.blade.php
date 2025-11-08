@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style type="text/css">
    .nav>li.active>a>img.verticalTab {
        filter: grayscale(0%);
    }

    .top {
        margin-bottom: 14px;
    }

    .dt-buttons {
        /*float: right;*/
    }

    .card.border {
        margin-top: 30px;
    }

    .dataTables_filter {
        float: left;
        width: 400px;
        padding: 0px !important;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        margin-left: 0px !important;
        margin-right: 10px !important;
    }

    .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 60px);
        /* Adjust the value as needed */
    }

    .mt_10 {
        margin-top: 3px !important;
    }

    .pending-table-border {
        border-right: 1px solid #dfe3e7;
        padding-right: 10px;
    }
</style>

{{-- page title --}}
@section('title','Staff Leave List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/leave.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
@endsection

@section('content')
<!-- invoice list -->
<section class="leave-list-wrapper">
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
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" aria-controls="pending" role="tab" aria-selected="true">
                        <span class="align-middle">Pending</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" aria-controls="approved" role="tab" aria-selected="false">
                        <span class="align-middle">Approved</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" aria-controls="rejected" role="tab" aria-selected="false">
                        <span class="align-middle">Rejected</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="statistics-tab" data-toggle="tab" href="#statistics" aria-controls="statistics" role="tab" aria-selected="false">
                        <span class="align-middle">Statistics</span>
                    </a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="pending" aria-labelledby="pending-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="pending_body pending-table-border">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mt_10">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select class="form-control" id="search_by_staff">
                                            <option value="">Select Staff</option>
                                            @foreach ($staff as $row)
                                            <option value="{{$row->sid}}">
                                                {{$row->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-md round  view_statistics_by_staff"><strong>View</strong></a>
                                </div>
                                <div class="col-12" id="leaveTotal">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="approved" aria-labelledby="approved-tab" role="tabpanel">
                    <div class="approved_body">

                    </div>
                </div>
                <div class="tab-pane" id="rejected" aria-labelledby="rejected-tab" role="tabpanel">
                    <div class="rejected_body">

                    </div>
                </div>
                <div class="tab-pane" id="statistics" aria-labelledby="statistics-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-label-group">
                            <select name="month" id="month" class="form-control">
                                    @for($i=1;$i<=12;$i++) 
                                    @if($i==(date('m')-1))
                                    <option value="{{ date('m', mktime(null, null, null, $i)) }}" selected>{{ date("F", mktime(null, null, null, $i)); }}</option>
                                       @else
                                       <option value="{{ date('m', mktime(null, null, null, $i)) }}">{{ date("F", mktime(null, null, null, $i)); }}</option>
                                       @endif
                                    
                                    @endfor
                                </select>
                            </div>
                            <span class="text-danger month_err"></span>
                        </div>
                        <div class="col-md-3">
                           <select name="year" id="year" class="form-control">
                                    @for($i=2023;$i<=date('Y');$i++) 
                                    @if($i==date('Y'))
                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}" >{{ $i }}</option>
                                    @endif
                                        @endfor
                            </select>
                            <span class="text-danger year_err"></span>
                        </div>
                        <div class="col-md-3">
                            <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                        </div>
                    </div>
                    <div class="statistics_body">
                    <div class="card">
                    <div class="card-body">
                        <center>
                            <div class="spinner-grow text-primary loader" role="status" style="display:block">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h5 class="loader" style="display:block">Please wait...</h5>
                        </center>
                        <div class="data_div">

                        </div>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header LeaveTopClosedBox">
                        <button type="button" class="close LeaveTopClose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="LeaveTopBox">
                            <center><img src="images/leave-statistic-icon.svg"></center>
                            <h4>Leave Statistics</h4>
                        </div>
                        <div class="row TotalStaffLeaveBoxMain">
                            <div class="col-sm-6">
                                <div class="TotalStaffLeaveBox">
                                    <!-- <img src="images/TotalStaffLeaveIcon.svg"> -->
                                    <div class="TotalStaffLeaveContent">
                                        <h4>Total Earned Leaves</h4>
                                        <h3 class="total_earned_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="AvailableLeavesBox">
                                    <!-- <img src="images/AvailableLeavesIcon.svg"> -->
                                    <div class="AvailableLeavesContent">
                                        <h4>Applied Earned Leaves</h4>
                                        <h3 class="applied_earned_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row TotalStaffLeaveBoxMain">
                            <div class="col-sm-6">
                                <div class="TotalStaffLeaveBox">
                                    <!-- <img src="images/TotalStaffLeaveIcon.svg"> -->
                                    <div class="TotalStaffLeaveContent">
                                        <h4>Total Unpaid Leaves</h4>
                                        <h3 class="total_unpaid_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="AvailableLeavesBox">
                                    <!-- <img src="images/AvailableLeavesIcon.svg"> -->
                                    <div class="AvailableLeavesContent">
                                        <h4>Applied Unpaid Leaves</h4>
                                        <h3 class="applied_unpaid_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row TotalStaffLeaveBoxMain">
                            <div class="col-sm-6">
                                <div class="TotalStaffLeaveBox">
                                    <!-- <img src="images/TotalStaffLeaveIcon.svg"> -->
                                    <div class="TotalStaffLeaveContent">
                                        <h4>Total Sick Leaves</h4>
                                        <h3 class="total_sick_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="AvailableLeavesBox">
                                    <!-- <img src="images/AvailableLeavesIcon.svg"> -->
                                    <div class="AvailableLeavesContent">
                                        <h4>Applied Sick Leaves</h4>
                                        <h3 class="applied_sick_leaves"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row TotalStaffLeaveBoxMain">
                            <div class="col-sm-6">
                                <div class="TotalStaffLeaveBox">
                                    <!-- <img src="images/TotalStaffLeaveIcon.svg"> -->
                                    <div class="TotalStaffLeaveContent">
                                        <h4>Total Weekly Off</h4>
                                        <h3 class="total_weekly_off"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="AvailableLeavesBox">
                                    <!-- <img src="images/AvailableLeavesIcon.svg"> -->
                                    <div class="AvailableLeavesContent">
                                        <h4>Applied Weekly Off</h4>
                                        <h3 class="applied_weekly_off"></h3>
                                    </div>
                                </div>
                            </div>
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
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/leave_list.js')}}"></script>
@endsection