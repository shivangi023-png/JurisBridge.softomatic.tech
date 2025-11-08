@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','New Staff')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-staff.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
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

                <div class="card-body">
                    @include('layouts.tabs')
                    <form class="form" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-3">
                                    <input type="hidden" class="client_id">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control staff_name" id="staff_name"
                                            name="staff_name" placeholder="Staff Name">
                                        <label for="staff_name">Staff Name</label>
                                        <span class="staff_name_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="email" class="form-control emailid" id="emailid" name="emailid"
                                            placeholder="Email">
                                        <label for="emailid">Email</label>
                                        <span class="emailid_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control dob datepicker" id="dob" name="dob"
                                            placeholder="DOB" autocomplete="off">
                                        <label for="dob">DOB</label>
                                        <span class="dob_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control mobile" id="mobile" name="mobile"
                                            placeholder="Mobile">
                                        <label for="mobile">Mobile</label>
                                        <span class="mobile_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control qualification" id="qualification"
                                            name="qualification" placeholder="Qualification">
                                        <label for="qualification">Qualification</label>
                                        <span class="qualification_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select name="gender" id="gender" class="form-control gender">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <span class="gender_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control designation" id="designation"
                                            name="designation" placeholder="Department">
                                        <label for="designation">Department</label>
                                        <span class="designation_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control date_of_joining datepicker"
                                            placeholder="Date Of Joining" id="date_of_joining" name="date_of_joining"
                                            autocomplete="off">
                                        <label for="date_of_joining">Date of Joining</label>
                                        <span class="date_of_joining_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control password" placeholder="Password"
                                            id="password" name="password">
                                        <label for="password">Password</label>
                                        <span class="password_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <label for="">Select Role</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="role_id" id="role_id" class="form-control role_id">
                                                    <option value="">Select Role</option>
                                                    @foreach($role as $rl)
                                                    <option value="{{$rl->id}}">{{$rl->role}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="role_id_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <label for="">Select City</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="city" id="city" class="form-control city">
                                                    <option value="">Select City</option>
                                                    @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="city_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <label for="">Select Company</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="company[]" id="company" class="form-control company"
                                                    multiple="multiple">
                                                    <option value="">Select Company</option>
                                                    @foreach($company as $com)
                                                    <option value="{{$com->id}}">{{$com->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="company_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-label-group">
                                        <textarea class="form-control address" id="address" name="address"
                                            autocomplete="off" placeholder="Address"></textarea>
                                        <label for="address">Address</label>
                                        <span class="address_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <fieldset class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file" name="image" id="image">

                                            <span class="custom-file-label">Upload Image</span>
                                        </div>
                                        <span class="file_err valid_err"></span>
                                    </fieldset>
                                </div>
                                <div class="col-3">
                                    <fieldset class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file" name="signature"
                                                id="signature">

                                            <span class="custom-file-label">Upload Signature</span>
                                        </div>
                                        <span class="file1_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="submit" name="submit"
                                class="btn btn-primary mr-3 px-5">Submit</button>
                            <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="staff-list-wrapper">
                <div class="card">
                    <div class="card-body">
                        <!-- datatable start -->
                        <div class="table-responsive">
                            <table class="table staff-data-table wrap">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>id</th>
                                        <th>Staff Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="data_div">
                                    <?php $i = 1; ?>
                                    @foreach($staff as $staff_item)
                                    <tr>
                                        <td><a href="staff_edit-{{$staff_item->sid}}"
                                                class="btn btn-icon rounded-circle glow btn-warning"
                                                data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                                            @if($staff_item->status=='inactive')
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon rounded-circle glow btn-success mx-2 staff_status_change"
                                                data-tooltip="Convert to Active" data-staff_id="{{$staff_item->sid}}"
                                                data-status="{{$staff_item->status}}"><i class="bx bx-check"></i></a>
                                            @else
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon rounded-circle glow btn-danger mx-2 staff_status_change"
                                                data-tooltip="Convert to Inactive" data-staff_id="{{$staff_item->sid}}"
                                                data-status="{{$staff_item->status}}"><i class="bx bx-x"></i></a>
                                            @endif
                                        <td>{{$i++}}</td>
                                        <td>{{$staff_item->name}}</td>
                                        <td>{{$staff_item->emailid}}</td>
                                        <td>{{$staff_item->mobile}}</td>
                                        <td>{{$staff_item->role}}</td>
                                        <td>
                                            @if($staff_item->status=='active')
                                            <span
                                                class="badge badge-light-success badge-pill">{{$staff_item->status}}</span>
                                            @else
                                            <span
                                                class="badge badge-light-danger badge-pill">{{$staff_item->status}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </div>
            </div>
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

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/staff.js')}}"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // $(document).on('change', '.city', function() {

    //     var city = $(this).val();
    //     if (city == 68) {

    //         $('.company').val('1').prop('disabled', true);
    //         $('#company').trigger('change');
    //     } else if (city == "") {
    //         $('.company').val('').prop('disabled', true);
    //         $('#company').trigger('change');
    //     } else {

    //         $('.company').val('2').prop('disabled', true);
    //         $('#company').trigger('change');
    //     }
    // });

    $(document).on('click', '#submit', function() {
        $('.valid_err').html('');
        var arr = [];
        var staff_name = $('.staff_name').val();
        var emailid = $('.emailid').val();
        var dob = $('.dob').val();
        var mobile = $('.mobile').val();
        var qualification = $('.qualification').val();
        var gender = $('.gender').val();
        var designation = $('.designation').val();
        var date_of_joining = $('.date_of_joining').val();
        var password = $('.password').val();
        var role_id = $('.role_id').val();
        var city = $('.city').val();
        var company = new Array();
        $('.company').each(function() {
            var company_id = JSON.stringify($(this).val());
            company.push(company_id);
        });
        var address = $('.address').val();
        var file_data1 = $('#image').prop('files')[0];
        var file_data2 = $('#signature').prop('files')[0];
        var form_data = new FormData();
        form_data.append('staff_name', staff_name);
        form_data.append('emailid', emailid);
        form_data.append('dob', dob);
        form_data.append('mobile', mobile);
        form_data.append('qualification', qualification);
        form_data.append('gender', gender);
        form_data.append('designation', designation);
        form_data.append('date_of_joining', date_of_joining);
        form_data.append('password', password);
        form_data.append('role_id', role_id);
        form_data.append('city', city);
        form_data.append('company', company);
        form_data.append('address', address);
        form_data.append('image', file_data1);
        form_data.append('signature', file_data2);

        var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (staff_name == '') {
            arr.push('staff_name_err');
            arr.push('Staff name required');
        }

        if (emailid != '' && mailformat.test(emailid) == false) {
            arr.push('emailid_err');
            arr.push('Invalid staff Email ID');
        }

        if (dob == '') {
            arr.push('dob_err');
            arr.push('Date of birth required');
        }

        if (mobile != '' && mobile.length != 10) {
            arr.push('mobile_err');
            arr.push('Invalid staff mobile no');
        }

        if (qualification == '') {
            arr.push('qualification_err');
            arr.push('Qualification required');
        }

        if (gender == '') {
            arr.push('gender_err');
            arr.push('Gender required');
        }

        if (designation == '') {
            arr.push('designation_err');
            arr.push('Designation required');
        }

        if (date_of_joining == '') {
            arr.push('date_of_joining_err');
            arr.push('Date of joining required');
        }

        if (password == '') {
            arr.push('password_err');
            arr.push('Password required');
        }

        if (role_id == '') {
            arr.push('role_id_err');
            arr.push('Staff Role required');
        }

        if (city == '') {
            arr.push('city_err');
            arr.push('City  required');
        }

        if (company == '') {
            arr.push('company_err');
            arr.push('Company required');
        }
        if (address == '') {
            arr.push('address_err');
            arr.push('Address required');
        }

        $('#image').each(function() {
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
                if (items[0].size > 1000000) {
                    $(this).closest('fieldset').find('span.file_err').html(
                        'File size must be less than or equal to 1 MB');
                    valid = false;
                }


            }

        });

        $('#signature').each(function() {
            var fp = $(this);
            var lg = fp[0].files.length; // get length 

            var items = fp[0].files;

            console.log(ext);
            if (lg == 0) {

                $(this).closest('fieldset').find('span.file1_err').html('Upload File');
                valid = false;
            } else {
                var ext = items[0].name.substr(-3);
                var file_msg = validFile(ext);
                if (file_msg) {

                    $(this).closest('fieldset').find('span.file1_err').html(file_msg);
                    valid = false;
                }
                if (items[0].size > 1000000) {
                    $(this).closest('fieldset').find('span.file1_err').html(
                        'File size must be less than or equal to 1 MB');
                    valid = false;
                }


            }

        });

        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;
                $('.' + arr[i]).html(arr[j]).css('color', 'red');
                i = j;
            }
        } else {
            $.ajax({
                type: 'POST',
                url: "staff_add",
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
                        $('#image').val("");
                        $('#signature').val("");
                        $('.city').val("");
                        $('.city').trigger('change');
                        $('.comapny').val("");
                        $('.comapny').trigger('change');

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

$(document).on("click", ".staff_status_change", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $(this).data("staff_id");
    var status1 = $(this).data("status");
    if (status1 == 'active') {
        status = 'inactive';
    }

    if (status1 == 'inactive') {
        status = 'active';
    }

    Swal.fire({
        title: "Are you sure?",
        text: "You want to change this status?",
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
                type: "post",
                url: "staff_status_change",
                data: {
                    id: id,
                    status: status
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    if (res.status == "success") {
                        $(".data_div").empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();

                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });

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
                    //console.log(data);
                    $("#approveModal").modal("toggle");
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
                },
            });
        } else {

        }
    });
});
</script>

@endsection