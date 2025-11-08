@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style type="text/css">
.nav>li.active>a>img.verticalTab {
    filter: grayscale(0%);
}

.expense-list-wrapper .dataTables_wrapper .top .action-filters .dataTables_filter label input {
    border: 0px;
    border-bottom: 2px solid #a3afbd;
    width: 95% !important;
}
.dt-buttons
{
    margin-left:22px !important;
}
</style>

{{-- page title --}}
@section('title','Expense Report')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/expense.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
@endsection

@section('content')
<!-- invoice list -->
<section class="expense-list-wrapper">

    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
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
            <form action="" class="ml-2">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <select class="form-control  staff" name="staff">
                                <option value="all">All</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach

                            </select>
                            <span class="valid_err by_whom_err"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 form-group">
                        <input type="text" class="form-control pickadate" name="from_date" id="from_date"
                            placeholder="From Date" autocomplete="off">
                    </div>
                    <div class="col-sm-12 col-md-3 form-group">
                        <input type="text" class="form-control pickadate" name="to_date" id="to_date"
                            placeholder="To Date" autocomplete="off">
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <button type="button" name="submit"
                            class="btn btn-sm btn-primary search_btn px-5"><strong>Search</strong></button>
                    </div>
                </div>
            </form>
            <div class="data_div">
                <div class="table-responsive">
                    <table class="table expense-data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Staff Name</th>
                                <th>Total Expenses</th>
                                <th>Total Reimbursement</th>
                                <th>Total Approved</th>
                                <th>Total Unapproved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $row)
                            <tr>
                                <td></td>
                                <td>{{$row->name}}</td>
                                <td>{{number_format($row->total_expense,2)}}</td>
                                <td>{{number_format($row->total_self,2)}}</td>
                                <td>{{number_format($row->total_approved,2)}}</td>
                                <td>{{number_format($row->total_unapproved,2)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
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
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')

<script type="text/javascript">
$(document).ready(function() {
    if ($(".pickadate").length) {
        $(".pickadate").pickadate({
            format: "dd/mm/yyyy",
        });
    }
    if ($(".expense-data-table").length) {
        var dataListView = $(".expense-data-table").DataTable({
            columnDefs: [{
                    //   targets: 0,
                    //   className: "control",
                },
                {
                    //   orderable: true,
                    //   targets: 1,
                    //   checkboxes: { selectRow: true },
                },
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
            order: [2, "asc"],
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
             buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            language: {
                search: "",
                searchPlaceholder: "Search",
            },
            select: {
                style: "multi",
                selector: "td:first-child",
                items: "row",
            },
            responsive: {
                details: {
                    type: "column",
                    target: 0,
                },
            },
        });
    }
});

$(document).on("click", ".search_btn", function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.data_div').empty();
    var staff = $('.staff').val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    if (staff == "") {
        $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select Staff First!</span></div></div>'
        );
        return false;
    }

    if (new Date(from_date) > new Date(to_date)) {
        $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select to date greater than from date!</span></div></div>'
        );

    }

    $.ajax({
        type: "post",
        url: "get_expenses_report",
        data: {
            staff: staff,
            from_date: from_date,
            to_date: to_date
        },

        success: function(data) {
            console.log(data);
            var res = JSON.parse(data);
            if (res.status == "success") {
                $(".data_div").empty().html(res.out);

                var dataListView = $(".expense-data-table").DataTable({
                    columnDefs: [{
                            // targets: 0,
                            // className: "control",
                        },
                        {
                            // orderable: true,
                            // targets: 1,
                            // checkboxes: {
                            //     selectRow: true,
                            // },
                        },
                        {
                            targets: [0, 1],
                            orderable: false,
                        },
                    ],
                    order: [2, "asc"],
                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                     buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    language: {
                        search: "",
                        searchPlaceholder: "Search",
                    },

                    select: {
                        style: "multi",
                        selector: "td:first-child",
                        items: "row",
                    },
                    responsive: {
                        details: {
                            type: "column",
                            target: 0,
                        },
                    },
                });

            }
        },
        error: function(data) {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0),
                },
                "slow"
            );

            $("#alert")
                .html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
                )
                .focus();
            $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
        },
    });
});
</script>
@endsection
@section('jquery')

@endsection