@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Cases')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>

</style>
@endsection

@section('content')

<section class="client-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
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
    <div class="card">
        <div class="card-header">
            <h2 class="card-title" style="font-size:25px;">Cases Details</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table client-data-table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="font-size:16px;">Case No</th>
                            <th style="font-size:16px;">Quotation No</th>
                            <th style="font-size:16px;">Service</th>
                            <th style="font-size:16px;">Finalize Date</th>
                            <th style="font-size:16px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        @foreach ($row->quotation_details as $row1)
                        <tr>
                            <td>{{$row->case_no}}</td>
                            <td>{{$row1->quotation_no}}</td>
                            <td>
                                <small class="text-muted"><?php echo nl2br($row1->name) ?></small>
                            </td>
                            <td>
                                @if($row1->finalize=='yes' || $row1->finalize=='YES')
                                {{$row1->finalize_date}}
                                @endif
                            </td>
                            <td>
                                <div class="client-action">
                                    <a class="modal_add_participate btn btn-icon rounded-circle btn-success glow mb-1" href="javascript:void(0);" title="Add Member" data-tooltip="Add Member" data-client_id="{{$row->client_id}}" data-mycases_id="{{$row->id}}">
                                        <i class="bx bx-user-plus"></i>
                                    </a>
                                    <a class="modal_remove_participate btn btn-icon rounded-circle btn-danger glow mb-1" href="javascript:void(0);" title="Remove Member" data-tooltip="Remove Member" data-client_id="{{$row->client_id}}" data-mycases_id="{{$row->id}}">
                                        <i class="bx bx-user-minus"></i>
                                    </a>
                                    <a class="invoice_btn btn btn-icon rounded-circle glow btn-warning mb-1" href="javascript:void(0);" title="Case Invoice" data-tooltip="Case Invoice" data-mycases_id="{{$row->id}}">
                                        <i class="bx bxs-report"></i>
                                    </a>
                                    <a class="doucment_btn btn btn-icon rounded-circle btn-dark-blue glow mb-1" href="javascript:void(0);" title="Case Document" data-tooltip="Case Document" data-mycases_id="{{$row->id}}">
                                        <i class="bx bxs-file-blank"></i>
                                    </a>
                                    <a class="modal_add_staff btn btn-icon rounded-circle btn-success glow mb-1" href="javascript:void(0);" title="Add Staff Member" data-tooltip="Add Staff Member" data-client_id="{{$row->client_id}}" data-mycases_id="{{$row->id}}">
                                        <i class="bx bx-user-plus"></i>
                                    </a>
                                    <a class="modal_remove_staff btn btn-icon rounded-circle btn-danger glow mb-1" href="javascript:void(0);" title="Remove Staff Member" data-tooltip="Remove Staff Member" data-client_id="{{$row->client_id}}" data-mycases_id="{{$row->id}}">
                                        <i class="bx bx-user-minus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="add_participate" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Participate</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- <form> -->
                    <input type="hidden" value="" class="modal_client_id">
                    <input type="hidden" value="" class="modal_mycases_id">
                    <span class="contacts_err text-danger"></span>
                    <div class="contacts">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save_participate">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal_remove_participate" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Remove Participate</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- <form> -->
                    <input type="hidden" value="" class="modal_remove_client_id">
                    <input type="hidden" value="" class="modal_remove_mycases_id">
                    <span class="contacts_err text-danger"></span>
                    <div class="remove_contacts">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success remove_participate">Remove</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal_invoice" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Case Invoice</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="table-responsive case_invoice_table">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="modal_document" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Case Document</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="table-responsive case_document_table">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="add_staff" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Staff</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- <form> -->
                    <input type="hidden" value="" class="modal_client_id">
                    <input type="hidden" value="" class="modal_mycases_id">
                    <span class="contacts_err text-danger"></span>
                    <div class="staff_contact">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save_staff_participate">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="remove_staff" tabindex="-1" role="dialog" aria-labelledby="modal_title
    modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Remove Staff</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- <form> -->
                    <input type="hidden" value="" class="modal_remove_client_id">
                    <input type="hidden" value="" class="modal_remove_mycases_id">
                    <span class="contacts_err text-danger"></span>
                    <div class="remove_staff_contacts">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success remove_staff_participate">Remove</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.modal_add_participate', function() {
            $("#add_participate").modal("show");
            $(".contacts_err").html('');
            var client_id = $(this).data('client_id');
            var id = $(this).data('mycases_id');
            $.ajax({
                type: 'get',
                url: 'get_contacts',
                data: {
                    client_id: client_id
                },
                success: function(data) {
                    var contacts = '';
                    $.each(data.contacts, function(i, val) {
                        contacts += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_contact_no" name="add_checkbox" id="add_checkbox' + i + '" value="' + val.contact + '"><label class="custom-control-label" for="add_checkbox' + i + '">' + val.name + '(' + val.contact + ')</label></div>';
                    });
                    $('.contacts').empty().html(contacts);
                    $('.modal_client_id').val(client_id);
                    $('.modal_mycases_id').val(id);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.save_participate', function() {
            $(".contacts_err").html('');
            var phone_nos = [];
            var modal_mycases_id = $('.modal_mycases_id').val();
            var modal_client_id = $('.modal_client_id').val();
            $('.modal_contact_no').each(function() {
                if ($(this).is(":checked")) {
                    phone_nos.push($(this).val());
                }
            });
            if (phone_nos.length == 0) {
                $(".contacts_err").html('Please select Participate');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'add_participate',
                data: {
                    case_id: modal_mycases_id,
                    client_id: modal_client_id,
                    phone_no: phone_nos,
                    type: 'client'
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#add_participate').modal('toggle');
                        $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' + res.msg + '</span></div></div>').focus();
                    } else {
                        $('#add_participate').modal('toggle');
                        $('#alert').empty().html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' + res.msg + '</span></div></div>').focus();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

        });
        $(document).on('click', '.modal_remove_participate', function() {
            $(".contacts_err").html('');
            $("#modal_remove_participate").modal("show");
            var client_id = $(this).data('client_id');
            var mycases_id = $(this).data('mycases_id');
            $.ajax({
                type: 'get',
                url: 'get_contacts',
                data: {
                    client_id: client_id
                },
                success: function(data) {
                    var contacts = '';
                    $.each(data.assign_cases, function(i, val) {
                        contacts += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_remove_asses_cases" name="remove_checkbox" id="remove_checkbox' + i + '" value="' + val.id + '"><label class="custom-control-label" for="remove_checkbox' + i + '">' + val.name + '(' + val.phone_no + ')</label></div>';
                    });
                    $('.remove_contacts').empty().html(contacts);
                    $('.modal_remove_client_id').val(client_id);
                    $('.modal_remove_mycases_id').val(mycases_id);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.remove_participate', function() {
            $(".contacts_err").html('');
            var assign_cases_ids = [];
            var modal_mycases_id = $('.modal_remove_mycases_id').val();
            var modal_client_id = $('.modal_remove_client_id').val();
            $('.modal_remove_asses_cases').each(function() {
                if ($(this).is(":checked")) {
                    assign_cases_ids.push($(this).val());
                }
            });
            if (assign_cases_ids.length == 0) {
                $(".contacts_err").html('Please select participate');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'remove_participate',
                data: {
                    case_id: modal_mycases_id,
                    client_id: modal_client_id,
                    assign_id: assign_cases_ids
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#modal_remove_participate').modal('toggle');
                        $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' + res.msg + '</span></div></div>').focus();
                    } else {
                        $('#modal_remove_participate').modal('toggle');
                        $('#alert').empty().html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' + res.msg + '</span></div></div>').focus();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

        });

        $(document).on('click', '.modal_add_staff', function() {
            $("#add_staff").modal("show");
            $(".contacts_err").html('');
            var client_id = $(this).data('client_id');
            var id = $(this).data('mycases_id');

            $.ajax({
                type: 'get',
                url: 'get_staff_contacts',
                data: {
                    client_id: client_id,
                    type: 'staff'
                },
                success: function(data) {
                    var staff_contact = '';
                    $.each(data.staff_contact, function(i, val) {
                        staff_contact += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_contact_no" name="add_checkbox" id="add_checkbox' + i + '" value="' + val.mobile + '"><label class="custom-control-label" for="add_checkbox' + i + '">' + val.name + '(' + val.mobile + ')</label></div>';
                    });
                    $('.staff_contact').empty().html(staff_contact);
                    $('.modal_client_id').val(client_id);
                    $('.modal_mycases_id').val(id);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.save_staff_participate', function() {
            $(".contacts_err").html('');
            var phone_nos = [];
            var modal_mycases_id = $('.modal_mycases_id').val();
            var modal_client_id = $('.modal_client_id').val();
            $('.modal_contact_no').each(function() {
                if ($(this).is(":checked")) {
                    phone_nos.push($(this).val());
                }
            });
            if (phone_nos.length == 0) {
                $(".contacts_err").html('Please select Participate');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'add_staff',
                data: {
                    case_id: modal_mycases_id,
                    client_id: modal_client_id,
                    phone_no: phone_nos,
                    type: 'staff'
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#add_staff').modal('toggle');
                        $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' + res.msg + '</span></div></div>').focus();
                    } else {
                        $('#add_staff').modal('toggle');
                        $('#alert').empty().html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' + res.msg + '</span></div></div>').focus();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

        });

        $(document).on('click', '.modal_remove_staff', function() {
            $(".contacts_err").html('');
            $("#remove_staff").modal("show");
            var client_id = $(this).data('client_id');
            var mycases_id = $(this).data('mycases_id');
            $.ajax({
                type: 'get',
                url: 'get_staff_contacts',
                data: {
                    client_id: client_id,
                    type: 'staff'
                },
                success: function(data) {
                    var staff_assign_cases = '';
                    $.each(data.staff_assign_cases, function(i, val) {
                        staff_assign_cases += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_remove_asses_cases" name="remove_checkbox" id="remove_checkbox' + i + '" value="' + val.id + '"><label class="custom-control-label" for="remove_checkbox' + i + '">' + val.name + '(' + val.phone_no + ')</label></div>';
                    });
                    $('.remove_staff_contacts').empty().html(staff_assign_cases);
                    $('.modal_remove_client_id').val(client_id);
                    $('.modal_remove_mycases_id').val(mycases_id);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.remove_staff_participate', function() {
            $(".contacts_err").html('');
            var assign_cases_ids = [];
            var modal_mycases_id = $('.modal_remove_mycases_id').val();
            var modal_client_id = $('.modal_remove_client_id').val();
            $('.modal_remove_asses_cases').each(function() {
                if ($(this).is(":checked")) {
                    assign_cases_ids.push($(this).val());
                }
            });
            if (assign_cases_ids.length == 0) {
                $(".contacts_err").html('Please select participate');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'remove_staff',
                data: {
                    case_id: modal_mycases_id,
                    client_id: modal_client_id,
                    assign_id: assign_cases_ids,
                    type: 'staff'
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#remove_staff').modal('toggle');
                        $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' + res.msg + '</span></div></div>').focus();
                    } else {
                        $('#remove_staff').modal('toggle');
                        $('#alert').empty().html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' + res.msg + '</span></div></div>').focus();
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });

        });

        if ($(".client-data-table").length) {
            var dataListView = $(".client-data-table").DataTable({
                columnDefs: [{
                        orderable: true,
                        targets: 0,

                    },
                    {
                        targets: [4],
                        orderable: false
                    },
                ],

                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                select: {
                    style: "multi",
                    selector: "td:first-child",
                    items: "row"
                },
                responsive: {
                    details: {
                        type: "column",
                        target: 0
                    }
                }
            });
        }
    });
    $(document).on('click', '.invoice_btn', function() {
        $("#modal_invoice").modal("show");
        var mycases_id = $(this).data('mycases_id');
        $.ajax({
            type: 'post',
            url: 'case_invoice',
            data: {
                case_id: mycases_id
            },
            success: function(data) {
                $('.case_invoice_table').empty().html(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    $(document).on('click', '.doucment_btn', function() {
        $("#modal_document").modal("show");
        var mycases_id = $(this).data('mycases_id');
        $.ajax({
            type: 'post',
            url: 'case_document',
            data: {
                case_id: mycases_id
            },
            success: function(data) {
                $('.case_document_table').empty().html(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
</script>
@endsection