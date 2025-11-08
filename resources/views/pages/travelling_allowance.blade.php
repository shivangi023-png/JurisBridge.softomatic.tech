@extends('layouts.contentLayoutMaster')
<style>
.dropdown-menu {
    z-index: 99999 !important;
}
</style>
{{-- title --}}
@section('title','Travelling Allowance')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/travelling_allowance.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
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

.valid_err {

    font-size: 12px;
}

.modal-lg {
    width: 1200px;
}

.row {
    margin-right: unset !important;
    margin-left: unset !important;
}

.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-control-placeholder {
    position: absolute;
    top: 0;
    padding: 7px 0 0 13px;
    transition: all 200ms;
    opacity: 0.5;
}

.form-control:focus+.form-control-placeholder,
.form-control:valid+.form-control-placeholder {
    font-size: 75%;
    transform: translate3d(0, -100%, 0);
    opacity: 1;
}


.ui-autocomplete {

    z-index: 9999 !important;

}
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->
<section class="allowance-list-wrapper">
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
            <div id="alert"></div>
            <div class="data_div">
                <div class="action-dropdown-btn d-none">
                    <div class="dropdown allowance-filter-action">
                        <button class="btn border dropdown-toggle mr-1" type="button" id="allowance-filter-btn"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="status_selected">PENDING</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="allowance-filter-btn">
                            <a type="button" class="dropdown-item status_btn" data-value="pending">Pending</a>
                            <a type="button" class="dropdown-item status_btn" data-value="approved">Approved</a>
                            <a type="button" class="dropdown-item status_btn" data-value="rejected">Rejected</a>
                        </div>
                    </div>
                    <div class="dropdown allowance-options">
                        <button class="btn border dropdown-toggle mr-2" type="button" id="allowance-options-btn"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="allowance-options-btn">
                            <a class="dropdown-item all_delete" href="javascript:;">Delete</a>
                            <a class="dropdown-item all_allowance_approve" href="javascript:;">Approve</a>
                            <a class="dropdown-item all_allowance_reject" href="javascript:;">Reject</a>
                        </div>
                    </div>
                    <div class="dropdown allowance-options">
                        <a href="#" class="allowance-action-view mr-1">
                            <button type="button" data-toggle="modal" data-target="#addModal"
                                class="btn mr-2 btn-primary">Add
                                Travelling
                                Allowance</button>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table allowance-data-table wrap" style="width:100%">
                        <thead>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Action</th>
                            <th>Destination</th>
                            <th>Distance</th>
                            <th>Vehicle Type</th>
                            <th>Date</th>
                            <th>Entry By</th>
                            <th>Status</th>
                            @if(session('role_id')==1 || session('role_id')==3)
                            <th>Approval Date</th>
                            <th>Approval By</th>
                            @endif
                        </thead>
                        <tbody>
                            @foreach($travelling_allowance as $row)
                            <tr>
                                <td></td>
                                <td></td>
                                <td><input type="hidden" class="form-control travelling_allowance_id"
                                        value="{{$row->id}}"></td>
                                <td>
                                    @if(session('role_id')==1 || session('role_id')==3)
                                    @if($row->status=='pending' || $row->status=='rejected')
                                    <div style="float: left">
                                        <button type="button" data-id="{{$row->id}}"
                                            class="btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 create_approve_btn"
                                            data-tooltip="Approve">
                                            <i class="bx bx-list-check"></i>
                                        </button>
                                    </div>
                                    <div style="float: left">
                                        <a href="#" data-id="{{$row->id}}"
                                            class="btn btn-icon rounded-circle glow btn-success approve_btn mr-1 mb-1"
                                            style="display:none;" data-tooltip="Done"><i class="bx bx-check"></i></a>
                                    </div>
                                    <div style="float: left">
                                        <a href="#" data-id="{{$row->id}}"
                                            class="btn btn-icon rounded-circle glow btn-danger close_approve_btn mr-1 mb-1"
                                            style="display:none;" data-tooltip="Close"><i
                                                class="bx bx-window-close"></i></a>
                                    </div>
                                    @elseif($row->status=='approved')
                                    <div style="float: left">
                                        <button type="button" data-id="{{$row->id}}"
                                            class="btn btn-icon rounded-circle glow btn-success mr-1 mb-1 reject_btn"
                                            data-tooltip="Reject">
                                            <i class="bx bx-show-alt"></i>
                                        </button>
                                    </div>
                                    @endif
                                    @endif
                                    <button type="button"
                                        class="btn btn-icon rounded-circle glow btn-warning updateModal mr-1 mb-1"
                                        data-toggle="modal" data-target="#updateModal" data-id="{{$row->id}}"
                                        data-place="{{$row->place}}" data-date="{{$row->date}}"
                                        data-distance="{{$row->distance}}"
                                        data-destination_id="{{$row->destination_id}}"
                                        data-vehicle_type="{{$row->vehicle_type}}"
                                        data-entry_by="{{$row->entry_by}}" data-tooltip="Edit"><i
                                            class="bx bx-edit"></i></button>
                                    <a href="javascript:void(0);"
                                        class="cursor-pointer btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_allowance"
                                        data-allowance_id="{{$row->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;
                                </td>
                                <td>{{$row->place}}</td>
                                <td>{{$row->distance}}</td>
                                <td>{{$row->vehicle_type}}</td>
                                <td>{{date('d-m-Y',strtotime($row->date))}}</td>
                                <td>{{$row->entry}}</td>
                                @if($row->status=='pending')
                                <td><span class="badge badge-pill badge-light-warning">{{$row->status}}</span></td>
                                @elseif($row->status=='approved')
                                <td><span class="badge badge-pill badge-light-danger">{{$row->status}}</span></td>
                                @else
                                <td><span class="badge badge-pill badge-light-secondary">{{$row->status}}</span></td>
                                @endif
                                @if(session('role_id')==1 || session('role_id')==3)
                                <td>
                                    <div class="apr_dt_data">
                                        @if($row->approve_date!='')
                                        {{date('d-m-Y',strtotime($row->approve_date))}}
                                        @endif
                                    </div>
                                    <div class="apr_dt_ui" style="display:none">
                                        <input type="text" class="form-control datepicker approve_date"
                                            placeholder="approve_date">
                                        <span class="valid_err approve_date_err"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="apr_by_data">{{$row->approved_by_name}}</div>
                                    <div class="apr_by_ui" style="display:none">

                                        <select class="form-control required approve_by" name="approve_by"
                                            style="width:100%">
                                            @foreach($staff as $stf)
                                            @if((session('role_id')==1 || session('role_id')==3) &&
                                            (session('user_id')==$stf->user_id))
                                            <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                            @endif
                                            @endforeach


                                            @if(session('role_id')==1 || session('role_id')==3)
                                            <option value="">---Select Approval By---</option>
                                            @foreach($staff as $stf)
                                            <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="valid_err approve_by_err"></span>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title" id="uModalLabel">Add Travelling Allowance</h4>
            </div>
            <div class="modal-body">
                <form id="addAllowanceForm">
                    <div class="row">
                        <div class="col-6 mt-1">
                            <div class="form-label-group">
                                <input type="text" class="form-control destination" name="destination" id="destination"
                                    value="" required placeholder="Destination">
                                <label for="destination">Destination</label>
                                <span class="valid_err destination_err"></span>
                            </div>
                        </div>

                        <div class="col-6 mt-1">
                            <div class="form-label-group">
                                <input type="text" class="form-control datepicker" name="date" id="travel_date" value=""
                                    required>
                                <span class="valid_err date_err"></span>
                            </div>
                        </div>
                        <div class="col-6 my-1">
                            <div class="form-label-group">
                                <input type="text" class="form-control" name="distance" id="distance" value="" required
                                    placeholder="Distance">
                                <label for="distance">Distance(Km)</label>
                                <span class="valid_err distance_err"></span>
                            </div>
                        </div>
                        <div class="col-6 my-1">
                            <div class="form-label-group">
                                <select class="form-control" id="by_whom" name="by_whom">

                                    @foreach($staff as $stf)
                                    @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))

                                    <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>

                                    @endif
                                    @endforeach
                                    @if(session('role_id')==1)
                                    <option value="">---Select Entry by---</option>
                                    @foreach($staff as $stf)
                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="valid_err by_whom_err"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-8">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="2_wheeler" name="vehicle_type" class="custom-control-input vehicle_type" value="2 Wheeler" checked>
                                <label class="custom-control-label" for="2_wheeler">2 wheeler</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="4_wheeler" name="vehicle_type" class="custom-control-input vehicle_type" value="4 Wheeler">
                                <label class="custom-control-label" for="4_wheeler">4 wheeler</label>
                              </div>
                          </div>
                        </div>
                        <div class="col-12 my-1">
                            <button type="button" class="btn btn-primary" id="submit" name="submit">Save
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title" id="uModalLabel">Update Travelling Allowance</h4>
            </div>
            <div class="modal-body">
                <form id="updateAllowanceForm">
                    <div class="row">
                        <div class="col-6 mt-1">
                            <div class="form-label-group">
                                <input type="hidden" class="form-control travelling_allowance_id">
                                <input type="text" class="form-control  modal_destination" name="destination"
                                    id="mdestination" value="" required placeholder="Destination">
                                <label for="mdestination">Destination</label>
                                <span class="modal_valid_err modal_destination_err"></span>
                            </div>
                        </div>

                        <div class="col-6 mt-1">
                            <div class="form-label-group">
                                <input type="text" class="form-control datepicker modal_travel_date" name="date"
                                    id="mdate" value="" required>
                                <label for="mdate">Date</label>
                                <span class="modal_valid_err modal_date_err"></span>
                            </div>
                        </div>
                        <div class="col-6 my-1">
                            <div class="form-label-group">
                                <input type="text" class="form-control  modal_distance" name="distance" id="mdistance"
                                    value="" required placeholder="Distance">
                                <label for="mdistance">Distance(Km)</label>
                                <span class="modal_valid_err modal_distance_err"></span>
                            </div>
                        </div>
                        <div class="col-6 my-1">
                            <div class="form-label-group">
                                <select class="form-control  modal_by_whom" id="" name="by_whom">

                                    @foreach($staff as $stf)
                                    @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))
                                    <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>

                                    @endif
                                    @endforeach
                                    @if(session('role_id')==1)
                                    <option value="">---Select Entry by---</option>
                                    @foreach($staff as $stf)
                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <label for="mby_whom">By Whom</label>
                                <span class="modal_valid_err modal_by_whom_err"></span>
                            </div>
                        </div>
                         <div class="col-12 mb-1">
                            <div class="col-8">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="modal_2_wheeler" name="vehicle_type" class="custom-control-input vehicle_type" value="2 Wheeler">
                                <label class="custom-control-label" for="modal_2_wheeler">2 Wheeler</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="modal_4_wheeler" name="vehicle_type" class="custom-control-input vehicle_type" value="4 Wheeler">
                                <label class="custom-control-label" for="modal_4_wheeler">4 Wheeler</label>
                              </div>
                          </div>
                        </div>
                        <div class="col-12 mb-1">
                            <button type="button" class="btn btn-primary update" id="update">Update
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="">

            <div class="modal-header">
                <h4 class="modal-title" id="">Approve Travelling Allowance</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-sm-12 my-1">
                        <label>Approval Date : </label>
                        <input type="hidden" class="form-control travelling_allowance_id">
                        <input type="text" class="form-control all_approve_date datepicker" placeholder="Approve Date">
                        <span class="valid_errr all_approve_date_err"></span>
                    </div>

                    <div class="col-sm-12 mb-1">
                        <label for="">Approval By : </label>
                        <select class="form-control required all_approve_by" name="approve_by" style="width:100%">
                            @foreach($staff as $stf)
                            @if((session('role_id')==1 || session('role_id')==3) &&
                            (session('user_id')==$stf->user_id))
                            <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                            @endif
                            @endforeach


                            @if(session('role_id')==1 || session('role_id')==3)
                            <option value="">---Select approve by---</option>
                            @foreach($staff as $stf)
                            <option value="{{$stf->sid}}">{{$stf->name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="valid_errr all_approve_by_err"></span>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <button type="button" class="btn btn-icon btn-primary  approve_allowance_btn">Approve</button>
                        <button type="button" class="btn btn-icon btn-secondary " data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script> -->
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script> -->
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/travelling_allowance.js')}}"></script>
<script>
$(document).on("click", ".all_allowance_approve", function() {

    var travelling_allowance_id = new Array();
    $(".dt-checkboxes:checked").each(function() {
        travelling_allowance_id.push(
            $(this).closest("tr").find(".travelling_allowance_id").val()
        );
    });

    if (travelling_allowance_id == "") {
        $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
        );
        return false;
    }

    $(".travelling_allowance_id").val(travelling_allowance_id);
    $("#approveModal").modal("show");
});

$(document).on("click", ".approve_allowance_btn", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.valid_errr').html('');
    var status1 = $('.status_selected').html();
    var travelling_allowance_id = $(".travelling_allowance_id").val();
    var approve_date = $(".all_approve_date").val();
    var approve_by = $(".all_approve_by").val();
    var status = 'approved';
    var value = $("#allowance_filter").val();
    if (value) {
        filter = value;
    } else {
        filter = null;
    }

    var arr = [];
    if (approve_date == "") {
        arr.push("all_approve_date_err");
        arr.push("Please select approve date");
    }
    if (approve_by == "") {
        arr.push("all_approve_by_err");
        arr.push("Please select approve by");
    }

    if (arr != '') {
        for (var i = 0; i < arr.length; i++) {
            var j = i + 1;
            $('.' + arr[i]).html(arr[j]).css('color', 'red');
            i = j;
        }
    } else {
        $.ajax({
            type: "post",
            url: "approve_travelling_allowance",
            data: {
                id: travelling_allowance_id,
                approve_date: approve_date,
                approve_by: approve_by,
                status: status,
                filter: filter
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );
                if (res.status == "success") {
                    $("#approveModal").modal("toggle");
                    $(".data_div").empty().html(res.out);
                    $('#alert').html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg + '</span></div></div>').focus();

                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                    let str = status1;

                    $(".status_selected").html(str.toUpperCase());
                    var dataListView = $(".allowance-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control",
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                },
                            },
                            {
                                targets: [0, 1],
                                orderable: false,
                            },
                        ],
                        //order: [2, "asc"],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search",
                        },
                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row",
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0,
                            },
                        },
                    });

                    // To append actions dropdown inside action-btn div
                    var allowanceFilterAction = $(".allowance-filter-action");
                    var allowanceOptions = $(".allowance-options");
                    $(".action-btns").append(allowanceFilterAction,
                        allowanceOptions);
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
                    $('.datepicker').datepicker().on('changeDate', function(ev) {
                        $('.datepicker.dropdown-menu').hide();
                    });
                } else {
                    $("#approveModal").modal("toggle");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );

                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>').focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            },
            error: function(data) {
                //console.log(data);
                $("#approveModal").modal("toggle");
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );

                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                ).focus();
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            },
        });
    }
});

