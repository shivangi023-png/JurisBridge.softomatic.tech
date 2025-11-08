@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- page title --}}
@section('title','Invoice List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>
@endsection
@section('content')
<!-- invoice list -->
<section class="invoice-list-wrapper">
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
    <div id="alert">
    </div>
    <div class="data_div">
        <div class="action-dropdown-btn d-none">
            <!-- <div class="dropdown invoice-filter-action">
                <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="selection">Filter Invoice</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                    <a class="dropdown-item statusbtn" data-value="partial">Partial Payment</a>
                    <a class="dropdown-item statusbtn" data-value="unpaid">Unpaid</a>
                    <a class="dropdown-item statusbtn" data-value="paid">Paid</a>
                </div>
            </div> -->
            <div class="dropdown invoice-options">

                <a href="invoice_add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
                    <i class="bx bx-plus"></i>Add Invoice</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body card-dashboard">
                <div class="table-responsive">
                    <table class="table invoice-data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>
                                    <span class="align-middle">Invoice#</span>
                                </th>
                                <th>Action</th>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Bill Date</th>
                                <th>Due Date</th>
                                <th>Seal</th>
                                <th>Sign</th>
                </div>
                </tr>
                </thead>
                <tbody id="invoice_table">
                    @foreach($data as $row)
                    <?php  
                        $sess = '';
                        $prev_yr=substr (date('Y')-1,-2);
                        $cur_yr=substr (date('Y'),2);
                        $nxt_yr=substr (date('Y')+1,-2);
                        if(date('m')<=3)
                        {
                           $sess=$prev_yr.'-'.$cur_yr;
                        }
                        else
                        {
                           $sess=$cur_yr.'-'.$nxt_yr;
                        }
                    // $invoice_no=session('short_code'). '/' .$sess.'/'.str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT);
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                             @if(session('header_footer')=='no')
                        <a href="generate_invoice-{{$row->id}}-proforma">{{session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date))}}</a>
                        @else
                        <a href="generate_invoice_UT-{{$row->id}}-proforma">{{session('short_code'). '-' . str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT) . '/' . date('Y',strtotime($row->bill_date))}}</a>
                        @endif  
                        </td>
                        <td>
                            <div class="invoice-action">
                                @if(session('header_footer')=='no')
                                <a href="generate_invoice-{{$row->id}}-proforma" class="invoice-action-view btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-invoice_id="{{$row->id}}" data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                </a>
                                @else
                                <a href="generate_invoice_UT-{{$row->id}}-proforma" class="invoice-action-view btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-invoice_id="{{$row->id}}" data-tooltip="Generate Invoice">
                                    <i class="bx bx-printer"></i>
                                </a>
                                @endif
                                <a href="proforma_edit-{{$row->id}}" class="invoice-action-edit btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 " data-id="{{$row->id}}" data-tooltip="Edit">
                                    <i class="bx bx-edit"></i>
                                    <a class="btn btn-icon rounded-circle btn-info mr-1 mb-1 delete_invoice glow" data-id="{{$row->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash-alt"></i>
                                    </a>
           
                                    @if($row->convert_tax == 'no')
                                     <a class="convert_to_tax_invoice btn btn-icon rounded-circle btn-dark-blue glow mr-1 mb-1" data-id="{{$row->id}}" data-tooltip="Convert to Tax Invoice"
                                        data-toggle="modal" data-target="#convert_taxinvoice_Modal"  data-bill_date="{{$row->bill_date}}" data-due_date="{{$row->due_date}}">
                                        <i class="bx bxs-analyse"></i>
                                    </a>
                                    @endif
                            </div>
                        </td>
                        <td><span class="invoice-customer">{{ $row->client_case_no }}
                            </span></td>
                        <td>
                            <small class="text-muted"><?php echo nl2br($row->service) ?></small>
                        </td>
                        <td><span class="invoice-amount">&#8377;{{number_format($row->total_amount,2)}}</span></td>
                        @if($row->status == 'unpaid')
                        <td><span class="badge badge-light-danger badge-pill">{{ $row->status }}</span></td>
                        @elseif($row->status == 'paid')
                        <td><span class="badge badge-light-success badge-pill">{{ $row->status }}</span></td>
                        @else
                        <td><span class="badge badge-light-warning badge-pill">{{ $row->status }}</span></td>
                        @endif
                        <td data-sort="{{strtotime($row->bill_date)}}">{{ date('d-m-Y',strtotime($row->bill_date))}}
                        </td>
                        <td>{{ date('d-m-Y',strtotime($row->due_date))}}</td>
                        <td>{{ $row->seal}}</td>
                        <td>{{ $row->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
       </div>
    </div>

 <div class="modal fade text-left" id="convert_taxinvoice_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Convert to Tax Invoice</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        <div class="modal-body">
             <form method="POST" id="convert_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="invoice_id" id=invoice_id>
                 <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <label for="bill_date">Bill Date</label>
                           <fieldset class="d-flex ">

                            <input type="text" class="form-control pickadate1 mr-2 mb-50 mb-sm-0 bill_date"placeholder="Select Date">
                           </fieldset>
                            <span class="valid_err"></span>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-6">
                            <label for="due_date">Due Date</label>
                            <fieldset class="d-flex">
                                <input type="text" class="form-control pickadate1 mb-50 mb-sm-0 due_date" placeholder="Select Date">
                            </fieldset>
                            <span class="valid_err"></span>
                        </div>
                    </div>
            </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="upload_btn_convert" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Convert</span>
                    </button>
                </div> 
            </form>
        </div>
    </div>
</div>

</section>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
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
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>

<script>
    $(function() {
        $(".dropdown-menu a").click(function() {
        });

    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    function isInt(value) {
        var er = /^-?[0-9]+$/;
        return er.test(value);
    }
    $(document).on("blur", ".tds", function() {
        var sum = 0;

        var tds1 = isInt(
            $(this).val(
                $(this)
                .val()
                .match(/^\d+\.?\d{0,2}/)
            )
        );
        if (tds1) {
            sum = parseInt($(".payable").val()) - parseInt($(this).val(), 10);
        } else {
            sum = parseInt($(".payable").val()) - parseFloat($(this).val());
        }

        $(".total").val(sum);
    });
    $(document).on('click', '.statusbtn', function() {
        var value = $(this).data('value');
        $('.data_div').empty();
        $('.loader').css('display', 'block');
        var value = $(this).data('value');
        $.ajax({
            type: 'post',
            url: 'filter_proforma_invoice',
            data: {
                value: value
            },

            success: function(data) {
                $('.loader').css('display', 'none');



                $('.data_div').empty().html(data);
                let str = value;

                $(".selection").html(str.toUpperCase());
                if ($(".invoice-data-table").length) {
                    var dataListView = $(".invoice-data-table").DataTable({
                        columnDefs: [{
                                targets: 0,
                                className: "control"
                            },
                            {
                                orderable: true,
                                targets: 1,
                                checkboxes: {
                                    selectRow: true
                                }
                            },
                            {
                                targets: [0, 1],
                                orderable: false
                            },
                        ],

                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Invoice"
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
                            }
                        }
                    });
                }

                // To append actions dropdown inside action-btn div
                var invoiceFilterAction = $(".invoice-filter-action");
                var invoiceOptions = $(".invoice-options");
                $(".action-btns").append(invoiceFilterAction, invoiceOptions);

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
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    $(document).on('click', '.delete_invoice', function() {
        var id = $(this).data('id');
        var status = $('.selection').html();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this Proforma Invocie",
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
                    url: 'delete_proforma_invoice',
                    data: {
                        id: id,
                        status: status,
                    },
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            // let str = status;
                            // $(".selection").html(str.toUpperCase());
                            location.reload();
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        ).focus();
                    }


                });
            }
        });
    });

$(document).on('click', '.convert_to_tax_invoice', function() {
        var id = $('#invoice_id').val($(this).data('id'));
        var bill_date = $('.bill_date').val($(this).data('bill_date'));
        var due_date = $('.due_date').val($(this).data('due_date'));
});
$(document).on('click', '#upload_btn_convert', function() {
     var id = $('#invoice_id').val();
     var bill_date = $('.bill_date').val();
     var due_date = $('.due_date').val();
    Swal.fire({
            title: 'Are you sure?',
            text: "You want to Convert Proforma Invoice to Tax Invoice",
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
                    url: 'convert_to_tax_invoice',
                    data: {
                        id: id,
                        bill_date: bill_date,
                        due_date: due_date
                    },
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {
                            $("#convert_form")[0].reset();
                            $('#convert_taxinvoice_Modal').modal('hide');
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            location.reload();
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        ).focus();
                    }
                });
            }
        });
});
</script>
@endsection