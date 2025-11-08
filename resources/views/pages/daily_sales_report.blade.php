@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Clients Info')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
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
            <div class="row">
                <div class="col-md-7">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control staff" id="staff_id">
                                <option value="">--Select Staff--</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    <span class="staff_err valid_err"></span>
                </div>
                <div class="col-md-3">
                    <fieldset>
                        <input type="text" class="form-control datepicker" id="date" placeholder="Select Date">
                    </fieldset>
                    <span class="date_err valid_err"></span>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary filter_btn" type="button">Search</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="data_div">
                        <!-- Options and filter dropdown button-->
                        <div class="table-responsive">
                            <table class="table client-data-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>No of units</th>
                                        <th>Property type</th>
                                        <th>Quotations</th>
                                        <th>Follow-up</th>
                                        <th>Appointments</th>
                                        <th>Source</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Pincode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($client_list as $row)
                                    <tr>
                                        <td><span
                                                class="client-customer">{{$row->case_no }}<small>{{$row->client_name }}</small></span>
                                        </td>
                                        <td><a class="text-success" href="#" data-toggle="modal"
                                                data-target="#contactModal">View</a></td>
                                        <td>{{ $row->no_of_units }}</td>
                                        <td>{{ $row->property_type_name }}</td>
                                        <td><a href="#" class="detailBtn" data-client_id="{{ $row->id }}"
                                                data-detail="quotation" data-toggle="modal"
                                                data-target="#detailModal">{{ $row->quotations }}</a></td>
                                        <td><a href="#" class="detailBtn" data-client_id="{{ $row->id }}"
                                                data-detail="followup" data-toggle="modal"
                                                data-target="#detailModal">{{ $row->followups }}</a></td>
                                        <td><a href="#" class="detailBtn" data-client_id="{{ $row->id }}"
                                                data-detail="appointment" data-toggle="modal"
                                                data-target="#detailModal">{{ $row->appointments }}</a></td>
                                        <td>{{ $row->source_name }}</td>
                                        <td><small>{{ $row->address }}</small></td>
                                        <td>{{ $row->city_name }}</td>
                                        <td>{{ $row->pincode }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Basic Modal -->
<div class="modal fade text-left" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
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

<div class="modal fade text-left" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
    aria-hidden="true">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title contactModal-title" id="myModalLabel2">Client Contact </h4>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body contactModal-body">
                <p><strong class="text-primary">Mobile No -</strong> <span class="contacts"></span></p>
            </div>

        </div>
    </div>
</div>

<!---end-->
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
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')

<script>
$(document).ready(function() {
    $(".datepicker")
        .datepicker()
        .on("changeDate", function(ev) {
            $(".datepicker.dropdown-menu").hide();
        });

    if ($(".client-data-table").length) {
        var dataListView = $(".client-data-table").DataTable({
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    }

});
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#staff_id").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Select Staff",
    });

});

$(document).on('click', '.filter_btn', function() {
    $('.valid_err').html('');
    var arr = [];

    var staff_id = $('#staff_id').val();
    var date = $('#date').val();

    if (staff_id == '') {
        arr.push('staff_err');
        arr.push('Select Staff !!');
    }

    if (date == '') {
        arr.push('date_err');
        arr.push('Select Date !!');
    }
    if (arr != '') {
        for (var i = 0; i < arr.length; i++) {
            var j = i + 1;
            $('.' + arr[i]).html(arr[j]).css('color', 'red');
            i = j;
        }
    } else {
        $('.loader').css('display', 'block');
        $.ajax({
            type: 'get',
            url: 'get_daily_leads_by_sales',
            data: {
                staff_id: staff_id,
                date: date
            },

            success: function(data) {
                $('.loader').css('display', 'none');
                console.log(data);
                var res = data;
                $('.data_div').html(res.out);
                var dataListView = $(".client-data-table").DataTable({
                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        });
    }
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

function showContact(id) {
    $.ajax({
        type: 'get',
        url: 'get_client_contacts',
        data: {
            client_id: id
        },

        success: function(data) {
            console.log(data);
            var res = data;
            if (res.status == 'success') {
                $('#contactModal').modal('show');
                $('.contacts').empty().text(res.out);
            } else {
                $('#contactModal').modal('show');
                $('.contacts').empty().text(res.out);
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}
</script>
@endsection