$(document).on("click", ".all_allowance_reject", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var travelling_allowance_id = new Array();
    $(".dt-checkboxes:checked").each(function() {
        travelling_allowance_id.push(
            $(this).closest("tr").find(".travelling_allowance_id").val()
        );
    });

    if (travelling_allowance_id == "") {
        $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Checkbox is not selected!</span></div></div>'
        );
        return false;
    } else {
        var value = $("#allowance_filter").val();
        if (value) {
            filter = value;
        } else {
            filter = null;
        }
        var status1 = $('.status_selected').html();
        var status = "rejected";

        Swal.fire({
            title: "Are you sure?",
            text: "You want to reject this travelling allowance?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn btn-warning",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "reject_travelling_allowance",
                    data: {
                        travelling_allowance_id: travelling_allowance_id,
                        status: status,
                        filter: filter
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == "success") {

                            $(".data_div").empty().html(res.out);
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();

                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            let str = status1;

                            $(".status_selected").html(str.toUpperCase());
                            var dataListView = $(".allowance-data-table").DataTable({
                                columnDefs: [{
                                        targets: 0,
                                        className: "control",
                                    },
                                    {
                                        orderable: true,
                                        targets: 1,
                                        checkboxes: {
                                            selectRow: true
                                        },
                                    },
                                    {
                                        targets: [0, 1],
                                        orderable: false,
                                    },
                                ],
                                //order: [2, "asc"],
                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                buttons: [
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5'
                                ],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search",
                                },
                                select: {
                                    style: "multi",
                                    selector: "td:first-child",
                                    items: "row",
                                },
                                responsive: {
                                    details: {
                                        type: "column",
                                        target: 0,
                                    },
                                },
                            });

                            // To append actions dropdown inside action-btn div
                            var allowanceFilterAction = $(".allowance-filter-action");
                            var allowanceOptions = $(".allowance-options");
                            $(".action-btns").append(allowanceFilterAction,
                                allowanceOptions);
                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass(
                                            "selected-row-bg");
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
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );

                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        }
                    },
                    error: function(data) {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );

                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                        ).focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            }
        });
    }
});

