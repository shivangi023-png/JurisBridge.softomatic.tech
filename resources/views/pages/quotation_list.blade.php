@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Quotation List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.bubble.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-quotation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" href="{{asset('css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }

    .select2-search__field {
        width: 100% !important;
    }

    .quot_editor {
        margin-bottom: 10px;
    }

    #full-subject-container {
        height: 50px;
    }

    #full-body-container {
        height: 400px;
    }

    .editor_error {
        margin-top: 85px;
    }

    .layout {
        border-top: 0 !important;
    }
    .dropdown-menu 
   {
    z-index:100000;
   }
   .datepicker
   {
    z-index:100000;
   }

</style>

@endsection

@section('content')
<!-- invoice list -->
<section class="quotation-list-wrapper">
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
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quotations</h4>
                </div>
                <div class="card-body pb-0 mx-25">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <div class="input-group">

                                    <select class="form-control client" id="client" multiple="multiple">
                                        <option value="">--Select Clients--</option>
                                        @foreach ($clients as $item)
                                        <option value={{$item->id}}>
                                            {{$item->client_case_no}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="data_div">
                        <div class="action-dropdown-btn d-none">
                            <div class="dropdown quotation-filter-action">
                                <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filter Quotation
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
                                    <a class="dropdown-item" href="javascript:;">Finalize</a>
                                    <a class="dropdown-item" href="javascript:;">Unfinalize</a>
                                </div>
                            </div>
                            <div class="dropdown quotation-options">
                                <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Options
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">

                                    <a class="dropdown-item" href="javascript:;">Delete</a>
                                    <a class="dropdown-item all_finalize" href="javascript:;">finalize</a>
                                    <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
                                </div>
                            </div>

                            <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
                                <i class="bx bx-plus"></i>Add Quotation</a>


                        </div>

                        <div class="table-responsive">
                            <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <span class="align-middle">#</span>
                                        </th>
                                        <th>Action</th>
                                        <th>Client Name</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Send Date</th>
                                        <th>Finalized</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice_table">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="finalizeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" id="">

            <div class="modal-header">
                <h4 class="modal-title" id="">Finalize Quotation</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <h5>Finalizing Date : </h5>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control status">
                        <input type="hidden" class="form-control client_id">
                        <input type="hidden" class="form-control quotation_details_id">
                        <input type="text" class="form-control quotation_date pickadate" placeholder="date">
                        <span class="valid_err date_err"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-icon btn-light-success finalize_btn">Finalize</button>
                <button type="button" class="btn btn-icon btn-light-danger " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!--Extra Large Modal -->
<div class="modal fade text-left w-100" id="updatequotation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Update Quotation <span class="font-small-3" id="up_client"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>


            </div>
            <div class="modal-body">
                <form id="up_form">
                    <div class="row">
                        <input type="hidden" id="up_id" name="up_id" class="form-control" />
                        <input type="hidden" id="up_detid" name="up_detailid" class="form-control" />
                        <input type="hidden" id="up_total_amt" name="total_amt" class="form-control" />
                        <input type="hidden" id="prev_amt" name="prev_amt" class="form-control" />
                    </div>
                    <div class="row">

                        <div class="col-md-8">
                            <fieldset class="form-group">
                                <div class="input-group">
                                    <select name="service" class="form-control up_service" id="up_service">
                                        <option value=""></option>
                                        @foreach($services as $service)
                                        <option value="{{$service->id}}">{{$service->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                            <span class="up_service_err valid_err"></span>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <div class="input-group">
                                    <input type="text" name="date" class="form-control date pickadate  mb-50 mb-sm-0 up_send_date" id="floating-label1" placeholder="Date">
                                </div>
                                <span class="up_send_date_err valid_err"></span>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row  padding-top">



                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <div class="form-label-group">
                                    <input type="text" class="form-control up_no_of_units" id="up_no_of_units" placeholder="No of Units">
                                    <label for="number-id-column">No of Units</label>
                                    <span class="up_no_of_units_err valid_err"></span>

                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <div class="form-label-group">
                                    <input type="text" class="form-control up_per_unit_amount" id="up_per_unit_amount" placeholder="Amount/unit">
                                    <label for="number-id-column">Amount/unit</label>
                                    <span class="up_per_unit_amount_err valid_err"></span>

                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <div class="form-label-group">
                                    <input type="text" class="form-control up_amount" id="up_amount" placeholder="Amount">
                                    <label for="number-id-column">Amount</label>
                                    <span class="up_amount_err valid_err"></span>

                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 link_div">
                            <a href="" id="a_link"><span id="file_label"></span></a><span><i class="bx bxs-pencil" id="edit_doc"></i></span>

                        </div>
                        <div class="col-md-11 up_file_div" style="display:none">

                            <div class="custom-file">
                                <input type="file" class="custom-file-input up_file" id="up_file" name="file">

                                <span class="custom-file-label">Upload quotation</span>
                            </div>
                            <span class="up_file_err valid_err"></span>
                        </div>
                        <div class="col-md-1 up_file_div" style="display:none">
                            <span><i class="bx bx-x" id="cancel_doc" style="color:red"></i></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning ml-1 update" id="update">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">update</span>
                </button>
                <button type="button" class="btn btn-light-danger" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>

            </div>
        </div>
    </div>
</div>

<!-- New Project modal -->
<div class="modal fade text-left" id="NewProjectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title modal_project_title" id="myModalLabel1">Create Project</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
                <div class="row">
                        <div class="col-12 mb-1">
                          <input type="hidden" id="projectid">
                          <input type="hidden" id="client_id">
                          <input type="hidden" id="proj_quotation_id">
                          <input type="hidden" id="case_no">
                          <input type="text" class="form-control mr-2 mb-50 mb-sm-0 project_name" placeholder="Project Name" readonly>
                            <span class="valid_err project_name_err"></span>
                        </div>

                        <div class="col-6 mb-1">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 datepicker project_start_date" placeholder="Start Date">
                            <span class="valid_err project_start_date_err"></span>
                        </div>
                        <div class="col-6 mb-1">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 datepicker project_end_date" placeholder="End Date">
                            <span class="valid_err project_end_date_err"></span>
                        </div>

                          <div class="col-12 mb-1">
                            <select class="form-control staff_id" id="staff_id">
                              <!-- <option value=""></option> -->
                              @foreach($staff_list as $staff)
                              <option value="{{$staff->sid}}">{{$staff->name}}</option>
                              @endforeach
                            </select>
                            <span class="valid_err staff_err"></span>
                          </div>

                          <div class="col-12 mb-1">
                            <select class="form-control select2" id="project_status">
                            @foreach($project_status_master as $row)
                              <option value="{{$row->id}}">{{$row->status}}</option>
                              @endforeach
                            </select>
                            <span class="valid_err project_status_err"></span>
                          </div>

                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit_project_btn" class="btn btn-primary ml-1 px-5 project_btn">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block project_btn_name">Create</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary px-5" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
<!-- End Project modal -->

</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-quotation.js')}}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script>
    $(document).ready(function() {
        $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });
    $(document).on('click','.New_Project_modal',function()
    {
        $('#proj_quotation_id').val($(this).data('quotation_details_id'));
    });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#staff_id").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: "Assignee Team",
            multiple:true,
            dropdownParent: $('#NewProjectModal')
        });


   


        var body_editor = new Quill("#full-body-container .body_editor", {
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
            theme: "snow",
        });

        $(document).on('click', '.send_mail', function() {
            $('.valid_err').html('');
            var arr = [];

            var quotation_details_id = $('.quotation_details_id').val();
            var template_name = $('.mailTemplate').val();
            var client_email = $('.client_email').val();
            var cc_email = $('.cc_email').val();
            var quotation_file = $('.quotation_file').val();
            var unit = $('.unit').val();

            var body = body_editor.root.innerHTML.trim();

            var quill_subject = $('.subject_editor').val();
            var quill_body = $('#full-body-container .body_editor .ql-editor').text();
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            subject = quill_subject;
            if (template_name == '') {
                arr.push('template_name_err');
                arr.push('Template Name required');
            }

            if (client_email == '') {
                arr.push('client_email_err');
                arr.push('Client Email required');
            }

            if (unit != '') {
                if (unit > 23000000) {
                    arr.push('quotation_file_err');
                    arr.push('File size is greater than 23 MB');
                }
            }

            if (quotation_file == '') {
                arr.push('quotation_file_err');
                arr.push('Quotation required');
            }

            if (client_email != '') {
                var emailArray = client_email.split(',');

                $.each(emailArray, function(index, value) {
                    email = value;
                    if (mailformat.test(email) == false) {
                        arr.push('client_email_err');
                        arr.push('Invalid Client Email');
                    }
                });
            }

            if (cc_email != '') {
                var cc_emailArray = cc_email.split(',');
                $.each(cc_emailArray, function(index, value) {
                    cc_email = value;
                    console.log(cc_email);
                    if (mailformat.test(cc_email) == false) {
                        arr.push('cc_email_err');
                        arr.push('Invalid CC Email');
                    }
                });
            }




            if (isQuillEmpty(body_editor)) {
                arr.push('body_editor_err');
                arr.push('Message required');
            }

            if (quill_subject == '') {
                arr.push('subject_editor_err');
                arr.push('Subject required');
            }

            if (quill_subject != '') {
                if (quill_subject.length > 70) {
                    arr.push('subject_editor_err');
                    arr.push('Subject character length is greater than 70');
                }
            }

            if (quill_body == '') {
                arr.push('body_editor_err');
                arr.push('Message required');
            }

            // if (quill_body != '') {
            //     if (quill_body.length > 1000) {
            //         arr.push('body_editor_err');
            //         arr.push('Message character length is greater than 1000');
            //     }
            // }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $('#cover-spin').css('display', 'block');
                $(".send_mail").prop("disabled", true);
                $.ajax({
                    type: "post",
                    url: "send_quotation_mail",
                    data: {
                        quotation_details_id: quotation_details_id,
                        client_email: client_email,
                        cc_email: cc_email,
                        quotation_file: quotation_file,
                        subject: subject,
                        body: body
                    },

                    success: function(data) {
                        $('#cover-spin').css('display', 'none');
                        console.log(data);
                        var res = JSON.parse(data);
                        console.log(res);
                        if (res.status == "success") {
                            console.log('success');
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0),
                                },
                                "slow"
                            );
                            $("#alert").html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' + res.msg + "</span></div></div>");
                            $(".customizer").removeClass("open");



                        } else {
                            $(".send_mail").prop("disabled", false);
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
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        }
                    },
                    error: function(data) {
                        $('#cover-spin').css('display', 'none');
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0),
                            },
                            "slow"
                        );
                        $("#alert").html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>somthing went wrong</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    },
                });
            }
        });

        function isQuillEmpty(quill) {
            if (JSON.stringify(quill.getContents()) == "\{\"ops\":[\{\"insert\":\"\\n\"\}]\}") {
                return true;
            } else {
                return false;
            }
        }

        $(document).on('click', '.cc_link', function() {
            $('.cc_input').show();
            $('.cc_close').show();
            $('.cc_link').hide();
        });

        $(document).on('click', '.cc_close', function() {
            $('.cc_input').hide();
            $('.cc_close').hide();
            $('.cc_link').show();
        });

        $(document).on('click', '.get_client_mail_info', function() {
            var client = new Array();
            $("#client :selected").each(function() {
                client.push($(this).val());
            });

            var quotation_details_id = new Array();
            $(".dt-checkboxes:checked").each(function() {
                quotation_details_id.push($(this).closest("tr").find(".quotation_details_id").val());
            });

            if (client.length > 1) {
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0),
                    },
                    "slow"
                );
                $("#alert").html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>Select only one client!</span></div></div>'
                );
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
                return false;
            }

            if (quotation_details_id == "") {
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0),
                    },
                    "slow"
                );
                $("#alert").html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>Checkbox is not selected!</span></div></div>'
                );
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
                return false;
            }

            $.ajax({
                type: "get",
                url: "get_client_mail_info",
                data: {
                    quotation_id: quotation_details_id
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);

                    if (res.status == "success") {
                        $(".template_list").empty().html(res.template_list);
                        $(".mailTemplate").select2({
                            dropdownAutoWidth: true,
                            width: "100%",
                            placeholder: "Select Template",
                        });

                        $('.quotation_details_id').val(res.quotation_details_id);
                        var email_arr = res.email;
                        var email_str = (email_arr);
                        var i = 0;
                        var emails = '';
                        for (i = 0; i < email_str.length; i++) {
                            for (key in email_str[i]) {
                                if (email_str[i].hasOwnProperty(key)) {
                                    var str = email_str[i][key];
                                    console.log('mail=' + str);
                                    if (str != null)
                                        var emails = emails + ',' + str;
                                }
                            }
                            emails = emails.replace(/^,/, '');
                        }

                        console.log(emails);
                        if (emails == '') {
                            alert();
                            $("#cust-alert").html('<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>No any email id found of client please add any email address.</span></div></div>');
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0),
                                },
                                "slow"
                            );
                            $(".send_mail").prop("disabled", true);
                        }
                        $('.client_email').val(emails);

                        var file_arr = res.file;
                        var file_str = file_arr.toString();
                        $('.quotation_file').val(file_str);
                        $('.unit').val(res.unit);
                        $(".customizer").toggleClass("open");

                    } else {
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
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                },
                error: function(data) {
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>somthing went wrong</span></div></div>'
                    );
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                },
            });
        });

        $(document).on("click", ".customizer-close", function(e) {
            $(".customizer").removeClass("open");
        });

        $(document).on("change", ".mailTemplate", function() {
            var template_id = $(this).val();
            $.ajax({
                type: "get",
                url: "get_template_info",
                data: {
                    id: template_id
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    if (res.status == "success") {
                        console.log(res.subject);
                        $('.subject_editor').val(res
                            .subject);
                        $('#full-body-container .body_editor .ql-editor').html(res.message);
                    }
                }
            })
        });

        $(document).on('click', '.active_btn', function() {
            $('.data_div').empty();
            $('.loader').css('display', 'block');
            var client = $(".client").val();
            var value = $(this).data('value');

            $.ajax({
                type: 'post',
                url: 'get_finalize_quotation',
                data: {
                    client: client,
                    value: value
                },

                success: function(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);
                    var res = JSON.parse(data);
                    $('.data_div').empty().html(res.out);
                    let str = value;

                    $(".selection1").html(str.toUpperCase());
                    var dataListView = $(".quotation-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],
                        order: [2, 'asc'],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
                        language: {
                            search: "",
                            searchPlaceholder: "Search Quotation"
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
                            }
                        }
                    });
                    var quotationFilterAction = $(".quotation-filter-action");
                    var quotationOptions = $(".quotation-options");
                    var addButton = $(".add_button");
                    $(".action-btns").append(quotationFilterAction, quotationOptions,
                        addButton);
                }
            });
        });

        $(document).on('click', '#update', function() {
            var status = $('.selection1').html();
            var q_id = $('#up_id').val();
            var q_detailid = $('#up_detid').val();
            var total_amt = $('#up_total_amt').val();
            var prev_amt = $('#prev_amt').val();
            var service_id = $('#up_service').val();
            var send_date = $('.up_send_date').val();
            var no_of_units = $('#up_no_of_units').val();
            var units_amount = $('#up_per_unit_amount').val();
            var amount = $('#up_amount').val();
            var client = new Array();
            $('#client :selected').each(function() {
                client.push($(this).val());
            });
            if ($('#up_file').val() != '') {
                var file = $('#up_file').prop("files")[0];
            } else {
                var file = '';
            }

            var form_data = new FormData();
            form_data.append("q_id", q_id);
            form_data.append("q_detailid", q_detailid);
            form_data.append("total_amt", total_amt);
            form_data.append("prev_amt", prev_amt);
            form_data.append("service_id", service_id);
            form_data.append("send_date", send_date);
            form_data.append("no_of_units", no_of_units);
            form_data.append("units_amount", units_amount);
            form_data.append("amount", amount);
            form_data.append("file", file);
            for (var i = 0; i < client.length; i++) {
                form_data.append("client_id[]", client[i]);
            }
            var valid = upValidation();
            if (valid) {
                $.ajax({
                    type: 'POST',
                    url: "update/quotation",
                    dataType: 'text', // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(data) {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        console.log(data);
                        var res = JSON.parse(data);

                        if (res.status == 'success') {
                            $('#updatequotation').modal('hide');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $('.data_div').empty().html(res.out);
                            let str = status;

                            $(".selection1").html(str.toUpperCase());
                            var dataListView = $(".quotation-data-table").DataTable({
                                columnDefs: [{
                                        targets: 0,
                                        className: "control"
                                    },
                                    {
                                        orderable: true,
                                        targets: 1,
                                        checkboxes: {
                                            selectRow: true
                                        }
                                    },
                                    {
                                        targets: [0, 1],
                                        orderable: false
                                    },
                                ],
                                order: [2, 'asc'],
                                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search Quotation"
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
                                    }
                                }
                            });
                            var quotationFilterAction = $(".quotation-filter-action");
                            var quotationOptions = $(".quotation-options");
                            var addButton = $(".add_button");
                            $(".action-btns").append(quotationFilterAction, quotationOptions,
                                addButton);

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



                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>' +
                                res.msg + '</span></div></div>');
                        }
                    },
                    error: function(data) {
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>Error</span></div></div>'
                        );
                        console.log("Error");
                    }


                });
            }
        });

        $(document).on('click', '#delete_quotation', function() {
            var status = $('.selection1').html();
            var quotation_details_id = $(this).data('quotation_details_id');
            var quotation_id = $(this).data('quotation_id');
            var amount = $(this).data('qamount');
            var total_amount = $(this).data('qtotalamt');
            var client = new Array();
            $('.client :selected').each(function() {
                client.push($(this).val());
            });
            var value = $("#quotation_filter").val();
            if (value) {
                filter = value;
            } else {
                filter = null;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this quotation?",
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
                        type: 'post',
                        url: 'delete_quotation',
                        data: {
                            quotation_id: quotation_id,
                            quotation_details_id: quotation_details_id,
                            client_name: client,
                            amount: amount,
                            total_amount: total_amount,
                            filter: filter
                        },

                        success: function(data) {
                            console.log(data);

                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );
                            var res = JSON.parse(data);
                            if (res.status == "success") {
                                console.log(res.out);
                                $(".data_div").empty().html(res.out);
                                $('#alert').html(
                                    '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                    res.msg + '</span></div></div>').focus();

                                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                    $(".alert").slideUp(500);
                                });
                                let str = status;

                                $(".selection1").html(str.toUpperCase());
                                if ($(".quotation-data-table").length) {
                                    var dataListView = $(".quotation-data-table")
                                        .DataTable({
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
                                            order: [2, "asc"],
                                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
                                            language: {
                                                search: "",
                                                searchPlaceholder: "Search Quotation",
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
                                }

                                // To append actions dropdown inside action-btn div
                                var quotationFilterAction = $(
                                    ".quotation-filter-action");
                                var quotationOptions = $(".quotation-options");
                                var addButton = $(".add_button");
                                $(".action-btns").append(quotationFilterAction,
                                    quotationOptions, addButton);
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

        function upValidation() {
            var valid = true;

            var service = $('#up_service').val();
            var send_date = $('.up_send_date').val();
            var no_of_units = $('#up_no_of_units').val();
            var per_unit_amount = $('#up_per_unit_amount').val();
            var amount = $('#up_amount').val();

            if (service == '') {
                $('.up_service_err').html('Select service');
                valid = false;
            }
            if (send_date == '') {
                $('.up_send_date_err').html('Select date');
                valid = false;
            }
            if (no_of_units == '') {
                $('.up_no_of_units_err').html('Enter no of units');
                valid = false;
            }
            if (per_unit_amount == '') {
                $('.up_per_unit_amount_err').html('Enter Amount/unit');
                valid = false;
            }
            if (amount == '') {
                $('.up_amount_err').html('Enter Amount');
                valid = false;
            }

            var file_name = $('#up_file').val();
            if (file_name != '') {
                var fp = $('#up_file');
                var items = fp[0].files;
                var ext = file_name.substring(file_name.lastIndexOf('.') + 1);
                var file_msg = validFile(ext);
                console.log(ext);
                console.log(file_msg);
                if (items[0].size > 2000000) {
                    $('.up_file_err').html('File size must be less than or equal to 2 MB');
                    valid = false;
                }
                if (file_msg != '') {
                    $('.up_file_err').html(file_msg);
                    valid = false;
                }
            }

            return valid;
        }

        function validFile(ext) {

            var extension = ext;
            var msg = "";
            switch (extension) {
                case 'PDF':
                case 'jpg':
                case 'pdf':
                case 'peg':
                case 'png':
                case 'doc':
                case 'ocx':

                    msg = ""; // There's was a typo in the example where
                    return msg;
                    break;
                default:
                    msg = "File type must be pdf,doc or docx,jpg,png";
                    return msg;
            }
        }
    });
    $(document).on('keyup', '#up_no_of_units', function() {
        var no_of_units = $(this).val();

        var unit_amt = $(this).closest('div.row').find('#up_per_unit_amount').val();
        var result = parseInt(no_of_units) * parseInt(unit_amt);
        if (!isNaN(result)) {
            $(this).closest('div.row').find('#up_amount').val(result);
        } else {
            $(this).closest('div.row').find('#up_amount').val(0);
        }
    });
    $(document).on('keyup', '#up_per_unit_amount', function() {
        var no_of_units = $(this).closest('div.row').find('#up_no_of_units').val();

        var unit_amt = $(this).val();
        if (no_of_units != '')
            var result = parseInt(no_of_units) * parseInt(unit_amt);
        else
            var result = parseInt(unit_amt);
        if (!isNaN(result)) {
            $(this).closest('div.row').find('#up_amount').val(result);
        } else {
            $(this).closest('div.row').find('#up_amount').val(0);
        }
    });

    $(document).on('click', '.New_Project_modal', function() {
    $('#NewProjectModal').modal('toggle');
    $('.valid_err').html('');
    $('.project_btn').removeAttr('id');
    $('.modal_project_title').empty().html('Create Project');
    $('.project_btn').attr('id','submit_project_btn');
    $('.project_btn_name').empty().html('Create');
    
    $('#projectid').val('');
    $('.project_name').val($(this).data('project_name'));
    $('#client_id').val($(this).data('client_id'));
    $('#case_no').val($(this).data('case_no'));
    $('.project_start_date').val('');
    $('.project_end_date').val('');
    $('.staff_id').val('').select2({
                            dropdownAutoWidth: true,
                            width: "100%"
                        }).trigger('change');
    $('.service_id').val('').select2({
                            dropdownAutoWidth: true,
                            width: "100%"
                        }).trigger('change');
    $('#project_status').val('').select2({
                            dropdownAutoWidth: true,
                            width: "100%"
                        }).trigger('change');

});
</script>
@endsection