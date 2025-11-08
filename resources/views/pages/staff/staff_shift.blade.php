@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Staff Shift Time')
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
                    <form class="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label style="font-size:15px;">Select Staff</label>
                                        <div class="input-group">
                                            <select class="form-control" name="staff" id="staff_id">
                                                <option value="">Select Staff</option>
                                                @foreach ($staff as $item)
                                                <option value="{{$item->sid}}">
                                                    {{$item->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="staff_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-size:15px;">From Time</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control timepicker" id="from_time" name="from_time" placeholder="From Time">
                                        <span class="valid_err from_time_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label style="font-size:15px;">To Time</label>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control timepicker" id="to_time" name="to_time" placeholder="To Time">
                                        <span class="valid_err to_time_err"></span>
                                    </div>
                                </div>
                                <div class="col-3"></div>
                                <div class="col-3">
                                    <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="staff_shift-wrapper">
                <div class="card">
                    <div class="card-body">
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
                        <input type="hidden" class="form-control staff_shift_id">
                        <input type="hidden" class="form-control staff_id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control from_time timepicker" name="from_time" placeholder="time">
                                    <span class="valid_err1 from_time_err1"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control to_time timepicker" name="to_time" placeholder="time">
                                    <span class="valid_err1 to_time_err1"></span>
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
        get_staff_shift();
        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var staff_id = $('#staff_id').val();
            var from_time = $('#from_time').val();
            var to_time = $('#to_time').val();

            if (staff_id == '') {
                arr.push('staff_err');
                arr.push('Staff name required');
            }

            if (from_time == '') {
                arr.push('from_time_err');
                arr.push('time required');
            }

            if (to_time == '') {
                arr.push('to_time_err');
                arr.push('time required');
            }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'POST',
                    url: "staff_shift",
                    data: {
                        staff_id: staff_id,
                        from_time: from_time,
                        to_time: to_time
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
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                            get_staff_shift();
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
            var staff_shift_id = $('.staff_shift_id').val();
            var staff_id = $(".staff_id").val();
            var from_time = $('.from_time').val();
            var to_time = $('.to_time').val();
            if (from_time == '') {
                arr.push('from_time_err1');
                arr.push('time required');
            }
            if (to_time == '') {
                arr.push('to_time_err1');
                arr.push('time required');
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
                    url: 'update_staff_shift',
                    data: {
                        id: staff_shift_id,
                        staff_id: staff_id,
                        from_time: from_time,
                        to_time: to_time

                    },
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            console.log(data);
                            $("#updateModal").modal("toggle");
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            get_staff_shift();
                        } else {
                            console.log(data);
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
        $('.staff_shift_id').val($(this).data('id'));
        $(".staff_id").val($(this).data("staff_id"));
        $('.from_time').val($(this).data('from_time'));
        $('.to_time').val($(this).data('to_time'));
        $('#updateModal').modal('show');
    });


    function get_staff_shift() {
        $.ajax({
            type: "get",
            url: "get_staff_shift",
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