$(document).on("click", ".all_delete", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var travelling_allowance_id = new Array();
    $(".dt-checkboxes:checked").each(function() {
        travelling_allowance_id.push(
            $(this).closest("tr").find(".travelling_allowance_id").val()
        );
    });

    if (travelling_allowance_id == "") {
        $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Checkbox is not selected!</span></div></div>'
        );
        return false;
    } else {
        var status = $('.status_selected').html();
        var value = $("#allowance_filter").val();
        if (value) {
            filter = value;
        } else {
            filter = null;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this travelling allowance?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn btn-warning",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "delete_travelling_allowance",
                    data: {
                        travelling_allowance_id: travelling_allowance_id,
                        filter: filter
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == "success") {

                            $(".data_div").empty().html(res.out);
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();

                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            let str = status;

                            $(".status_selected").html(str.toUpperCase());
                            var dataListView = $(".allowance-data-table").DataTable({
                                columnDefs: [{
                                        targets: 0,
                                        className: "control",
                                    },
                                    {
                                        orderable: true,
                                        targets: 1,
                                        checkboxes: {
                                            selectRow: true
                                        },
                                    },
                                    {
                                        targets: [0, 1],
                                        orderable: false,
                                    },
                                ],
                                //order: [2, "asc"],
                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                buttons: [
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5'
                                ],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search",
                                },
                                select: {
                                    style: "multi",
                                    selector: "td:first-child",
                                    items: "row",
                                },
                                responsive: {
                                    details: {
                                        type: "column",
                                        target: 0,
                                    },
                                },
                            });

                            // To append actions dropdown inside action-btn div
                            var allowanceFilterAction = $(".allowance-filter-action");
                            var allowanceOptions = $(".allowance-options");
                            $(".action-btns").append(allowanceFilterAction,
                                allowanceOptions);
                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass(
                                            "selected-row-bg");
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
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );

                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        }
                    },
                    error: function(data) {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );

                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                        ).focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            }
        });
    }
});

