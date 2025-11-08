@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','New Company')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-company.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }

    .valid_err1 {
        color: red;
        font-size: 12px;
    }
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
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
            <div class="card">
                <div class="card-body">
                    @include('layouts.tabs')
                    <form class="form" id="form" enctype="multipart/form-data">

                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                                        <label for="company_name">Company Name</label>
                                        <span class="company_name_err valid_err"></span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="short_code" name="short_code" placeholder="Short Code">
                                        <label for="short_code">Short Code</label>
                                        <span class="short_code_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="Pan Number">
                                        <label for="pan_no">Pan Number</label>
                                        <span class="pan_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="GST Number">
                                        <label for="gst_no">GST Number</label>
                                        <span class="gst_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="company_email" name="company_email" placeholder="Email">
                                        <label for="company_email">Email</label>
                                        <span class="company_email_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="company_contact" name="company_contact" placeholder="Contact">
                                        <label for="company_contact">Contact</label>
                                        <span class="company_contact_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="website_url" name="website_url" placeholder="Website Url">
                                        <label for="website_url">Website Url</label>
                                        <span class="website_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="facebook_url" name="facebook_url" placeholder="Facebook Url">
                                        <label for="facebook_url">Facebook Url</label>
                                        <span class="facebook_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="youtube_url" name="youtube_url" placeholder="Youtube Url">
                                        <label for="youtube_url">Youtube Url</label>
                                        <span class="youtube_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="head_office" name="head_office" placeholder="Head Office">
                                        <label for="head_office">Head Office</label>
                                        <span class="head_office_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <fieldset class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file" name="company_logo" id="company_logo">

                                            <span class="custom-file-label">Upload Logo</span>
                                        </div>
                                        <span class="company_logo_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-2">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="tds_applicable" id="tds_applicable">
                                            <label class="custom-control-label" for="tds_applicable">TDS
                                                Applicable</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-2">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="tax_applicable" id="tax_applicable">
                                            <label class="custom-control-label" for="tax_applicable">TAX
                                                Applicable</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12">
                                    <div class="form-label-group">
                                        <textarea type="text" class="form-control" id="company_address" name="company_address" placeholder="Address"></textarea>
                                        <label for="company_address">Address</label>
                                        <span class="company_address_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row main_row">
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control company_branch" id="company_branch" name="company_branch[]" placeholder="Branch Name">
                                        <label for="company_branch">Company Branch</label>
                                        <span class="company_branch_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <button class="btn btn-light-primary btn-sm add_row" type="button">
                                                <i class="bx bx-plus"></i>
                                                <span class="invoice-repeat-btn"></span>
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="branch_div">
                                <input type="hidden" class="form-control total" value="1">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                            <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="company-list-wrapper">
                <div class="card">
                    <div class="card-body">
                        <!-- datatable start -->
                        <div class="data_div">
                            <div class="table-responsive">
                                <table class="table company-data-table wrap">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Company Name</th>
                                            <th>Company Email</th>
                                            <th>Company Contact</th>
                                            <th>Company Address</th>
                                            <th>Short Code</th>
                                            <th>GST Number</th>
                                            <th>Pan Number</th>
                                            <th>Tax Applicable</th>
                                            <th>TDS Applicable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($company as $val)
                                        <tr>
                                            <td><a href="company_edit-{{$val->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                                            </td>
                                            <td>{{$val->company_name}}</td>
                                            <td>{{$val->company_email}}</td>
                                            <td>{{$val->company_contact}}</td>
                                            <td>{{$val->company_address}}</td>
                                            <td>{{$val->short_code}}</td>
                                            <td>{{$val->gst_no}}</td>
                                            <td>{{$val->pan_no}}</td>
                                            <td>
                                                @if($val->tax_applicable=='yes')
                                                <span class="badge badge-pill badge-light-success">{{$val->tax_applicable}}</span>
                                                @else
                                                <span class="badge badge-pill badge-light-danger">{{$val->tax_applicable}}</span>
                                                @endif

                                            </td>
                                            <td>
                                                @if($val->tds_applicable=='yes')
                                                <span class="badge badge-pill badge-light-success">{{$val->tds_applicable}}</span>
                                                @else
                                                <span class="badge badge-pill badge-light-danger">{{$val->tds_applicable}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </div>
            </div>
            <!-- <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" style="width:900px" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit Company</h4>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-label-group">
                                                <input type="hidden" class="form-control company_id" id="company_id"
                                                    name="company_id" value="">
                                                <input type="text" class="form-control company_name" id="company_name"
                                                    name="company_name" placeholder="Company Name" value="">
                                                <label for="company_name">Company Name</label>
                                                <span class="company_name_err1 valid_err1"></span>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-label-group">
                                                <input type="text" class="form-control short_code" id="short_code"
                                                    name="short_code" placeholder="Short Code" value="">
                                                <label for="short_code">Short Code</label>
                                                <span class="short_code_err1 valid_err1"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-label-group">
                                                <input type="text" class="form-control pan_no" id="pan_no" name="pan_no"
                                                    placeholder="Pan Number" value="">
                                                <label for="pan_no">Pan Number</label>
                                                <span class="pan_no_err1 valid_err1"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-label-group">
                                                <input type="text" class="form-control gst_no" id="gst_no" name="gst_no"
                                                    placeholder="GST Number" value="">
                                                <label for="gst_no">GST Number</label>
                                                <span class="gst_no_err1 valid_err1"></span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input tds_applicable"
                                                        name="tds_applicable" id="tds_applicable1" value="1">

                                                    <label class="custom-control-label" for="tds_applicable1">TDS
                                                        Applicable</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-3">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input tax_applicable"
                                                        name="tax_applicable" id="tax_applicable1" value="1">

                                                    <label class="custom-control-label" for="tax_applicable1">TAX
                                                        Applicable</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary update" id="update">Update
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
</section>

<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/company.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.add_row', function() {
            var i = $('.total').val();
            var j = parseInt(i) + 1;
            $('.branch_div').append(
                ' <div class="row main_row"><div class="col-3"><div class="form-label-group"><input type="text" class="form-control company_branch" id="company_branch" name="company_branch[]" placeholder="Branch Name"><label>Company Branch</label><span class="company_brach_err valid_err"></span></div> </div><div class="col-3 "> <button class="btn btn-light-danger btn-sm delete_row" type="button"> <i class="bx bx-trash-alt"></i> <span class="invoice-repeat-btn"></span> </button></div></div>'
            );
        });

        $(document).on('click', '.delete_row', function() {
            var i = $('.total').val();
            var j = parseInt(i) - 1;
            // $('.total').val(j);
            $(this).closest('.main_row').remove();
        });


        $('#submit').click(function() {
            $('.valid_err').html('');
            var arr = [];
            var company_name = $('#company_name').val();
            var short_code = $('#short_code').val();
            var company_email = $('#company_email').val();
            var company_contact = $('#company_contact').val();
            var company_address = $('#company_address').val();
            var head_office = $('#head_office').val();
            var website_url = $('#website_url').val();
            var facebook_url = $('#facebook_url').val();
            var youtube_url = $('#youtube_url').val();
            var pan_no = $('#pan_no').val();
            var gst_no = $('#gst_no').val();

            if ($('#tax_applicable').is(':checked')) {
                var tax_applicable = 'yes';
            } else {
                var tax_applicable = 'no';
            }

            if ($('#tds_applicable').is(':checked')) {
                var tds_applicable = 'yes';
            } else {
                var tds_applicable = 'no';
            }

            var file_data = $("#company_logo")[0].files[0];

            var company_branch = new Array();
            $('.company_branch').each(function() {
                if ($(this).val() == '') {
                    arr.push('company_branch_err');
                    arr.push('Company branch required');
                } else {
                    company_branch.push($(this).val());
                }

            });

            var form_data = new FormData();
            form_data.append('company_name', company_name);
            form_data.append(
                'short_code', short_code);
            form_data.append('company_email', company_email);
            form_data.append('company_contact', company_contact);
            form_data.append('company_address', company_address);
            form_data.append('head_office', head_office);
            form_data.append('website_url', website_url);
            form_data.append('facebook_url', facebook_url);
            form_data.append('youtube_url', youtube_url);
            form_data.append('pan_no', pan_no);
            form_data.append('gst_no', gst_no);
            form_data
                .append('tax_applicable', tax_applicable);
            form_data.append('tds_applicable',
                tds_applicable);
            form_data.append('company_logo', file_data);
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

            for (var i = 0; i < company_branch.length; i++) {
                form_data.append("company_branch[]", company_branch[i]);
            }

            if (company_name == '') {
                arr.push('company_name_err');
                arr.push('Company name required');
            }

            if (short_code == '') {
                arr.push('short_code_err');
                arr.push('Short Code required');
            }

            if (company_email == '') {
                arr.push('company_email_err');
                arr.push('Company email required');
            }

            if (company_email != '' && mailformat.test(company_email) == false) {
                arr.push('company_email_err');
                arr.push('Invalid Email ID');
            }

            if (company_contact == '') {
                arr.push('company_contact_err');
                arr.push('Company contact number required');
            }

            if (company_contact != '' && company_contact.length != 10) {
                arr.push('company_contact_err');
                arr.push('Invalid contact number');
            }


            if (company_address == '') {
                arr.push('company_address_err');
                arr.push('Company address required');
            }

            if (pan_no == '') {
                arr.push('pan_no_err');
                arr.push('Pan Number required');
            }

            if (gst_no == '') {
                arr.push('gst_no_err');
                arr.push('GST Number required');
            }

            // if (file_data != '') {
            //     var file_size = $("#company_logo")[0].files[0].size;
            //     if (file_size > 1000000) {
            //         arr.push('company_logo_err');
            //         arr.push('file must be less than 1-Mb');
            //     }
            // }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'company_add',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,

                    success: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');

                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            get_company_update();
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

    function get_company_update() {
        $.ajax({
            type: "get",
            url: "get_company_update",
            datatype: "text",

            success: function(data) {
                console.log(data);
                $(".data_div").html(data);
            },
            error: function(data) {
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