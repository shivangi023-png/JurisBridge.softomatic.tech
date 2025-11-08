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

    .dt-buttons {
        margin-left: 22px !important;
    }
</style>
{{-- page title --}}
@section('title', 'Upload Lead Data')
{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
@endsection
{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/expense.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tooltip-style.css') }}">
@endsection

@section('content')
    <section  class="expense-list-wrapper">
        <center>
            <div class="spinner-grow text-primary loader" role="status" style="display:none">
                <span class="sr-only">Loading...</span>
            </div>
            <h5 class="loader" style="display:none">Please wait...</h5>
        </center>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upload Lead Data</h5>
                @include('layouts.tabs')
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if (Session::has('alert-' . $msg))
                        <div class="alert bg-rgba-{{ $msg }} alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                @if (Session::has('alert-success'))
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
                <form method="POST" action='' id="form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="file" class="form-control csv_file" name="file">
                                </div>
                                <span class="valid_err file_err"></span>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 form-group">
                            <button type="button" id="submit" class="btn btn-icon btn-success mr-1 mb-1">
                                <i class="bx bx-upload"></i></button>
                                  <a href="{{url('')}}/sample_csv/FB_leads_csv_sample.csv" class="btn btn-icon btn-warning mr-1 mb-1">
                                <i class="bx bx-download"></i><span>Sample CSV</span></a>
                        </div>
                      
                    </div>
                </form>
                <hr>
                <div class="data_div">
                    <div class="table-responsive">
                        <table class="table upload_data_table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>DOB</th>
                                   
                                    <th>Commitee Member</th>
                                    <th>Society Name</th>
                                    <th>Address</th>
                                    <th>Area</th>
                                    <th>Units</th>
                                    <th>From</th>
                                    <th>Status</th>
                                    <th>Convert Client</th>
                                    <th>City</th>
                                    <th>Role</th>
                                    <th>Services</th>
                                    <th>Any Query</th>
                                   

                                   
                                   
                                    <th>Fb id</th>
                                    <th>Ad id</th>
                                    <th>Ad name</th>
                                    <th>Adset id</th>
                                    <th>Adset name</th>
                                    <th>Campaign id</th>
                                    <th>Campaign name</th>
                                    <th>Form id</th>
                                    <th>Form name</th>

                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leads as $row)
                                    <tr>
                                        <td style="white-space: nowrap;">
                                        <div class="client-action">
                                        <a href="upload_leads_edit-{{$row->id}}" class=" btn btn-icon rounded-circle btn-warning glow mr-1 mb-1" data-tooltip="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1 delete_lead" data-id="{{$row->id}}" data-tooltip="Delete">
                                            <i class="bx bx-trash-alt"></i>
                                        </a>
                                        </div>
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->mobile_no }}</td>
                                        <td>{{ !empty($row->dob) ? date('d-m-Y',strtotime($row->dob)) : '' }}</td>
                                       
                                        <td>{{ $row->check_commitee_member }}</td>
                                        <td>{{ $row->society_name }}</td>
                                        <td>{{ $row->address }}</td>
                                        <td>{{ $row->area }}</td>
                                        <td>{{ $row->units }}</td>
                                        <td>{{ $row->from }}</td>
                                        <td>{{ $row->status }}</td>
                                        <td>{{ $row->convert_client }}</td>
                                        <td>{{ $row->city }}</td>
                                        <td>{{ $row->role }}</td>
                                        <td>{{ $row->services }}</td>
                                        <td>{{ $row->any_query }}</td>
                                     
                                        

                                        <td>{{ $row->fb_id }}</td>
                                        <td>{{ $row->ad_id }}</td>
                                        <td>{{ $row->ad_name }}</td>
                                        <td>{{ $row->adset_id }}</td>
                                        <td>{{ $row->adset_name }}</td>
                                        <td>{{ $row->campaign_id }}</td>
                                        <td>{{ $row->campaign_name }}</td>
                                        <td>{{ $row->form_id }}</td>
                                        <td>{{ $row->form_name }}</td>
                                        <td>{{ date('d-m-Y',strtotime($row->created_at)) }}</td>
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
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if ($(".upload_data_table").length) {
                var dataListView = $(".upload_data_table").DataTable({
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
                    // order: [2, "asc"],
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

        function getExtension(filename) {
            var parts = filename.split('.');
            return parts[parts.length - 1];
        }

        function isCSV(filename) {
            var ext = getExtension(filename);
            switch (ext.toLowerCase()) {
                case 'csv':
                    //etc
                    return true;
            }
            return false;
        }
        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var form = $('#form')[0];
            var formdata = new FormData(form);
            console.log(formdata);
            var file = $('.csv_file').val();
            var msg = isCSV(file);

            if (file != '') {
                if (msg == false) {
                    arr.push('file_err');
                    arr.push('Only csv file required');
                }
            } else {
                arr.push('file_err');
                arr.push('CSV file required');
            }
            if (arr != '') {
                $('.valid_err').html('');
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'upload_data',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#cover-spin").show();
                    },
                    complete: function() {
                        $("#cover-spin").hide();
                    },
                    success: function(data) {
                    if (data.status == 'success') {
                        $('.csv_file').val('');
                         Swal.fire({
                            icon: "success",
                            title: "Upload",
                            text: data.msg
                          });
                        setTimeout(function() {window.location.reload()}, 2000);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: data.msg
                          });
                        }
                    },
                    error: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: data.msg
                      });
                    }
                });
            }
        });

$(document).on("click", ".delete_lead", function () {
    var id = $(this).data("id");
    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this lead",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
      confirmButtonClass: "btn btn-warning",
      cancelButtonClass: "btn btn-danger ml-1",
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: "post",
          url: "upload_leads_delete",
          data: {
            id: id
          },
          success: function (res) {
            var res = JSON.parse(res);
            if (res.status == "success") {
              Swal.fire({
                icon: "success",
                title: "Deleted!",
                text: res.msg,
                confirmButtonClass: "btn btn-success",
              });
              setTimeout(function() {window.location.reload()}, 2000);
            } else {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: res.msg,
                confirmButtonClass: "btn btn-danger",
              });
            }
          },
        });
      }
    });
  });
    </script>
@endsection
@section('jquery')
@endsection