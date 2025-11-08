@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Appointment List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .modal-lg,
    .modal-xl {
        max-width: 1500px;
    }

    .dropdown-menu {
        z-index: 99999 !important;
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

<section class="client-list-wrapper">

    <!-- create client button-->
    <!-- <div class="client-create-btn mb-1">
    <a href="{{asset('app/client/add')}}" class="btn btn-primary glow client-create" role="button" aria-pressed="true">Create
      client</a>
  </div> -->
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

        <!-- Options and filter dropdown button-->
        <div class="action-dropdown-btn d-none">
            <div class="dropdown client-filter-action">
                <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="selection">Filter Appointment</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
                    <a type="button" href="#" class="dropdown-item active_btn" data-value="finalize">Finalize</a>
                    <a type="button" href="#" class="dropdown-item active_btn" data-value="pending">Pending</a>

                </div>
            </div>
            <!-- <button type="button" class="btn mr-1 mb-1 btn-primary">Add Client</button> -->
            <div class="client-options">
                <a href="appointment-add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                    <i class="bx bx-plus"></i>New Appointment</a>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Action</th>
                                <th>Client Name </th>
                                <th>Visit Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Attended by</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($appointmentsData as $item)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="client-action">
                                        @if($item->status=='finalize')
                                        <button type="button" class="btn btn-icon rounded-circle btn-success mr-1 mb-1 view_consulting_btn" data-toggle="modal" data-target="#viewConsultingFee" data-appointment_id="{{$item->id}}" data-tooltip="View Fee">
                                            <i class="bx bx-down-arrow-alt"></i></button>

                                        @else

                                        <button type="button" class="btn btn-icon rounded-circle btn-success mr-1 mb-1 consulting_pay_btn" data-appointment_id="{{$item->id}}" data-fees="{{$item->charges}}" data-toggle="modal" data-target="#modalconsultingfee" data-tooltip="View Fee">
                                            <i class="bx bx-credit-card"></i></button>

                                        @endif
                                        @if($item->status=='finalize')

                                        <a href="consulting_fee_reciept-{{$item->id}}" class="btn btn-icon rounded-circle btn-warning mr-1 mb-1" data-appointment_id="{{$item->id}}" data-tooltip="Generate Reciept">
                                            <i class="bx bx-printer"></i></a>

                                        @else
                                        <button type="button" class="btn btn-icon rounded-circle btn-warning mr-1 mb-1" disabled="disabled">
                                            <i class="bx bx-printer"></i></button>
                                        @endif

                                        <button type="button" class="btn btn-icon rounded-circle btn-info mr-1 mb-1 remodalbtn" data-appointment_id="{{$item->id}}" data-meeting_with="{{$item->meeting_with}}" data-place="{{$item->place}}" data-time="{{$item->meeting_time}}" data-date="{{$item->meeting_date}}" data-toggle="modal" data-target="#reshcheduleModal" data-tooltip="Reschedule">
                                            <i class="bx bx-reset"></i></button>

                                        <button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete_appointment_btn" data-appointment_id="{{$item->id}}" data-tooltip="Delete">
                                            <i class="bx bx-trash"></i></button>

                                    </div>
                                </td>
                                <td><span class="client-customer">{{ $item->client_case_no }}
                                    </span></td>
                                <td>{{$item->aname}}</td>
                                <td>{{$item->status}}</td>
                                <td data-sort="{{strtotime($item->meeting_date)}}"><?php echo date("d-m-Y", strtotime($item->meeting_date)); ?></td>
                                <td>{{$item->meeting_time}}</td>
                                <td>{{$item->meetname}}</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reshcheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="">

                <div class="modal-header bg-pink">
                    <h4 class="modal-title" id="uModalLabel">Reschedule Meeting</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" class="form-control re_appointment_id">
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <select class="form-control  re_meeting_with" name="meeting_with">
                                <option value="">--meeting with--</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach
                            </select>
                            <span class="valid_err meeting_with_err"></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control re_time timepicker" placeholder="time">
                            <span class="valid_err time_err"></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control re_date datepicker" placeholder="date">
                            <span class="valid_err date_err"></span>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control re_meeting_place">
                                <option value="">--select meeting place--</option>
                                @foreach($appointment_places as $ap)
                                <option value="{{$ap->id}}">{{$ap->name}}</option>
                                @endforeach
                            </select>
                            <span class="valid_err meeting_place_err"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-success reschedule_btn">Reschedule</button>
                    <button type="button" class="btn btn-icon btn-danger " data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalconsultingfee" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="">

                <div class="modal-header bg-pink">
                    <h4 class="modal-title" id="uModalLabel">Consulting Fees</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <input type="hidden" placeholder="Appointment Id" class="form-control appointment_id">
                        <div class="col-sm-4">
                            <input type="text" placeholder="calculating fee" class="form-control fees">
                            <span class="valid_err fees_err"></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" placeholder="payment date" class="form-control payment_date datepicker">
                            <span class="valid_err payment_date_err"></span>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control payment_mode">
                                <option value="">payment mode</option>
                                <option value="cash">cash</option>
                                <option value="cheque">cheque</option>
                                <option value="online">online</option>
                            </select>
                            <span class="valid_err payment_mode_err"></span>
                        </div>
                    </div>

                    <div class="row clearfix cheque_div" style="display:none">
                        <div class="col-sm-12 my-2">
                            <input type="text" placeholder="Cheque no" class="form-control cheque_no">
                            <span class="valid_err cheque_no_err"></span>
                        </div>
                        <div class="col-sm-12 my-2">
                            <input type="text" placeholder="Cheque Date" class="form-control datepicker cheque_date">
                            <span class="valid_err cheque_date_err"></span>
                        </div>
                    </div>

                    <div class="row clearfix ref_div" style="display:none">
                        <div class="col-sm-12 my-2">
                            <input type="text" placeholder="Reference" class="form-control reference">
                            <span class="valid_err reference_err"></span>
                        </div>
                        <div class="col-sm-12 my-2">
                            <textarea class="form-control remark" placeholder="Remark"></textarea>
                            <span class="valid_err remark_err"></span>
                        </div>
                    </div>

                    <div class="row clearfix bank_div" style="display:none">
                        <div class="col-sm-12 my-2">
                            <select class="form-control bank">
                                <option value="">Bank</option>
                                @foreach($bank as $row)
                                <option value="{{$row->id}}">{{$row->bankname}}</option>
                                @endforeach
                            </select>
                            <span class="valid_err bank_err"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-success fees_btn">payment</button>
                    <button type="button" class="btn btn-icon btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewConsultingFee" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="">

                <div class="modal-header bg-pink">
                    <h4 class="modal-title" id="uModalLabel">View consulting fee</h4>
                </div>
                <div class="modal-body">

                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Client</th>
                                    <th>Place</th>
                                    <th>Fees</th>
                                    <th>Payment mode</th>
                                    <th>Cheque no</th>
                                    <th>Cheque date</th>
                                    <th>Reference</th>
                                    <th>Remark</th>
                                    <th>Bank</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="consulting_body">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-icon btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>



            </div>
        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script> -->
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script> -->
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/appointment.js')}}"></script>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });

    $(document).on('click', '.active_btn', function() {
        $('.loader').css('display', 'block');
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'get_appointment_by_status',
            data: {
                value: value
            },

            success: function(data) {
                $('.loader').css('display', 'none');
                //console.log(data);
                var res = JSON.parse(data);
                $('.data_div').empty().html(res.out);
                let str = value;

                $(".selection").html(str.toUpperCase());
                if ($(".client-data-table").length) {
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
                            searchPlaceholder: "Search Appointment"
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
                }

                // To append actions dropdown inside action-btn div
                var clientFilterAction = $(".client-filter-action");
                var clientOptions = $(".client-options");
                $(".action-btns").append(clientFilterAction, clientOptions);
            }
        });
    });
</script>
@endsection