@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Travelling Allowance Report')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/travelling_allowance.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
.valid_err {

    font-size: 12px;
}

.modal-lg {
    width: 1200px;
}

.row {
    margin-right: unset !important;
    margin-left: unset !important;
}

.dropdown-menu {
    z-index: 999999;
}

.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-control-placeholder {
    position: absolute;
    top: 0;
    padding: 7px 0 0 13px;
    transition: all 200ms;
    opacity: 0.5;
}

.form-control:focus+.form-control-placeholder,
.form-control:valid+.form-control-placeholder {
    font-size: 75%;
    transform: translate3d(0, -100%, 0);
    opacity: 1;
}


.ui-autocomplete {

    z-index: 9999 !important;

}

.allowance-list-wrapper .dataTables_wrapper .top .action-filters .dataTables_filter label input {
    border: 0px;
    border-bottom: 2px solid #a3afbd;
    width: 95% !important;
}
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->
<section class="allowance-list-wrapper">
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
            <form action="">
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
                            class="btn btn-primary btn-sm search_btn px-5"><strong>Search</strong></button>
                    </div>
                </div>
            </form>
            <div class="data_div">
                <div class="table-responsive">
                    <table class="table allowance-data-table">
                        <thead>
                            <th></th>
                            <th>Sr No.</th>
                            <th>Staff Name</th>
                            <th>Total Distance</th>
                        </thead>
                        <?php $i = 1; ?>
                        <tbody>
                            @foreach($travelling_allowance as $row)
                            <tr>
                                <td></td>
                                <td>{{$i++}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->total_distance}}</td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
$(document).ready(function() {
    if ($(".pickadate").length) {
        $(".pickadate").pickadate({
            format: "dd/mm/yyyy",
        });
    }

    if ($(".allowance-data-table").length) {
        var dataListView = $(".allowance-data-table").DataTable({
            columnDefs: [{
                    // targets: 0,
                    // className: "control",
                },
                {
                    // orderable: true,
                    // targets: 1,
                    // checkboxes: {
                    //     selectRow: true
                    // },
                },
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
            //order: [2, "asc"],
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
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
        url: "get_travelling_allowance_report",
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

                var dataListView = $(".allowance-data-table").DataTable({
                    columnDefs: [{
                            // targets: 0,
                            // className: "control",
                        },
                        {
                            // orderable: true,
                            // targets: 1,
                            // checkboxes: {
                            //     selectRow: true
                            // },
                        },
                        {
                            targets: [0, 1],
                            orderable: false,
                        },
                    ],
                    //order: [2, "asc"],
                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
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

                // To append actions dropdown inside action-btn div
                var allowanceFilterAction = $(".allowance-filter-action");
                var allowanceOptions = $(".allowance-options");
                $(".action-btns").append(allowanceFilterAction,
                    allowanceOptions);
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