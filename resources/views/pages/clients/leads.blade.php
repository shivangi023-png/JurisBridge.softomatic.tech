@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Leads')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
  .staff-dropdown {
    height: 250px !important;
    overflow-y: auto !important;
  }

  .card-body {
    margin-top: 10px;
  }
</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class='col-10'>
          @include('layouts.tabs')
        </div>
        <div class='col-2'>
          <a href="client_add" class="btn btn-icon btn-outline-primary px-3 float-right" role="button" aria-pressed="true">
            <strong><i class="bx bx-plus"></i>Add Lead</strong></a>
        </div>

      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="form-label-group">
            <input type="text" class="form-control input_control datepicker from_date" placeholder="From Date">
          </div>
          <span class="text-danger from_date_err"></span>
        </div>
        <div class="col-md-2">
          <div class="form-label-group">
            <input type="text" class="form-control input_control datepicker to_date" placeholder="To Date">
          </div>
          <span class="text-danger to_date_err"></span>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <select class="form-control" id="search_by_staff">
              <option value="">Staff</option>
              @foreach ($staff as $row2)
              <option value="{{$row2->sid}}">
                {{$row2->name}}
              </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <select class="form-control" id="search_by_source">
              <option value="">Source</option>
              @foreach ($source as $row3)
              <option value="{{$row3->id}}">
                {{$row3->source}}
              </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-label-group">
            <input type="text" class="form-control input_control" placeholder="Enter Address" id="search_by_address">
          </div>
        </div>
        <div class="col-md-2">
          <fieldset class="form-group">
            <div class="input-group">
              <select class="form-control input_control" id="search_by_city">
                <option value="">City</option>
                @foreach ($address as $row4)
                <option value="{{$row4->id}}">
                  {{$row4->city_name}}
                </option>
                @endforeach
              </select>
            </div>
          </fieldset>
        </div>
        <div class="col-md-2">
          <fieldset class="form-group">
            <div class="input-group">
              <select class="form-control input_control" id="search_by_status">
                <option value="">Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </fieldset>
        </div>
        <div class="col-md-2">
          <fieldset class="form-group">
            <div class="input-group">
              <select class="form-control input_control" id="search_by_leadtype">
                <option value="">Lead Type</option>
                @foreach ($leadtype as $row5)
                <option value="{{$row5->id}}">
                  {{$row5->type}}
                </option>
                @endforeach
              </select>
            </div>
          </fieldset>
        </div>

        <div class="col-md-1">
          <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
        </div>
        <div class="col-md-1 ml-3">
          <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <select class="form-control client" id="client" multiple="multiple">
              <option value="">Search By Clients</option>
              @foreach ($client_case as $row1)
              <option value="{{$row1->id}}">
                {{$row1->case_no}} ({{$row1->client_name}})
              </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
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
      <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
          <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
      </center>

      <div class="data_div">
        <div class="dropdown_action"></div>
        <div class="table-responsive">
          <table class="table client-data-table wrap data_table">
            <thead>
              <tr>
                <th></th>
                <th width="13%">Action</th>
                <th>Name</th>
                <th>Lead Type</th>
                <th>Assigned To</th>
                <th>No of units</th>
                <th>Property type</th>
                <th>Source</th>
                <th>Created Date</th>
                <th>Assigned Date</th>
                <th>Remarks</th>
                <th>Address</th>
              </tr>
            </thead>
            <tbody>
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
<!---end-->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/common_leads.js')}}"></script>
<script>
  function load_table(pass_data) {
    var table = $('.data_table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      searching: false,
      pageLength: 50,
      columnDefs: [{
          orderable: false,
          targets: 0,
          checkboxes: {
            selectRow: true,
          }
        },
        // {
        //     targets: [0, 1, 2, 3],
        //     orderable: false,
        // },
      ],
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      ajax: {
        url: "{{ route('get_leads_list') }}",
        data: pass_data
      },
      columns: [{
          data: 'checkbox',
          name: 'checkbox',
          orderable: false,
          searchable: false
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
        {
          data: 'client_case_no',
          name: 'client_case_no'
        },
        {
          data: 'type',
          'render': function(data, type, row) {
            if (row.type == 'New') {
              return `<div class="lead_type_view"><span class="badge badge-light-success badge-pill">` + row.type + `</span></div> <div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Cold') {
              return `<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Potential') {
              return `<div class="lead_type_view"><span class="badge badge-light-primary badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Hot') {
              return `<div class="lead_type_view"><span class="badge badge-light-warning badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Closed') {
              return `<div class="lead_type_view"><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Reopen') {
              return `<div class="lead_type_view"><span class="badge badge-light-brown badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'Not Interested') {
              return `<div class="lead_type_view"><span class="badge badge-light-secondary badge-pill">Nt Int</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            } else if (row.type == 'NCT') {
              return `<div class="lead_type_view"><span class="badge badge-light-secondary badge-pill">` + row.type + `</span></div><div class="select_lead_type" style="display:none;"><select class="form-control lead_type" name="lead_type"> @foreach($leadtype as $val)<option value="{{$val->id}}" {{ ($val->id == ` + row.type + `) ? "selected " : "" }}>{{$val->type}}</option>@endforeach </select> </div>`;
            }
          }
        },
        {
          data: 'assign_staff_name',
          'render': function(data, assign_staff_name, row) {
            if (row.assign_staff_name != null) {
              return '<a href="javascript:void(0);" class="lead_history" data-client_id="' + row.id + '"><small class="assign_staff">' + row.assign_staff_name + '</small></a>';
            } else {
              return '';
            }
          }
        },
        {
          data: 'no_of_units',
          name: 'no_of_units'
        },
        {
          data: 'abbrev',
          name: 'abbrev'
        },
        {
          data: 'source_name',
          'render': function(data, source_name, row) {
            if (row.source_name == 'Whatsapp group') {
              return '<img src="{{asset("images/source_icons/whatsApp-group.png")}}" alt="Whatsapp group">';
            } else if (row.source_name == 'Active Sales') {
              return '<img src="{{asset("images/source_icons/active-sales.png")}}" alt="Active Sales">';
            } else if (row.source_name == 'Client ref') {
              return '<img src="{{asset("images/source_icons/client-ref.png")}}" alt="Client ref">';
            } else if (row.source_name == 'Newspaper') {
              return '<img src="{{asset("images/source_icons/newspaper.png")}}" alt="Newspaper">';
            } else if (row.source_name == 'Franchise') {
              return '<img src="{{asset("images/source_icons/franchise.png")}}" alt="Franchise">';
            } else if (row.source_name == 'LinkedIn') {
              return '<img src="{{asset("images/source_icons/linkedin.png")}}" alt="LinkedIn">';
            } else if (row.source_name == 'Quora') {
              return '<img src="{{asset("images/source_icons/quora.png")}}" alt="Quora">';
            } else if (row.source_name == 'YouTube') {
              return '<img src="{{asset("images/source_icons/youtube.png")}}" alt="YouTube">';
            } else if (row.source_name == 'Google ads') {
              return '<img src="{{asset("images/source_icons/googleAds.png")}}" alt="Google ads">';
            } else if (row.source_name == 'Walk-in') {
              return '<img src="{{asset("images/source_icons/walk-in.png")}}" alt="Walk-in">';
            } else if (row.source_name == 'Facebook') {
              return '<img src="{{asset("images/source_icons/facebook.png")}}" alt="Facebook">';
            } else {
              return '';
            }
          }
        },
        {
          data: 'created_at',
          name: 'created_at'
        },
        {
          data: 'assigned_at',
          name: 'assigned_at'
        },
        {
          data: 'remarks',
          name: 'remarks'
        },
        {
          data: 'address',
          name: 'address'
        },
      ]
    });

    $(".loader").css("display", "none");
    $('.dropdown_action').empty().append('<div class="action_dropdown"> @if(session("role_id") == 1) <div class="action-dropdown-btn mt-2"> <div class="dropdown client-filter-action source-action mr-1"> <select name="source" id="bulk_source" class="form-control"> <option value="">Select Source</option> @foreach($source as $val) <option value="{{$val->id}}">{{$val->source}}</option> @endforeach </select> </div> </div> <div class="dropdown client-filter-action"> <button class="btn btn-outline-primary dropdown-toggle" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="selection">Assign Lead To Team</span> </button> <div class="dropdown-menu dropdown-menu-right staff-dropdown" aria-labelledby="client-filter-btn"> @foreach ($staff as $item) <a type="button" href="#" class="dropdown-item assign_btn" data-assign_id="{{$item->sid}}" data-assign_val="{{$item->name}}">{{$item->name}}</a> @endforeach </div> </div> @endif </div>');

    var clientFilterAction = $(".client-filter-action");
    $(".action-btns").append(clientFilterAction);
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

  }
  $(document).ready(function() {
    $('.client-data-table').hide();
    $("#client").select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Search By Clients",
    });
    $("#search_by_staff").select2();
    $("#search_by_source").select2();
    $("#search_by_city").select2();

    $(".datepicker")
      .datepicker()
      .on("changeDate", function(ev) {
        $(".datepicker.dropdown-menu").hide();
      });

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });


    $(document).on("click", ".search", function() {
      $('.client-data-table').show();
      var from_date = $(".from_date").val();
      var to_date = $(".to_date").val();
      var staff_id = $("#search_by_staff").val();
      var source = $("#search_by_source").val();
      var address = $("#search_by_address").val();
      var city = $("#search_by_city").val();
      var status = $("#search_by_status").val();
      var lead_type = $("#search_by_leadtype").val();
      if (
        source != "" ||
        staff_id != "" ||
        address != "" ||
        city != "" ||
        status != "" ||
        lead_type != "" ||
        (from_date != "" && to_date != "")
      ) {
        $(".loader").css("display", "block");
        $(".from_date_err").text("");
        $(".to_date_err").text("");
        $(".to_date_err").text("");

        var pass_data = {
          client_leads: "leads",
          page: "leads",
          selection_val: "Active",
          from_date: from_date,
          to_date: to_date,
          address: address,
          staff_id: staff_id,
          source: source,
          city: city,
          status: status,
          lead_type: lead_type
        };
        load_table(pass_data);
      } else {
        if (from_date == "" && to_date != "") {
          $(".from_date_err").text("Select From Date");
          return false;
        }
        if (from_date != "" && to_date == "") {
          $(".to_date_err").text("Select To Date");
          return false;
        }
        if (
          from_date == "" &&
          to_date == "" &&
          address == "" &&
          source == "" &&
          staff_id == "" &&
          city == "" &&
          status == "" &&
          lead_type == ""
        ) {
          $("#alert").animate({
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          $("#alert")
            .html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select filter</span></div></div>'
            )
            .focus();
          return false;
        }
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
        $('.source-action').hide();
      } else {
        $(".dt-checkboxes-cell")
          .find("input")
          .prop("checked", "")
          .closest("tr")
          .removeClass("selected-row-bg");
        $('.source-action').show();
      }
    });

    $(document).on("change", ".dt-checkboxes-cell input", function() {
      if ($(this).is(":checked")) {
        $(this)
          .find("input")
          .prop("checked", this.checked)
          .closest("tr")
          .addClass("selected-row-bg");
        $('.source-action').hide();
      } else {
        $(this)
          .find("input")
          .prop("checked", "")
          .closest("tr")
          .removeClass("selected-row-bg");
        $('.source-action').show();
      }
    });

    $(document).on("click", ".change_lead_type", function() {
      $(this).closest("tr").find(".change_lead_type").css("display", "none");
      $(this).closest("tr").find(".lead_type_view").css("display", "none");
      $(this).closest("tr").find(".select_lead_type").css("display", "block");
      $(this).closest("tr").find(".save_lead_div").css("display", "block");
    });

    $(document).on("click", ".close_lead_type", function() {
      $(this).closest("tr").find(".change_lead_type").css("display", "block");
      $(this).closest("tr").find(".lead_type_view").css("display", "block");
      $(this).closest("tr").find(".select_lead_type").css("display", "none");
      $(this).closest("tr").find(".save_lead_div").css("display", "none");
    });
    $(document).on("click", ".assign_btn", function() {
      var staff_id = $(this).data("assign_id");
      var staff_name = $(this).data("assign_val");
      var bulk_source = $('#bulk_source').val();
      var search_by_leadtype = $('#search_by_leadtype').val();
      var search_by_status = $('#search_by_status').val();
      var search_by_city = $('#search_by_city').val();
      var page = "leads";



      var client_id = new Array();
      $(".dt-checkboxes:checked").each(function() {
        client_id.push($(this).closest("tr").find(".clientID").val());
      });
      if (client_id.length > 0) {
        bulk_source = null;
      }
      if (client_id == "" && bulk_source == '') {
        $("#alert").animate({
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        $("#alert").html(
          '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Please Lead or Source is not selected!</span></div></div>'
        );
        $(".alert")
          .fadeTo(2000, 500)
          .slideUp(500, function() {
            $(".alert").slideUp(500);
          });
      } else {
        $(".loader").css("display", "block");
        $.ajax({
          type: "post",
          url: "assign_leads_to_staff",
          data: {
            staff_id: staff_id,
            client_id: client_id,
            bulk_source: bulk_source,
            leadtype: search_by_leadtype,
            status: search_by_status,
            city: search_by_city
          },

          success: function(data) {
            var res = JSON.parse(data);

            if (res.status == "success") {
              $(".loader").css("display", "none");
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
              $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                  $(".alert").slideUp(500);
                });

              var from_date = $(".from_date").val();
              var to_date = $(".to_date").val();
              var staff_id = $("#search_by_staff").val();
              var source = $("#search_by_source").val();
              var address = $("#search_by_address").val();
              var city = $("#search_by_city").val();
              var status = $("#search_by_status").val();
              var lead_type = $("#search_by_leadtype").val();

              //   if ($.isArray(client_id)) {
              //   client = client_id;
              // } else {
              //   var client = [];
              //   client.push(client_id);
              // }
              var pass_data = {
                client_leads: "leads",
                page: page,
                from_date: from_date,
                to_date: to_date,
                address: address,
                staff_id: staff_id,
                source: source,
                city: city,
                status: status,
                lead_type: lead_type,
                // client_id: client
              };
              load_table(pass_data);
            } else if (res.status == "fail") {
              $(".loader").css("display", "none");
              $("#alert").animate({
                  scrollTop: $(window).scrollTop(0),
                },
                "slow"
              );
              $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
              );
              $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                  $(".alert").slideUp(500);
                });
            }
          },
          error: function(data) {
            console.log(data);
          },
        });
      }
    });
    $(document).on("click", ".save_lead_type", function() {

      $(".loader").css("display", "block");
      var mythis = $(this);
      var lead_type = $(this).closest("tr").find(".lead_type").val();
      var client_id = $(this).data("client_id");
      var page = "leads";
      $.ajax({
        type: "post",
        url: "save_lead_type",
        data: {
          lead_type: lead_type,
          client_id: client_id,
        },

        success: function(data) {
          var res = JSON.parse(data);

          if (res.status == "success") {
            $(".loader").css("display", "none");
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
            $(".alert")
              .fadeTo(2000, 500)
              .slideUp(500, function() {
                $(".alert").slideUp(500);
              });
            console.log(client_id);
            var from_date = $(".from_date").val();
            var to_date = $(".to_date").val();
            var staff_id = $("#search_by_staff").val();
            var source = $("#search_by_source").val();
            var address = $("#search_by_address").val();
            var city = $("#search_by_city").val();
            var status = $("#search_by_status").val();
            var lead_type = $("#search_by_leadtype").val();

            var pass_data = {
              client_leads: "",
              page: "leads",
              selection_val: "Active",
              from_date: from_date,
              to_date: to_date,
              address: address,
              staff_id: staff_id,
              source: source,
              city: city,
              status: status,
              lead_type: lead_type
            };
            load_table(pass_data);
            // get_leads_by_id(page, "leads", client_id, mythis, "save_lead_type");
          } else if (res.status == "fail") {
            $(".loader").css("display", "none");
            $("#alert").animate({
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
            );
            $(".alert")
              .fadeTo(2000, 500)
              .slideUp(500, function() {
                $(".alert").slideUp(500);
              });
            // get_leads_by_id(
            // page, "leads", client_id, mythis, "save_lead_type");
          }
        },
        error: function(data) {
          console.log(data);
        },
      });
    });

    $(document).on("change", "#client", function() {
      $('.client-data-table').show();
      var client_id = new Array();
      $("#client :selected").each(function() {
        client_id.push($(this).val());
      });
      if (client_id != "") {
        var pass_data = {
          client_leads: "leads",
          page: "leads",
          client_id: client_id
        };
        load_table(pass_data);
      }
    });

    $(document).on("click", ".lead_history", function() {
      var client_id = $(this).data("client_id");
      get_lead_history(client_id);
    });

    $("#reset").click(function() {
      location.reload();
    });


    $(document).on("click", ".convert_client", function() {
      $(".loader").css("display", "block");
      var mythis = $(this);
      var client_id = $(this).data("client_id");
      var page = "leads";
      $.ajax({
        type: "post",
        url: "convert_client",
        data: {
          client_id: client_id,
        },

        success: function(data) {
          console.log(data);
          var res = data;
          $("#alert").animate({
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          if (res.status == "success") {
            $(".loader").css("display", "none");
            $("#alert")
              .html(
                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
              )
              .focus();

            mythis.closest("tr").remove();
          }
        },
        error: function(data) {
          $(".loader").css("display", "none");
          var res = data;
          $("#alert")
            .html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
              res.msg +
              "</span></div></div>"
            )
            .focus();
        },
      });
    });
    $(document).on("click", ".delete_client", function() {
      var mythis = $(this);
      var id = $(this).data("id");
      var page = "leads";
      Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this client",
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
            url: "delete_client",
            data: {
              id: id,
            },

            success: function(data) {
              console.log(data);
              var res = JSON.parse(data);
              if (res.status == "success") {
                Swal.fire({
                  icon: "success",
                  title: "Deleted!",
                  text: "Client has been deleted.",
                  confirmButtonClass: "btn btn-success",
                });
                mythis.closest("tr").remove();
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Error!",
                  text: "Client can`t be deleted.",
                  confirmButtonClass: "btn btn-danger",
                });
              }
            },
          });
        }
      });
    });
  });
</script>
@endsection