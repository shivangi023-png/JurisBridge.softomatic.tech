@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Add Client')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')

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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add Client</h4>
                    <h4 class="card-title pull-right">Case no : <span class="case_no_span"></span></h4>
                </div>
                <div class="card-body">
                    <form class="form" id="form">

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <input type="hidden" class="client_id">
                                    <div class="form-label-group">

                                        <input type="text" class="form-control client_name" name="client_name" placeholder="Client Name" name="fname-column">
                                        <label for="first-name-column">Client Name</label>
                                        <span class="client_name_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <fieldset class="form-group">
                                        <div class="input-group">

                                            <select name="service[]" id="service" class="form-control service" multiple="multiple">

                                                @foreach($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <span class="service_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="inputGroupSelect01">Property
                                                    Type</label>
                                            </div>
                                            <select class="form-control property_type" name="property_type" id="property_type">
                                                <option value="0">Choose...</option>
                                                @foreach($property_type as $pt)
                                                <option value="{{$pt->id}}">{{$pt->type}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <span class="property_type_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <input type="number" class="form-control no_of_units" name="no_of_units" placeholder="No of Units">
                                        <label for="number-id-column">No of Units</label>
                                        <span class="no_of_units_err valid_err"></span>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">

                                        <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0 date start_date">
                                        <span class="start_date_err valid_err"></span>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="inputGroupSelect01">Source</label>
                                                </div>
                                                <select class="form-control source" name="source" id="source">
                                                    <option value="0">Choose...</option>
                                                    @foreach($sources as $source)
                                                    <option value="{{$source->id}}">{{$source->source}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <span class="source_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">

                                        <input type="text" class="form-control mr-2 mb-50 mb-sm-0 latitude" autocomplete="off" placeholder="Latitude">
                                        <label for="latitude">Latitude</label>
                                        <span class="latitude_err valid_err"></span>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">

                                        <input type="text" class="form-control mr-2 mb-50 mb-sm-0 longitude" autocomplete="off" placeholder="Longitude">
                                        <label for="longitude">Longitude</label>
                                        <span class="longitude_err valid_err"></span>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <textarea class="form-control address" name="address" autocomplete="off" placeholder="Address"></textarea>
                                        <label for="address">Address</label>
                                        <span class="address_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="inputGroupSelect01"> City</label>
                                            </div>
                                            <select class="form-control city" name="city" id="city">
                                                <option value="">Choose...</option>
                                                @foreach($cities as $city)
                                                <option value="{{$city->id}}">{{$city->city_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="city_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-md-3 col-12">
                                    <fieldset class="form-group">
                                        <div class="input-group">

                                            <select class="form-control company" name="company" id="company">

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
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-label-group">
                                        <textarea class="form-control client_enquiry" name="client_enquiry" autocomplete="off" placeholder="Enquiry/Remark"></textarea>
                                        <label for="enquiry">Enquiry/Remark</label>
                                        <span class="client_enquiry_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h4 class="card-title">Client Contacts</h4>
                                </div>
                            </div>
                            <div class="contact_div_up">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control name name1" name="name[]" value="" placeholder="Name">
                                            <label for="last-name-column">Name</label>
                                            <span class="name1_err valid_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control email email1" name="email[]" value="" placeholder="Email Id">
                                            <label for="last-name-column">Email Id</label>
                                            <span class="email_err email1_err valid_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control contact contact1" name="contact[]" placeholder="Contact No">
                                            <label for="city-column">Contact No</label>
                                            <span class="contact_err contact1_err valid_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control whatsapp whatsapp1" name="whatsapp[]" placeholder="Whatsapp No">
                                            <label for="city-column">Whatsapp No</label>
                                            <span class="whatsapp_err whatsapp1_err valid_err"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                     <div class="row">
                                         <div class="col-md-3">
                                        Are you committee member?
                                         </div>
                                          <div class="col-md-3">
                                         <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                <div class="radio">
                                                  <input type="radio" name="committee_member[1]" class="check_committee_member" value="yes" data-check="1" id="radio1">
                                                        <label for="radio1">Yes</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="radio">
                                                     <input type="radio" name="committee_member[1]" class="check_committee_member" value="no" data-check="1" id="radio02">
                                                        <label for="radio02">No</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            </ul>
                                         </div>
                                         <div class="col-md-4" id="div_position1" style="display:none;">
                                            <fieldset class="form-group">
                                            <div class="input-group">
                                             <select class="form-control position" name="position[1]" id="position1">
                                                    <option value="">Select Position</option>
                                                    <option value="Chairman">Chairman</option>
                                                    <option value="Secretary">Secretary</option>
                                                    <option value="Treasurer">Treasurer</option>
                                                    <option value="Committee Member">Committee Member</option>
                                             </select>
                                            </div>
                                            <span class="position_err1 valid_err"></span>
                                        </fieldset>
                                         </div>
                                     </div>
                                    </div>


                                    <div class="col-md-1 col-12 mb-1">
                                        <button type="button" class="btn mr-2 btn-light-secondary add_row"><i class="bx bx-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="contact_div">
                                <input type="hidden" class="form-control total" value="1">
                            </div>
                            <div class="row add_row_up mb-1" style="display:none">

                                <div class="col-md-1 col-12 ">
                                    <button type="button" class="btn mr-2 btn-light-secondary add_row"><i class="bx bx-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{url()->previous()}}" class="btn btn-icon btn-warning mr-1 px-5">Go Back</a>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                                    <button type="button" id="update" name="update" class="btn btn-primary mr-3 px-5" style="display:none">Update</button>
                                    <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/clients.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit').click(function() {

            $('.valid_err').html('');
            var arr = [];
            var name = new Array();
            $('.name').each(function() {
                name.push($(this).val());
            });
            var email = new Array();
            $('.email').each(function() {
                email.push($(this).val());
            });
            var contact = new Array();
            $('.contact').each(function() {
                contact.push($(this).val());
            });
            var whatsapp = new Array();
            $('.whatsapp').each(function() {
                whatsapp.push($(this).val());
            });
            var service = new Array();
            $('.service :selected').each(function() {
                service.push($(this).val());
            });

            var check_committee_member = new Array();
            $('.check_committee_member').each(function() {
                if ($(this).prop('checked')){
                   check_committee_member.push($(this).val());
                }
            });

            var position  = new Array();
            $('.position :selected').each(function() {
                position.push($(this).val());
            });

            var client_name = $('.client_name').val();
            var name1 = $('.name1').val();
            var email1 = $('.email1').val();
            var contact1 = $('.contact1').val();
            var whatsapp1 = $('.whatsapp1').val();
            var case_no = $('.case_no').val();
            //var service=$('.service').val();
            var start_date = $('.start_date').val();
            var city = $('.city').val();
            var address = $('.address').val();
            var no_of_units = $('.no_of_units').val();
            var property_type = $('.property_type').val();

            var client_enquiry = $('.client_enquiry').val();
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var company = $('#company').val();
            var longitude = $('.longitude').val();
            var latitude = $('.latitude').val();
            var source = $('.source').val();
            if (client_name == '') {
                arr.push('client_name_err');
                arr.push('Client name required');
            }

            if (email1 != '' && mailformat.test(email1) == false) {
                arr.push('email1_err');
                arr.push('Invalid client Email ID');
            }
            if (whatsapp1 != '' && whatsapp1.length != 10) {
                arr.push('whatsapp1_err');
                arr.push('Invalid client whatsapp no');
            }
            if (contact1 != '' && contact1.length != 10) {
                arr.push('contact1_err');
                arr.push('Invalid client contact no');
            }
            var total = $('.total').val();
            var count_arr = new Array();
            $('.count').each(function() {
                count_arr.push($(this).val());
            });

            for (var i = 0; i < count_arr.length; i++) {
                var name1 = $('.name' + count_arr[i]).val();
                var email1 = $('.email' + count_arr[i]).val();
                var contact1 = $('.contact' + count_arr[i]).val();
                var whatsapp1 = $('.whatsapp' + count_arr[i]).val();

                if (name1 == '') {
                    arr.push('name1_err');
                    arr.push('Contact name required');
                }
                if (email1 != '' && mailformat.test(email1) == false) {
                    arr.push('email_err' + count_arr[i]);
                    arr.push('Invalid  Email ID');
                }
                if (whatsapp1 != '' && whatsapp1.length != 10) {
                    arr.push('whatsapp_err' + count_arr[i]);
                    arr.push('Invalid  whatsapp no');
                }
                if (contact1 == '' && contact1.length != 10) {
                    arr.push('contact_err' + count_arr[i]);
                    arr.push('client contact no valid required');
                    arr.push('contact1_err');
                    arr.push('client contact no valid required');
                }
            }
            if (address == '') {
                arr.push('address_err');
                arr.push('Address required');
            }
            if (city == '') {
                arr.push('city_err');
                arr.push('City is required');
            }
            if (service == '') {
                arr.push('service_err');
                arr.push('Please select services');
            }
            if (start_date == '') {
                arr.push('start_date_err');
                arr.push('Date is required');
            }
            if (case_no == '') {
                arr.push('case_no_err');
                arr.push('Case no is required');
            }
            if (no_of_units == '') {
                arr.push('no_of_units_err');
                arr.push('No of units required required');
            }
            if (property_type == '') {
                arr.push('property_type_err');
                arr.push('Property type required');
            }
            var i = 1;
            $('.check_committee_member').each(function() {
                if($("input[name='committee_member["+i+"]']:checked").val() == 'yes' && $('#position'+i).val() == ''){
                    arr.push('position_err'+i);
                    arr.push('Please select position');
                }
                i++;
            });
            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'client_add',
                    data: {
                        name: name,
                        email: email,
                        contact: contact,
                        whatsapp: whatsapp,
                        client_name: client_name,
                        case_no: case_no,
                        service: service,
                        start_date: start_date,
                        city: city,
                        address: address,
                        source: source,
                        longitude: longitude,
                        latitude: latitude,
                        client_enquiry: client_enquiry,
                        company: company,
                        no_of_units: no_of_units,
                        property_type: property_type,
                        check_committee_member:check_committee_member,
                        position:position
                    },

                    success: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $(".client_name").autocomplete({

                                source: res.client_detail
                            });
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $('#company').val("");

                            $('#service').val("");
                            $('#service').trigger('change');
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
        $(document).on('click', '#update', function() {
            $('.valid_err').html('');
            var arr = [];
            var name = new Array();
            $('.name').each(function() {
                name.push($(this).val());
            });
            var email = new Array();
            $('.email').each(function() {
                email.push($(this).val());
            });
            var contact = new Array();
            $('.contact').each(function() {
                contact.push($(this).val());
            });
            var contact_id = new Array();
            $('.contact_id').each(function() {
                contact_id.push($(this).val());
            });
            var whatsapp = new Array();
            $('.whatsapp').each(function() {
                whatsapp.push($(this).val());
            });
            var service = new Array();
            $('.service :selected').each(function() {
                service.push($(this).val());
            });

            var check_committee_member = new Array();
            $('.check_committee_member').each(function() {
                if ($(this).prop('checked')){
                   check_committee_member.push($(this).val());
                }
            });

            var position  = new Array();
            $('.position :selected').each(function() {
                position.push($(this).val());
            });

            var client_name = $('.client_name').val();
            var name1 = $('.name1').val();
            var email1 = $('.email1').val();
            var contact1 = $('.contact1').val();
            var whatsapp1 = $('.whatsapp1').val();
            var case_no = $('.case_no').val();
            //var service=$('.service').val();
            var start_date = $('.start_date').val();
            var city = $('.city').val();
            var address = $('.address').val();
            var no_of_units = $('.no_of_units').val();
            var property_type = $('.property_type').val();
            var client_id = $('.client_id').val();

            var client_enquiry = $('.client_enquiry').val();
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var company = $('.company').val();
            var longitude = $('.longitude').val();
            var latitude = $('.latitude').val();
            var source = $('.source').val();

            if (client_name == '') {
                arr.push('client_name_err');
                arr.push('Client name required');
            }

            if (email1 != '' && mailformat.test(email1) == false) {
                arr.push('email1_err');
                arr.push('Invalid client Email ID');
            }
            if (whatsapp1 != '' && whatsapp1.length != 10) {
                arr.push('whatsapp1_err');
                arr.push('Invalid client whatsapp no');
            }
            if (contact1 != '' && contact1.length != 10) {
                arr.push('contact1_err');
                arr.push('Invalid client contact no');
            }
            var total = $('.total').val();
            var count_arr = new Array();
            $('.count').each(function() {
                count_arr.push($(this).val());
            });
            for (var i = 0; i < count_arr.length; i++) {


                var name1 = $('.name' + count_arr[i]).val();
                var email1 = $('.email' + count_arr[i]).val();
                var contact1 = $('.contact' + count_arr[i]).val();
                var whatsapp1 = $('.whatsapp' + count_arr[i]).val();

                if (email1 != '' && mailformat.test(email1) == false) {
                    arr.push('email_err' + count_arr[i]);
                    arr.push('Invalid  Email ID');
                }
                if (whatsapp1 != '' && whatsapp1.length != 10) {
                    arr.push('whatsapp_err' + count_arr[i]);
                    arr.push('Invalid  whatsapp no');
                }
                if (contact1 != '' && contact1.length != 10) {
                    arr.push('contact_err' + count_arr[i]);
                    arr.push('Invalid client contact no');
                }
            }
            if (address == '') {
                arr.push('address_err');
                arr.push('Address required');
            }
            if (city == '') {
                arr.push('city_err');
                arr.push('City is required');
            }
            if (service == '') {
                arr.push('service_err');
                arr.push('Please select services');
            }
            if (start_date == '') {
                arr.push('start_date_err');
                arr.push('Date is required');
            }
            if (case_no == '') {
                arr.push('case_no_err');
                arr.push('Case no is required');
            }
            if (no_of_units == '') {
                arr.push('no_of_units_err');
                arr.push('No of units required required');
            }
            if (property_type == '') {
                arr.push('property_type_err');
                arr.push('Property type required');
            }
            var i = 1;
            $('.check_committee_member').each(function() {
                if($("input[name='committee_member["+i+"]']:checked").val() == 'yes' && $('#position'+i).val() == ''){
                    arr.push('position_err'+i);
                    arr.push('Please select position');
                }
                i++;
            });
            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'update_clients',
                    data: {
                        name: name,
                        email: email,
                        contact: contact,
                        whatsapp: whatsapp,
                        client_name: client_name,
                        case_no: case_no,
                        service: service,
                        start_date: start_date,
                        city: city,
                        address: address,
                        client_id: client_id,
                        contact_id: contact_id,
                        client_enquiry: client_enquiry,
                        company: company,
                        no_of_units: no_of_units,
                        property_type: property_type,
                        source: source,
                        longitude: longitude,
                        latitude: latitude,
                        check_committee_member:check_committee_member,
                        position:position
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

                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            $('.contact_div_up').html(
                                '<div class="row"> <div class="col-md-4 col-12"> <div class="form-label-group"> <input type="text" class="form-control name name1" name="name[]" value="" placeholder="Name"> <label for="last-name-column">Name</label> <span class="name1_err valid_err"></span> </div> </div> <div class="col-md-3 col-12"> <div class="form-label-group"> <input type="text" class="form-control email email1" name="email[]" value="" placeholder="Email Id"> <label for="last-name-column">Email Id</label> <span class="email_err email1_err valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control contact contact1" name="contact[]" placeholder="Contact No" > <label for="city-column">Contact No</label> <span class="contact_err contact1_err valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control whatsapp whatsapp1" name="whatsapp[]" placeholder="Whatsapp No" > <label for="city-column">Whatsapp No</label> <span class="whatsapp_err whatsapp1_err valid_err"></span> </div> </div> <div class="col-md-1 col-12 "> <button type="button" class="btn mr-2 btn-light-secondary add_row"><i class="bx bx-plus"></i></button> </div> </div>'
                            );
                            $('.contact_div').empty();
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();

                        }


                    },
                    error: function(data) {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        console.log(data);
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
                        ).focus();
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