$(document).on('click', '#submit', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.valid_err').html('');
    var status = $('.status_selected').html();
    var destination = $('#destination').val();
    var distance = $('#distance').val();
    var by_whom = $('#by_whom').val();
    var travel_date = $('#travel_date').val();

    if ($('#2_wheeler').prop("checked") == true) {
       var vehicle_type = $('#2_wheeler').val();
    }
    if ($('#4_wheeler').prop("checked") == true) {
        var vehicle_type = $('#4_wheeler').val();
    }

    var num = /^[0-9]+$/;
    var arr = [];

    if (destination == '') {
        arr.push('destination_err');
        arr.push('enter destination');
    }
    if (distance == '') {
        arr.push('distance_err');
        arr.push('enter distance');
    }
    if (distance != '') {
        if (num.test(distance) == false) {
            arr.push('distance_err');
            arr.push('valid number required');
        }
    }
    if (by_whom == '') {
        arr.push('by_whom_err');
        arr.push('select expense by');
    }

    if (travel_date == '') {
        arr.push('date_err');
        arr.push('select date');
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
            url: 'travelling_allowance',

            data: {
                destination: destination,
                distance: distance,
                by_whom: by_whom,
                date: travel_date,
                vehicle_type: vehicle_type
            },

            success: function(data) {
                var res = JSON.parse(data);
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );

                if (res.status == 'success') {
                    $("#addAllowanceForm").trigger("reset");
                    $('#by_whom').val('');
                    $('#by_whom').trigger('change');
                    $('#addModal').modal('hide');
                    $(".destination").autocomplete({
                        source: res.destination_name_array
                    });
                    $('.data_div').empty().html(res.out);
                    $('#alert').html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg + '</span></div></div>').focus();

                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                    let str = status;

                    $(".status_selected").html(str.toUpperCase());
                    var dataListView = $(".allowance-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control",
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                },
                            },
                            {
                                targets: [0, 1],
                                orderable: false,
                            },
                        ],
                        //order: [2, "asc"],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search",
                        },
                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row",
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0,
                            },
                        },
                    });

                    // To append actions dropdown inside action-btn div
                    var allowanceFilterAction = $(".allowance-filter-action");
                    var allowanceOptions = $(".allowance-options");
                    $(".action-btns").append(allowanceFilterAction, allowanceOptions);

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
                    $("#addAllowanceForm").trigger("reset");
                    $('#by_whom').val([]).trigger('change');
                    $('#addModal').modal('hide');

                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>').focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            },
            error: function(data) {
                $("#addAllowanceForm").trigger("reset");
                $('#by_whom').val([]).trigger('change');
                $('#addModal').modal('hide');
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );
                //console.log(data);
                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                ).focus();
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        });
    }
});

