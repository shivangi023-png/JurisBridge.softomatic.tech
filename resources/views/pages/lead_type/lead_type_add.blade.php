@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Lead Type')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
@section('page-styles')
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
                    <form class="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="lead_type" name="lead_type" placeholder="Lead Type">
                                        <label for="lead_type">Lead Type</label>
                                        <span class="lead_type_err valid_err"></span>
                                    </div>
                                </div>
                            </div>
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

                        <!-- datatable start -->
                        <div class="table-responsive">
                            <table class="table staff-data-table wrap">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>id</th>
                                        <th>Lead Type</th>
                                    </tr>
                                </thead>
                                <tbody class="data_div">
                                    <?php $i = 1; ?>
                                    @foreach($lead_type as $val)
                                    <tr>
                                        <td><a href="#" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle glow btn-warning updateModal" data-tooltip="Edit" data-id="{{$val->id}}" data-type="{{$val->type}}"><i class="bx bx-edit"></i></a>
                                        <td>{{$i++}}</td>
                                        <td>{{$val->type}}</td>
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
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="width:900px" role="document">
            <div class="modal-content">
                <div class="modal-header bg-pink">
                    <h4 class="modal-title" id="uModalLabel">Edit Service</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="hidden" class="form-control type_id" name="type_id" value="">
                                    <input type="text" class="form-control lead_type" name="lead_type" placeholder="Lead type">
                                    <label for="lead_type">Lead Type</label>
                                    <span class="lead_type_err1 valid_err1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="update">Update
                        </button>
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

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var lead_type = $('#lead_type').val();

            if (lead_type == '') {
                arr.push('lead_type_err');
                arr.push('Lead Type required');
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
                    url: "lead_type_add",
                    data: {
                        lead_type: lead_type
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
                            get_lead_type_update();
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
            var id = $('.type_id').val();
            var lead_type = $('.lead_type').val();

            if (lead_type == '') {
                arr.push('lead_type_err1');
                arr.push('Lead type required');
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
                    url: 'lead_type_update',
                    data: {
                        id: id,
                        lead_type: lead_type
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: 'Updated!',
                                text: 'Service has been updated.',
                                confirmButtonClass: 'btn btn-success',
                            })
                            $("#updateModal").modal("hide");
                            get_lead_type_update();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: 'Error!',
                                text: 'Service can`t be updated.',
                                confirmButtonClass: 'btn btn-danger',
                            })

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
        $('.type_id').val($(this).data('id'));
        $('.lead_type').val($(this).data('type'));
        $('#updateModal').modal('show');
    });


    function get_lead_type_update() {
        $.ajax({
            type: "get",
            url: "get_lead_type_update",
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