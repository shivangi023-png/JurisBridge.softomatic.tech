@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Leads Timeline')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{(asset('vendors/css/forms/select/select2.min.css'))}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<script src="{{asset('vendors/js/jquery/jquery.min.js')}}"></script>
@endsection
{{-- page styles --}}
<style>

</style>
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/leads-timeline.css') }}">
@endsection
@section('content')
<input type="hidden" value="{{url('/')}}" id="base_url">
<div class="card">
    <div class="card-body">
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

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <section class="about-timeline">
                        <div class="wrapper inner-wrapper-padding">
                            <h3>
                                {{ $client->case_no }} ({{ $client->client_name }})
                            </h3>

                            <div class="start-point">
                                <div class="black-dot"></div>
                                <h4>{{ date('d-M-Y', strtotime($client->created_at)) }}</h4>
                                <div class="corner bl"></div>
                            </div>

                            <div class="timeline-main">
                                @php $index = 0; @endphp
                                @foreach ($mergedRecordsByDate as $date => $data)
                                @if ($index % 3 === 0)
                                <div class="timeline-row">
                                    @endif
                                    <div class="timeline-box">
                                        <div class="timeline-box-wrap">
                                            <h6 class="NumbarColor{{ ($loop->index % 8) + 1 }}">{{ date('d-M-Y', strtotime($date)) }}</h6>
                                            <div class="timeline-content">
                                                <div class="timeline-content-txt">
                                                    @foreach ($data as $record)
                                                    @if ($record['title'] === 'APPOINTMENTS')
                                                    <h4 class="text-primary"><u>{{$record['title']}}:</u></h4>
                                                    @foreach($record['staff_name'] as $staff_name)
                                                    <ul>
                                                        <li>{{ $staff_name }}</li>
                                                    </ul>
                                                    @endforeach
                                                    @elseif ($record['title'] === 'FOLLOW-UPS')
                                                    <h4 class="text-primary"><u>{{$record['title']}}:</u></h4>
                                                    @foreach($record['method'] as $method)
                                                    <ul>
                                                        <li>{{ $method }}</li>
                                                    </ul>
                                                    @endforeach
                                                    @else
                                                    <h4 class="text-primary"><u>{{ $record['title'] }}:</u></h4>
                                                    @foreach($record['services'] as $services)
                                                    <ul>
                                                        <li>{!! $services !!}</li>
                                                    </ul>
                                                    @endforeach
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-line"></div>
                                    <div class="verticle-line"></div>
                                    <div class="corner top"></div>
                                    <div class="corner bottom"></div>
                                    @php $index++; @endphp
                                    @if ($index % 3 === 0 || $loop->last)
                                </div>
                                @endif
                                @endforeach
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
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
@endsection