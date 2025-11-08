@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Settings')
@section('vendor-styles')

@endsection
@section('page-styles')

<style>
.valid_err {

    font-size: 12px;
}
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="staff_add" class="btn btn-outline-primary mr-1">Add Staff</a>
                    <a href="company_add" class="btn btn-outline-primary">Add Company</a>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
</section>

<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')


@endsection
{{-- page scripts --}}
@section('page-scripts')


@endsection