$(document).on('click', '.updateModal', function() {

    $('.modal_destination').val($(this).data('place'));
    $('.modal_travel_date').val($(this).data('date'));
    $('.modal_distance').val($(this).data('distance'));
    $('.modal_by_whom').val($(this).data('entry_by'));
    $('.travelling_allowance_id').val($(this).data('id'));

    if($(this).data('vehicle_type')  == '2 Wheeler'){
        $('#modal_2_wheeler').prop("checked", 'checked');
    }else if($(this).data('vehicle_type')  == '4 Wheeler'){
        $('#modal_4_wheeler').prop("checked", 'checked');
    }
});

$('.update').click(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.modal_valid_err').html('');
    var status = $('.status_selected').html();
    var value = $("#allowance_filter").val();
    if (value) {
        filter = value;
    } else {
        filter = null;
    }

    var destination = $('.modal_destination').val();
    var travelling_allowance_id = $('.travelling_allowance_id').val();
    var distance = $('.modal_distance').val();
    var by_whom = $('.modal_by_whom').val();
    var date = $('.modal_travel_date').val();
    var num = /^[0-9]+$/;
    var arr = [];

    if ($('#modal_2_wheeler').prop("checked") == true) {
       var vehicle_type = $('#modal_2_wheeler').val();
    }
    if ($('#modal_4_wheeler').prop("checked") == true) {
        var vehicle_type = $('#modal_4_wheeler').val();
    }

    if (destination == '') {
        arr.push('modal_destination_err');
        arr.push('enter destination');
    }
    if (distance == '') {
        arr.push('modal_distance_err');
        arr.push('enter distance');
    }
    if (distance != '') {
        if (num.test(distance) == false) {
            arr.push('modal_distance_err');
            arr.push('valid number required');
        }
    }
    if (by_whom == '') {
        arr.push('modal_by_whom_err');
        arr.push('Please select expense by');
    }

    if (date == '') {
        arr.push('date_err');
        arr.push('select date');
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
            url: 'travelling_allowance_update',

            data: {
                destination: destination,
                distance: distance,
                by_whom: by_whom,
                date: date,
                travelling_allowance_id: travelling_allowance_id,
                filter: filter,
                vehicle_type:vehicle_type
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );
                if (res.status == 'success') {
                    $("#updateAllowanceForm").trigger('reset');
                    $('#updateModal').modal('hide');
                    $(".destination").autocomplete({
                        source: res.destination_name_array
                    });
                    $('.data_div').empty().html(res.out);
                    $('#alert').html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg + '</span></div></div>').focus();

                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                    let str = status;

                    $(".status_selected").html(str.toUpperCase());
                    var dataListView = $(".allowance-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control",
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                },
                            },
                            {
                                targets: [0, 1],
                                orderable: false,
                            },
                        ],
                        //order: [2, "asc"],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search",
                        },
                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row",
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0,
                            },
                        },
                    });
                    // To append actions dropdown inside action-btn div
                    var allowanceFilterAction = $(".allowance-filter-action");
                    var allowanceOptions = $(".allowance-options");
                    $(".action-btns").append(allowanceFilterAction, allowanceOptions);
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
                    $("#updateAllowanceForm").trigger('reset');
                    $('#updateModal').modal('hide');

                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>').focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            },
            error: function(data) {
                //console.log(data);
                $("#updateAllowanceForm").trigger('reset');
                $('#updateModal').modal('hide');
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );

                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                ).focus();
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        });

    }

});

