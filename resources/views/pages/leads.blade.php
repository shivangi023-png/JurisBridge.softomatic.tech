@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Clients Info')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>


</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
    <div id="alert"></div>
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
            <div class="data_div">

                <!-- Options and filter dropdown button-->
                <div class="action-dropdown-btn d-none">
                    <div class="dropdown client-filter-action">
                        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="selection">Active</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
                            <a type="button" href="#" class="dropdown-item filter_btn" data-value="active" data-selection_val="Active">Active</a>
                            <a type="button" href="#" class="dropdown-item filter_btn" data-value="inactive" data-selection_val="In Active">In Active</a>

                        </div>
                    </div>
                    <!-- <button type="button" class="btn mr-1 mb-1 btn-primary">Add Client</button> -->
                    <div class="client-options">
                        <a href="client_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                            <i class="bx bx-plus"></i>Add Lead</a>

                    </div>
                </div <div class="table-responsive">
                <table class="table client-data-table wrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>No of units</th>
                            <!-- <th>area</th> -->
                            <th>Property type</th>
                            <th>Quotations</th>
                            <th>Follow-up</th>
                            <th>Appointments</th>
                            <th>Source</th>
                            <th>Remarks</th>

                            <th>Address</th>
                            <th>City</th>
                            <th>Pincode</th>



                        </tr>
                    </thead>

                    <tbody>
                        @foreach($client_list as $row)
                        <tr>
                            <td style="white-space: nowrap;">
                                <div class="client-action">
                                    <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    @if(session('role_id')==1 || session('role_id')==3)
                                    <a href="#" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
                                    <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-secondary mr-1 mb-1 convert_client" data-tooltip="Convert to client">
                                        <i class="bx bx-transfer"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            <td><span class="client-customer">{{ $row->client_case_no }}</span>
                            </td>
                            <td>{{$row->no_of_units}}</td>
                            <!-- <td>{{$row->area}}</td> -->
                            <td>{{$row->property_type_name}}</td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="quotation" data-toggle="modal" data-target="#detailModal">{{$row->quotations}}</a></td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="followup" data-toggle="modal" data-target="#detailModal">{{$row->followups}}</a></td>
                            <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="appointment" data-toggle="modal" data-target="#detailModal">{{$row->appointments}}</a></td>
                            <td>{{$row->source_name}}</td>
                            <td><small>{{$row->remarks}}</small></td>
                            <td><small>{{$row->address}}</small></td>
                            <td>{{$row->city_name}}</td>

                            <td>{{$row->pincode}}</td>



                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>
    </div>
    </div>
</section>
<!--Basic Modal -->
<div class="modal fade text-left" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title detailModal-title" id="myModalLabel1"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body detailModal-body">

            </div>

        </div>
    </div>
</div>
</div>
</div>
</div>
<!---end-->
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
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/leads.js')}}"></script>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.filter_btn', function() {
            $('.data_div').empty();
            $('.loader').css('display', 'block');
            var value = $(this).data('value');
            var client_leads = 'lead'
            var selection_val = $(this).data('selection_val');

            $.ajax({
                type: 'post',
                url: 'filter_client',
                data: {
                    value: value,
                    client_leads: client_leads,
                    page: 'leads',
                    selection_val: selection_val
                },

                success: function(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);
                    var res = data;

                    $('.data_div').empty().html(res.out);

                    var dataListView = $(".client-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 0,
                                // checkboxes: { selectRow: true }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],
                        order: [2, 'asc'],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Leads"
                        },

                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row"
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0
                            },


                        },


                    });

                    // To append actions dropdown inside action-btn div
                    var clientFilterAction = $(".client-filter-action");
                    var clientOptions = $(".client-options");
                    $(".action-btns").append(clientFilterAction, clientOptions);
                }
            });
        });
        $(document).on('click', '.active_btn', function() {
            $('.data_div').empty();
            $('.loader').css('display', 'block');
            var value = $(this).data('value');
            $.ajax({
                type: 'post',
                url: 'get_active_client',
                data: {
                    value: value
                },

                success: function(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);
                    var res = JSON.parse(data);
                    $('.data_div').empty().html(res.out);

                    var dataListView = $(".client-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 0,
                                // checkboxes: { selectRow: true }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],
                        order: [2, 'asc'],
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Leads"
                        },

                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row"
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0
                            },


                        },


                    });

                    // To append actions dropdown inside action-btn div
                    var clientFilterAction = $(".client-filter-action");
                    var clientOptions = $(".client-options");
                    $(".action-btns").append(clientFilterAction, clientOptions);
                }
            });
        });
        $(document).on('click', '.delete_client', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this client",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'post',
                        url: 'delete_client',
                        data: {
                            id: id
                        },

                        success: function(data) {

                            console.log(data);
                            var res = JSON.parse(data);
                            if (res.status == 'success') {
                                Swal.fire({
                                    icon: "success",
                                    title: 'Deleted!',
                                    text: 'Client has been deleted.',
                                    confirmButtonClass: 'btn btn-success',
                                })
                                $('.data_div').empty().html(res.out);

                                var dataListView = $(".client-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control"
                                        },
                                        {
                                            orderable: true,
                                            targets: 0,
                                            // checkboxes: { selectRow: true }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false
                                        },
                                    ],
                                    order: [2, 'asc'],
                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Leads"
                                    },

                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row"
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0
                                        },


                                    },


                                });

                                // To append actions dropdown inside action-btn div
                                var clientFilterAction = $(".client-filter-action");
                                var clientOptions = $(".client-options");
                                $(".action-btns").append(clientFilterAction,
                                    clientOptions);

                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: 'Error!',
                                    text: 'Client can`t be deleted.',
                                    confirmButtonClass: 'btn btn-danger',
                                })
                            }
                        }
                    });
                }
            });
        });
        $(document).on('click', '.convert_client', function() {
            var client_id = $(this).data('client_id');
            $.ajax({
                type: 'post',
                url: 'convert_client',
                data: {
                    client_id: client_id
                },

                success: function(data) {

                    console.log(data);
                    var res = data;
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    if (res.status == 'success') {
                        $('.data_div').empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();

                        var dataListView = $(".client-data-table").DataTable({

                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search Leads"
                            },

                            select: {
                                style: "multi",
                                selector: "td:first-child",
                                items: "row"
                            },
                            responsive: {
                                details: {
                                    type: "column",
                                    target: 0
                                },


                            },


                        });

                        // To append actions dropdown inside action-btn div
                        var clientFilterAction = $(".client-filter-action");
                        var clientOptions = $(".client-options");
                        $(".action-btns").append(clientFilterAction, clientOptions);


                    }
                },
                error: function(data) {
                    var res = data;
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>').focus();
                }

            });
        });
        $(document).on('click', '.detailBtn', function() {

            var client_id = $(this).data('client_id');
            var detail = $(this).data('detail');
            $.ajax({
                type: 'post',
                url: 'get_quo_appo_foll',
                data: {
                    client_id: client_id,
                    detail: detail,
                },

                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('.detailModal-title').empty().html(detail);
                    $('.detailModal-body').empty().html(res.out);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    });
</script>
@endsection