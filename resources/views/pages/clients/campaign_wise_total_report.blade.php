@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Campaign wise Total')
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
    div.dataTables_wrapper div.dataTables_filter {
        margin-left: -22px !important;
        margin-top: 1rem !important;
    }
</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
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
                                <td>Sr.No.</td>
                                <th>Campaign Id</th>
                                <th>Campaign Name</th>
                                <th>Created Date</th>
                                <th>Total Leads</th>
                                <th>Converted to Clients</th>
                                <th>Quotation</th>
                                @foreach($lead_type as $lt)
                                <th>{{ $lt->type }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;?>
                            @foreach($campaigns as $campaign_id => $campaign_group)
                                @php
                                    $campaign = $campaign_group[0]; // Get the first item in the sorted group
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $campaign->campaign_id }}</td>
                                    <td>{{ $campaign->campaign_name }}</td>
                                    <td>{{ date('d M,Y',strtotime($campaign->created_date)) }}</td>
                                    <td>{{ array_sum(array_column($campaign_group, 'total_count')) }}</td>
                                    <td>{{ array_sum(array_column($campaign_group, 'converted_clients')) }}</td>
                                    <td>{{ array_sum(array_column($campaign_group, 'total_quotations')) }}</td>
                                    @foreach($lead_type as $lt)
                                        @php
                                            $lead_type_count = array_sum(array_column(
                                                array_filter($campaign_group, function($item) use ($lt) {
                                                    return $item->lead_type == $lt->type;
                                                }), 'total_count'
                                            ));
                                        @endphp
                                        <td>{{ $lead_type_count }}</td>
                                    @endforeach
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

@endsection