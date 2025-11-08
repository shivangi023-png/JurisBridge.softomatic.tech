@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Update Company')
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
<style>
.valid_err {

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
            @foreach($company as $val)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Company</h4>
                </div>
                <div class="card-body">
                    <form class="form" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="hidden" class="form-control" id="company_id" name="company_id"
                                            value="{{$val->id}}">
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                            placeholder="Company Name" value="{{$val->company_name}}">
                                        <label for="company_name">Company Name</label>
                                        <span class="company_name_err valid_err"></span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="short_code" name="short_code"
                                            placeholder="Short Code" value="{{$val->short_code}}">
                                        <label for="short_code">Short Code</label>
                                        <span class="short_code_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="pan_no" name="pan_no"
                                            placeholder="Pan Number" value="{{$val->pan_no}}">
                                        <label for="pan_no">Pan Number</label>
                                        <span class="pan_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="gst_no" name="gst_no"
                                            placeholder="GST Number" value="{{$val->gst_no}}">
                                        <label for="gst_no">GST Number</label>
                                        <span class="gst_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="company_email" name="company_email"
                                            placeholder="Email" value="{{$val->company_email}}">
                                        <label for="company_email">Email</label>
                                        <span class="company_email_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="company_contact"
                                            name="company_contact" placeholder="Contact"
                                            value="{{$val->company_contact}}">
                                        <label for="company_contact">Contact</label>
                                        <span class="company_contact_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="website_url" name="website_url"
                                            placeholder="Website Url" value="{{$val->website_url}}">
                                        <label for="website_url">Website Url</label>
                                        <span class="website_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="facebook_url" name="facebook_url"
                                            placeholder="Facebook Url" value="{{$val->facebook_url}}">
                                        <label for="facebook_url">Facebook Url</label>
                                        <span class="facebook_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="youtube_url" name="youtube_url"
                                            placeholder="Youtube Url" value="{{$val->youtube_url}}">
                                        <label for="youtube_url">Youtube Url</label>
                                        <span class="youtube_url_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="head_office" name="head_office"
                                            placeholder="Head Office" value="{{$val->head_office}}">
                                        <label for="head_office">Head Office</label>
                                        <span class="head_office_err valid_err"></span>
                                    </div>
                                </div>
                                @if($val->company_logo!='')
                                <div class="col-4 img_link_div">
                                    <a href="{{$val->company_logo}}" id="a_link"
                                        target="_blank">{{$val->company_logo}}</a><span><i class="bx bxs-pencil"
                                            id="edit_img"></i></span>

                                </div>
                                <div class="col-3 img_file_div" style="display:none">
                                    <fieldset class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file" name="company_logo"
                                                id="company_logo">

                                            <span class="custom-file-label">Upload Logo</span>
                                        </div>
                                        <span class="company_logo_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-1 img_file_div" style="display:none">
                                    <span><i class="bx bx-x my-1" id="cancel_img" style="color:red"></i></span>
                                </div>
                                @else
                                <div class="col-4">
                                    <fieldset class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file" name="company_logo"
                                                id="company_logo">

                                            <span class="custom-file-label">Upload Logo</span>
                                        </div>
                                        <span class="company_logo_err valid_err"></span>
                                    </fieldset>
                                </div>
                                @endif

                                <div class="col-2">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="tds_applicable"
                                                id="tds_applicable" {{ ($val->tds_applicable=="yes")? "checked" : "" }}>
                                            <label class="custom-control-label" for="tds_applicable">TDS
                                                Applicable</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-2">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="tax_applicable"
                                                id="tax_applicable" {{ ($val->tax_applicable=="yes")? "checked" : "" }}>
                                            <label class="custom-control-label" for="tax_applicable">TAX
                                                Applicable</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12">
                                    <div class="form-label-group">
                                        <textarea type="text" class="form-control" id="company_address"
                                            name="company_address" placeholder="Address"
                                            value="{{$val->company_address}}">{{$val->company_address}}</textarea>
                                        <label for="company_address">Address</label>
                                        <span class="company_address_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row main_row">
                                <div class="col-3">
                                    <div class="form-label-group">
                                        @if(!empty($val->company_branch))
                                        @foreach($val->company_branch as $branch)
                                        <input type="text" class="form-control company_branch my-2" id="company_branch"
                                            name="company_branch[]" placeholder="Branch Name" value="{{$branch}}">
                                        <label for="company_branch">Company Branch</label>
                                        <span class="company_branch_err valid_err"></span>
                                        @endforeach
                                        @else
                                        <input type="text" class="form-control company_branch my-2" id="company_branch"
                                            name="company_branch[]" placeholder="Branch Name" value="">
                                        <label for="company_branch">Company Branch</label>
                                        <span class="company_branch_err valid_err"></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-3">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <button class="btn btn-light-primary btn-sm add_row my-2" type="button">
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
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <a href="company_add" class="btn btn-icon btn-warning px-5 mr1 mb-1">Go Back</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" id="update" name="update"
                                    class="btn btn-primary mr-3 px-5">Update</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    </div>
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

@endsection
{{-- page scripts --}}
@section('page-scripts')

<script>
$("#edit_img").click(function() {
    $(".img_link_div").hide();
    $(".img_file_div").show();
});

$("#cancel_img").click(function() {
    $(".img_link_div").show();
    $(".img_file_div").hide();
});
$(document).ready(function() {
    $(document).on('click', '.add_row', function() {
        var i = $('.total').val();
        var j = parseInt(i) + 1;
        $('.branch_div').append(
            ' <div class="row main_row"><div class="col-3"><div class="form-label-group"><input type="text" class="form-control company_branch" id="company_branch" name="company_branch[]" placeholder="Branch Name"><label>Company Branch</label><span class="company_brach_err valid_err"></span></div> </div><div class="col-3 ">  <button class="btn btn-light-danger btn-sm delete_row" type="button"> <i class="bx bx-trash-alt"></i> <span class="invoice-repeat-btn"></span> </button> </div></div>'
        );
    });

    $(document).on('click', '.delete_row', function() {
        var i = $('.total').val();
        var j = parseInt(i) - 1;
        // $('.total').val(j);
        $(this).closest('.main_row').remove();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#update').click(function() {
        $('.valid_err').html('');
        var arr = [];
        var id = $('#company_id').val();
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
        form_data.append('id', id);
        form_data.append('company_name', company_name);
        form_data.append('short_code', short_code);
        form_data.append('company_email', company_email);
        form_data.append('company_contact', company_contact);
        form_data.append('company_address', company_address);
        form_data.append('head_office', head_office);
        form_data.append('website_url', website_url);
        form_data.append('facebook_url', facebook_url);
        form_data.append('youtube_url', youtube_url);
        form_data.append('pan_no', pan_no);
        form_data.append('gst_no', gst_no);
        form_data.append('tax_applicable', tax_applicable);
        form_data.append('tds_applicable', tds_applicable);
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
                url: 'company_update',
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