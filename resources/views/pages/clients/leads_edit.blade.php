@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Update Leads')
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

    .wd {
        max-width: 4%;
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
            <div id="alert"></div>
            @foreach($leads_data as $val)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Leads</h4>
                    <!-- <h4 class="card-title pull-right">Case no : <span class="case_no_span">case_no</span></h4> -->
                </div>
                <div class="card-body">
                    <form class="form" id="form">
                        <input type="hidden" class="id" value="{{$val->id}}">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control leads_name" name="leads_name" placeholder="Name" value="{{$val->name}}">
                                        <label for="leads_name-column">Name</label>
                                        <span class="leads_name_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <input type="number" class="form-control mobile_no" name="mobile_no" value="{{$val->mobile_no}}" placeholder="Mobile No">
                                        <label for="mobile_no-column">Mobile No</label>
                                        <span class="mobile_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control email" name="email" value="{{$val->email}}" placeholder="Email">
                                        <label for="email-column">Email</label>
                                        <span class="email_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <label for="dob">Date of Birth</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control pickadate1" value="{{$val->dob}}" id="dob">
                                        <span class="dob_err valid_err"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <textarea class="form-control society_name" name="society_name" autocomplete="off" placeholder="Society Name" value="{{$val->society_name}}">{{$val->society_name}}</textarea>
                                        <label for="society_name">Society Name</label>
                                        <span class="society_name_err valid_err"></span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <input type="number" class="form-control units" name="units" placeholder="Units" value="{{$val->units}}">
                                        <label for="units-column">Units</label>
                                        <span class="units_err valid_err"></span>

                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control from" name="from" placeholder="From" value="{{$val->from}}">
                                        <label for="from-column">From</label>
                                        <span class="from_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select Status</option>
                                            @if($val->status == 'active')
                                            <option value="active" selected>Active</option>
                                            @else
                                            <option value="active">Active</option>
                                            @endif
                                            @if($val->status == 'inactive')
                                            <option value="inactive" selected>Inactive</option>
                                            @else
                                            <option value="inactive">Inactive</option>
                                            @endif
                                        </select>
                                        <span class="status_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    <label for="Services-column">Services</label>
                                    <div class="form-label-group">
                                        <select name="service[]" id="service" class="form-control service" multiple="multiple">
                                            <?php $val->services = json_decode($val->services); ?>
                                            @foreach($services as $service)
                                            @if($val->services !='' || $val->services !=null)
                                            <option value="{{$service->id}}" {{ (in_array($service->id,$val->services)) ? 'selected' : '' }}>
                                                {{$service->name}}
                                            </option>
                                            @else
                                            <option value="{{$service->id}}">
                                                {{$service->name}}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <span class="Services_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <label for=" role-column">Role</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control role" name="role" value="{{$val->role}}" placeholder="Role">
                                        <span class="role_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <label for="city">City</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control mr-2 mb-50 mb-sm-0 city" autocomplete="off" placeholder="City" value="{{$val->city}}">
                                        <span class="city_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label for="area">Area</label>
                                    <div class="form-label-group">
                                        <textarea class="form-control area" name="area" autocomplete="off" placeholder="Area" value="{{$val->area}}">{{$val->area}}</textarea>
                                        <span class="area_err valid_err"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-label-group">
                                        <textarea class="form-control address" name="address" autocomplete="off" placeholder="Address" value="{{$val->address}}">{{$val->address}}</textarea>
                                        <label for="address">Address</label>
                                        <span class="address_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-3">
                                    <label for="any_query">Any Query</label>
                                    <div class="form-label-group">
                                        <textarea class="form-control any_query" name="any_query" autocomplete="off" placeholder="Any query">{{$val->any_query}}</textarea>
                                        <span class="any_query_err valid_err"></span>
                                    </div>
                                </div>



                                <!-- <div class="col-md-3 col-3">
                                        <label for="lead_source-column">Lead Source</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control lead_source" name="lead_source" placeholder="Lead Source" value="{{$val->lead_source}}">
                                        <span class="lead_source_err valid_err"></span>
                                    </div>
                                </div> -->
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select class="form-control" id="leadtype" name="leadtype">
                                                <option value="">Lead Type</option>
                                                @foreach ($lead_types as $row)
                                                <option value="{{$row->id}}" {{$row->id==$val->lead_type?'selected':''}}>
                                                    {{$row->type}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="leadtype_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="check_commitee_member">Are you Commitee Member?</label>
                                        <select class="form-control" name="check_commitee_member" id="check_commitee_member">
                                            <option value="">Select</option>
                                            @if($val->check_commitee_member == 'yes')
                                            <option value="yes" selected>Yes</option>
                                            @else
                                            <option value="yes">Yes</option>
                                            @endif
                                            @if($val->status == 'no')
                                            <option value="no" selected>No</option>
                                            @else
                                            <option value="no">No</option>
                                            @endif
                                        </select>
                                        <span class="scheck_commitee_member_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="fb_id">fb_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="fb_id" placeholder="fb_id" value="{{$val->fb_id}}" id="fb_id">
                                        </div>
                                        <span class="fb_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="ad_id">ad_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ad_id" placeholder="ad_id" value="{{$val->ad_id}}" id="ad_id">
                                        </div>
                                        <span class="ad_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="ad_name">ad_name</label>
                                        <textarea class="form-control" name="ad_name" autocomplete="off" placeholder="ad_name" id="ad_name">{{$val->ad_name}}</textarea>
                                        <span class="ad_name_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="adset_id">adset_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="adset_id" placeholder="adset_id" value="{{$val->adset_id}}" id="adset_id">
                                        </div>
                                        <span class="adset_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="adset_name">adset_name</label>
                                        <textarea class="form-control" name="adset_name" autocomplete="off" placeholder="adset_name" id="adset_name">{{$val->adset_name}}</textarea>
                                        <span class="adset_name_err valid_err"></span>
                                    </fieldset>
                                </div>


                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="campaign_id">campaign_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="campaign_id" placeholder="campaign_id" value="{{$val->campaign_id}}" id="campaign_id">
                                        </div>
                                        <span class="campaign_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="campaign_name">campaign_name</label>
                                        <textarea class="form-control" name="campaign_name" autocomplete="off" placeholder="campaign_name" id="campaign_name">{{$val->campaign_name}}</textarea>
                                        <span class="campaign_name_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="form_id">form_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="form_id" placeholder="form_id" value="{{$val->form_id}}" id="form_id">
                                        </div>
                                        <span class="form_id_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-md-3 col-3">
                                    <fieldset class="form-group">
                                        <label for="form_name">form_name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="form_name" placeholder="form_name" value="{{$val->form_name}}" id="form_name">
                                        </div>
                                        <span class="form_name_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <a href="{{url()->previous()}}" class="btn btn-icon btn-warning mr-1 px-5">Go Back</a>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="button" id="update" name="update" class="btn btn-primary mr-3 px-5">Update</button>
                                    <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
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
        $(document).on('click', '#update', function() {
            $('.valid_err').html('');
            var arr = [];
            var id = $('.id').val();
            var leads_name = $('.leads_name').val();
            var email = $('.email').val();
            var mobile_no = $('.mobile_no').val();
            var dob = $('#dob').val();
            var society_name = $('.society_name').val();
            var units = $('.units').val();
            var from = $('.from').val();
            var service = new Array();
            $('.service :selected').each(function() {
                service.push($(this).val());
            });

            var lead_source = $('.lead_source').val();
            var leadtype = $('#leadtype').val();
            var role = $('.role').val();
            var city = $('.city').val();
            var area = $('.area').val();
            var address = $('.address').val();
            var any_query = $('.any_query').val();
            var check_commitee_member = $('#check_commitee_member').val();
            var fb_id = $('#fb_id').val();
            var ad_id = $('#ad_id').val();
            var ad_name = $('#ad_name').val();
            var adset_id = $('#adset_id').val();
            var adset_name = $('#adset_name').val();
            var campaign_id = $('#campaign_id').val();
            var campaign_name = $('#campaign_name').val();
            var form_id = $('#form_id').val();
            var form_name = $('#form_name').val();
            var status = $('#status').val();
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

            if (leads_name == '') {
                arr.push('leads_name_err');
                arr.push('name required');
            }
            if (email != '' && mailformat.test(email) == false) {
                arr.push('email_err');
                arr.push('Invalid Lead Email ID');
            }
            if (mobile_no != '' && mobile_no.length != 10) {
                arr.push('mobile_no_err');
                arr.push('Invalid mobile no');
            }
            // if (city == '') {
            //     arr.push('city_err');
            //     arr.push('City is required');
            // }
            if (from == '') {
                arr.push('from_err');
                arr.push('Source is required');
            }
            if (society_name == '') {
                arr.push('society_name_err');
                arr.push('society name is required');
            }
            if (leadtype == '') {
                arr.push('leadtype_err');
                arr.push('lead type is required');
            }
            console.log(arr);
            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'update_leads',
                    data: {
                        id: id,
                        name: leads_name,
                        email: email,
                        dob: dob,
                        mobile_no: mobile_no,
                        units: units,
                        society_name: society_name,
                        from: from,
                        lead_source: lead_source,
                        leadtype: leadtype,
                        services: service,
                        role: role,
                        city: city,
                        area: area,
                        address: address,
                        any_query: any_query,
                        check_commitee_member: check_commitee_member,
                        status: status,
                        fb_id: fb_id,
                        ad_id: ad_id,
                        ad_name: ad_name,
                        adset_id: adset_id,
                        adset_name: adset_name,
                        campaign_id: campaign_id,
                        campaign_name: campaign_name,
                        form_id: form_id,
                        form_name: form_name
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

                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
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