$(document).on("click", ".delete_allowance", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var status = $('.status_selected').html();
    var value = $("#allowance_filter").val();
    if (value) {
        filter = value;
    } else {
        filter = null;
    }

    var id = $(this).data("allowance_id");

    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this travelling allowance?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        confirmButtonClass: "btn btn-warning",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false,
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "delete_travelling_allowance",
                data: {
                    id: id,
                    filter: filter
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    if (res.status == "success") {

                        $(".data_div").empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                        let str = status;

                        $(".status_selected").html(str.toUpperCase());
                        var dataListView = $(".allowance-data-table").DataTable({
                            columnDefs: [{
                                    targets: 0,
                                    className: "control",
                                },
                                {
                                    orderable: true,
                                    targets: 1,
                                    checkboxes: {
                                        selectRow: true
                                    },
                                },
                                {
                                    targets: [0, 1],
                                    orderable: false,
                                },
                            ],
                            //order: [2, "asc"],
                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search",
                            },
                            select: {
                                style: "multi",
                                selector: "td:first-child",
                                items: "row",
                            },
                            responsive: {
                                details: {
                                    type: "column",
                                    target: 0,
                                },
                            },
                        });

                        // To append actions dropdown inside action-btn div
                        var allowanceFilterAction = $(".allowance-filter-action");
                        var allowanceOptions = $(".allowance-options");
                        $(".action-btns").append(allowanceFilterAction, allowanceOptions);

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
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            res.msg + '</span></div></div>').focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                },
                error: function(data) {
                    console.log(data);

                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );

                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                    ).focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            });
        }
    });
});

