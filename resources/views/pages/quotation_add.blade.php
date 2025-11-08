@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','New Quotation')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
{{-- page styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-quotation.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<!-- app invoice View Page -->
<section class="quotation-edit-wrapper">
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-9 col-md-8 col-12">
            <div id="alert">


            </div>
            <div class="card">
                <div class="card-body pb-0 mx-25">
                    <!-- header section -->
                    <form method="POST" action='' id="form">
                        {{ csrf_field() }}
                        <div class="row mx-0 invoice-info">
                            <div class="col-xl-6 col-md-12 d-flex align-items-center pl-0">
                                <h5 class="invoice-number mb-0 mr-75">New Quotation</h6>

                            </div>
                            <div class="col-xl-6 col-md-12">
                                <div class="quotation-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <fieldset class="d-flex ">
                                            <input type="text" name="date" class="form-control date pickadate  mb-50 mb-sm-0" id="floating-label1" placeholder="Date">

                                        </fieldset>

                                    </div>
                                    <span class="date_err valid_err"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row pt-50">
                            <div class="col-12 col-md-8 ">
                                <fieldset class="form-group">
                                    <div class="input-group">

                                        <select class="form-control" id="client" name="client">
                                            <option value="">Client name</option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$client->id == $client_id  ? 'selected' : ''}}>{{$client->case_no}} ({{$client->client_name}})</option>
                                            @endforeach

                                        </select>
                                        <span class="client_err valid_err"></span>

                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-4">
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Company</span>
                                        </div>
                                        <select class="form-control company" id="company" name="company">
                                            <option value="">Choose...</option>
                                            @foreach($company as $com)
                                            <option value="{{$com->id}}">{{$com->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="company_err valid_err"></span>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row  pt-50">
                            <div class="col-md-6 col-12">
                                <select name="service[]" class="form-control service service1" name="service[]">
                                    <option value=""></option>
                                    @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                    @endforeach
                                </select>
                                <span class="service_err service1_err valid_err"></span>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-md-3 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control no_of_units no_of_units1" name="no_of_units[]" placeholder="No of Units">
                                            <label for="number-id-column">Units</label>
                                            <span class="no_of_units_err no_of_units1_err  valid_err"></span>

                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control per_unit_amount per_unit_amount1" name="per_unit_amount[]" placeholder="Amount/unit">
                                            <label for="number-id-column">Amt/unit</label>
                                            <span class="per_unit_amount_err per_unit_amount1_err valid_err"></span>

                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control amount amount1" name="amount[]" placeholder="Amount">
                                            <label for="number-id-column">Amount</label>
                                            <span class="amount_err amount1_err valid_err"></span>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <fieldset class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input file" name="file">

                                        <span class="custom-file-label">Upload quotation</span>
                                    </div>
                                    <span class="file_err file1_err valid_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="form-label-group">
                                    <input type="text" class="form-control ref_no ref_no1" name="ref_no[]" placeholder="ref_no">
                                    <label for="number-id-column">ref_no</label>
                                    <span class="ref_no_err ref_no1_err valid_err"></span>

                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 col-12">
                                <button type="button" class="btn mr-2 btn-light-secondary add_row"><i class="bx bx-plus"></i></button>
                            </div>
                        </div>
                        <div class="service_div">
                            <input type="hidden" class="form-control total" value="1">
                        </div>


                        <div class="row">
                            <div class="col-auto mr-auto">
                                <a href="{{url()->previous()}}" class="btn btn-icon btn-warning mr-1 mb-1 px-5">Go Back</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" name="submit" class="btn btn-primary mr-2 submit px-5">Submit</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                            </div>
                        </div>


                    </form>

                    <br><br>
                </div>
            </div>
        </div>

        <!-- invoice action  -->
        <div class="col-xl-3 col-md-4 col-12">
            <div class="card quotation-action-wrapper shadow-none border">
                <div class="card-body">
                    <div class="quotation-action-btn mb-1">
                        <button class="btn btn-primary btn-block quotation-send-btn">
                            <i class="bx bx-send"></i>
                            <span>Send Quotation</span>
                        </button>
                    </div>
                    <div class="quotation-action-btn mb-1">
                        <button type="button" name="submit" class="btn btn-light-primary btn-block submit">Save</button>
                        <button type="button" name="update" class="btn btn-primary mr-1 update" style="display:none">Update</button>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>

@endsection
{{-- page scripts --}}

@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-quotation.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        console.log("inside ajax")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.add_row', function() {


            var i = $('.total').val();
            var j = parseInt(i) + 1;
            $('.service_div').append('<div class="row main_row"><input type=hidden value="' + (j) +
                '" class="form-control count cou' + j +
                '"> <div class="col-md-6 col-12"> <select name="service[]" class="form-control service service' +
                j +
                '" name="service[]"> <option value=""></option> @foreach($services as $service) <option value="{{$service->id}}">{{$service->name}}</option> @endforeach </select>  <span class="service_err service' +
                j +
                '_err valid_err"></span></div><div class="col-md-6 col-12"><div class="row"> <div class="col-md-3 col-12"> <div class="form-label-group"> <input type="text" class="form-control no_of_units no_of_units' +
                j +
                '" name="no_of_units[]" placeholder="No of Units"> <label for="number-id-column">Units</label> <span class="no_of_units_err no_of_units' +
                j +
                '_err  valid_err"></span></div> </div> <div class="col-md-4 col-12"> <div class="form-label-group"> <input type="text" class="form-control per_unit_amount per_unit_amount' +
                j +
                '" name="per_unit_amount[]"placeholder="Amount/unit"> <label for="number-id-column">Amount/unit</label> <span class="per_unit_amount_err per_unit_amount' +
                j +
                '_err valid_err"></span> </div> </div> <div class="col-md-5 col-12"> <div class="form-label-group"> <input type="text" class="form-control amount amount' +
                j +
                '" name="amount[]" placeholder="Amount"> <label for="number-id-column">Amount</label> <span class="amount_err amount1_err valid_err"></span></div></div></div> </div><div class="col-md-6 col-12"><fieldset class="form-group"><div class="custom-file"><input type="file" class="custom-file-input file"  name="file"><span class="custom-file-label" >Upload quotation</span></div><span class="file_err valid_err"></span></fieldset></div><div class="col-md-5 col-12"><div class="form-label-group"><input type="text" class="form-control ref_no ref_no' +
                j +
                '" name="ref_no[]" placeholder="ref_no"><label for="number-id-column">ref_no</label><span class="ref_no_err ref_no' +
                j +
                '_err valid_err"></span></div></div> <div class="col-md-1 col-12 "> <button type="button" class="btn mr-2 btn-light-danger delete_row"><i class="bx bx-trash-alt"></i></button></div></div>'
            );

            $('.total').val(j);
            $(document).on('change', '.custom-file-input', function(event) {
                $(this).next('.custom-file-label').html(event.target.files[0].name);
            });
            $(".service").select2({

                dropdownAutoWidth: true,
                width: '100%',
                placeholder: "Select Service"
            });
            $('.no_of_units').val($('.no_of_units1').val());
        });
        $(document).on('click', '.delete_row', function() {


            var i = $('.total').val();
            var j = parseInt(i) - 1;
            // $('.total').val(j);
            $(this).closest('.main_row').remove();
            var count = $(this).closest('.main_row').find('.count').val();
            var count_arr = new Array();
            $('.count').each(function() {
                count_arr.push($(this).val());
            });
            console.log(count_arr);



        });
        $(document).on('change', '#client', function() {
            var client_id = $(this).val();
            $.ajax({
                type: 'post',
                url: 'get_client_no_of_units',
                data: {
                    client_id: client_id
                },
                success: function(data) {
                    console.log(data);
                    $('.no_of_units').val(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $(document).on('click', '.submit', function() {
            var client = $('#client').val();
            var date = $('.date').val();
            var company = $('.company').val();

            var service = new Array();
            $('.service :selected').each(function() {
                service.push($(this).val());
            });
            var no_of_units = new Array();
            $('.no_of_units').each(function() {
                no_of_units.push($(this).val());
            });
            var per_unit_amount = new Array();
            $('.per_unit_amount').each(function() {
                per_unit_amount.push($(this).val());
            });
            var amount = new Array();
            $('.amount').each(function() {
                amount.push($(this).val());
            });
            var ref_no = new Array();
            $('.ref_no').each(function() {
                ref_no.push($(this).val());
            });
            var file_data = new Array();
            $('.file').each(function() {
                file_data.push($(this).prop("files")[0]);
            });

            var form_data = new FormData();

            form_data.append("client", client);
            for (var i = 0; i < service.length; i++) {
                form_data.append("file[]", file_data[i]);
                form_data.append("service[]", service[i]);
                form_data.append("no_of_units[]", no_of_units[i]);
                form_data.append("per_unit_amount[]", per_unit_amount[i]);
                form_data.append("amount[]", amount[i]);
                form_data.append("ref_no[]", ref_no[i]);
            }


            form_data.append("company", company);
            form_data.append("date", date);

            console.log(form_data);
            var valid = validation();
            if (valid) {
                $.ajax({
                    type: 'POST',
                    url: "submit/quotation",
                    dataType: 'text', // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);

                        if (res.status == 'success') {

                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $('.file').val("");
                            $('.service').val("");

                            $('.service').trigger('change');
                            $('#client').val("");
                            $('#client').trigger('change');


                            $("#form").trigger('reset');
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

        function validation() {
            $('.valid_err').html('');
            var valid = true;
            var client = $('#client').val();
            var date = $('.date').val();
            var company = $('.company').val();
            var ref_no = $('.ref_no').val();
            var service = new Array();
            if (client == '') {
                $('.client_err').html('Please select client');
                valid = false;
            }
            if (company == '') {
                $('.company_err').html('Please select company');
                valid = false;
            }
            if (date == '') {
                $('.date_err').html('Please select date');
                valid = false;
            }
            if (ref_no == '') {
                $('.ref_no_err').html('Enter ref_no');
                valid = false;
            }
            $('.service :selected').each(function() {
                if ($(this).val() == '') {
                    $(this).closest('div').find('.service_err').html('Please select service');
                    valid = false;
                }
            });
            var no_of_units = new Array();
            $('.no_of_units').each(function() {
                if ($(this).val() == '') {
                    $(this).closest('div').find('.no_of_units_err').html('Enter no of units');
                    valid = false;
                }
            });

            var amount = new Array();
            $('.amount').each(function() {
                if ($(this).val() == '') {
                    $(this).closest('div').find('.amount_err').html('Enter amount');
                    valid = false;
                }
            });
            $('.file').each(function() {
                var fp = $(this);
                var lg = fp[0].files.length; // get length 

                var items = fp[0].files;

                console.log(ext);
                if (lg == 0) {

                    $(this).closest('fieldset').find('span.file_err').html('Upload File');
                    valid = false;
                } else {
                    var ext = items[0].name.substr(-3);
                    var file_msg = validFile(ext);
                    if (file_msg) {

                        $(this).closest('fieldset').find('span.file_err').html(file_msg);
                        valid = false;
                    }
                    if (items[0].size > 2000000) {
                        $(this).closest('fieldset').find('span.file_err').html(
                            'File size must be less than or equal to 2 MB');
                        valid = false;
                    }


                }

            });

            return valid;
        }
    });

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
    $(document).on('keyup', '.no_of_units', function() {
        var no_of_units = $(this).val();

        var unit_amt = $(this).closest('div.row').find('.per_unit_amount').val();
        var result = parseInt(no_of_units) * parseInt(unit_amt);
        if (!isNaN(result)) {
            $(this).closest('div.row').find('.amount').val(result);
        } else {
            $(this).closest('div.row').find('.amount').val(0);
        }
    });
    $(document).on('keyup', '.per_unit_amount', function() {
        var no_of_units = $(this).closest('div.row').find('.no_of_units').val();

        var unit_amt = $(this).val();
        if (no_of_units != '')
            var result = parseInt(no_of_units) * parseInt(unit_amt);
        else
            var result = parseInt(unit_amt);
        if (!isNaN(result)) {
            $(this).closest('div.row').find('.amount').val(result);
        } else {
            $(this).closest('div.row').find('.amount').val(0);
        }
    });
</script>
@endsection