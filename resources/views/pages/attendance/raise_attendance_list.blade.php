@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Raised Attendance List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/wickedpicker/dist/wickedpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .hasWickedpicker {
        z-index: 1700 !important;
    }

    .modal-content.pop_up {
        border-radius: 20px;
        height: 55%;
        width: 90%;
    }

    .row.clearfix.cont {
        margin-top: 50px;
    }

    .btn.btn-icon.pop_btn {
        width: 20%;
        height: 100%;
        font-size: 20px;
        margin-left: 5px;
        border-radius: 8px;
    }

    .btn.btn-icon.cancel_btn {
        margin-left: 20px;

    }
</style>
@endsection

@section('content')

<section class="client-list-wrapper">

    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
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
    <div class="data_div">

    </div>

    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content pop_up">
                <div class="modal-body">
                    <center> <img class="att_img" width="150px" src="images\edit_raise_attendance.svg">
                        <h4 class="modal-title" id="uModalLabel"><b>Edit Attendance</b></h4>
                    </center>
                    <input type="hidden" class="form-control attend_id">
                    <input type="hidden" class="form-control staff_id">
                    <div class="row clearfix cont">
                        <div class="col-md-6">
                            <label style="font-size:15px;">Sign In Time</label>
                            <input type="text" class="form-control signin_time timepicker" placeholder="time" required>
                            <span class="valid_err signin_time_err"></span>
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:15px;">Sign Out Time</label>
                            <input type="text" class="form-control signout_time timepicker" placeholder="time" required>
                            <span class="valid_err signout_time_err"></span>
                        </div>
                    </div>
                    <div class="row clearfix cont">
                        <div class=" col-md-6">
                            <label style="font-size:15px;">Sign In Location (Latitude, Longitude)</label>
                            <input type="text" class="form-control signin_location" placeholder="location (Latitude, Longitude)">
                            <span class="valid_err signin_location_err"></span>
                        </div>

                        <div class="col-md-6">
                            <label style="font-size:15px;">Sign Out Location (Latitude, Longitude)</label>
                            <input type="text" class="form-control signout_location" placeholder="location (Latitude, Longitude)">
                            <span class="valid_err signout_location_err"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon pop_btn btn-primary edit_attendance_btn">Update</button>
                    <button type="button" class="btn btn-icon pop_btn btn-danger cancel_btn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
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
        get_attendance();

        // $(document).on("click", ".all_attendance_approve", function() {
        //     var status = $(this).data('status');
        //     var attendance_id = new Array();
        //     $(".dt-checkboxes:checked").each(function() {
        //         attendance_id.push(
        //             $(this).closest("tr").find(".attendance_id").val()
        //         );
        //     });

        //     if (attendance_id == "") {
        //         $("#alert").html(
        //             '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
        //         );
        //         return false;
        //     }
        //     $.ajax({
        //         type: "post",
        //         url: "attendance_status_update",
        //         data: {
        //             attendance_id: attendance_id,
        //             status: status
        //         },
        //         success: function(data) {
        //             console.log(data);
        //             console.log(data.status);
        //             $(".loader").css("display", "none");
        //             if (data.status == 'success') {
        //                 $("#alert")
        //                     .html(
        //                         '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
        //                         data.msg +
        //                         "</span></div></div>"
        //                     )
        //                     .focus();
        //                 get_attendance();
        //             }
        //         },
        //         error: function(data) {
        //             $(".loader").css("display", "none");
        //             $("#alert")
        //                 .html(
        //                     '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
        //                     data.msg +
        //                     "</span></div></div>"
        //                 )
        //                 .focus();
        //         },
        //     });
        // });

        // $(document).on("click", ".approve_btn", function() {
        //     var attendance_id = $(this).closest("tr").find(".attendance_id").val();
        //     var status = $(this).data('status');
        //     $.ajax({
        //         type: "post",
        //         url: "attendance_status_update",
        //         data: {
        //             attendance_id: attendance_id,
        //             status: status
        //         },
        //         success: function(data) {
        //             console.log(data);
        //             console.log(data.status);
        //             $(".loader").css("display", "none");
        //             if (data.status == 'success') {
        //                 $("#alert")
        //                     .html(
        //                         '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
        //                         data.msg +
        //                         "</span></div></div>"
        //                     )
        //                     .focus();
        //                 get_attendance();
        //             }
        //         },
        //         error: function(data) {
        //             $(".loader").css("display", "none");
        //             $("#alert")
        //                 .html(
        //                     '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
        //                     data.msg +
        //                     "</span></div></div>"
        //                 )
        //                 .focus();
        //         },
        //     });
        // });

        // $(document).on("click", ".all_attendance_delete", function() {
        //     var attendance_id = new Array();
        //     $(".dt-checkboxes:checked").each(function() {
        //         attendance_id.push(
        //             $(this).closest("tr").find(".attendance_id").val()
        //         );
        //     });
        //     if (attendance_id == "") {
        //         $("#alert").html(
        //             '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
        //         );
        //         return false;
        //     }
        //     Swal.fire({
        //         title: "Are you sure?",
        //         text: "You want to delete this Attendance?",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Yes",
        //         confirmButtonClass: "btn btn-warning",
        //         cancelButtonClass: "btn btn-danger ml-1",
        //         buttonsStyling: false,
        //     }).then(function(result) {
        //         if (result.value) {
        //             $.ajax({
        //                 type: "post",
        //                 url: "delete_attendance",
        //                 data: {
        //                     attendance_id: attendance_id,
        //                 },

        //                 success: function(data) {
        //                     console.log(data);
        //                     $("#alert").animate({
        //                             scrollTop: $(window).scrollTop(0)
        //                         },
        //                         "slow"
        //                     );
        //                     if (data.status == "success") {
        //                         $('#alert').html(
        //                             '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
        //                             data.msg + '</span></div></div>').focus();

        //                         get_attendance();

        //                     } else {
        //                         $("#alert").animate({
        //                                 scrollTop: $(window).scrollTop(0)
        //                             },
        //                             "slow"
        //                         );

        //                         $('#alert').html(
        //                             '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
        //                             data.msg + '</span></div></div>').focus();
        //                         $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        //                             $(".alert").slideUp(500);
        //                         });
        //                     }
        //                 },
        //                 error: function(data) {
        //                     $("#alert").animate({
        //                             scrollTop: $(window).scrollTop(0)
        //                         },
        //                         "slow"
        //                     );

        //                     $('#alert').html(
        //                         '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
        //                     ).focus();
        //                     setTimeout(function() {
        //                         location.reload();
        //                     }, 3000);
        //                 }
        //             });
        //         }
        //     });
        // });

        // $(document).on("click", ".delete_btn", function() {
        //     var attendance_id = $(this).closest("tr").find(".attendance_id").val();

        //     $.ajax({
        //         type: "post",
        //         url: "reject_attendance",
        //         data: {
        //             attendance_id: attendance_id,
        //         },

        //         success: function(data) {
        //             console.log(data);
        //             $("#alert").animate({
        //                     scrollTop: $(window).scrollTop(0)
        //                 },
        //                 "slow"
        //             );
        //             if (data.status == "success") {
        //                 $('#alert').html(
        //                     '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
        //                     data.msg + '</span></div></div>').focus();

        //                 get_attendance();
        //             } else {
        //                 $("#alert").animate({
        //                         scrollTop: $(window).scrollTop(0)
        //                     },
        //                     "slow"
        //                 );

        //                 $('#alert').html(
        //                     '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
        //                     data.msg + '</span></div></div>').focus();
        //                 $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        //                     $(".alert").slideUp(500);
        //                 });
        //             }
        //         },
        //         error: function(data) {
        //             $("#alert").animate({
        //                     scrollTop: $(window).scrollTop(0)
        //                 },
        //                 "slow"
        //             );

        //             $('#alert').html(
        //                 '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
        //             ).focus();
        //         }
        //     });


        // });

        $(document).on("click", ".reject_btn", function() {
            var attendance_id = $(this).closest("tr").find(".attendance_id").val();
            console.log(attendance_id);
            $.ajax({
                type: "post",
                url: "reject_attendance",
                data: {
                    id: attendance_id
                },
                success: function(data) {
                    console.log(data);
                    console.log(data.status);
                    $(".loader").css("display", "none");
                    if (data.status == 'success') {
                        $("#alert")
                            .html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                data.msg +
                                "</span></div></div>"
                            )
                            .focus();
                        get_attendance();
                    }
                },
                error: function(data) {
                    $(".loader").css("display", "none");
                    $("#alert")
                        .html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            data.msg +
                            "</span></div></div>"
                        )
                        .focus();
                },
            });
        });

        $(document).on("click", ".edit_attendance_btn", function() {
            var attendance_id = $(".attend_id").val();
            var staff_id = $(".staff_id").val();
            var signin_time = $(".signin_time").val();
            var signout_time = $(".signout_time").val();
            var signin_location = $(".signin_location").val();
            var signout_location = $(".signout_location").val();

            var arr = [];
            $(".valid_err").html("");
            if (signin_time == "") {
                arr.push("signin_time_err");
                arr.push("sign in time required");
            }
            if (signout_time == "") {
                arr.push("signout_time_err");
                arr.push("sign out time required");
            }
            if (signin_location == "") {
                arr.push("signout_time_err");
                arr.push("sign out time required");
            }
            if (signout_location == "") {
                arr.push("signout_time_err");
                arr.push("sign out time required");
            }

            if (arr != "") {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;

                    $("." + arr[i])
                        .html(arr[j])
                        .css("color", "red");

                    i = j;
                }
            } else {
                $.ajax({
                    type: "post",
                    url: "edit_raise_attendance",

                    data: {
                        attendance_id: attendance_id,
                        staff_id: staff_id,
                        signin_time: signin_time,
                        signout_time: signout_time,
                        signin_location: signin_location,
                        signout_location: signout_location
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        if (res.status == "success") {
                            console.log(data);
                            $("#attendanceModal").modal("toggle");
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            get_attendance();

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
                    },
                });
            }
        });

        $(document).on("click", ".edit_btn", function() {
            $(".attend_id").val($(this).data("attendance_id"));
            $(".staff_id").val($(this).data("staff_id"));
            $(".signin_time").val($(this).data("signin_time"));
            $(".signout_time").val($(this).data("signout_time"));
            $(".signin_location").val($(this).data("signin_location"));
            $(".signout_location").val($(this).data("signout_location"));
        });
    });

    function get_attendance() {
        $(".loader").css("display", "block");
        $.ajax({
            type: "get",
            url: "raise_attendance_table",

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