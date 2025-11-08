@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Leads List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .modal-lg,
    .modal-xl {
        max-width: 1500px;
    }

    .dropdown-menu {
        z-index: 99999 !important;
    }

    .table-condensed {
        border-collapse: initial;
    }

    .datepicker-days {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-years {
        width: 235px !important;
        height: 220px !important;
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
    <div id="alert"></div>
    <div class="data_div">
        <div class="action-dropdown-btn d-none">
            <div class="leads-options">
                <div class="row">
                <div class="col-md-4">
                        <select class="form-control month">
                            <option value="">--Select Month--</option>
                            @for($i=1;$i<=12;$i++)
                                @if(date('m')==date('m', mktime(0, 0, 0, $i, 10)))
                                <option value="{{date('m', mktime(0, 0, 0, $i, 10))}}" selected>{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                                @else
                                <option value="{{date('m', mktime(0, 0, 0, $i, 10))}}">{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control year">
                            <option value="">--Select Year</option>
                            @for($i=2021;$i<=date('Y');$i++)
                                @if($i==date('Y'))
                              <option value="{{$i}}" selected>{{$i}}</option>
                              @else
                              <option value="{{$i}}">{{$i}}</option>
                              @endif
                            @endfor
                        </select>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-xs btn-success waves-effect search pull-right"><i class="bx bx-search"></i></button>
                </div>
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table leads_list wrap">
                        <thead>
                            <tr>
                                 <th>#</th>
                                 <th>Name</th>
                                 <th>Society Name</th>
                                 <th>Units</th>
                                 <th>Mobile No</th>
                                 <th>Email</th>
                                 <th>City</th>
                                 <th>Any Query</th>
                                 <th>Area</th>
                                 <th>Address</th>
                                 <th>Role</th>
                                 <th>Services</th>
                                 <th>From</th>
                                 <th>Lead Source</th>
                                 <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php $i = 1; ?>
                            @foreach ($leads as $row)
                            <tr>
                               <td>{{$i++}}</td>
                         <td>{{$row->name}}</td>
                         <td>{{$row->society_name}}</td>
                         <td>{{$row->units}}</td>
                         <td>{{$row->mobile_no}}</td>
                         <td>{{$row->email}}</td>
                         <td>{{$row->city}}</td>
                         <td>{{$row->any_query}}</td>
                         <td>{{$row->area}}</td>
                         <td>{{$row->address}}</td> 
                         <td>{{$row->role}}</td>
                         <td>{{$row->services}}</td>
                         <td>{{$row->from}}</td>
                         <td>{{$row->lead_source}}</td>
                         <td>
                             @if($row->created_at!='')
                             {{date('d-M-Y',strtotime($row->created_at))}}
                             @endif
                         </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
    $(document).ready(function() {
        if ($(".leads_list").length) {
    var dataListView = $(".leads_list").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: true,
          targets: 0,
        },
        {
          targets: [0, 1],
          orderable: false,
        },
      ],

      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      language: {
        search: "",
        searchPlaceholder: "Search Lead",
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
  var leadsFilterAction = $(".leads-filter-action");
  var leadsOptions = $(".leads-options");
  $(".action-btns").append(leadsFilterAction, leadsOptions);
    });

     $(document).on('click', '.search', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var month = $('.month').val();
        var year = $('.year').val();
        var msg = '';
        if (month == '' || year == '') {
            msg = "Please select month and year both";
        }
        if (msg != '') {
            swal({
                title: "Warning!",
                text: msg,
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: 'get_leads_list',
                data: {
                    month: month,
                    year: year
                },
                success: function(data) {
                    console.log(data);
                    $('.leads_list').empty().html(data);
                    $('.js-exportable1').DataTable({   
                        dom: "Bfrtip",
                    buttons: [
                        "copyHtml5",
                        "excelHtml5",
                        "csvHtml5",
                        "pdfHtml5",
                        "print"
                    ],
                    "paging": true,
                    "bFilter": true,
                    "ordering": true,
                    "info": true,
                });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    });
</script>
@endsection