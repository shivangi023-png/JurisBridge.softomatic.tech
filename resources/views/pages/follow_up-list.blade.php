@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Follow-up')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
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
    .ui-autocomplete {
        z-index: 1061 !important;
    }
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
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
            <div class="data_div">

                <!-- Options and filter dropdown button-->
                <div class="action-dropdown-btn d-none">
                    <div class="dropdown client-filter-action">
                        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="selection">Filter Follow-up</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
                            <a type="button" href="javascript:void(0);" class="dropdown-item " data-value="finalized">finalized</a>
                            <a class="dropdown-item " href="javascript:void(0);" data-value="lead_closed">Lead
                                closed</a>

                        </div>
                    </div>
                    <!-- <button type="button" class="btn mr-1 mb-1 btn-primary">Add Client</button> -->
                    <div class="client-options">
                        <a href="follow-up-add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                            <i class="bx bx-plus"></i>New Follow-up</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Client</th>
                                <th>Contact to</th>
                                <th>Method</th>
                                <th>Contact By</th>
                                <th>Follow-up Date</th>
                                <th>Next Follow-Up date</th>
                                <th>Finalized</th>
                                <th>Lead closed</th>
                                <th>discussion</th>
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

<div class="modal fade" id="FollowUpHistory" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-pink">
                <h5 class="modal-title" id="uModalLabel">All call details </h5>
            </div>
            <div class="modal-body call_detail_body">


            </div>
        </div>
    </div>
</div>

<!--Extra Large Modal -->
<div class="modal fade text-left w-100" id="saveFollowup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Client Follow-up <span class="font-small-3" id="client_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>


            </div>
            <div class="modal-body">
                <form method="POST" action='' id="form">
                    {{ csrf_field() }}



                    <div class="row pt-50">
                        <input type="hidden" class="form-control  mr-2 client_id">
                        <div class="col-12 col-md-6">
                            <fieldset class="form-group">
                                <div class="input-group">

                                    <input type="text" class="form-control pickadate mr-2 followup_date" placeholder="Select Date">


                                </div>
                                <span class="valid_err followup_date_err"></span>
                            </fieldset>
                        </div>
                        <div class="col-12 col-md-6">
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
                    </div>

                    <div class="row  pt-50">

                        <div class="col-md-6 col-12">
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
                        <div class="col-12 col-md-6">
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
                        <div class="col-md-4 col-12">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="next_follow_up_date" id="next_radio" checked="">
                                    <label class="custom-control-label" for="customRadio3">Next Follow-up date</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-3 col-12 next_date_div">

                            <fieldset class="form-group">
                                <input type="text" class="form-control pickadate mr-2 next_followup_date" placeholder="Next Follow-up Date">
                                <span class="valid_err next_date_err"></span>
                            </fieldset>
                        </div>

                        <div class="col-md-5 col-12">
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


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning ml-1 save_follow_up_btn" id="save_follow_up_btn">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save Follow-up</span>
                </button>
                <button type="button" class="btn btn-light-danger" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>

            </div>
        </div>
        </form>
    </div>
