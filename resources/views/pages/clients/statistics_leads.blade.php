@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Statistics')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
    .card-body{
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
    <div id="alert"></div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    @include('layouts.tabs')
                </div>
                <div class="col-2">
                    <a href="client_add" class="btn btn-icon btn-outline-primary px-3 float-right" role="button" aria-pressed="true">
                        <strong><i class="bx bx-plus"></i>Add Lead</strong></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker from_date" placeholder="From Date">
                    </div>
                    <span class="text-danger from_date_err"></span>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker to_date" placeholder="To Date">
                    </div>
                    <span class="text-danger to_date_err"></span>
                </div>
                @if(session('role_id') == 1)
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" id="search_by_staff">
                            <option value="">Staff</option>
                            @foreach ($staff as $row2)
                            <option value={{$row2->sid}}>
                                {{$row2->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" id="search_by_source">
                            <option value="">Source</option>
                            @foreach ($source as $row3)
                            <option value={{$row3->id}}>
                                {{$row3->source}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control" placeholder="Enter Address" id="search_by_address">
                    </div>
                </div>
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_city">
                                <option value="">City</option>
                                @foreach ($address as $row4)
                                <option value={{$row4->id}}>
                                    {{$row4->city_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_status">
                                <option value="">Status</option>
                                <option value="active">Active</option>
                                <option value="inctive">Inactive</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_leadtype">
                                <option value="">Lead Type</option>
                                @foreach ($leadtype as $row5)
                                <option value={{$row5->id}}>
                                    {{$row5->type}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-3">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>
                <!-- <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div> -->
                <div class="col-md-12">
                    <div class="form-group">
                        <select class="form-control client" id="client" multiple="multiple">
                            <option value="">Search By Clients</option>
                            @foreach ($client_case as $row1)
                            <option value={{$row1->id}}>
                                {{$row1->case_no}} ({{$row1->client_name}})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <center>
                <div class="spinner-grow text-primary loader" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="loader" style="display:none">Please wait...</h5>
            </center>
            <div class="data_div">
                <!-- Options and filter dropdown button-->
                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th width="10%">Action</th>
                                <th>Name</th>
                                <th>Assigned To</th>
                                <th>Lead Type</th>
                                <th>No of units</th>
                                <th>Property type</th>
                                <th>Quotations</th>
                                <th>Follow-up</th>
                                <th>Appointments</th>
                                <th>Source</th>
                                <th>Address</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>

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

<!---end-->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.client-data-table').hide();
        $("#client").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: "Search By Clients",
        });
        $("#search_by_staff").select2();
        $("#search_by_source").select2();
        $("#search_by_city").select2();

        $(".datepicker")
            .datepicker()
            .on("changeDate", function(ev) {
                $(".datepicker.dropdown-menu").hide();
            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).on("change", "#client", function() {
        $('.client-data-table').show();
        var client_id = new Array();
        $("#client :selected").each(function() {
            client_id.push($(this).val());
        });
        if (client_id != "") {
            $(".loader").css("display", "block");
            var pass_data = {
                client_leads: "leads",
                page: "statistics",
                client_id: client_id
            };
            load_table(pass_data);
        } else {
            $(".loader").css("display", "none");
        }
    });

    $(document).on("click", ".search", function() {
        $('.client-data-table').show();
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var staff_id = $("#search_by_staff").val();
        var source = $("#search_by_source").val();
        var address = $("#search_by_address").val();
        var city = $("#search_by_city").val();
        var status = $("#search_by_status").val();
        var lead_type = $("#search_by_leadtype").val();

        if (
            source != "" ||
            staff_id != "" ||
            address != "" ||
            city != "" ||
            status != "" ||
            lead_type != "" ||
            (from_date != "" && to_date != "")
        ) {
            $(".loader").css("display", "block");
            $(".from_date_err").text("");
            $(".to_date_err").text("");

            var pass_data = {
                client_leads: "leads",
                page: "statistics",
                selection_val: "Active",
                from_date: from_date,
                to_date: to_date,
                address: address,
                staff_id: staff_id,
                source: source,
                city: city,
                status: status,
                lead_type: lead_type
            };

            load_table(pass_data);
        } else {
            if (from_date == "" && to_date != "") {
                $(".from_date_err").text("Select From Date");
                return false;
            }
            if (from_date != "" && to_date == "") {
                $(".to_date_err").text("Select To Date");
                return false;
            }
            if (
                from_date == "" &&
                to_date == "" &&
                address == "" &&
                source == "" &&
                staff_id == "" &&
                city == "" &&
                status == "" &&
                lead_type == ""
            ) {
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0),
                    },
                    "slow"
                );
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select filter</span></div></div>'
                    )
                    .focus();
                return false;
            }
        }
    });

    $(document).on('click', '.delete_client', function() {
        var id = $(this).data('id');
        var page = 'statistics';
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
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: 'Deleted!',
                                text: 'Client has been deleted.',
                                confirmButtonClass: 'btn btn-success',
                            })
                            var pass_data = {
                                client_leads: 'leads',
                                page: page,
                                status: 'active'
                            };
                            load_table(pass_data);
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
        $('.loader').css('display', 'block');
        var mythis = $(this);
        var client_id = $(this).data('client_id');
        var page = 'statistics';
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
                    $('.loader').css('display', 'none');
                    $('#alert').html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg + '</span></div></div>').focus();

                    mythis.closest("tr").remove();
                }
            },
            error: function(data) {
                $('.loader').css('display', 'none');
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
        get_quo_appo_foll(client_id, detail);
    });


    function load_table(pass_data) {
        var table = $('.client-data-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            searching: false,
            pageLength: 50,
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
            ajax: {
                url: "{{ route('get_leads_list') }}",
                data: pass_data
            },
            columns: [{
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'client_case_no',
                    name: 'client_case_no'
                },
                {
                    data: 'assign_staff_name',
                    'render': function(data, assign_staff_name, row) {
                        if (row.assign_staff_name != null) {
                            return row.assign_staff_name;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'type',
                    'render': function(data, type, row) {
                        if (row.type == 'New') {
                            return '<div class="lead_type_view"><span class="badge badge-light-success badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Cold') {
                            return '<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Potential') {
                            return '<div class="lead_type_view"><span class="badge badge-light-primary badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Hot') {
                            return '<div class="lead_type_view"><span class="badge badge-light-warning badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Closed') {
                            return '<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Reopen') {
                            return '<div class="lead_type_view"><span class="badge badge-light-brown badge-pill">' + row.type + '</span></div>';
                        } else if (row.type == 'Not Interested') {
                            return '<div class="lead_type_view"><span class="badge badge-light-secondary badge-pill">Nt Int</span></div>';
                        }
                    }
                },
                {
                    data: 'no_of_units',
                    name: 'no_of_units'
                },
                {
                    data: 'abbrev',
                    name: 'abbrev'
                },
                {
                    data: 'quotations',
                    'render': function(data, quotations, row) {
                        if (row.quotations != null) {
                            return '<a href="#" class="detailBtn" data-client_id="' + row.id + '" data-detail="Quotation" data-toggle="modal" data-target="#detailModal">' + row.quotations + '</a>';
                        } else {
                            return '0';
                        }
                    }
                },
                {
                    data: 'followups',
                    'render': function(data, followups, row) {
                        if (row.followups != null) {
                            return '<a href="#" class="detailBtn" data-client_id="' + row.id + '" data-detail="Follow-Up" data-toggle="modal" data-target="#detailModal">' + row.followups + '</a>';
                        } else {
                            return '0';
                        }
                    }
                },
                {
                    data: 'appointments',
                    'render': function(data, appointments, row) {
                        if (row.followups != null) {
                            return '<a href="#" class="detailBtn" data-client_id="' + row.id + '" data-detail="Appointment" data-toggle="modal" data-target="#detailModal">' + row.appointments + '</a>';
                        } else {
                            return '0';
                        }
                    }
                },
                {
                    data: 'source_name',
                    'render': function(data, source_name, row) {
                        if (row.source_name == 'Whatsapp group') {
                            return '<img src="{{asset("images/source_icons/whatsApp-group.png")}}" alt="Whatsapp group">';
                        } else if (row.source_name == 'Active Sales') {
                            return '<img src="{{asset("images/source_icons/active-sales.png")}}" alt="Active Sales">';
                        } else if (row.source_name == 'Client ref') {
                            return '<img src="{{asset("images/source_icons/client-ref.png")}}" alt="Client ref">';
                        } else if (row.source_name == 'Newspaper') {
                            return '<img src="{{asset("images/source_icons/newspaper.png")}}" alt="Newspaper">';
                        } else if (row.source_name == 'Franchise') {
                            return '<img src="{{asset("images/source_icons/franchise.png")}}" alt="Franchise">';
                        } else if (row.source_name == 'LinkedIn') {
                            return '<img src="{{asset("images/source_icons/linkedin.png")}}" alt="LinkedIn">';
                        } else if (row.source_name == 'Quora') {
                            return '<img src="{{asset("images/source_icons/quora.png")}}" alt="Quora">';
                        } else if (row.source_name == 'YouTube') {
                            return '<img src="{{asset("images/source_icons/youtube.png")}}" alt="YouTube">';
                        } else if (row.source_name == 'Google ads') {
                            return '<img src="{{asset("images/source_icons/googleAds.png")}}" alt="Google ads">';
                        } else if (row.source_name == 'Walk-in') {
                            return '<img src="{{asset("images/source_icons/walk-in.png")}}" alt="Walk-in">';
                        } else if (row.source_name == 'Facebook') {
                            return '<img src="{{asset("images/source_icons/facebook.png")}}" alt="Facebook">';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'address',
                    name: 'address'
                }
            ]
        });
        $(".loader").css("display", "none");
    }
</script>
@endsection