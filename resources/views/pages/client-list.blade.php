@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Clients Info')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>


</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
    <div id="alert"></div>
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
            <div class="data_div">

                <!-- Options and filter dropdown button-->
                <div class="action-dropdown-btn d-none">
                    <div class="dropdown client-filter-action">
                        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="selection">Active</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
                            <a type="button" href="#" class="dropdown-item filter_btn" data-value="active" data-selection_val="Active">Active</a>
                            <a type="button" href="#" class="dropdown-item filter_btn" data-value="inactive" data-selection_val="In Active">In Active</a>

                        </div>
                    </div>
                    <!-- <button type="button" class="btn mr-1 mb-1 btn-primary">Add Client</button> -->
                    <div class="client-options">
                        <a href="client_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                            <i class="bx bx-plus"></i>Add Client</a>

                    </div>
                </div <div class="table-responsive">
                <table class="table client-data-table wrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>No of units</th>
                            <!-- <th>area</th> -->
                            <th>Property type</th>
                            <th>Quotations</th>
                            <th>Follow-up</th>
                            <th>Appointments</th>
                            <th>Source</th>
                            <th>Remarks</th>

                            <th>Address</th>
                            <th>City</th>
                            <th>Pincode</th>



                        </tr>
                    </thead>

                    <tbody>
                        @foreach($client_list as $row)
                        <tr>
                            <td style="white-space: nowrap;">
                                <div class="client-action">
                                    <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon rounded-circle glow btn-info mr-1 mb-1 add_appointment_btn" data-id="{{$row->id}}" data-client_name="{{$row->client_name}}" data-tooltip="Add appointment" data-toggle="modal" data-target="#appointmentModal">
                                        <i class="bx bx-alarm-add"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon rounded-circle glow btn-secondary mr-1 mb-1 add_followup" data-id="{{$row->id}}" data-client_name="{{$row->client_name}}" data-tooltip="Add follow-up" data-toggle="modal" data-target="#followupModal">
                                        <i class="bx bx-phone-call"></i>
                                    </a>
                                    @if(session('role_id')==1 || session('role_id')==3)
                                    <a href="#" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                            <td><span class="client-customer">{{ $row->client_case_no }}</span>
                            </td>
                            <td>{{$row->no_of_units}}</td>
                            <!-- <td>{{$row->area}}</td> -->
                            <td>{{$row->property_type_name}}</td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Quotation" data-toggle="modal" data-target="#detailModal">{{$row->quotations}}</a></td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Follow-Up" data-toggle="modal" data-target="#detailModal">{{$row->followups}}</a></td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Appointment" data-toggle="modal" data-target="#detailModal">{{$row->appointments}}</a></td>
                            <td>{{$row->source_name}}</td>
                            <td><small>{{$row->remarks}}</small></td>
                            <td><small>{{$row->address}}</small></td>
                            <td>{{$row->city_name}}</td>

                            <td>{{$row->pincode}}</td>



                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>
    </div>
    </div>
</section>
<!--Basic Modal -->
<div class="modal fade text-left" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title detailModal-title" id="myModalLabel1"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body detailModal-body">

            </div>

        </div>
    </div>
</div>
</div>
</div>
</div>
<!---end-->

<!--Basic Modal -->
<div class="modal fade text-left" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">New appointment</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!----modal body start-->
                <div class="row mx-0">
                    <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">


                    </div>
                    <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                        <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                            <div class="d-flex align-items-center">
                                <span class="mr-75 label_title">Date: </span>

                                <fieldset class="d-flex ">
                                    <input type="text" class="form-control pickadate mr-2 meeting_date" placeholder="Select Date">
                                </fieldset>
                                <span class="valid_err meeting_date_err"></span>

                            </div>

                            <div class="d-flex align-items-center">
                                <span class="mr-75 label_title">Time: </span>

                                <fieldset class="d-flex">
                                    <input type="text" class="form-control timepicker time" placeholder="Select Date">
                                </fieldset>
                                <span class="valid_err time_err"></span>

                            </div>
                        </div>
                    </div>
                </div><br>

                <div class="row pt-50">
                    <div class="col-12 col-md-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="hidden" class="form-control appointment_client_id">
                                <input type="text" class="form-control appointment_client_name">
                                <span class="client_err valid_err"></span>

                            </div>
                        </fieldset>
                    </div>

                </div>

                <div class="row  pt-50">
                    <div class="col-12 col-md-4">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Place</span>
                                </div>
                                <select class="form-control meeting_place" id="meeting_place" name="meeting_place">
                                    <option value="">Choose...</option>
                                    @foreach($appointment_places as $ap)
                                    <option value="{{$ap->id}}">{{$ap->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="meeting_place_err valid_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4 col-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Meeting with</span>
                                </div>
                                <select class="form-control meeting_with" id="meeting_with" name="meeting_with">
                                    <option value="">Choose...</option>
                                    @foreach($staff as $stf)

                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>


                                    @endforeach
                                </select>
                            </div>
                            <span class="meeting_with_err valid_err"></span>
                        </fieldset>
                    </div>


                    <div class="col-md-4 col-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Schedule By</span>
                                </div>
                                <select class="form-control schedule_by" id="schedule_by" name="schedule_by">

                                    @foreach($staff as $stf)
                                    @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))
                                    <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>

                                    @endif
                                    @endforeach
                                    @if(session('role_id')==1)
                                    <option value="">Choose...</option>
                                    @foreach($staff as $stf)
                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <span class="schedule_by_err valid_err"></span>
                        </fieldset>
                    </div>

                </div>





            </div><!-- modal body end-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="save_appointment" data-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!---end-->

