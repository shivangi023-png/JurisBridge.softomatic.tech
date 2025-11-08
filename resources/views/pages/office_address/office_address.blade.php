@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Office Address')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/wickedpicker/dist/wickedpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-staff.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {

        font-size: 12px;
    }

    .hasWickedpicker {
        z-index: 1700 !important;
    }

    .staff-data-table {
        border-collapse: separate;
        border-spacing: 10px;
        /* Adjust the spacing value as needed */
    }

    .staff-data-table th,
    .staff-data-table td {
        padding: 10px;
        /* Add padding to cells for spacing */
    }

    /* Add margin or padding to the specific columns you want */
    .from-time-column {
        margin-right: 5px;
        /* Adjust the margin value as needed */
    }

    .to-time-column {
        margin-left: 10px;
        /* Adjust the margin value as needed */
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

            <div class="card">
                <div class="card-body">
                    @include('layouts.tabs')
                    <div id="alert"></div>
                    <h4>Add Office</h4>
                    <form class="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="dept_name" name="dept_name" placeholder="Department Name">
                                        <label for="dept_name">Department Name</label>
                                        <span class="dept_name_err valid_err"></span>
                                    </div>
                                </div>
                            
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Short Name">
                                        <label for="short_name">Short Name</label>
                                        <span class="short_name_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                        <label for="latitude">Latitude</label>
                                        <span class="latitude_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                        <label for="longitude">Longitude</label>
                                        <span class="longitude_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                        <label for="address">Address</label>
                                        <span class="address_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Landmark">
                                        <label for="landmark">Landmark</label>
                                        <span class="landmark_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                        <button type="button" id="submit" name="submit" class="btn btn-primary mt-2 mr-3 px-5">Submit</button>
                                        <button type="reset" class="btn btn-light-secondary mt-2 px-5">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="staff_shift-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h4>List of Ofiice Address</h4>
                        <div class="data_div">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="width:900px" role="document">
            <div class="modal-content">
                <div class="modal-header bg-pink">
                    <h4 class="modal-title" id="uModalLabel">Edit Office</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" class="form-control office_id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control dept_name" name="dept_name" placeholder="Department Name">
                                    <span class="valid_err1 dept_name_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control short_name" id="" name="short_name" placeholder="Short Name">
                                    <span class="valid_err1 short_name_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control latitude" id="" name="latitude" placeholder="Latitude">
                                    <span class="valid_err1 latitude_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control longitude" id="" name="longitude" placeholder="Longitude">
                                    <span class="valid_err1 longitude_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <textarea class="form-control address" id="" name="address" placeholder="Address"></textarea>
                                    <span class="valid_err1 address_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control landmark" id="" name="landmark" placeholder="Landmark">
                                    <span class="valid_err1 landmark_err1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="update">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/wickedpicker/dist/wickedpicker.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.timepicker').wickedpicker();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       office_address();
        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var dept_name = $('#dept_name').val();
            var short_name = $('#short_name').val();
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
            var address = $('#address').val();
            var landmark = $('#landmark').val();

            if (dept_name == '') {
                arr.push('dept_name_err');
                arr.push('Department name required');
            }

            if (short_name == '') {
                arr.push('short_name_err');
                arr.push('Short Name required');
            }

            if (latitude == '') {
                arr.push('latitude_err');
                arr.push('Latitude required');
            }

            if (longitude == '') {
                arr.push('longitude_err');
                arr.push('Longitude required');
            }

            if (address == '') {
                arr.push('address_err');
                arr.push('Address required');
            }

            if (landmark == '') {
                arr.push('landmark_err');
                arr.push('Landmark required');
            }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                var geolocation = [latitude, longitude];
                console.log(geolocation);
                $.ajax({
                    type: 'POST',
                    url: "save_department",
                    data: {
                        department_name :dept_name,
                        short_name:short_name,
                        geolocation:geolocation,
                        address:address,
                        landmark:landmark
                    },
                    success: function(res) {
                        console.log(res);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.mag + '</span></div></div>');
                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                            office_address();
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.mag + '</span></div></div>');

                        }
                    },
                    error: function(data) {
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

        $(document).on('click', '#update', function() {
            $('.valid_err1').html('');
            var arr = [];
            var office_id = $('.office_id').val();
            var dept_name = $('.dept_name').val();
            var short_name = $('.short_name').val();
            var latitude = $('.latitude').val();
            var longitude = $('.longitude').val();
            var address = $('.address').val();
            var landmark = $('.landmark').val();
            if (dept_name == '') {
                arr.push('dept_name_err1');
                arr.push('Department name required');
            }

            if (short_name == '') {
                arr.push('short_name_err1');
                arr.push('Short Name required');
            }

            if (latitude == '') {
                arr.push('latitude_err1');
                arr.push('Latitude required');
            }

            if (longitude == '') {
                arr.push('longitude_err1');
                arr.push('Longitude required');
            }

            if (address == '') {
                arr.push('address_err1');
                arr.push('Address required');
            }

            if (landmark == '') {
                arr.push('landmark_err1');
                arr.push('Landmark required');
            }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                var geolocation = [latitude, longitude];
                $.ajax({
                    type: 'post',
                    url: "update_department",
                    data: {
                        office_id:office_id,
                        department_name :dept_name,
                        short_name:short_name,
                        geolocation:geolocation,
                        address:address,
                        landmark:landmark
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.status == 'success') {
                            $("#updateModal").modal("toggle");
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.mag + '</span></div></div>').focus();
                            office_address();
                        } else {
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );

                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.mag + '</span></div></div>').focus();
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                        }

                    },
                    error: function(data) {
                        console.log(data);
                        Swal.fire({
                            icon: "error",
                            title: 'Error!',
                            text: 'something went wrong. try again later',
                            confirmButtonClass: 'btn btn-danger',
                        })
                    }

                });
            }

        });
    });

    $(document).on('click', '.updateModal', function() {
        $('.office_id').val($(this).data('office_id'));
        $('.dept_name').val($(this).data('dept_name'));
        $(".short_name").val($(this).data("short_name"));
        $('.address').val($(this).data('address'));
        $('.latitude').val($(this).data('latitude'));
        $('.longitude').val($(this).data('longitude'));
        $('.landmark').val($(this).data('landmark'));
        $('#updateModal').modal('show');
    });


    function office_address() {
        $.ajax({
            type: "get",
            url: "office-address-list",
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