@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Lead Report')
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
    .card-body{
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
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-label-group">
                        <select class="form-control input_control" id="campaign_id" name="campaign_id">
                            <option value="">Select Campaign</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->campaign_id }}">{{ $campaign->campaign_name }} ({{ $campaign->campaign_id }})</option>
                            @endforeach
                        </select>
                        <span class="text-danger campaign_id_err"></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
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
                <div class="table-responsive">
                    <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Client Name</th>
                                <th>Society Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>Address</th>
                                <th>Lead Source</th>
                                <th>Lead type</th>
                                <th>Appointment</th>
                                <th>Follow Up</th>
                                <th>Quotation</th>
                                <th>Status</th>
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
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
    function load_table(pass_data) {
        var table = $('.client-data-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            searching: false,
            pageLength: 25,
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
            ajax: {
                url: "{{ route('get_lead_report') }}",
                data: pass_data
            },
            columns: [
                {
                    data: null, 
                    name: 'serial_no',
                    render: function(data, type, row, meta) {
                        return meta.row + 1; 
                    },
                },
                {
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    data: 'society_name',
                    name: 'society_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'contact',
                    name: 'contact'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'source_name',
                    name:'source_name'
                    
                },
                
                {
                    data: 'type',
                    'render': function(data, type, row) {
                        if (row.type == 'New') {
                            return `<div><span class="badge badge-light-success badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Cold') {
                            return `<div><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Potential') {
                            return `<div><span class="badge badge-light-primary badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Hot') {
                            return `<div><span class="badge badge-light-warning badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Closed') {
                            return `<div><span class="badge badge-light-danger badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Reopen') {
                            return `<div><span class="badge badge-light-brown badge-pill">` + row.type + `</span></div>`;
                        } else if (row.type == 'Not Interested') {
                            return `<div><span class="badge badge-light-secondary badge-pill">Nt Int</span></div>`;
                        }else{
                            return `<div><span class="badge badge-light-secondary badge-pill"></span></div>`;
                        }
                    }
                },
                {
                    data: 'total_appointments',
                    name:'total_appointments'
                },
                {
                    data: 'total_follow_ups',
                    name:'total_follow_ups'
                },
                {
                    data: 'total_quotations',
                    name:'total_quotations'
                },
                {
                    data: 'client_leads',
                    name: 'client_leads'
                },
            ]
        });

        $(".loader").css("display", "none");
    }

    $(".search").on("click",  function() {
        $('.campaign_id_err').html('');
            $('.client-data-table').show();
            var campaign_id = $("#campaign_id").val();

            if (campaign_id != "" ) {
                $(".loader").css("display", "block");
                var pass_data = {
                    campaign_id:campaign_id
                };
                load_table(pass_data);
            } else {
                if (campaign_id == "") {
                    $('.campaign_id_err').html('Plese select campaign');
                    return false;
                }
            }
        });
</script>
@endsection