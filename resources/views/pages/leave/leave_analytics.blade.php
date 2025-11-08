@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Leave Analytics')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-staff.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {

        font-size: 12px;
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
            <div id="alert">


            </div>
            <div class="card">
                <div class="card-body">
                    @include('layouts.tabs')
                    <form class="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <label for="">Select Staff</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="staff_id" id="staff_id" class="form-control">
                                                    <option value="">Select Staff</option>
                                                    @foreach($staff as $val)
                                                    <option value="{{$val->sid}}">{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="staff_id_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <label for="">Month</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select class="form-control" id="monthFilter">

                                                </select>
                                            </div>
                                            <span class="month_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <label for="">Session</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select id="yearFilter" class="form-control">
                                                </select>
                                            </div>
                                            <span class="year_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <?php $i = 1; ?>
                            @foreach($leave_type as $val)
                            <div class="row">
                                <div class="col-1">( {{$i++}} )</div>
                                <div class="col-3">
                                    <strong>{{$val->type}}</strong>
                                    <input type="hidden" class="leave_type" name="leave_type[]" value="{{$val->id}}">
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="number" class="form-control total_leaves" id="total_leaves" name="total_leaves[]" value="0">
                                        <label for="total_leaves">Total Leaves</label>
                                        <span class="total_leaves_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="number" class="form-control available_leaves" id="available_leaves" name="available_leaves[]" value="0">
                                        <label for="available_leaves">Available Leaves</label>
                                        <span class="available_leaves_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                            <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="staff-list-wrapper">
                <div class="card">
                    <div class="card-body">
                        <center>
                            <div class="spinner-grow text-primary loader" role="status" style="display:none">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h5 class="loader" style="display:none">Please wait...</h5>
                        </center>
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
                    <h4 class="modal-title" id="uModalLabel">Edit Leave</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <h4><strong class="staff_name"></strong></h4>
                                <input type="hidden" class="form-control" id="leave_id" name="leave_id">
                                <input type="hidden" class="form-control staff_id" name="staff_id">
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <label for="">Leave Type</label>
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select name="leave_type" class="form-control eleave_type">
                                                <option value="">Leave Type</option>
                                                @foreach($leave_type as $val)
                                                <option value="{{$val->id}}">{{$val->type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="update_leave_type_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" class="form-control etotal_leaves" name="total_leaves" placeholder="Total Leaves" autocomplete="off">
                                    <label for="total_leaves">Total Leaves</label>
                                    <span class="update_total_leaves_err valid_err"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" class="form-control eavailable_leaves" name="available_leaves" placeholder="Available Leaves" autocomplete="off">
                                    <label for="available_leaves">Available Leaves</label>
                                    <span class="update_available_leaves_err valid_err"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <label for="">Month</label>
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select class="form-control" id="update_monthFilter">

                                            </select>
                                        </div>
                                        <span class="update_month_err valid_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <label for="">Year</label>
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <select id="update_yearFilter" class="form-control">
                                            </select>
                                        </div>
                                        <span class="update_year_err valid_err"></span>
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
    </div>
</section>

@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/staff.js')}}"></script>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     var currentYear = new Date().getFullYear();
    //     var startYear = currentYear;
    //     for (var i = 0; i < 5; i++) {
    //         var financialYear = startYear + '-' + (startYear + 1);
    //         var option = document.createElement('option');
    //         option.text = financialYear;
    //         option.value = financialYear;
    //         document.getElementById('session').appendChild(option);
    //         startYear++;
    //     }
    // });

    $(document).ready(function() {
        get_month('monthFilter');
        get_year('yearFilter');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        get_staff_leave();

        $("#staff_id").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: "Select Staff",
        });
        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var staff_id = $('#staff_id').val();
            var month = $('#monthFilter').val();
            var year = $('#yearFilter').val();
            var leave_type = [];
            var total_leaves = [];
            var available_leaves = [];
            var validationError = false;

            $('.leave_type').each(function() {
                leave_type.push($(this).val());
            });
            $('.total_leaves').each(function() {
                total_leaves.push($(this).val());
            });
            $('.available_leaves').each(function() {
                available_leaves.push($(this).val());
            });

            if (staff_id == '') {
                arr.push('staff_id_err');
                arr.push('Staff Name required');
            }
            if (month == '') {
                arr.push('month_err');
                arr.push('Month required');
            }
            if (year == '') {
                arr.push('year_err');
                arr.push('Year required');
            }
            for (var i = 0; i < total_leaves.length; i++) {
                console.log(total_leaves.length);
                if (total_leaves[i] == '0' && available_leaves[i] > '0') {
                    console.log(available_leaves[i]);
                    validationError = true;
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Total leaves required when available leaves are greater than 0</span></div></div>'
                    );
                    return false;
                }

                if (parseInt(available_leaves[i]) > parseInt(total_leaves[i])) {
                    validationError = true;
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Available leaves cannot be greater than total leaves</span></div></div>'
                    );
                    return false;
                }
            }
            if (arr.length > 0) {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                var allTotalLeavesZero = total_leaves.every(function(value) {
                    return value == '0';
                });
                var allAvailableLeavesZero = available_leaves.every(function(value) {
                    return value == '0';
                });

                if (allTotalLeavesZero && allAvailableLeavesZero) {
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please fill in total leaves and available leaves count!</span></div></div>'
                    );
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "add_leave_analytics",
                        data: {
                            staff_id: staff_id,
                            month: month,
                            year: year,
                            leave_type: leave_type,
                            total_leaves: total_leaves,
                            available_leaves: available_leaves,
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
                                $('#staff_id').val(null).trigger('change');
                                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                    $(".alert").slideUp(500);
                                });
                                get_staff_leave();
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
            }
        });


        $(document).on('click', '.updateModal', function() {
            $('#leave_id').val($(this).data('leave_id'));
            $('.staff_name').text($(this).data('staff_name'));
            $('.staff_id').val($(this).data('staff_id'));
            $('.eleave_type').val($(this).data('leave_type'));
            $('.etotal_leaves').val($(this).data('total_leaves'));
            $('.eavailable_leaves').val($(this).data('available_leaves'));
            $('#update_monthFilter').val($(this).data('month'));
            $('#update_yearFilter').val($(this).data('year'));
            get_month('update_monthFilter');
            get_year('update_yearFilter');

            $('#updateModal').modal('show');
        });

        $(document).on('click', '#update', function() {
            $('.valid_err').html('');
            var arr = [];
            var leave_id = $('#leave_id').val();
            var staff_id = $('.staff_id').val();
            var leave_type = $('.eleave_type').val();
            var total_leaves = $('.etotal_leaves').val();
            var available_leaves = $('.eavailable_leaves').val();
            var month = $('#update_monthFilter').val();
            var year = $('#update_yearFilter').val();

            // if (staff_id == '') {
            //     arr.push('staff_id_err');
            //     arr.push('Staff name required');
            // }
            if (leave_type == '') {
                arr.push('leave_type_err');
                arr.push('Leave Type required');
            }
            if (total_leaves == '') {
                arr.push('update_total_leaves_err');
                arr.push('Total Leaves required');
            }
            if (available_leaves == '') {
                arr.push('update_available_leaves_err');
                arr.push('Available Leaves required');
            }

            if (total_leaves != '' && available_leaves != '') {
                if (parseInt(total_leaves, 10) < parseInt(available_leaves, 10)) {
                    arr.push('update_available_leaves_err');
                    arr.push('Available Leaves can not be more than Total Leaves');
                }
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
                    url: "update_leave_analytics",
                    data: {
                        leave_id: leave_id,
                        staff_id: staff_id,
                        leave_type: leave_type,
                        total_leaves: total_leaves,
                        available_leaves: available_leaves,
                        month: month,
                        year: year
                    },
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');

                            $(".alert").fadeTo(2000, 800).slideUp(800, function() {
                                $(".alert").slideUp(800);
                            });
                            $("#updateModal").modal("hide");
                            get_staff_leave();
                        } else {
                            $("#alert").animate({
                                    scrollTop: $(window).scrollTop(0)
                                },
                                "slow"
                            );
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');
                            $(".alert").fadeTo(2000, 800).slideUp(800, function() {
                                $(".alert").slideUp(800);
                            });
                            $("#updateModal").modal("hide");
                            get_staff_leave();
                        }
                    },
                    error: function(data) {
                        console.log(data);
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
    });

    function get_month(monthFilter) {
        var currentMonthIndex = new Date().getMonth();
        var months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        for (var i = 0; i < months.length; i++) {
            var option = $('<option>', {
                value: i + 1,
                text: months[i]
            });
            if (i === currentMonthIndex) {
                option.attr('selected', 'selected');
            }

            $('#' + monthFilter).append(option);
        }
    }

    function get_year(yearFilter) {
        var currentYear = new Date().getFullYear();
        for (var i = 0; i < 3; i++) {
            var year = currentYear + i;
            var option = $('<option>', {
                value: year,
                text: year
            });
            if (year === currentYear) {
                option.attr('selected', 'selected');
            }
            $('#' + yearFilter).append(option);
        }
    }

    function get_staff_leave() {
        $(".loader").css("display", "block");
        $.ajax({
            type: "get",
            url: "get_staff_leave",
            datatype: "text",

            success: function(data) {
                console.log(data);
                $(".loader").css("display", "none");
                $(".data_div").html(data);
            },
            error: function(data) {
                $(".loader").css("display", "none");
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