</div>
</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/followup.js')}}"></script>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'get',
            url: 'autocomplete_followup_disc',


            success: function(data) {

                $(".discussion").autocomplete({

                    source: data
                });
            }
        });
        $(document).on('click', '.saveFollowupBtn', function() {
            $('#client_name').html('(' + $(this).data('client_name') + ')');
            $('.client_id').empty().val($(this).data('client_id'));
            var id = $(this).data('client_id');
            $.ajax({
                type: 'post',
                url: 'get_contacts_followup',

                data: {
                    id: id
                },

                success: function(data) {
                    console.log(data);
                    $('.contact_div').empty().html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.radio_btn', function() {
            if ($('.radio_btn').is(':checked')) {
                if ($(this).val() == 'next_follow_up_date') {
                    $('.next_date_div').css('display', 'block');
                } else {
                    $('.next_date_div').css('display', 'none');
                }
            }
        });
        $(document).on('click', '#save_follow_up_btn', function() {
            var status = $('.selection').html();
            var client_id = $('.client_id').val();

            var followup_date = $('.followup_date').val();
            var next_followup_date = $('.next_followup_date').val();
            var contact_by = $('.contact_by').val();

            var company = $('.company').val();
            var discussion = $('.discussion').val();
            var next_radio = $('#next_radio').val();

            var arr = [];
            if ($('#finalized_radio').prop("checked") == true) {
                var finalized = 'yes';
            } else {
                var finalized = 'no';
            }
            if ($('#leadclosed_radio').prop("checked") == true) {
                var lead_closed = 'yes';
            } else {
                var lead_closed = 'no';
            }
            if ($('#next_radio').prop("checked") == true) {
                if (next_radio == 'next_follow_up_date') {

                    if (next_followup_date == '') {
                        arr.push('next_date_err');
                        arr.push('Next Follow-up date required');
                    } else {
                        var next_date = next_followup_date;
                    }
                }
            } else {
                var next_date = "";
            }


            var method = new Array();

            method.push($('#method').val());

            var contact_to = new Array();
            var contact_to = [];
            $.each($(".contact_to:checked"), function() {
                contact_to.push($(this).val());
            });


            if (followup_date == '') {
                arr.push('followup_date_err');
                arr.push('Follow-up date required');
            }
            if (company == '') {
                arr.push('company_err');
                arr.push('company required');
            }

            if (contact_by == '') {
                arr.push('contact_by_err');
                arr.push('Please select contact by');
            }
            if (contact_to == '') {
                arr.push('contact_to_err');
                arr.push('Please check any contact person name');
            }
            if (method == '') {
                arr.push('method_err');
                arr.push('Please select ant method');
            }
            if (discussion == '') {
                arr.push('discussion_err');
                arr.push('Discussion required');
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
                    url: 'save_follow_up',
                    data: {
                        client_id: client_id,
                        followup_date: followup_date,
                        contact_by: contact_by,
                        next_followup_date: next_date,
                        contact_to: contact_to,
                        method: method,
                        finalized: finalized,
                        lead_closed: lead_closed,
                        discussion: discussion,
                        company: company
                    },
                    success: function(data) {
                        console.log(data);
                        var res = data;
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#saveFollowup').modal('hide');
                        if (res.status == 'success') {

                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            let str = status;

                            $(".selection").html(str.toUpperCase());

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
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete_follow_up', function() {
        var id = $(this).data('client_id');
        var value = $('#filter').val();
        var status = $('.selection').html();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this follow up",
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
                    url: 'delete_follow_up',
                    data: {
                        id: id,
                        value: value
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
                            $('.data_div').empty().html(res.out);
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            let str = status;

                            $(".selection").html(str.toUpperCase());
                            var dataListView = $(".client-data-table").DataTable({
                                sorting: false,
                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                buttons: [
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5'
                                ],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search Follow-up"
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

    $(document).on('click', '.active_btn', function() {
        var value = $(this).data('value');
        var staff = $('.staff').val();
        $('.data_div').empty();
        $('.loader').css('display', 'block');
        var value = $(this).data('value');
        $.ajax({
            type: 'post',
            url: 'filter_follow_up',
            data: {
                value: value,
                staff: staff
            },

            success: function(data) {
                console.log(data);
                $('.loader').css('display', 'none');

                $('.data_div').empty().html(data);
                let str = value;

                $(".selection").html(str.toUpperCase());
                if ($(".client-data-table").length) {
                    var dataListView = $(".client-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                // orderable: true,
                                // targets: 0,
                                // checkboxes: { selectRow: true }
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
                            searchPlaceholder: "Search Follow-up"
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
                var staffFilter = $('.staff_filter');
                $(".action-btns").append(staffFilter, clientFilterAction, clientOptions);
                $(".staff").select2({

                    dropdownAutoWidth: true,
                    width: '100%',
                    placeholder: "Search staff wise follow-up"
                });

            },
            error: function(data) {
                console.log(data);

            }
        });
    });
</script>
@endsection