$(document).on('click', '.status_btn', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.data_div').empty();
    $('.loader').css('display', 'block');
    var value = $(this).data('value');

    $.ajax({
        type: 'post',
        url: 'filter_travelling_allowance',
        data: {
            value: value
        },

        success: function(data) {
            $('.loader').css('display', 'none');
            console.log(data);
            var res = JSON.parse(data);
            if (res.status == 'success') {
                $('.data_div').empty().html(res.out);
                let str = value;

                $(".status_selected").html(str.toUpperCase());
                var dataListView = $(".allowance-data-table").DataTable({
                    columnDefs: [{
                            targets: 0,
                            className: "control",
                        },
                        {
                            orderable: true,
                            targets: 1,
                            checkboxes: {
                                selectRow: true
                            },
                        },
                        {
                            targets: [0, 1],
                            orderable: false,
                        },
                    ],
                    //order: [2, "asc"],
                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    language: {
                        search: "",
                        searchPlaceholder: "Search",
                    },
                    select: {
                        style: "multi",
                        selector: "td:first-child",
                        items: "row",
                    },
                    responsive: {
                        details: {
                            type: "column",
                            target: 0,
                        },
                    },
                });

                // To append actions dropdown inside action-btn div
                var allowanceFilterAction = $(".allowance-filter-action");
                var allowanceOptions = $(".allowance-options");
                $(".action-btns").append(allowanceFilterAction,
                    allowanceOptions);
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
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );

                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                    res.msg + '</span></div></div>').focus();
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        },
        error: function(data) {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0)
                },
                "slow"
            );

            $('#alert').html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
            ).focus();
            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                $(".alert").slideUp(500);
            });
        },
    });
});

$(document).on("click", ".create_approve_btn", function() {
    $(this).closest("tr").find(".apr_dt_ui").css("display", "block");
    $(this).closest("tr").find(".apr_dt_data").css("display", "none");
    $(this).closest("tr").find(".apr_by_ui").css("display", "block");
    $(this).closest("tr").find(".apr_by_data").css("display", "none");
    $(this).closest("tr").find(".approve_btn").css("display", "block");
    $(this).closest("tr").find(".close_approve_btn").css("display", "block");
    $(this).closest("tr").find(".create_approve_btn").css("display", "none");
});

$(document).on("click", ".close_approve_btn", function() {
    $(this).closest("tr").find(".apr_dt_ui").css("display", "none");
    $(this).closest("tr").find(".apr_dt_data").css("display", "block");
    $(this).closest("tr").find(".apr_by_ui").css("display", "none");
    $(this).closest("tr").find(".apr_by_data").css("display", "block");
    $(this).closest("tr").find(".approve_btn").css("display", "none");
    $(this).closest("tr").find(".close_approve_btn").css("display", "none");
    $(this).closest("tr").find(".create_approve_btn").css("display", "block");
});

