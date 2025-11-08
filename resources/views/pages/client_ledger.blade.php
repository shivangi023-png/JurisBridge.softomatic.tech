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

    <!-- create client button-->
    <!-- <div class="client-create-btn mb-1">
    <a href="{{asset('app/client/add')}}" class="btn btn-primary glow client-create" role="button" aria-pressed="true">Create
      client</a>
  </div> -->
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
            <div class="data_div">

               
                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                @if(session('role_id')==1 || session('role_id')==3)
                                <th>Finalized</th>
                                <th>Future Invoices</th>
                                <th>Invoices Raised</th>
                                <th>Payment</th>
                                <th>Additional Invoices</th>
                                <th>Payment</th>
                                <th>Dues</th>
                                <th>Unapproved Payment</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <?php $total_dues = 0;
                            $total_unapproved = 0;
                            ?>
                            @foreach($client_list as $row)
                            <?php

                            $total_dues += $row->due_amt;
                            $total_unapproved += $row->unapproved_payment;
                            ?>
                            <tr>
                                <td style="white-space: nowrap;">
                                    <div class="client-action">
                                        <a href="client_edit-{{$row->id}}"
                                            class="client-action-edit btn btn-icon rounded-circle btn-warning glow mr-1 mb-1"
                                            data-tooltip="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1 delete_client"
                                            data-id="{{$row->id}}" data-tooltip="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                                <td><span class="client-customer">{{ $row->client_case_no }}
                                        </span></td>
                                @if(session('role_id')==1 || session('role_id')==3)
                                <td>{{number_format($row->finalize_quotation,2)}}</td>
                                <td>{{number_format($row->future_invoices,2)}}</td>
                                <td>{{number_format($row->bill_on_quotation,2)}}</td>
                                <td>{{number_format($row->payment_on_quo,2)}}</td>
                                <td>{{number_format($row->additional_bill,2)}}</td>
                                <td>{{number_format($row->payment_on_add,2)}}</td>
                                <td>{{number_format($row->due_amt,2)}}</td>
                                <td>{{number_format($row->unapproved_payment,2)}}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><br>

                <div class="row">
                    <div class="col-12 col-md-2 pt-0 mx-25">

                        Total Dues: <span><b>{{number_format($total_dues,2)}}</b></span>
                    </div>


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
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')

<script>
$(document).ready(function() {
$(".client-data-table").DataTable({
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
                        searchPlaceholder: "Search Clients"
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
                let str = value;

                $(".selection").html(str.toUpperCase());
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
                        searchPlaceholder: "Search Client"
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

               
            }
        });
    });
    $(document).on('click', '.delete_client', function() {
        var id = $(this).data('id');
        var status = $('.selection').html();
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
                            let str = value;

                            $(".selection").html(str.toUpperCase());
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
                                    searchPlaceholder: "Search Client"
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
});
</script>
@endsection