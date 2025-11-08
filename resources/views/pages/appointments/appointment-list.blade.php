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
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.bubble.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<link rel="stylesheet" href="{{asset('css/plugins/forms/form-quill-editor.css')}}">

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
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-years {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker thead {
        background-color: #e9edf1;
        color: #2454b1;
    }

    .dow {
        color: #2454b1;
    }

    .mt {
        margin-top: 10px;
    }

    .mb {
        margin-bottom: 10px;
    }

    #editorContainer {
        height: 300px;
        margin-bottom: 55px;
    }
    .radioBox {
        margin-bottom: 0px;
        margin-top: 10px;
    }
    .dataTables_filter {
        padding-left: 0px !important;
        margin-left: -6px !important;
    }
    .action-btns {
        margin-top: -16px;
    }
    .TopForm{
        padding: 6px 0px 6px 16px;
    }
    .dataTables_filter {
        display: inline-flex;
        width: 62%;
        margin-left: 12px !important;
        margin-top: 0px !important;
    }
    .dt-buttons.btn-group.flex-wrap {
        padding-bottom: 4px;
    }
    .table-responsive {
        margin-top: -4px;
    }
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

    <div class="card mt">
        <div id="alert">


        </div>
        <div class="card-body">
            <div class="row TopForm">
                <div class="col-md-1">
                    <fieldset class="form-group radioBox">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" name="all_appointment" value="all" id="all_appointment">
                            <label class="custom-control-label" for="all_appointment">All</label>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker from_date" placeholder="From Date" value="{{date('01/m/Y')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker to_date" placeholder="To Date" value="{{date('d/m/Y')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control input_control" id="meeting_with">
                            <option value="">Meeting With</option>
                            @foreach ($staff as $row)
                            <option value="{{$row->sid}}">
                                {{$row->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control input_control" id="schedule_by">
                            <option value="">Schedule By</option>
                            @foreach ($staff as $row)
                            <option value="{{$row->sid}}">
                                {{$row->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control input_control" id="app_meeting_type">
                            <option value="">Meeting Type</option>
                            <option value="free">Free</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>
            </div>

            <div class="data_div">

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
                        <div class="col-sm-4">
                            <select class="form-control  re_meeting_with" name="meeting_with">
                                <option value="">--meeting with--</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach
                            </select>
                            <span class="valid_err meeting_with_err"></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control re_time timepicker" placeholder="time">
                            <span class="valid_err time_err"></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control re_date datepicker" placeholder="date">
                            <span class="valid_err date_err"></span>
                        </div>

                        <div class="col-4 col-md-4 mt-2">
                            <select class="form-control" id="meeting_type" name="meeting_type">
                                <option value="">Select Meeting Type</option>
                                @foreach($appointment_places as $ap)
                                <option value="{{$ap->id}}">{{$ap->name}}</option>
                                @endforeach
                            </select>
                            <span class="meeting_type_err valid_err"></span>
                        </div>

                        <div class="col-8 col-md-8 mt-2">
                            <input type="text" name="online_meeting" id="online_meeting" class="form-control" placeholder="Online Meeting">
                            <span class="online_meeting_err valid_err"></span>
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

                <div class="modal-header">
                    <h4 class="modal-title" id="uModalLabel">View Consulting Fee</h4>
                </div>
                <div class="modal-body consulting_body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>



        </div>
    </div>

    <div class="modal fade" id="viewMeetingNotes" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="">

                <div class="modal-header">
                    <h4 class="modal-title" id="uModalLabel">View Meeting Notes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <input type="hidden" name="appID" id="appID">
                            <div id="editorContainer" class="body_editor"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-success save_notes" data-dismiss="modal">Save</button>
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
<script src="{{asset('vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/appointment.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#reset").click(function() {
            location.reload();
        });
        var pass_data = {
            from_date: $(".from_date").val(),
            to_date: $(".to_date").val(),
            meeting_with: $("#meeting_with").val(),
            schedule_by: $("#schedule_by").val(),
            meeting_type: $("#meeting_type").val(),
            status: '',
            all_appointment: ''
        };
        get_appointment_by_status(pass_data);

        $('input[type="radio"]').click(function() {
            var selectedValue = $(this).val();
            if (selectedValue == 'all') {
                $(".from_date").val('');
                $(".to_date").val('');
                $("#meeting_with").val('');
                $("#schedule_by").val('');
                $("#app_meeting_type").val('');
            }
        });

        $('#meeting_with').change(function() {
            $('#all_appointment').prop('checked', false);
        });

        $('#schedule_by').change(function() {
            $('#all_appointment').prop('checked', false);
        });

        $('#app_meeting_type').change(function() {
            $('#all_appointment').prop('checked', false);
        });

        $(document).on("click", ".search", function() {
            $('.client-data-table').show();
            var from_date = $(".from_date").val();
            var to_date = $(".to_date").val();
            var meeting_with = $("#meeting_with").val();
            var schedule_by = $("#schedule_by").val();
            var meeting_type = $("#app_meeting_type").val();
            var all_appointment = $('#all_appointment:checked').val();

            if (from_date !== "" && to_date !== "") {
                var startDateParts = from_date.split("/");
                var endDateParts = to_date.split("/");

                var startDateObj = new Date(
                    startDateParts[2],
                    startDateParts[1] - 1,
                    startDateParts[0]
                );
                var endDateObj = new Date(
                    endDateParts[2],
                    endDateParts[1] - 1,
                    endDateParts[0]
                );
                if (endDateObj < startDateObj) {
                    $("#alert").html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please select To Date greater than or equal to From Date</span></div></div>'
                        )
                        .focus();
                    return false;
                }
            }
            if (from_date != '' || to_date != '' || meeting_with != '' || schedule_by != '' || meeting_type != '') {
                all_appointment = '';
            }
            var pass_data = {
                from_date: from_date,
                to_date: to_date,
                meeting_with: meeting_with,
                schedule_by: schedule_by,
                meeting_type: meeting_type,
                status: '',
                all_appointment: all_appointment
            };
            get_appointment_by_status(pass_data)
        });

        var quill = null;

        function initializeQuillEditor() {
            var editorContainer = $('#editorContainer');

            if (!quill) {
                quill = new Quill(editorContainer[0], {
                    bounds: "#full-body-container .body_editor",
                    modules: {
                        formula: true,
                        syntax: true,
                        toolbar: [
                            [{
                                    font: [],
                                },
                                {
                                    size: [],
                                },
                            ],
                            ["bold", "italic", "underline", "strike"],
                            [{
                                    color: [],
                                },
                                {
                                    background: [],
                                },
                            ],
                            [{
                                    script: "super",
                                },
                                {
                                    script: "sub",
                                },
                            ],
                            [{
                                    header: "1",
                                },
                                {
                                    header: "2",
                                },
                                "blockquote",
                                "code-block",
                            ],
                            [{
                                    list: "ordered",
                                },
                                {
                                    list: "bullet",
                                },
                                {
                                    indent: "-1",
                                },
                                {
                                    indent: "+1",
                                },
                            ],
                            [
                                "direction",
                                {
                                    align: [],
                                },
                            ],
                            ["link", "image", "video", "formula"],
                            ["clean"],
                        ],
                    },
                    theme: "snow"
                });
            }

            return quill;
        }

        $(document).on("click", ".view_notes", function() {
            var appID = $(this).data("appointment_id");
            var meetingNotes = $(this).data("meeting_notes");

            var editor = initializeQuillEditor();
            if (editor) {
                editor.root.innerHTML = meetingNotes;
            }
            $('#appID').val(appID);
            $('#viewMeetingNotes').modal('show');
        });

        $('#viewMeetingNotes').on('hidden.bs.modal', function() {
            if (quill) {
                quill.root.innerHTML = '';
            }
        });

        function isQuillEditorEmpty(editor) {
            var editorHtml = quill.getText().trim();
            return editorHtml === '';
        }

        $(document).on('click', '.save_notes', function() {
            var appID = $('#appID').val();
            var meeting_notes = quill.root.innerHTML.trim();
            if (isQuillEditorEmpty(meeting_notes)) {
                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please write something in editor!</span></div></div>');
            } else {
                $.ajax({
                    type: 'post',
                    url: 'save_meeting_notes',

                    data: {
                        appointment_id: appID,
                        meeting_notes: meeting_notes
                    },

                    success: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );

                        if (data.status == 'success') {
                            $('#viewMeetingNotes').modal('hide');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                data.msg + '</span></div></div>');
                            var pass_data = {
                                from_date: $(".from_date").val(),
                                to_date: $(".to_date").val(),
                                meeting_with: $("#meeting_with").val(),
                                schedule_by: $("#schedule_by").val(),
                                meeting_type: $("#meeting_type").val(),
                                status: '',
                                all_appointment: ''
                            };
                            get_appointment_by_status(pass_data);
                        } else {
                            $('#viewMeetingNotes').modal('hide');
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                data.msg + '</span></div></div>');
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
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            }
        });

        $(document).on('click', '.active_btn', function() {
            var status = $(this).data('value');
            var pass_data = {
                from_date: $(".from_date").val(),
                to_date: $(".to_date").val(),
                meeting_with: $("#meeting_with").val(),
                schedule_by: $("#schedule_by").val(),
                meeting_type: $("#meeting_type").val(),
                status: status,
                all_appointment: ''
            };
            get_appointment_by_status(pass_data);
        });
    });

    function get_appointment_by_status(pass_data) {
        $(".loader").css("display", "block");
        $.ajax({
            type: "post",
            url: "get_appointment_by_status",
            data: {
                pass_data: pass_data
            },
            success: function(data) {
                console.log(data);
                $(".loader").css("display", "none");
                $(".data_div").html(data);
            },
            error: function(data) {
                $(".loader").css("display", "none");
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        data.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    }

    function view_consulting_fee(appointment_id) {
        $.ajax({
            type: "post",
            url: "view_consulting_fee",

            data: {
                appointment_id: appointment_id,
            },

            success: function(data) {
                $(".consulting_body").html(data);
            },
            error: function(data) {
                console.log(data);
                var res = data;
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    }
</script>
@endsection