$(document).on("click", ".approve_btn", function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var status1 = $('.status_selected').html();
    var value = $("#allowance_filter").val();
    if (value) {
        filter = value;
    } else {
        filter = null;
    }
    var status = "approved";
    var approve_date = $(this).closest("tr").find(".approve_date").val();
    var approve_by = $(this).closest("tr").find(".approve_by").val();

    var id = $(this).data("id");

    var arr = [];
    if (approve_date == "") {
        arr.push("approve_date_err");
        arr.push("Please select approve date");
    }
    if (approve_by == "") {
        arr.push("approve_by_err");
        arr.push("Please select approve by");
    }

    if (arr != "") {
        for (var i = 0; i < arr.length; i++) {
            var j = i + 1;

            $(this)
                .closest("tr")
                .find("." + arr[i])
                .html(arr[j])
                .css("color", "red");

            i = j;
        }
    } else {
        var id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You want to approve this travelling allowance?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn btn-warning",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "approve_travelling_allowance",
                    data: {
                        status: status,
                        id: id,
                        approve_date: approve_date,
                        approve_by: approve_by,
                        filter: filter
                    },

                    success: function(data) {
                        //console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == "success") {
                            $(".data_div").empty().html(res.out);
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();

                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            let str = status1;

                            $(".status_selected").html(str.toUpperCase());
                            var dataListView = $(".allowance-data-table").DataTable({
                                columnDefs: [{
                                        targets: 0,
                                        className: "control",
                                    },
                                    {
                                        orderable: true,
                                        targets: 1,
                                        checkboxes: {
                                            selectRow: true
                                        },
                                    },
                                    {
                                        targets: [0, 1],
                                        orderable: false,
                                    },
                                ],
                                //order: [2, "asc"],
                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                buttons: [
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5'
                                ],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search",
                                },
                                select: {
                                    style: "multi",
                                    selector: "td:first-child",
                                    items: "row",
                                },
                                responsive: {
                                    details: {
                                        type: "column",
                                        target: 0,
                                    },
                                },
                            });

                            // To append actions dropdown inside action-btn div
                            var allowanceFilterAction = $(".allowance-filter-action");
                            var allowanceOptions = $(".allowance-options");
                            $(".action-btns").append(allowanceFilterAction,
                                allowanceOptions);
                            // add class in row if checkbox checked
                            $(".dt-checkboxes-cell")
                                .find("input")
                                .on("change", function() {
                                    var $this = $(this);
                                    if ($this.is(":checked")) {
                                        $this.closest("tr").addClass("selected-row-bg");
                                    } else {
                                        $this.closest("tr").removeClass(
                                            "selected-row-bg");
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
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );

                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        }
                    },
                    error: function(data) {
                        //console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );

                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
                        ).focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    },
                });
            }
        });
    }
});

$(document).on("click", ".reject_btn", function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var status1 = $('.status_selected').html();
    var value = $("#allowance_filter").val();
    if (value) {
        filter = value;
    } else {
        filter = null;
    }
    var status = "rejected";
    var id = $(this).data("id");

    Swal.fire({
        title: "Are you sure?",
        text: "You want to reject this travelling allowance?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        confirmButtonClass: "btn btn-warning",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false,
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "reject_travelling_allowance",
                data: {
                    status: status,
                    id: id,
                    filter: filter
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    if (res.status == "success") {
                        $(".data_div").empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                        let str = status1;

                        $(".status_selected").html(str.toUpperCase());
                        var dataListView = $(".allowance-data-table").DataTable({
                            columnDefs: [{
                                    targets: 0,
                                    className: "control",
                                },
                                {
                                    orderable: true,
                                    targets: 1,
                                    checkboxes: {
                                        selectRow: true
                                    },
                                },
                                {
                                    targets: [0, 1],
                                    orderable: false,
                                },
                            ],
                            //order: [2, "asc"],
                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search",
                            },
                            select: {
                                style: "multi",
                                selector: "td:first-child",
                                items: "row",
                            },
                            responsive: {
                                details: {
                                    type: "column",
                                    target: 0,
                                },
                            },
                        });

                        // To append actions dropdown inside action-btn div
                        var allowanceFilterAction = $(".allowance-filter-action");
                        var allowanceOptions = $(".allowance-options");
                        $(".action-btns").append(allowanceFilterAction,
                            allowanceOptions);
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
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            res.msg + '</span></div></div>').focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                },
                error: function(data) {
                    //console.log(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );

                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
                    ).focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                },
            });
        }
    });
});
</script>
@endsection