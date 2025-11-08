@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style type="text/css">
    .nav>li.active>a>img.verticalTab {
        filter: grayscale(0%);
    }

    .dropdown-menu {
        z-index: 99999 !important;
    }
</style>

{{-- page title --}}
@section('title','Expense List')
{{-- vendor style --}}
@section('vendor-styles')

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/expense.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
@endsection

@section('content')
<style>
    .table-condensed {
        border-collapse: initial;
    }

    .datepicker-days {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker-years {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker thead {
        background-color: #e9edf1;
        color: #2454b1;
    }

    .dow {
        color: #2454b1;
    }
</style>
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
            <!-- Options and filter dropdown button-->
            <div class="data_div">
                <div class="action-dropdown-btn d-none">
                    <div class="dropdown expense-filter-action">
                        <button class="btn border dropdown-toggle mr-1" type="button" id="expense-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="selection">OPEN</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="expense-filter-btn">
                            <a type="button" class="dropdown-item active_btn" data-value="open">Open</a>
                            <a type="button" class="dropdown-item active_btn" data-value="approved">Approved</a>
                            <!-- <a type="button" class="dropdown-item active_btn" data-value="reimbursed">Reimbursed</a> -->

                        </div>
                    </div>
                    <div class="dropdown expense-options">
                        <button class="btn border dropdown-toggle mr-2" type="button" id="expense-options-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="expense-options-btn">
                            <a class="dropdown-item all_delete" href="javascript:void(0);">Delete</a>
                            <a class="dropdown-item all_expense_approve" href="javascript:void(0);">Approve</a>
                        </div>
                    </div>
                    <div class="dropdown expense-options">
                        <a href="{{asset('expenses_add')}}" class="expense-action-view mr-1">
                            <button type="button" id="create_expenses" class="btn mr-2 btn-primary">Add
                                Expenses</button>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table expense-data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Action</th>

                                <th>
                                    <span class="align-middle">Expenses#</span>
                                </th>
                                <th>Client</th>
                                <th>Entry By</th>
                                <th>Ledger</th>
                                <th>Amount</th>
                                <th>Bill</th>
                                <th>Date</th>
                                <th>Mode of payment</th>
                                <th>Reference No</th>
                                <th>Reimbursement</th>
                                @if(session('role_id')==1 || session('role_id')==3)
                                <th>Approval Date</th>
                                <th>Approval By</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expense as $row)
                            <tr>
                                <td></td>
                                <td></td>
                                <td><input type="hidden" class="form-control expense_id" value="{{$row->id}}"></td>
                                <td>
                                    <div class="row expense-action">
                                        @if($row->status!='approved')
                                        @if(session('role_id')==1 || session('role_id')==3)
                                        <div class="col-2 approve_div" style="display:none;">
                                            <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-success approve_btn mr-1" data-tooltip="Done"><i class="bx bx-check"></i></button>
                                        </div>
                                        <div class="col-2 close_approve_div" style="display:none;">
                                            <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-danger close_approve_btn mr-1" data-tooltip="Close"><i class="bx bx-window-close"></i></button>
                                        </div>
                                        <div class="col-2 create_approve_div">
                                            <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary mr-1 create_approve_btn" data-tooltip="Approve">
                                                <i class="bx bx-list-check"></i>
                                            </button>
                                        </div>
                                        @endif
                                        @else
                                        @if(session('role_id')==1 || session('role_id')==3)
                                        <div class="col-2 mr-1">
                                            <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary" data-tooltip="Approved" disabled>
                                                <i class="bx bx-list-check"></i>
                                            </button>
                                        </div>
                                        @endif
                                        @endif
                                        <div class="col-2">
                                            <a href="javascript:void(0);" class="btn btn-icon rounded-circle glow btn-secondary mr-1" data-tooltip="Generate Invoice">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        </div>

                                        @if($row->status!='approved')
                                        <div class="col-2">
                                            <a href="expenses_edit-{{$row->id}}" class="expense-action-edit cursor-pointer btn btn-icon rounded-circle glow btn-warning mr-1" id="update_expenses" data-tooltip="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        </div>
                                        <div class="col-2">
                                            <a href="javascript:void(0);" class="expense-action-edit cursor-pointer btn btn-icon rounded-circle glow btn-danger mr-3 delete_expenses" data-expense_id="{{$row->id}}" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <a href="javascript:void(0);">EXP-{{ $row->id }}</a>
                                </td>

                                <td id="client_id">{{ $row->client_case_no }}
                                </td>
                                <td>{{$row->entry_by}}</td>
                                <td>{{$row->sub_heads}}</td>
                                <td>{{number_format($row->amount,2)}}</td>
                                <td>
                                    @if($row->bill!="")
                                    <a href="{{$row->bill}}">View</a>
                                    @else
                                    @endif

                                </td>
                                <td>{{date('d-m-Y',strtotime($row->date))}}</td>

                                <td>{{$row->mode_of_payment}}</td>
                                <td>{{$row->ref_no}}</td>

                                <td>
                                    @if($row->self=='no')
                                    <span class="badge badge-pill badge-light-danger">{{$row->self}}</span>
                                    @else
                                    <span class="badge badge-pill badge-light-success">{{$row->self}}</span>
                                    @endif
                                </td>
                                @if(session('role_id')==1 || session('role_id')==3)
                                <td>
                                    <div class="apr_dt_data">
                                        @if($row->approve_date!='')
                                        {{date('d-m-Y',strtotime($row->approve_date))}}
                                        @endif
                                    </div>
                                    <div class="apr_dt_ui" style="display:none">
                                        <input type="text" class="form-control datepicker approve_date" placeholder="approve_date">
                                        <span class="valid_err approve_date_err"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="apr_by_data">{{$row->approved_by_name}}</div>
                                    <div class="apr_by_ui" style="display:none">

                                        <select class="form-control required approve_by" name="approve_by" style="width:100%">
                                            @foreach($staff as $stf)
                                            @if((session('role_id')==1 || session('role_id')==3) &&
                                            (session('user_id')==$stf->user_id))
                                            <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                            @endif
                                            @endforeach


                                            @if(session('role_id')==1 || session('role_id')==3)
                                            <option value="">---Select approve by---</option>
                                            @foreach($staff as $stf)
                                            <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="valid_err approve_by_err"></span>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="">

                <div class="modal-header">
                    <h4 class="modal-title" id="">Approve Expense</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12 my-1">
                            <label>Approval Date : </label>
                            <input type="hidden" class="form-control expense_id">
                            <input type="text" class="form-control all_approve_date datepicker" placeholder="Approve Date">
                            <span class="valid_errr all_approve_date_err"></span>
                        </div>

                        <div class="col-sm-12 mb-1">
                            <label for="">Approval By : </label>
                            <select class="form-control required all_approve_by" name="approve_by" style="width:100%">
                                @foreach($staff as $stf)
                                @if((session('role_id')==1 || session('role_id')==3) &&
                                (session('user_id')==$stf->user_id))
                                <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                @endif
                                @endforeach


                                @if(session('role_id')==1 || session('role_id')==3)
                                <option value="">---Select approve by---</option>
                                @foreach($staff as $stf)
                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="valid_errr all_approve_by_err"></span>
                        </div>
                        <div class="col-sm-12 mb-1">
                            <button type="button" class="btn btn-icon btn-primary  approve_expense_btn">Approve</button>
                            <button type="button" class="btn btn-icon btn-secondary " data-dismiss="modal">Close</button>
                        </div>
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
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/expense_list.js')}}"></script>
<script type="text/javascript">
    $(document).on("click", ".create_approve_btn", function() {
        $(this).closest("tr").find(".apr_dt_ui").css("display", "block");
        $(this).closest("tr").find(".apr_dt_data").css("display", "none");
        $(this).closest("tr").find(".apr_by_ui").css("display", "block");
        $(this).closest("tr").find(".apr_by_data").css("display", "none");
        $(this).closest("tr").find(".approve_div").css("display", "block");
        $(this).closest("tr").find(".close_approve_div").css("display", "block");
        $(this).closest("tr").find(".create_approve_div").css("display", "none");
    });

    $(document).on("click", ".close_approve_btn", function() {
        $(this).closest("tr").find(".apr_dt_ui").css("display", "none");
        $(this).closest("tr").find(".apr_dt_data").css("display", "block");
        $(this).closest("tr").find(".apr_by_ui").css("display", "none");
        $(this).closest("tr").find(".apr_by_data").css("display", "block");
        $(this).closest("tr").find(".approve_div").css("display", "none");
        $(this).closest("tr").find(".close_approve_div").css("display", "none");
        $(this).closest("tr").find(".create_approve_div").css("display", "block");
    });

    $(document).on("click", ".all_delete", function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var expense_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            expense_id.push(
                $(this).closest("tr").find(".expense_id").val()
            );
        });

        if (expense_id == "") {
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
            );
            return false;
        } else {
            var status = $('.selection').html();
            var value = $("#expense_filter").val();
            if (value) {
                filter = value;
            } else {
                filter = null;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this expense?",
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
                        url: "delete_expense",
                        data: {
                            expense_id: expense_id,
                            filter: filter
                        },

                        success: function(data) {
                            // $(".pickadate").pickadate({
                            //     format: "dd/mm/yyyy",
                            //     onStart: function () {
                            //         this.set({ select: new Date() });
                            //     },
                            //     });
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
                                let str = status;

                                $(".selection").html(str.toUpperCase());
                                var dataListView = $(".expense-data-table").DataTable({
                                    scrollX: true,
                                    scrollCollapse: true,
                                    autoWidth: true,
                                    columnDefs: [{
                                            width: "240px",
                                            targets: [3]
                                        },
                                        {
                                            targets: 0,
                                            className: "control",
                                        },
                                        {
                                            orderable: false,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            },
                                        },
                                        {
                                            targets: [0, 1, 2, 3, 4],
                                            orderable: false,
                                        },
                                    ],
                                    order: [5, "asc"],
                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Expense",
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row",
                                    },

                                });

                                // To append actions dropdown inside action-btn div
                                var expenseFilterAction = $(".expense-filter-action");
                                var expenseOptions = $(".expense-options");
                                $(".action-btns").append(expenseFilterAction,
                                    expenseOptions);
                                // add class in row if checkbox checked
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .on("change", function() {
                                        var $this = $(this);
                                        if ($this.is(":checked")) {
                                            $this.closest("tr").addClass("selected-row-bg");
                                        } else {
                                            $this.closest("tr").removeClass(
                                                "selected-row-bg");
                                        }
                                    });
                                // Select all checkbox
                                $(document).on("change", ".dt-checkboxes-select-all input",
                                    function() {
                                        if ($(this).is(":checked")) {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", this.checked)
                                                .closest("tr")
                                                .addClass("selected-row-bg");
                                        } else {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", "")
                                                .closest("tr")
                                                .removeClass("selected-row-bg");
                                        }
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
                        }
                    });
                }
            });
        }
    });

    $(document).on("click", ".approve_expense_btn", function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.valid_errr').html('');
        var expense_id = $(".expense_id").val();
        var approve_date = $(".all_approve_date").val();
        var approve_by = $(".all_approve_by").val();
        var status1 = $('.selection').html();
        var status = 'approved';
        var value = $("#expense_filter").val();
        if (value) {
            filter = value;
        } else {
            filter = null;
        }

        var arr = [];
        if (approve_date == "") {
            arr.push("all_approve_date_err");
            arr.push("Please select approve date");
        }
        if (approve_by == "") {
            arr.push("all_approve_by_err");
            arr.push("Please select approve by");
        }

        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;
                $('.' + arr[i]).html(arr[j]).css('color', 'red');
                i = j;
            }
        } else {
            $.ajax({
                type: "post",
                url: "approve_expense",
                data: {
                    id: expense_id,
                    approve_date: approve_date,
                    approve_by: approve_by,
                    status: status,
                    filter: filter
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
                        $("#approveModal").modal("toggle");
                        $(".data_div").empty().html(res.out);
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>').focus();

                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                        let str = status1;

                        $(".selection").html(str.toUpperCase());
                        var dataListView = $(".expense-data-table").DataTable({
                            scrollX: true,
                            scrollCollapse: true,
                            autoWidth: true,
                            columnDefs: [{
                                    width: "240px",
                                    targets: [3]
                                },
                                {
                                    targets: 0,
                                    className: "control",
                                },
                                {
                                    orderable: false,
                                    targets: 1,
                                    checkboxes: {
                                        selectRow: true
                                    },
                                },
                                {
                                    targets: [0, 1, 2, 3, 4],
                                    orderable: false,
                                },
                            ],
                            order: [5, "asc"],
                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search Expense",
                            },
                            select: {
                                style: "multi",
                                selector: "td:first-child",
                                items: "row",
                            },

                        });

                        // To append actions dropdown inside action-btn div
                        var expenseFilterAction = $(".expense-filter-action");
                        var expenseOptions = $(".expense-options");
                        // add class in row if checkbox checked
                        $(".dt-checkboxes-cell")
                            .find("input")
                            .on("change", function() {
                                var $this = $(this);
                                if ($this.is(":checked")) {
                                    $this.closest("tr").addClass("selected-row-bg");
                                } else {
                                    $this.closest("tr").removeClass("selected-row-bg");
                                }
                            });
                        // Select all checkbox
                        $(document).on("change", ".dt-checkboxes-select-all input", function() {
                            if ($(this).is(":checked")) {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", this.checked)
                                    .closest("tr")
                                    .addClass("selected-row-bg");
                            } else {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", "")
                                    .closest("tr")
                                    .removeClass("selected-row-bg");
                            }
                        });
                    } else {
                        $("#approveModal").modal("toggle");
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
        }
    });

    $(document).on("click", ".all_expense_approve", function() {

        var expense_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            expense_id.push(
                $(this).closest("tr").find(".expense_id").val()
            );
        });

        if (expense_id == "") {
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
            );
            return false;
        }

        $(".expense_id").val(expense_id);
        $("#approveModal").modal("show");
    });

    $(document).on("click", ".approve_btn", function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var value = $("#expense_filter").val();
        if (value) {
            filter = value;
        } else {
            filter = null;
        }
        var status1 = $('.selection').html();
        var status = "approved";
        var approve_date = $(this).closest("tr").find(".approve_date").val();
        var approve_by = $(this).closest("tr").find(".approve_by").val();

        var id = $(this).data("id");

        var arr = [];
        if (approve_date == "") {
            arr.push("approve_date_err");
            arr.push("Please select approve date");
        }
        if (approve_by == "") {
            arr.push("approve_by_err");
            arr.push("Please select approve by");
        }

        if (arr != "") {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;

                $(this)
                    .closest("tr")
                    .find("." + arr[i])
                    .html(arr[j])
                    .css("color", "red");

                i = j;
            }
        } else {
            var id = $(this).data("id");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this expense?",
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
                        url: "approve_expense",
                        data: {
                            status: status,
                            id: id,
                            approve_date: approve_date,
                            approve_by: approve_by,
                            filter: filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);

                            if (res.status == "success") {
                                $("#alert").animate({
                                        scrollTop: $(window).scrollTop(0)
                                    },
                                    "slow"
                                );
                                $('#alert').html(
                                    '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                    res.msg + '</span></div></div>').focus();

                                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                    $(".alert").slideUp(500);
                                });
                                $(".data_div").empty().html(res.out);
                                let str = status1;

                                $(".selection").html(str.toUpperCase());
                                var dataListView = $(".expense-data-table").DataTable({
                                    scrollX: true,
                                    scrollCollapse: true,
                                    autoWidth: true,
                                    columnDefs: [{
                                            width: "240px",
                                            targets: [3]
                                        },
                                        {
                                            targets: 0,
                                            className: "control",
                                        },
                                        {
                                            orderable: false,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            },
                                        },
                                        {
                                            targets: [0, 1, 2, 3, 4],
                                            orderable: false,
                                        },
                                    ],
                                    order: [5, "asc"],
                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Expense",
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row",
                                    },

                                });

                                // To append actions dropdown inside action-btn div
                                var expenseFilterAction = $(".expense-filter-action");
                                var expenseOptions = $(".expense-options");
                                $(".action-btns").append(expenseFilterAction, expenseOptions);
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .on("change", function() {
                                        var $this = $(this);
                                        if ($this.is(":checked")) {
                                            $this.closest("tr").addClass("selected-row-bg");
                                        } else {
                                            $this.closest("tr").removeClass(
                                                "selected-row-bg");
                                        }
                                    });
                                // Select all checkbox
                                $(document).on("change", ".dt-checkboxes-select-all input",
                                    function() {
                                        if ($(this).is(":checked")) {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", this.checked)
                                                .closest("tr")
                                                .addClass("selected-row-bg");
                                        } else {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", "")
                                                .closest("tr")
                                                .removeClass("selected-row-bg");
                                        }
                                    });
                                $('.datepicker').datepicker().on('changeDate', function(ev) {
                                    $('.datepicker.dropdown-menu').hide();
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
                    swal("Your payment not approved!");
                }
            });
        }
    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.active_btn', function() {
            $('.data_div').empty();
            $('.loader').css('display', 'block');
            var value = $(this).data('value');
            //console.log(value);
            $.ajax({
                type: 'post',
                url: 'get_filter_expense',
                data: {
                    value: value
                },

                success: function(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);
                    var res = JSON.parse(data);
                    if (res.status == 'success') {
                        $('.data_div').empty().html(res.out);
                        let str = value;

                        $(".selection").html(str.toUpperCase());
                        var dataListView = $(".expense-data-table").DataTable({
                            scrollX: true,
                            scrollCollapse: true,
                            autoWidth: true,
                            columnDefs: [{
                                    width: "240px",
                                    targets: [3]
                                },
                                {
                                    targets: 0,
                                    className: "control"
                                },
                                {
                                    orderable: false,
                                    targets: 1,
                                    checkboxes: {
                                        selectRow: true
                                    },
                                },
                                {
                                    targets: [0, 1, 2, 3, 4],
                                    orderable: false
                                },
                            ],
                            order: [5, 'asc'],
                            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            language: {
                                search: "",
                                searchPlaceholder: "Search Expense"
                            },

                            select: {
                                style: "multi",
                                selector: "td:first-child",
                                items: "row"
                            },
                        });

                        // To append actions dropdown inside action-btn div
                        var expenseFilterAction = $(".expense-filter-action");
                        var expenseOptions = $(".expense-options");
                        $(".action-btns").append(expenseFilterAction, expenseOptions);
                        // add class in row if checkbox checked
                        $(".dt-checkboxes-cell")
                            .find("input")
                            .on("change", function() {
                                var $this = $(this);
                                if ($this.is(":checked")) {
                                    $this.closest("tr").addClass("selected-row-bg");
                                } else {
                                    $this.closest("tr").removeClass("selected-row-bg");
                                }
                            });
                        // Select all checkbox
                        $(document).on("change", ".dt-checkboxes-select-all input", function() {
                            if ($(this).is(":checked")) {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", this.checked)
                                    .closest("tr")
                                    .addClass("selected-row-bg");
                            } else {
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .prop("checked", "")
                                    .closest("tr")
                                    .removeClass("selected-row-bg");
                            }
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
        });


        //copied code.....
        $(document).on("click", ".delete_expenses", function() {
            var id = $(this).data("expense_id");
            var status = $('.selection').html();
            var value = $("#expense_filter").val();
            if (value) {
                filter = value;
            } else {
                filter = null;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this expense?",
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
                        url: "delete_expense",
                        data: {
                            id: id,
                            filter: filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            if (res.status == "success") {
                                $(".data_div").empty().html(res.out);
                                $("#alert").animate({
                                        scrollTop: $(window).scrollTop(0)
                                    },
                                    "slow"
                                );

                                $('#alert').html(
                                    '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                    res.msg + '</span></div></div>').focus();
                                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                    $(".alert").slideUp(500);
                                });
                                let str = status;

                                $(".selection").html(str.toUpperCase());
                                $(".data_div").empty().html(res.out);

                                var dataListView = $(".expense-data-table").DataTable({
                                    scrollX: true,
                                    scrollCollapse: true,
                                    autoWidth: true,
                                    columnDefs: [{
                                            width: "240px",
                                            targets: [3]
                                        },
                                        {
                                            targets: 0,
                                            className: "control",
                                        },
                                        {
                                            orderable: false,
                                            targets: 1,
                                            checkboxes: {
                                                selectRow: true
                                            },
                                        },
                                        {
                                            targets: [0, 1, 2, 3, 4],
                                            orderable: false,
                                        },
                                    ],
                                    order: [5, "asc"],
                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                                    buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Expense",
                                    },
                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row",
                                    },

                                });

                                // To append actions dropdown inside action-btn div
                                var expenseFilterAction = $(".expense-filter-action");
                                var expenseOptions = $(".expense-options");
                                $(".action-btns").append(expenseFilterAction,
                                    expenseOptions);
                                // add class in row if checkbox checked
                                $(".dt-checkboxes-cell")
                                    .find("input")
                                    .on("change", function() {
                                        var $this = $(this);
                                        if ($this.is(":checked")) {
                                            $this.closest("tr").addClass(
                                                "selected-row-bg");
                                        } else {
                                            $this.closest("tr").removeClass(
                                                "selected-row-bg");
                                        }
                                    });
                                // Select all checkbox
                                $(document).on("change",
                                    ".dt-checkboxes-select-all input",
                                    function() {
                                        if ($(this).is(":checked")) {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", this.checked)
                                                .closest("tr")
                                                .addClass("selected-row-bg");
                                        } else {
                                            $(".dt-checkboxes-cell")
                                                .find("input")
                                                .prop("checked", "")
                                                .closest("tr")
                                                .removeClass("selected-row-bg");
                                        }
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
                            console.log(data);

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
                        }
                    });
                }
            });
        });

    });
    // function filterOpen(e){
    //   e.preventDefault();
    // console.log("inside filter function");
    // var status=$('#open').html();
    // // $(document).on('click','#open',function(){
    // // status="open";
    // // });
    // // $(document).on('click','#approved',function(){
    // // status='approved';
    // // });
    // console.log(status);
    // //
    //   $.ajax({
    //   type:'get',
    //   url:'filter',
    //   data: {status:status},
    //
    //   success:function(data){
    //       console.log("success");
    //       console.log(data);
    //
    //   },
    //   error:function(data){
    //       console.log("error");
    //       console.log(data);
    //
    //   }
    // });
    //
    //
    //
    //
    //
    //
    // }
</script>
@endsection
@section('jquery')

@endsection
<style>
    .test {
        align: center;
    }
</style>