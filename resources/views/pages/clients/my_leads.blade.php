@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','My Leads')
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
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class='col-10'>
                    @include('layouts.tabs')
                </div>
                <div class='col-2'>
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
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_status">
                                <option value="">Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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

                <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                </div>
                <div class="col-md-1 ml-3">
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>
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
            <center>
                <div class="spinner-grow text-primary loader" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="loader" style="display:none">Please wait...</h5>
            </center>

            <div class="data_div">
                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th width="10%">Action</th>
                                <th>Name</th>
                                <th>Lead Type</th>
                                <th>Quotations</th>
                                <th>Follow-up</th>
                                <th>Appointments</th>
                                <th>No of units</th>
                                <th>Property type</th>
                                <th>Source</th>
                                <th>Created Date</th>
                                <th>Assigned Date</th>
                                <th>Remarks</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
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
<!---end-->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<!-- <script src="{{asset('js/scripts/pages/get_my_leads_action.js')}}"></script> -->
<script>
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
                    data: 'type',
                    'render': function(data, type, row) {
                        if (row.type == 'New') {
                            return `<div class="lead_type_view"><span class="badge badge-light-success badge-pill">` + row.type + `</span></div> <div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Cold') {
                            return `<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Potential') {
                            return `<div class="lead_type_view"><span class="badge badge-light-primary badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Hot') {
                            return `<div class="lead_type_view"><span class="badge badge-light-warning badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Closed') {
                            return `<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Reopen') {
                            return `<div class="lead_type_view"><span class="badge badge-light-brown badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        } else if (row.type == 'Not Interested') {
                            return `<div class="lead_type_view"><span class="badge badge-light-secondary badge-pill">Nt Int</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
                        }
                    }
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
                    data: 'no_of_units',
                    name: 'no_of_units'
                },
                {
                    data: 'abbrev',
                    name: 'abbrev'
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
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'assigned_at',
                    name: 'assigned_at'
                },
                {
                    data: 'remarks',
                    name: 'remarks'
                },
                {
                    data: 'address',
                    name: 'address'
                }
            ]
        });

        $(".loader").css("display", "none");
    }
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
                    client_leads: "",
                    page: "my_leads",
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
        $(document).on('click', '.detailBtn', function() {
            var client_id = $(this).data('client_id');
            var detail = $(this).data('detail');
            get_quo_appo_foll(client_id, detail);
        });


        $(document).on("click", ".change_lead_type", function() {
            $(this).closest("tr").find(".change_lead_type").css("display", "none");
            $(this).closest("tr").find(".lead_type_view").css("display", "none");
            $(this).closest("tr").find(".select_lead_type").css("display", "block");
            $(this).closest("tr").find(".save_lead_div").css("display", "block");
        });

        $(document).on("click", ".close_lead_type", function() {
            $(this).closest("tr").find(".change_lead_type").css("display", "block");
            $(this).closest("tr").find(".lead_type_view").css("display", "block");
            $(this).closest("tr").find(".select_lead_type").css("display", "none");
            $(this).closest("tr").find(".save_lead_div").css("display", "none");
        });

        $(document).on("click", ".save_lead_type", function() {
            $(".loader").css("display", "block");
            var mythis = $(this);
            var lead_type = $(this).closest("tr").find(".lead_type").val();
            var client_id = $(this).data("client_id");
            var page = "my_leads";
            $.ajax({
                type: "post",
                url: "save_lead_type",
                data: {
                    lead_type: lead_type,
                    client_id: client_id,
                },

                success: function(data) {
                    var res = JSON.parse(data);

                    if (res.status == "success") {
                        $(".loader").css("display", "none");
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0),
                            },
                            "slow"
                        );
                        $("#alert").html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg +
                            "</span></div></div>"
                        );
                        $(".alert")
                            .fadeTo(2000, 500)
                            .slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        // console.log(client_id);
                        // if ($.isArray(client_id)) {
                        //     client = client_id;
                        // } else {
                        //     var client = [];
                        //     client.push(client_id);
                        // }
                        // var pass_data = {
                        //     page: 'my_leads',
                        //     // client_id: client
                        // };
                        var from_date = $(".from_date").val();
                        var to_date = $(".to_date").val();
                        var staff_id = $("#search_by_staff").val();
                        var source = $("#search_by_source").val();
                        var address = $("#search_by_address").val();
                        var city = $("#search_by_city").val();
                        var status = $("#search_by_status").val();
                        var lead_type = $("#search_by_leadtype").val();

                        var pass_data = {
                            client_leads: "",
                            page: "my_leads",
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
                    } else if (res.status == "fail") {
                        $(".loader").css("display", "none");
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0),
                            },
                            "slow"
                        );
                        $("#alert").html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg +
                            "</span></div></div>"
                        );
                        $(".alert")
                            .fadeTo(2000, 500)
                            .slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                    }
                },
                error: function(data) {
                    console.log(data);
                },
            });
        });
    });
</script>
@endsection