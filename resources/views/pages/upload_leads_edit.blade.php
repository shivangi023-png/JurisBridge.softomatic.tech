@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Update FB Lead')
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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Lead</h4>
                </div>
                <div class="card-body">
                    <form class="form" id="form">
                        <input type="hidden" name="id" value="{{$data->id}}" id="id">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="name">Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" placeholder="Name" value="{{$data->name}}" id="name">
                                        </div>
                                        <span class="name_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="email">Email</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{$data->email}}" id="email">
                                        </div>
                                        <span class="email_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-2 col-12">
                                    <fieldset class="form-group">
                                        <label for="name">Mobile No.</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="mobile_no" placeholder="Mobile No." value="{{$data->mobile_no}}" id="mobile_no">
                                        </div>
                                        <span class="name_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-md-2 col-12">
                                    <fieldset class="form-group">
                                        <label for="city">City</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="city" placeholder="city" value="{{$data->city}}" id="city">
                                        </div>
                                        <span class="city_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                             <div class="row">


                                 <div class="col-md-3 col-12">
                                        <label for="dob">Date of Birth</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control pickadate1 mr-2 mb-50 mb-sm-0" value="{{$data->dob}}" id="dob">
                                        <span class="dob_err valid_err"></span>

                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <fieldset class="form-group">
                                        <label for="units">NO OF UNITS</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="units" placeholder="Units" value="{{$data->units}}" id="units">
                                        </div>
                                        <span class="units_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-3 col-12">
                                    <fieldset class="form-group">
                                        <label for="from">From</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="from" placeholder="From" value="{{$data->from}}" id="from">
                                        </div>
                                        <span class="form_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-3 col-12">
                                    <fieldset class="form-group">
                                        <label for="role">Role</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="role" placeholder="Role" value="{{$data->role}}" id="role">
                                        </div>
                                        <span class="role_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                             <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="society_name">Society Name</label>
                                        <textarea class="form-control" name="society_name" autocomplete="off" placeholder="Society Name" id="society_name">{{$data->society_name}}</textarea>
                                        <span class="society_name_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="area">Area</label>
                                        <textarea class="form-control" name="area" autocomplete="off" placeholder="Area" id="area">{{$data->area}}</textarea>
                                        <span class="area_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" name="address" autocomplete="off" placeholder="Address" id="address">{{$data->address}}</textarea>
                                        <span class="address_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="any_query">Any Query</label>
                                        <textarea class="form-control" name="any_query" autocomplete="off" placeholder="Any Query" id="any_query">{{$data->any_query}}</textarea>
                                        <span class="any_query_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="services">Services</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="services" placeholder="Services" value="{{$data->services}}" id="services">
                                        </div>
                                        <span class="services_err valid_err"></span>
                                    </fieldset>
                                </div>
                              <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select Status</option>
                                            @if($data->status == 'active')
                                            <option value="active" selected>Active</option>
                                            @else
                                            <option value="active">Active</option>
                                            @endif
                                            @if($data->status == 'inactive')
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
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="lead_type">Lead Type</label>
                                        <select class="form-control" name="lead_type" id="lead_type">
                                            <option value="">Select type</option>
                                            @foreach($lead_types as $lead_type)
                                            @if($data->lead_type == $lead_type->id)
                                            <option value="{{$lead_type->id}}" selected>{{$lead_type->type}}</option>
                                            @else
                                            <option value="{{$lead_type->id}}">{{$lead_type->type}}</option>
                                            @endif
                                            @endforeach
                                            </select>
                                        <span class="lead_type_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="lead_source">Lead Source</label>
                                         <div class="input-group">
                                            <input type="text" class="form-control" name="lead_source" placeholder="Lead Source" value="{{$data->lead_source}}" id="lead_source">
                                        </div>
                                        <span class="lead_source_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="check_commitee_member">Are you Commitee Member?</label>
                                         <select class="form-control" name="check_commitee_member" id="check_commitee_member">
                                            <option value="">Select</option>
                                            @if($data->check_commitee_member == 'yes')
                                            <option value="yes" selected>Yes</option>
                                            @else
                                            <option value="yes">Yes</option>
                                            @endif
                                            @if($data->status == 'no')
                                            <option value="no" selected>No</option>
                                            @else
                                            <option value="no">No</option>
                                            @endif
                                            </select>
                                        <span class="scheck_commitee_member_err valid_err"></span>
                                    </fieldset>
                                </div>
                              
                            </div>


                              <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="fb_id">fb_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="fb_id" placeholder="fb_id" value="{{$data->fb_id}}" id="fb_id">
                                        </div>
                                        <span class="fb_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="ad_id">ad_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ad_id" placeholder="ad_id" value="{{$data->ad_id}}" id="ad_id">
                                        </div>
                                        <span class="ad_id_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="ad_name">ad_name</label>
                                         <textarea class="form-control" name="ad_name" autocomplete="off" placeholder="ad_name" id="ad_name">{{$data->ad_name}}</textarea>
                                        <span class="ad_name_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="adset_id">adset_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="adset_id" placeholder="adset_id" value="{{$data->adset_id}}" id="adset_id">
                                        </div>
                                        <span class="adset_id_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="adset_name">adset_name</label>
                                          <textarea class="form-control" name="adset_name" autocomplete="off" placeholder="adset_name" id="adset_name">{{$data->adset_name}}</textarea>
                                        <span class="adset_name_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="campaign_id">campaign_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="campaign_id" placeholder="campaign_id" value="{{$data->campaign_id}}" id="campaign_id">
                                        </div>
                                        <span class="campaign_id_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="campaign_name">campaign_name</label>
                                            <textarea class="form-control" name="campaign_name" autocomplete="off" placeholder="campaign_name" id="campaign_name">{{$data->campaign_name}}</textarea>
                                        <span class="campaign_name_err valid_err"></span>
                                    </fieldset>
                                </div>

                                <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="form_id">form_id</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="form_id" placeholder="form_id" value="{{$data->form_id}}" id="form_id">
                                        </div>
                                        <span class="form_id_err valid_err"></span>
                                    </fieldset>
                                </div>
                               <div class="col-md-4 col-12">
                                    <fieldset class="form-group">
                                        <label for="form_name">form_name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="form_name" placeholder="form_name" value="{{$data->form_name}}" id="form_name">
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
    });
    $(document).on('click', '#update', function() {
            $('.valid_err').html('');
            var arr = [];
            var id = $('#id').val();
            var name = $('#name').val();
            var email = $('#email').val();
            var mobile_no = $('#mobile_no').val();
            var dob = $('#dob').val();
            var check_commitee_member = $('#check_commitee_member').val();
            var city = $('#city').val();
            var society_name = $('#society_name').val();
            var area = $('#area').val();
            var address = $('#address').val();
            var role = $('#role').val();
            var units = $('#units').val();
            var services = $('#services').val();
            var from = $('#from').val();
            var any_query = $('#any_query').val();
            var lead_source = $('#lead_source').val();
            var lead_type = $('#lead_type').val();
            var status = $('#status').val();
            var fb_id = $('#fb_id').val();
            var ad_id = $('#ad_id').val();
            var ad_name = $('#ad_name').val();
            var adset_id = $('#adset_id').val();
            var adset_name = $('#adset_name').val();
            var campaign_id = $('#campaign_id').val();
            var campaign_name = $('#campaign_name').val();
            var form_id = $('#form_id').val();
            var form_name = $('#form_name').val();

            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            
            if (name == '') {
                arr.push('name_err');
                arr.push('Name required');
            }
            if (email == '') {
                arr.push('email_err');
                arr.push('Email is required');
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
                    url: 'upload_leads_update',
                    data: {
                        id : id ,
                        name : name ,
                        email : email ,
                        mobile_no : mobile_no ,
                        dob : dob ,
                        check_commitee_member : check_commitee_member ,
                        city : city ,
                        society_name : society_name ,
                        area : area ,
                        address : address ,
                        role : role ,
                        units : units ,
                        services : services ,
                        from : from ,
                        any_query : any_query ,
                        lead_source : lead_source ,
                        lead_type : lead_type ,
                        status : status ,
                        fb_id : fb_id ,
                        ad_id : ad_id ,
                        ad_name : ad_name ,
                        adset_id : adset_id ,
                        adset_name : adset_name ,
                        campaign_id : campaign_id ,
                        campaign_name : campaign_name ,
                        form_id : form_id ,
                        form_name : form_name
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
  
</script>
@endsection