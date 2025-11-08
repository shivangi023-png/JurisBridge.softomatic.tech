@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Assign Leads')
{{-- vendor style --}}
@section('vendor-styles')

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }

    .select2-search__field {
        width: 100% !important;
    }

    .action-btns {
        padding-top: 0px !important;
    }
</style>

@endsection

@section('content')
<!-- invoice list -->
<section class="client-list-wrapper">
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
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                @include('layouts.tabs')
                <center>
                    <div class="spinner-grow text-primary loader" role="status" style="display:none">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="loader" style="display:none">Please wait...</h5>
                </center>
                <div class="card-header">
                    <h4 class="card-title">Select Staff :</h4>
                </div>
                <div class="card-body pb-0 mx-25">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <div class="input-group">

                                    <select class="form-control" id="staff">
                                        <option value="">--Select Staff--</option>
                                        @foreach ($staff as $item)
                                        <option value={{$item->sid}}>
                                            {{$item->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="data_div">
                        <div class="table-responsive">
                            <table class="table client-data-table wrap">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Action</th>
                                        <th>Client Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Pincode</th>
                                        <th>Property Type</th>
                                        <th>No of Units</th>
                                        <th>Source</th>
                                        <th>Created By</th>
                                        <th>Assign To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="10" style="text-align:center;">No Data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="leadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" id="">

            <div class="modal-header">
                <h4 class="modal-title" id="">Assign Leads</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <input class="form-control client_id" type="hidden" value="">
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <select class="form-control staff_id">
                                    <option value="">--Select Staff--</option>
                                    @foreach ($staff as $item)
                                    <option value={{$item->sid}}>
                                        {{$item->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-icon btn-light-success assign_btn">Assign</button>
                <button type="button" class="btn btn-icon btn-light-danger " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
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
        $("#staff").select2({

            dropdownAutoWidth: true,
            width: '100%',
            placeholder: "Select Staff"
        });
    });

    $(document).on("change", "#staff", function() {
        var staff = $(this).val();

        get_assign_leads(staff);
    });

    $(document).on("click", ".assign_btn", function() {
        $('.loader').css('display', 'block');
        var clientid = $('.client_id').val();
        var staff = $('.staff_id').val();
        var selectedstaff = $('#staff').val();

        $.ajax({
            type: 'post',
            url: 'assign_leads',
            data: {
                staff: staff,
                clientid: clientid,
                selectedstaff: selectedstaff
            },

            success: function(data) {
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.loader').css('display', 'none');
                    $("#leadModal").modal("hide");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    );
                    get_assign_leads(selectedstaff);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });


    $(document).on('click', '.selectBtn', function() {
        var staffid = $(this).data('staffid');
        var clientid = $(this).data('clientid');
        $(".client_id ").val(clientid);
        $(".staff_id").val(staffid);
        $("#leadModal").modal("show");
    });

    $(document).on("click", ".all_assign", function() {
        var client_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            client_id.push(
                $(this).closest("tr").find(".clientID").val()
            );
        });

        if (client_id == "") {
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
            );
            return false;
        }
        $(".client_id").val(client_id);
        $("#leadModal").modal("show");
    });

    function get_assign_leads(staff) {
        $('.data_div').empty();
        $('.loader').css('display', 'block');
        $.ajax({
            type: 'post',
            url: 'get_assign_leads',
            data: {
                staff: staff
            },

            success: function(data) {
                console.log(data);
                $('.loader').css('display', 'none');
                $(".data_div").empty().html(data);
            },
            error: function(data) {
                var res = data;
                $('.loader').css('display', 'none');
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