<!--Basic Modal -->
<div class="modal fade text-left" id="followupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Follow-up</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!----modal body start-->
                <div class="row mx-0">
                    <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">


                    </div>
                    <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                        <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                            <div class="d-flex align-items-center">
                                <span class="mr-75 label_title">Date: </span>

                                <fieldset class="d-flex ">
                                    <input type="text" class="form-control pickadate mr-2 followup_date" placeholder="Select Date">
                                </fieldset>
                                <span class="valid_err followup_date_err"></span>

                            </div>


                        </div>
                    </div>
                </div><br>


                <div class="row pt-50">
                    <div class="col-12 col-md-12">
                        <fieldset class="form-group">
                            <div class="input-group">

                                <input type="hidden" class="form-control followup_client_id">
                                <input type="text" class="form-control followup_client_name">
                                <span class="client_err valid_err"></span>

                            </div>
                        </fieldset>
                    </div>

                </div>

                <div class="row  pt-50">
                    <div class="col-12 col-md-4">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Method</span>
                                </div>
                                <select class="form-control method" id="method" name="method">
                                    <option value="">Choose...</option>
                                    <option value="call">call</option>
                                    <option value="email">email</option>
                                    <option value="whatsapp">whatsapp</option>
                                    <option value="visit">visit</option>
                                </select>
                            </div>
                            <span class="method_err valid_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4 col-12">
                        <fieldset class="form-group">
                            <select class="form-control contact_by" id="contact_by" name="contact_by">
                                <option value="">Contact By</option>
                                @foreach($staff as $stf)
                                @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))
                                <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                @endif
                                @endforeach


                                @if(session('role_id')==1)
                                <option value="">--select Contact by--</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach
                                @endif

                            </select>
                            <span class="contact_by_err valid_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-12 col-md-4">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Company</span>
                                </div>
                                <select class="form-control company" id="company" name="company">
                                    <option value="">Choose...</option>
                                    @foreach($company as $com)
                                    @if($com->id==session('company_id'))
                                    <option value="{{$com->id}}" selected>{{$com->company_name}}</option>
                                    @else
                                    <option value="{{$com->id}}">{{$com->company_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <span class="company_err valid_err"></span>
                        </fieldset>
                    </div>
                </div>
                <div class="row  pt-50">
                    <div class="col-md-12 col-12 contact_div">

                    </div>
                    <div class="col-md-12 col-12">
                        <span class="valid_err contact_to_err"></span>
                    </div>
                </div>
                <hr>
                <div class="row  pt-50">
                    <div class="col-md-3 col-12">
                        <fieldset class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="next_follow_up_date" id="next_radio" checked="">
                                <label class="custom-control-label" for="next_radio">Next Follow-up date</label>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-3 col-12 next_date_div">

                        <fieldset class="form-group">
                            <input type="text" class="form-control pickadate mr-2 next_followup_date" placeholder="Next Follow-up Date">
                            <span class="valid_err next_date_err"></span>
                        </fieldset>
                    </div>

                    <div class="col-md-6 col-12">
                        <ul class="list-unstyled mb-0">
                            <li class="d-inline-block mr-2 mb-1">
                                <fieldset class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="finalized" id="finalized_radio">
                                        <label class="custom-control-label" for="finalized_radio">Finalized</label>
                                    </div>
                                </fieldset>
                            </li>
                            <li class="d-inline-block mr-2 mb-1">
                                <fieldset class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="lead_closed" id="leadclosed_radio">
                                        <label class="custom-control-label" for="leadclosed_radio">Lead
                                            closed</label>
                                    </div>
                                </fieldset>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row  pt-50">
                    <div class="col-md-12 col-12">
                        <fieldset class="form-label-group">
                            <textarea class="form-control discussion" id="discussion" rows="3" placeholder="Discussion"></textarea>
                            <label for="discussion">Discussion</label>
                            <span class="valid_err discussion_err"></span>
                        </fieldset>

                    </div>
                </div>


            </div><!-- modal body end-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="save_followup" data-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!---end-->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
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

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/client_list.js')}}"></script>
<script src="{{asset('js/scripts/wickedpicker/dist/wickedpicker.min.js')}}"></script>
<script>
    $(document).ready(function() {
        //$('.timepicker').wickedpicker();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.filter_btn', function() {
            $('.data_div').empty();
            $('.loader').css('display', 'block');
            var value = $(this).data('value');
            var client_leads = 'client'
            var selection_val = $(this).data('selection_val');

            $.ajax({
                type: 'post',
                url: 'filter_client',
                data: {
                    value: value,
                    client_leads: client_leads,
                    page: 'client_list',
                    selection_val: selection_val
                },

                success: function(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);
                    var res = data;

                    $('.data_div').empty().html(res.out);

                    var dataListView = $(".client-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 0,
                                // checkboxes: { selectRow: true }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],
                        order: [2, 'asc'],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Leads"
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
                            },


                        },


                    });

                    // To append actions dropdown inside action-btn div
                    var clientFilterAction = $(".client-filter-action");
                    var clientOptions = $(".client-options");
                    $(".action-btns").append(clientFilterAction, clientOptions);
                }
            });
        });
        $(document).on('click', '.delete_client', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this client",
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
                        url: 'delete_client',
                        data: {
                            id: id
                        },

                        success: function(data) {

                            console.log(data);
                            var res = JSON.parse(data);
                            if (res.status == 'success') {
                                Swal.fire({
                                    icon: "success",
                                    title: 'Deleted!',
                                    text: 'Client has been deleted.',
                                    confirmButtonClass: 'btn btn-success',
                                })
                                $('.data_div').empty().html(res.out);

                                var dataListView = $(".client-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control"
                                        },
                                        {
                                            orderable: true,
                                            targets: 0,
                                            // checkboxes: { selectRow: true }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false
                                        },
                                    ],
                                    order: [2, 'asc'],
                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Leads"
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
                                        },


                                    },


                                });

                                // To append actions dropdown inside action-btn div
                                var clientFilterAction = $(".client-filter-action");
                                var clientOptions = $(".client-options");
                                $(".action-btns").append(clientFilterAction,
                                    clientOptions);

                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: 'Error!',
                                    text: 'Client can`t be deleted.',
                                    confirmButtonClass: 'btn btn-danger',
                                })
                            }
                        }
                    });
                }
            });
        });
        $(document).on('click', '.convert_client', function() {
            var client_id = $(this).data('client_id');
            $.ajax({
                type: 'post',
                url: 'convert_client',
                data: {
                    client_id: client_id
                },

                success: function(data) {

                    console.log(data);
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

                        var dataListView = $(".client-data-table").DataTable({

                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search Leads"
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
                                },


                            },


                        });

                        // To append actions dropdown inside action-btn div
                        var clientFilterAction = $(".client-filter-action");
                        var clientOptions = $(".client-options");
                        $(".action-btns").append(clientFilterAction, clientOptions);


                    }
                },
                error: function(data) {
                    var res = data;
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>').focus();
                }

            });
        });
        $(document).on('click', '.detailBtn', function() {

            var client_id = $(this).data('client_id');
            var detail = $(this).data('detail');
            $.ajax({
                type: 'post',
                url: 'get_quo_appo_foll',
                data: {
                    client_id: client_id,
                    detail: detail,
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('.detailModal-title').empty().html(detail);
                    $('.detailModal-body').empty().html(res.out);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.add_appointment_btn', function() {
            $('.appointment_client_name').val($(this).data('client_name'));
            $('.appointment_client_id').val($(this).data('id'));
        });
        $(document).on('click', '#save_appointment', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.valid_err').html('');
            var client = $('.followup_client_id').val();
            var meeting_with = $('.meeting_with').val();
            var schedule_by = $('.schedule_by').val();
            var meeting_date = $('.meeting_date').val();
            var time = $('.time').val();
            var meeting_place = $('.meeting_place').val();

            var arr = [];

            if (client == '') {
                arr.push('client_err');
                arr.push('Please select client');
            }

            if (meeting_with == '') {
                arr.push('meeting_with_err');
                arr.push('Please select meeting with');
            }
            if (schedule_by == '') {
                arr.push('schedule_by_err');
                arr.push('Please select schedule by');
            }

            if (meeting_date == '') {
                arr.push('meeting_date_err');
                arr.push('Meeting date required');
            }
            if (time == '') {
                arr.push('time_err');
                arr.push('time required');
            }
            if (meeting_place == '') {
                arr.push('meeting_place_err');
                arr.push('Please select meeting place');
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
                    url: 'submit_appointment',

                    data: {
                        client: client,
                        meeting_with: meeting_with,
                        schedule_by: schedule_by,
                        meeting_date: meeting_date,
                        time: time,
                        meeting_place: meeting_place,
                    },

                    success: function(data) {
                        console.log(data);
                        $('#appointmentModal').modal('hide');
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            console.log('success');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $("#form").trigger('reset');


                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');

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
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            }

        });
    });
</script>
@endsection