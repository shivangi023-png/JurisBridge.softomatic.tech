@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- title --}}
@section('title','Export New Lead')
{{-- venodr style --}}
@section('vendor-styles')

@endsection
@section('content')
<!-- Dashboard Analytics Start -->
<section>
    <div class="card">
        <div class="card-body">
            @include('layouts.tabs')
            <h6 class="">Export New Leads Contact</h6>
            <div class="row">
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="form group">
                        <select name="contact_select" id="contact_select" class="form-control">
                            @for($i=1;$i<=12;$i++) <option value="{{ date('m', mktime(null, null, null, $i)) }}">{{ date("F", mktime(null, null, null, $i)); }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <button type="submit" class="btn btn-icon rounded-circle btn-light-success">
                        <i class="bx bx-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')

@endsection

@section('page-scripts')

<!-- firebase  -->

@endsection