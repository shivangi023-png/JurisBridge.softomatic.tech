@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','Update Proforma Invoice')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
{{-- page styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<!-- app invoice View Page -->
<section class="invoice-edit-wrapper">
    <form id="form">
        <div class="row">
            <?php $j = ''; ?>
            @foreach($invoices as $row)
            <!-- invoice view page -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card">
                    <input type="hidden" class="form-control bill_id" value="{{$row->id}}">
                    <input type="hidden" class="form-control case_no" value="{{$row->case_no}}">
                    <input type="hidden" class="form-control status" value="{{$row->status}}">
                    <div class="card-header mt-2">
                       <h4 class="card-title">proforma Invoice Edit</h4>
                    </div>
                    <div class="card-body pb-0 mx-25">
                        <!-- header section -->
                        <div class="row mx-0">
                            <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                                <span class="mr-75 label_title">Invoice#</span>
                                <input type="text" class="form-control pt-25 w-50" value="INV-{{$row->id}}" placeholder="#000" readonly>
                            </div>
                            <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                                <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                    <div>
                                        <span class="mr-75 label_title">Issue Date: </span>

                                        <fieldset class="d-flex ">
                                            <input type="text" class="form-control pickadate1 mr-2 mb-50 mb-sm-0 bill_date" value="{{date('d/m/Y',strtotime($row->bill_date))}}" placeholder="Select Date">
                                        </fieldset>
                                        <span class="valid_err bill_date_err"></span>

                                    </div>

                                    <div>
                                        <span class="mr-75 label_title">Due Date: </span>

                                        <fieldset class="d-flex">
                                            <input type="text" class="form-control pickadate1 mb-50 mb-sm-0 due_date" value="{{date('d/m/Y',strtotime($row->due_date))}}" placeholder="Select Date">
                                        </fieldset>
                                        <span class="valid_err due_date_err"></span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- logo and title -->
                        <div class="row">
                            <div class="col-sm-8 col-12  order-sm-1">
                                <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Client</label>
                                        </div>
                                        <select class="form-control client" name="client[]" id="client" required>
                                            <option value="" selected>Choose...</option>
                                            @foreach($clients as $client)
                                            @if($client->id==$row->client)
                                            <option value="{{$client->id}}" selected>{{$client->client_name}}
                                                @else
                                            <option value="{{$client->id}}">{{$client->client_name}}
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <span class="valid_err client_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-sm-4 col-12 order-2 order-sm-1">
                                <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Company</label>
                                        </div>
                                        <select class="form-control company" required>
                                            @foreach($companies as $com)
                                            @if($com->id==$row->company)
                                            <option value="{{$com->id}}" selected>{{$com->company_name}}</option>
                                            @else
                                            <option value="{{$com->id}}">{{$com->company_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <input type="hidden" class="form-control tax_app" value="{{$tax_applicable}}" name="GST_percent">
                    <div class="card-body">
                        <!-- product details table-->
                        <?php
                        $service_arr = json_decode($row->service);
                        $amount_arr = json_decode($row->amount);
                        $quotation_array = json_decode($row->quotation);
                        if ($service_arr != '') {
                            $size = sizeof($service_arr);
                            $discount_array = json_decode($row->discount);
                            if ($discount_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $discount_array[] = null;
                                }
                            }
                            $gst_per_array = json_decode($row->gst_per);
                            if ($gst_per_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $gst_per_array[] = null;
                                }
                            }
                            $gst_amount_array = json_decode($row->gst_amount);
                            if ($gst_amount_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $gst_amount_array[] = null;
                                }
                            }
                            $cgst_per_array = json_decode($row->cgst_per);
                            if ($cgst_per_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $cgst_per_array[] = null;
                                }
                            }
                            $cgst_amount_array = json_decode($row->cgst_amount);
                            if ($cgst_amount_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $cgst_amount_array[] = null;
                                }
                            }
                            $igst_per_array = json_decode($row->igst_per);
                            if ($igst_per_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $igst_per_array[] = null;
                                }
                            }
                            $igst_amount_array = json_decode($row->igst_amount);
                            if ($igst_amount_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $igst_amount_array[] = null;
                                }
                            }
                            $total_array = json_decode($row->total);

                            if ($total_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $total_array[] = null;
                                }
                            }
                            $round_check_array = json_decode($row->round_check);
                            if ($round_check_array == "") {
                                for ($p = 0; $p < sizeof($service_arr); $p++) {
                                    $round_check_array[] = null;
                                }
                            }
                        } else {
                            $discount_array = json_decode($row->discount);
                            if ($discount_array == "") {
                                $discount_array = array(null);
                            }
                            $gst_per_array = json_decode($row->gst_per);
                            if ($gst_per_array == "") {
                                $gst_per_array = array(null);
                            }
                            $gst_amount_array = json_decode($row->gst_amount);
                            if ($gst_amount_array == "") {
                                $gst_amount_array = array(null);
                            }
                            $cgst_per_array = json_decode($row->cgst_per);
                            if ($cgst_per_array == "") {
                                $cgst_per_array = array(null);
                            }
                            $cgst_amount_array = json_decode($row->cgst_amount);
                            if ($cgst_amount_array == "") {
                                $cgst_amount_array = array(null);
                            }
                            $igst_per_array = json_decode($row->igst_per);
                            if ($igst_per_array == "") {
                                $igst_per_array = array(null);
                            }
                            $igst_amount_array = json_decode($row->igst_amount);
                            if ($igst_amount_array == "") {
                                $igst_amount_array = array(null);
                            }
                            $total_array = json_decode($row->total);
                            if ($total_array == "") {
                                $total_array = array(null);
                            }
                            $round_check_array = json_decode($row->round_check);
                            if ($round_check_array == "") {
                                $round_check_array = array(null);
                            }
                        }
                        ?>
                        @if($service_arr!='')
                        @for($i=0;$i< sizeof($service_arr);$i++) <?php
                                                                    if ($discount_array != "") {
                                                                        if ($discount_array[$i] == "" || $discount_array[$i] == null) {
                                                                            $discount_array[$i] = "";
                                                                        }
                                                                    }


                                                                    ?> <div class="row main_row">
                            <div class="col-sm-6 col-12 order-2 order-sm-1">

                                <fieldset class="form-group">
                                    <div class="input-group">

                                        <select class="form-control service service1" name="service[]">
                                            <option value="" selected>Services</option>
                                            @foreach($services as $service)
                                            <option value="{{$service->id}}" <?php if ($service_arr[$i] == $service->id) echo 'selected'; ?>>
                                                {{$service->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="service_err valid_err"></span>
                                    </div>
                                </fieldset>

                            </div>
                            <div class="col-sm-6 col-12 order-2 order-sm-1">
                                <div class="row">

                                    <div class="col-sm-5 col-12 order-2 order-sm-1">
                                        <fieldset>
                                            <div class="form-label-group">
                                                <input type="text" class="form-control amount amount1" value="{{$amount_arr[$i]}}" name="amount" placeholder="Amount">
                                                <label for="first-name-column">Amount</label>
                                                <span class="amount_err valid_err"></span>
                                            </div>

                                        </fieldset>

                                    </div>

                                    <div class="col-sm-4 col-12 order-2 order-sm-1">

                                        <fieldset>
                                            <div class="form-label-group">
                                                @if($discount_array!="")
                                                <input type="text" class="form-control discount discount1" value="{{$discount_array[$i]}}" name="discount" placeholder="Discount">
                                                @else
                                                <input type="text" class="form-control discount discount1" value="{{$discount_array[$i]}}" name="discount" placeholder="Discount">
                                                @endif

                                                <label for="first-name-column">Discount</label>
                                            </div>
                                        </fieldset>
                                        <span class="discount_err valid_err"></span>
                                    </div>
                                    <div class="col-sm-3 col-12 order-2 order-sm-1">
                                        <fieldset>
                                            <div class="form-label-group">
                                                <div class="custom-control custom-switch custom-control-inline mb-1">
                                                    @if($round_check_array[$i] =="yes" )
                                                    <input type="checkbox" class="custom-control-input round_check" id="round_check{{$i}}" value="yes" checked>
                                                    @else
                                                    <input type="checkbox" class="custom-control-input round_check" id="round_check{{$i}}" value="yes">
                                                    @endif
                                                    <label class="custom-control-label mr-1" for="round_check{{$i}}">
                                                    </label>
                                                    <span>Round</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <span class="discount_err valid_err"></span>
                                    </div>
                                </div>
                            </div>


                            @if($tax_applicable=="yes")
                            <div class="col-sm-1 col-12 order-2 order-sm-1">

                                <label for="">GST%</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control GST_percent" value="{{$gst_per_array[$i]}}" name="GST_percent">
                                    </div>
                                </fieldset>
                                <span class="valid_err GST_percent_err"></span>
                            </div>
                            <div class="col-sm-2 col-12 order-2 order-sm-1">
                                <label for="">GST amount</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control GST_amount " name="GST_amount" value="{{$gst_amount_array[$i]}}">
                                    </div>
                                    <span class="valid_err GST_amount_err"></span>
                                </fieldset>

                            </div>

                            <div class="col-sm-1 col-12 order-2 order-sm-1">

                                <label for="">CGST%</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control CGST_percent" value="{{$cgst_per_array[$i]}}" name="CGST_percent">
                                    </div>
                                </fieldset>
                                <span class="valid_err CGST_percent_err"></span>
                            </div>
                            <div class="col-sm-2 col-12 order-2 order-sm-1">
                                <label for="">CGST amount</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control CGST_amount" value="{{$cgst_amount_array[$i]}}" name="CGST_amount">
                                    </div>
                                    <span class="valid_err CGST_amount_err"></span>
                                </fieldset>

                            </div>

                            <div class="col-sm-1 col-12 order-2 order-sm-1">

                                <label for="">IGST%</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control IGST_percent" value="{{$igst_per_array[$i]}}" name="IGST_percent" disabled>
                                    </div>
                                </fieldset>
                                <span class="valid_err IGST_percent_err"></span>
                            </div>
                            <div class="col-sm-2 col-12 order-2 order-sm-1">
                                <label for="">IGST amount</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control IGST_amount" value="{{$igst_amount_array[$i]}}" name="IGST_amount" disabled>
                                    </div>
                                    <span class="valid_err IGST_amount_err"></span>
                                </fieldset>

                            </div>
                            @endif

                            <div class="col-sm-2 col-12 order-2 order-sm-1">
                                <label for="">Total</label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control total_amount total_amount1" name="total_amount" value="{{$total_array[$i]}}" placeholder="total">
                                    </div>
                                </fieldset>
                                <span class="total_err valid_err"></span>
                            </div>
                            @if($i==0)
                            <div class="col-sm-1 col-12 order-2 order-sm-1">
                                <label for=""></label>
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <button class="btn btn-light-primary btn-sm add_row" type="button">
                                            <i class="bx bx-plus"></i>
                                            <span class="invoice-repeat-btn"></span>
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                    </div>

                    @else
                    <div class="col-sm-1 col-12 order-2 order-sm-1">
                        <label for=""></label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <button class="btn btn-light-danger btn-sm delete_row" type="button"> <i class="bx bx-trash-alt">
                                    </i> <span class="invoice-repeat-btn"></span> </button>
                            </div>
                        </fieldset>
                    </div>
                </div>
                @endif


                @endfor
                @else
                <div class="row main_row">
                    <div class="col-sm-6 col-12 order-2 order-sm-1">

                        <fieldset class="form-group">
                            <div class="input-group">

                                <select class="form-control service service1" name="service[]">
                                    <option value="" selected>Services</option>
                                    @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                    @endforeach
                                </select>
                                <span class="service_err valid_err"></span>
                            </div>
                        </fieldset>

                    </div>
                    <div class="col-sm-6 col-12 order-2 order-sm-1">
                        <div class="row">
                            <div class="col-sm-5 col-12 order-2 order-sm-1">
                                <fieldset>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control amount amount1" name="amount" placeholder="Amount">
                                        <label for="first-name-column">Amount</label>
                                        <span class="amount_err valid_err"></span>
                                    </div>

                                </fieldset>

                            </div>

                            <div class="col-sm-4 col-12 order-2 order-sm-1">

                                <fieldset>
                                    <div class="form-label-group">
                                        <input type="text" class="form-control discount discount1" name="discount" placeholder="Discount">
                                        <label for="first-name-column">Discount</label>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                            <div class="col-sm-3 col-12 order-2 order-sm-1">
                                <fieldset>
                                    <div class="form-label-group">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            <input type="checkbox" class="custom-control-input round_check" id="round_check1">
                                            <label class="custom-control-label mr-1" for="round_check1">
                                            </label>
                                            <span>Round</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    @if($tax_applicable=="yes")
                    @foreach($taxes as $tax)
                    <div class="col-sm-1 col-12 order-2 order-sm-1">
                        <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                        <label for="">{{$tax->tax}}%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control {{$tax->tax}}_percent" name="{{$tax->tax}}_percent" <?php if ($tax->status == "inactive") {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>>
                            </div>
                        </fieldset>
                        <span class="valid_err {{$tax->tax}}_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1">
                        <label for="">{{$tax->tax}} amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control {{$tax->tax}}_amount " name="{{$tax->tax}}_amount" <?php if ($tax->status == "inactive") {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>>
                            </div>
                            <span class="valid_err {{$tax->tax}}_amount_err"></span>
                        </fieldset>

                    </div>
                    @endforeach
                    @endif
                    <div class="col-sm-2 col-12 order-2 order-sm-1">
                        <label for="">Total</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control total_amount total_amount1" name="total_amount" placeholder="total">
                            </div>
                        </fieldset>
                        <span class="total_err valid_err"></span>
                    </div>
                    <div class="col-sm-1 col-12 order-2 order-sm-1">
                        <label for=""></label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <button class="btn btn-light-primary btn-sm add_row" type="button">
                                    <i class="bx bx-plus"></i>
                                    <span class="invoice-repeat-btn"></span>
                                </button>
                            </div>
                        </fieldset>
                    </div>
                </div>
                @endif
                <div class="service_div">
                    <input type="hidden" class="form-control total" value="1">
                </div>
                <hr>

                <div class="row">
                    <!---quotation div-->
                    <div class="col-sm-12 col-12 quotation_div">

                        <center><b>OR</b></center><br>

                    </div>
                </div>

                @if($row->quo_of_client!='[]')

                <?php $y = 1;
                $z = 0; ?>
                @foreach($row->quo_of_client as $quo)
                <?php


                ?>
                @if($quotation_array!='')

                @if(in_array($quo->quotation_details_id,$quotation_array))

                <div class="row quo_main_row">
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="padding-top:3px;border:1px solid #DFE3E7">
                        <fieldset class="form-label-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="colorCheckbox{{$y}}" value="{{$quo->quotation_details_id}}" class="quotation_check" checked>
                                <label for="colorCheckbox{{$y}}"></label>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7;">
                        <fieldset class="form-label-group" style="padding-top:4px">
                            <div class="input-group">
                                <b>{{ $quo->name}}</b>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <div class="row">
                            <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset class="form-label-group" style="padding-top:4px">
                                    <input type="text" value="{{$amount_arr[$z]}}" class="form-control quo_amount">
                                </fieldset>
                            </div>

                            <div class="col-sm-4 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset>
                                    <div class="form-label-group" style="padding-top:4px">
                                        <input type="text" class="form-control quo_discount" value="{{$discount_array[$z]}}" name="quo_discount" placeholder="Discount">
                                        <label for="first-name-column">Discount</label>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                            <div class="col-sm-3 col-12 order-2 order-sm-1">
                                <fieldset>
                                    <div class="form-label-group">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            @if($round_check_array[$z]=='yes')
                                            <input type="checkbox" class="custom-control-input quo_round_check" value="yes" id="quo_round_check{{$z}}" checked>
                                            @else
                                            <input type="checkbox" class="custom-control-input quo_round_check" value="no" id="quo_round_check{{$z}}">
                                            @endif

                                            <label class="custom-control-label mr-1" for="quo_round_check{{$z}}">
                                            </label>
                                            <span>Round</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    @if($tax_applicable=="yes")
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_percent" value="{{$gst_per_array[$z]}}" name="quo_GST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err GST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_amount" value="{{$gst_amount_array[$z]}}" name="quo_GST_amount">
                            </div>
                            <span class="valid_err GST_amount_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_percent" value="{{$cgst_per_array[$z]}}" name="quo_CGST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err CGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_amount" value="{{$cgst_amount_array[$z]}}" name="quo_CGST_amount">
                            </div>
                            <span class="valid_err CGST_amount_err"></span>
                        </fieldset>
                    </div>

                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_percent" value="{{$igst_per_array[$z]}}" name="quo_IGST_percent" disabled>
                            </div>
                        </fieldset>
                        <span class="valid_err IGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_amount" value="{{$igst_amount_array[$z]}}" name="quo_IGST_amount" disabled>
                            </div>
                            <span class="valid_err IGST_amount_err"></span>
                        </fieldset>
                    </div>
                    @endif

                    <div class="col-sm-3 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">Total</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_total_amount" name="quo_total_amount" value="{{$total_array[$z]}}" placeholder="total">
                            </div>
                        </fieldset>
                        <span class="total_err valid_err"></span>
                    </div>
                </div>
                <hr>


                <?php $z++; ?>

                @else

                <div class="row quo_main_row">
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="padding-top:3px;border:1px solid #DFE3E7">
                        <fieldset class="form-label-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="colorCheckbox{{$y}}" value="{{$quo->quotation_details_id}}" class="quotation_check">
                                <label for="colorCheckbox{{$y}}"></label>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7;">
                        <fieldset class="form-label-group" style="padding-top:4px">
                            <div class="input-group">
                                <b>{{ $quo->name}}</b>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <div class="row">
                            <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset class="form-label-group" style="padding-top:4px">
                                    <input type="text" value="{{$quo->amount}}" class="form-control quo_amount">
                                </fieldset>
                            </div>

                            <div class="col-sm-4 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset>
                                    <div class="form-label-group" style="padding-top:4px">
                                        <input type="text" class="form-control quo_discount" name="quo_discount" placeholder="Discount">
                                        <label for="first-name-column">Discount</label>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                            <div class="col-sm-3 col-12 order-2 order-sm-1">
                                <fieldset>
                                    <div class="form-label-group">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            <input type="checkbox" class="custom-control-input quo_round_check" value="no" id="quo_round_check{{$y}}">
                                            <label class="custom-control-label mr-1" for="quo_round_check{{$y}}">
                                            </label>
                                            <span>Round</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                        </div>
                    </div>
                    @if($tax_applicable=="yes")
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_percent" name="quo_GST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err GST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_amount " name="quo_GST_amount">
                            </div>
                            <span class="valid_err GST_amount_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_percent" name="quo_CGST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err CGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_amount " name="quo_CGST_amount">
                            </div>
                            <span class="valid_err GST_amount_err"></span>
                        </fieldset>
                    </div>

                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_percent" name="quo_IGST_percent" disabled>
                            </div>
                        </fieldset>
                        <span class="valid_err IGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_amount " name="quo_IGST_amount" disabled>
                            </div>
                            <span class="valid_err IGST_amount_err"></span>
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-sm-3 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">Total</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_total_amount" name="quo_total_amount" value="{{$quo->amount}}" placeholder="total">
                            </div>
                        </fieldset>
                        <span class="total_err valid_err"></span>
                    </div>
                </div>
                <hr>
                @endif
                @else
                <div class="row quo_main_row">
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="padding-top:3px;border:1px solid #DFE3E7">
                        <fieldset class="form-label-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="colorCheckbox{{$y}}" value="{{$quo->quotation_details_id}}" class="quotation_check">
                                <label for="colorCheckbox{{$y}}"></label>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7;">
                        <fieldset class="form-label-group" style="padding-top:4px">
                            <div class="input-group">
                                <b>{{ $quo->name}}</b>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <div class="row">
                            <div class="col-sm-5 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset class="form-label-group" style="padding-top:4px">
                                    <input type="text" value="{{$quo->amount}}" class="form-control quo_amount">
                                </fieldset>
                            </div>
                            <div class="col-sm-4 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                                <fieldset>
                                    <div class="form-label-group" style="padding-top:4px">
                                        <input type="text" class="form-control quo_discount" name="quo_discount" placeholder="Discount">
                                        <label for="first-name-column">Discount</label>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                            <div class="col-sm-3 col-12 order-2 order-sm-1">
                                <fieldset>
                                    <div class="form-label-group">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            <input type="checkbox" class="custom-control-input quo_round_check" value="no" id="quo_round_check1">
                                            <label class="custom-control-label mr-1" for="quo_round_check1">
                                            </label>
                                            <span>Round</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <span class="discount_err valid_err"></span>
                            </div>
                        </div>
                    </div>
                    @if($tax_applicable=="yes")
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_percent" name="quo_GST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err GST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">GST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_GST_amount " name="quo_GST_amount">
                            </div>
                            <span class="valid_err GST_amount_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_percent" name="quo_CGST_percent">
                            </div>
                        </fieldset>
                        <span class="valid_err CGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">CGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_CGST_amount " name="quo_CGST_amount">
                            </div>
                            <span class="valid_err GST_amount_err"></span>
                        </fieldset>
                    </div>

                    <div class="col-sm-1 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST%</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_percent" name="quo_IGST_percent" disabled>
                            </div>
                        </fieldset>
                        <span class="valid_err IGST_percent_err"></span>
                    </div>
                    <div class="col-sm-2 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">IGST amount</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_IGST_amount " name="quo_IGST_amount" disabled>
                            </div>
                            <span class="valid_err IGST_amount_err"></span>
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-sm-3 col-12 order-2 order-sm-1" style="border:1px solid #DFE3E7">
                        <label for="">Total</label>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control quo_total_amount" name="quo_total_amount" value="{{$quo->amount}}" placeholder="total">
                            </div>
                        </fieldset>
                        <span class="total_err valid_err"></span>
                    </div>
                </div>
                <hr>
                @endif
                <?php $y++; ?>
                @endforeach
                @endif


                <!---End quotation div-->


                <div class="row">
                    <div class="col-sm-4 col-12 order-2 order-sm-1">
                        <span class="valid_err quo_ser_err"></span>
                    </div>
                </div>
                 <input type="hidden" value="proforma" class="invoice_type">
                <div class="row">
                    <div class="col-sm-4 col-12 ">
                        <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Bank</label>
                                </div>
                                <select class="form-control bank" name="bank">
                                    <option value="" selected>Choose...</option>
                                    @foreach($banks as $ban)
                                    @if($ban->id==$row->bank)
                                    <option value="{{$ban->id}}" selected>{{$ban->bankname}}</option>
                                    @else
                                    <option value="{{$ban->id}}">{{$ban->bankname}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        <span class="valid_err bank_err"></span>
                    </div>
                    <div class="col-sm-4 col-12 order-2 order-sm-1">
                        <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Seal</label>
                                </div>
                                <select class="form-control seal" name="seal">
                                    @foreach($companies as $com)
                                    @if($com->seal==$row->seal)
                                    <option value="{{$com->seal}}" selected>{{$com->company_name}}</option>
                                    @else
                                    <option value="{{$com->seal}}">{{$com->company_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        <span class="valid_err seal_err"></span>
                    </div>
                    <div class="col-sm-4 col-12 order-2 order-sm-1">
                        <!-- <h4 class="text-primary">Invoice</h4>
                <input type="text" class="form-control" placeholder="Product Name"> -->
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Sign</label>
                                </div>
                                <select class="form-control sign" name="sign">
                                    @foreach($staffs as $staff)
                                    @if($staff->sid==1 || $staff->sid==10 || $staff->sid==15 || $staff->sid==7 ||
                                    $staff->sid==9 || $staff->sid==2 || $staff->sid==12)
                                    @if($staff->sid==$row->sign)
                                    <option value="{{$staff->sid}}" selected>{{$staff->name}}</option>
                                    @else
                                    <option value="{{$staff->sid}}">{{$staff->name}}</option>
                                    @endif
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        <span class="valid_err sign_err"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                       <label for="description">Description</label>
                                  <fieldset class="form-group">
                                    <div class="input-group">
                                     <textarea class="form-control" placeholder="Description" name="description" id="description">{{$row->description}}</textarea>
                                    </div>
                                  </fieldset>
                    </div>
                  </div>
                <div class="row">
                    <div class="col-auto mr-auto">
                        <a href="proforma_invoice_list" class="btn btn-icon btn-warning px-5 mr1 mb-1">Go Back</a>
                    </div>
                    <div class="col-auto">
                        <button type="button" name="update" class="btn btn-primary px-5 mr-3 update">Update</button>

                    </div>


                </div>

            </div>

        </div>
        </div>
    </form>
    <!-- invoice action  -->
    <div class="col-xl-3 col-md-4 col-12">
        <div class="card invoice-action-wrapper shadow-none border">
            <div class="card-body mt-1">
                <div class="invoice-action-btn mb-1">
                    <a href="download_invoice-{{$row->id}}-proforma" class="btn btn-light-primary btn-block">Download Invoice</a>
                </div>
                <div class="invoice-action-btn mb-1">
                    <a href="generate_invoice-{{$row->id}}-proforma" class="btn btn-light-primary btn-block">Preview</a>
                </div>
                <div class="invoice-action-btn mb-1">
                    <button type="button" class="btn btn-light-primary btn-block update">Update</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    </div>
    </div>

</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>
<script>
    $(document).ready(function() {


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.add_row', function() {
            var i = $('.total').val();
            var j = parseInt(i) + 1;
            $('.service_div').append(' <div class="row main_row"><div class="col-sm-6 col-12 order-2 order-sm-1"><fieldset class="form-group"> <div class="input-group"> <select class="form-control service service1" name="service[]"> <option value="" selected>Services</option> @foreach($services as $service) <option value="{{$service->id}}">{{$service->name}}</option> @endforeach </select> <span class="service_err valid_err"> </div> </fieldset></span> </div>  <div class="col-sm-6 col-12 order-2 order-sm-1"> <div class="row"> <div class="col-sm-5 col-12 order-2 order-sm-1"> <fieldset> <div class="form-label-group"> <input type="text" class="form-control amount amount1" name="amount" placeholder="Amount"> <label for="first-name-column">Amount</label> <span class="amount_err valid_err"></span> </div> </fieldset> </div> <div class="col-sm-4 col-12 order-2 order-sm-1"> <fieldset> <div class="form-label-group"> <input type="text" class="form-control discount discount1" name="discount" placeholder="Discount"> <label for="first-name-column">Discount</label> </div> </fieldset> <span class="discount_err valid_err"></span> </div> <div class="col-sm-3 col-12 order-2 order-sm-1"> <fieldset> <div class="form-label-group"> <div class="custom-control custom-switch custom-control-inline mb-1"> <input type="checkbox" class="custom-control-input round_check"  id="customSwitch' + j + '"> <label class="custom-control-label mr-1" for="customSwitch' + j + '"> </label> <span>Round</span> </div> </div> </fieldset> <span class="discount_err valid_err"></span> </div> </div> </div> @if($tax_applicable=="yes") @foreach($taxes as $tax) <div class="col-sm-1 col-12 order-2 order-sm-1"> <!-- <h4 class="text-primary">Invoice</h4> <input type="text" class="form-control" placeholder="Product Name"> --> <label for="">{{$tax->tax}}%</label> <fieldset class="form-group"> <div class="input-group"> <input type="text" class="form-control {{$tax->tax}}_percent" name="{{$tax->tax}}_percent" <?php if ($tax->status == "inactive") {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } ?>> </div> </fieldset> <span class="valid_err {{$tax->tax}}_percent_err"></span> </div> <div class="col-sm-2 col-12 order-2 order-sm-1"> <label for="">{{$tax->tax}} amount</label> <fieldset class="form-group"> <div class="input-group"> <input type="text" class="form-control {{$tax->tax}}_amount " name="{{$tax->tax}}_amount" <?php if ($tax->status == "inactive") {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } ?>> </div> </fieldset> <span class="valid_err {{$tax->tax}}_amount_err"></span> </div> @endforeach @endif<div class="col-sm-2 col-12 order-2 order-sm-1"> <label for="">Total</label> <fieldset class="form-group"> <div class="input-group"> <input type="text" class="form-control total_amount total_amount1"  name="total_amount" placeholder="total"> </div> </fieldset> <span class="total_err valid_err"></span> </div><div class="col-sm-1 col-12 order-2 order-sm-1"> <label for=""></label> <fieldset class="form-group"> <div class="input-group"> <button class="btn btn-light-danger btn-sm delete_row" type="button"> <i class="bx bx-trash-alt"></i> <span class="invoice-repeat-btn"></span> </button> </div> </fieldset> </div></div>');
            $('.total').val(j);
            $(".service").select2({

                dropdownAutoWidth: true,
                width: '100%',
                placeholder: "Select Services"
            });
        });
        $(document).on('click', '.delete_row', function() {


            var i = $('.total').val();
            var j = parseInt(i) - 1;
            // $('.total').val(j);
            $(this).closest('.main_row').remove();

        });
        $(document).on('change', '.client', function() {
            var client_id = $(this).val();

            $.ajax({
                type: 'post',
                url: 'get_bill_quotation',
                data: {
                    client_id: client_id
                },

                success: function(data) {
                    console.log(data);
                    $('.quotation_div').empty().html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });

        });
        $(document).on('click', '.update', function() {
            var status = $('.status').val();
            if (status == 'paid') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'can`t update. payment already done for this bill',
                    showConfirmButton: false,
                    showCloseButton: true,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,

                });
            } else {
                valid = validateinvoice();
                var arr = [];
                var service_array = [];
                var amount_array = [];
                var quo_array = [];
                var discount_array = [];
                var GST_percent_array = [];
                var GST_amount_array = [];
                var CGST_percent_array = [];
                var CGST_amount_array = [];
                var IGST_percent_array = [];
                var IGST_amount_array = [];
                var discount_array = [];
                var total_amount_array = [];
                var round_check_array = [];
                if ($('.quotation_check').is(":checked")) {

                    $('.quotation_check:checked').each(function() {
                        quo_array.push(this.value);
                    });
                    $('.quotation_check:checked').closest('.quo_main_row').find('.quo_round_check').each(function() {
                        round_check_array.push(this.value);
                    });
                    $('.quotation_check:checked').closest('.quo_main_row').find('.quo_amount').each(function() {
                        amount_array.push(this.value);
                    });
                    $('.quotation_check:checked').closest('.quo_main_row').find('.quo_discount').each(function() {
                        discount_array.push(this.value);
                    });
                    $('.quotation_check:checked').closest('.row').find('.quo_total_amount').each(function() {
                        total_amount_array.push(this.value);
                    });
                    if ($('.tax_app').val() == "yes") {

                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_GST_percent').each(function() {
                            GST_percent_array.push(this.value);
                        });
                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_GST_amount').each(function() {
                            GST_amount_array.push(this.value);
                        });
                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_CGST_percent').each(function() {
                            CGST_percent_array.push(this.value);
                        });
                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_CGST_amount').each(function() {
                            CGST_amount_array.push(this.value);
                        });
                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_IGST_percent').each(function() {
                            IGST_percent_array.push(this.value);
                        });
                        $('.quotation_check:checked').closest('.quo_main_row').find('.quo_IGST_amount').each(function() {
                            IGST_amount_array.push(this.value);
                        });


                    }
                    console.log(round_check_array);

                } else {
                    $('.service').each(function() {
                        service_array.push(this.value);
                    });
                    $('.amount').each(function() {
                        amount_array.push(this.value);

                    });
                    $('.discount').each(function() {
                        discount_array.push(this.value);
                    });
                    $('.total_amount').each(function() {
                        total_amount_array.push(this.value);
                    });
                    if ($('.tax_app').val() == "yes") {
                        $('.GST_percent').each(function() {
                            GST_percent_array.push(this.value);
                        });
                        $('.GST_amount').each(function() {
                            GST_amount_array.push(this.value);
                        });
                        $('.CGST_percent').each(function() {
                            CGST_percent_array.push(this.value);
                        });
                        $('.CGST_amount').each(function() {
                            CGST_amount_array.push(this.value);
                        });
                        $('.IGST_percent').each(function() {
                            IGST_percent_array.push(this.value);
                        });
                        $('.IGST_amount').each(function() {
                            IGST_amount_array.push(this.value);
                        });



                        $('.round_check').each(function() {
                            round_check_array.push(this.value);
                        });
                    }
                }

                var bill_id = $('.bill_id').val();
                var client = $('.client').val();
                var bill_date = $('.bill_date').val();
                var due_date = $('.due_date').val();
                var seal = $('.seal').val();
                var sign = $('.sign').val();
                var bank = $('.bank').val();
                var company = $('.company').val();
                var num = /^[0-9]+$/;
                var invoice_type = $('.invoice_type').val();
                var description = $('#description').val();

                if (valid) {

                    $.ajax({
                        type: 'post',
                        url: 'invoice_update',
                        data: {
                            bill_id: bill_id,
                            client: client,
                            bill_date: bill_date,
                            due_date: due_date,
                            seal: seal,
                            sign: sign,
                            service: service_array,
                            amount: amount_array,
                            quotation: quo_array,
                            bank: bank,
                            company: company,
                            gst_percent: GST_percent_array,
                            gst_amount: GST_amount_array,
                            cgst_percent: CGST_percent_array,
                            cgst_amount: CGST_amount_array,
                            igst_percent: IGST_percent_array,
                            igst_amount: IGST_amount_array,
                            discount: discount_array,
                            total_amount: total_amount_array,
                            round_check: round_check_array,
                            invoice_type:invoice_type,
                            description:description
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            if (res.status == 'success') {
                                $('.bill_view_body').empty().html(res.out);
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    text: res.msg,
                                    showConfirmButton: false,
                                    showCloseButton: true,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                })
                                setTimeout(function() {

                                    location.reload(true);
                                }, 2000);

                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    text: res.msg,
                                    showConfirmButton: false,
                                    showCloseButton: true,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                })

                            }


                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        });

        function validateinvoice() {

            var valid = true;
            var service_array = [];
            var amount_array = [];
            var quo_array = [];

            if ($('.quotation_check').is(":checked")) {

                $('.quotation_check:checked').each(function() {
                    quo_array.push(this.value);
                });

                $('.quotation_check:checked').closest('tr').find('.quo_amount').each(function() {
                    amount_array.push(this.value);
                });
                $('.service_err').html('');
                $('.amount_err').html('');
            } else {
                $('.service').each(function() {
                    if (this.value == '') {
                        $(this).closest('div').find('span').html('Services required')
                    } else {
                        $(this).closest('div').find('span').html('')
                    }
                    service_array.push(this.value);
                });
                $('.amount').each(function() {
                    if (this.value == '') {
                        $(this).closest('div').find('span').html('Amount required')
                    } else {
                        $(this).closest('div').find('span').html('')
                    }
                    amount_array.push(this.value);
                });
            }
            if ($('.client').val() == "") {
                $('.client_err').html('Client name required');
                valid = false;
            }
            if ($('.bill_date').val() == '') {
                $('.bill_date_err').html('Bill date name required');
                valid = false;
            }
            if ($('.due_date').val() == '') {
                $('.due_date_err').html('Due date required');
                valid = false;
            }
            if ($('.seal').val() == '') {
                $('.seal_err').html('Seal required');
                valid = false;
            }
            if ($('.sign').val() == '') {
                $('.sign_err').html('Sign name required');
                valid = false;
            }
            if ($('.bank').val() == '') {
                $('.bank_err').html('Bank name required');
                valid = false;
            }
            if ($('.company').val() == '') {
                $('.company_err').html('Company name required');
                valid = false;
            }


            if (quo_array.length == 0 && service_array.length == 0) {
                $('.quo_ser_err').html('Please select quotation or service');
                valid = false;
            }
            return valid;
        }
        $(document).on('change', '.quotation_check', function() {

            var service = $('.service').val();
            if (service != '') {
                if ($(".quotation_check").prop('checked') == true) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "you want to select quotation!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonText: 'No',
                        cancelButtonClass: 'btn btn-danger ml-1',
                        buttonsStyling: false,
                    }).then((willDelete) => {

                        if (willDelete) {

                            $('.service').val('');

                            $('.amount').val('');
                        } else {

                            $('.quotation_check').prop('checked', false);
                        }

                    });
                }

            } else {

            }

        });
        $(document).on('change', '.service', function() {
            $('.quotation_check').prop('checked', false);

        });
        $(document).on('change', '.mode_of_payment', function() {
            var mode = $(this).val();
            if (mode == "cheque") {
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'block';
                document.getElementById("bank_div").style.display = 'block';
            } else if (mode == "online") {
                document.getElementById("cheque_div").style.display = 'none';
                document.getElementById("ref_div").style.display = 'block';
                document.getElementById("bank_div").style.display = 'block';
            } else if (mode == 'cash') {
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'none';
                document.getElementById("bank_div").style.display = 'none';
            } else {
                document.getElementById("bank_div").style.display = 'none';
                document.getElementById("ref_div").style.display = 'none';
                document.getElementById("cheque_div").style.display = 'none';
            }
        });
    });
    $(document).on('keyup', '.discount', function() {
        var value = 0;


        var amount = $(this).closest('.main_row').find('.amount').val();
        var GST_percent = $(this).closest('.main_row').find('.GST_percent').val();
        var CGST_percent = $(this).closest('.main_row').find('.CGST_percent').val();
        var IGST_percent = $(this).closest('.main_row').find('.IGST_percent').val();
        var GST_amount = 0;
        var CGST_amount = 0;
        var IGST_amount = 0;
        if (isNaN(amount) || amount == "") {
            amount = 0;
        }
        amount = (parseFloat(amount) - parseFloat(this.value));
        if (!isNaN(GST_percent) && GST_percent.length != 0) {
            GST_amount = parseFloat((amount * GST_percent) / 100);
            $(this).closest('.main_row').find('.GST_amount').val(GST_amount);
        }
        if (!isNaN(CGST_percent) && CGST_percent.length != 0) {
            CGST_amount = parseFloat((amount * CGST_percent) / 100);
            $(this).closest('.main_row').find('.CGST_amount').val(CGST_amount);
        }

        if (!isNaN(IGST_percent) && IGST_percent.length != 0) {
            IGST_amount = parseFloat((amount * IGST_percent) / 100);
            $(this).closest('.main_row').find('.IGST_amount').val(IGST_amount);
        }

        if (!isNaN(this.value) && this.value.length != 0) {

            var total = parseFloat(amount) + (parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount));
        }

        if ((total - Math.floor(total)) != 0) {
            if ($(this).closest('.main_row').find('.round_check').is(":checked")) {
                $(this).closest('.main_row').find('.total_amount').val(Math.round(total));
            } else {
                $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));
            }
        } else {
            $(this).closest('.main_row').find('.total_amount').val(total);
        }



    });
    $(document).on('keyup', '.quo_discount', function() {
        var value = 0;



        var amount = $(this).closest('.quo_main_row').find('.quo_amount').val();
        var GST_percent = $(this).closest('.quo_main_row').find('.quo_GST_percent').val();
        var CGST_percent = $(this).closest('.quo_main_row').find('.quo_CGST_percent').val();
        var IGST_percent = $(this).closest('.quo_main_row').find('.quo_IGST_percent').val();
        var GST_amount = 0;
        var CGST_amount = 0;
        var IGST_amount = 0;
        if (isNaN(amount) || amount == "") {
            amount = 0;
        }
        amount = (parseFloat(amount) - parseFloat(this.value));
        if (!isNaN(GST_percent) && GST_percent.length != 0) {
            GST_amount = parseFloat((amount * GST_percent) / 100);
            $(this).closest('.quo_main_row').find('.quo_GST_amount').val(GST_amount);
        }
        if (!isNaN(CGST_percent) && CGST_percent.length != 0) {
            CGST_amount = parseFloat((amount * CGST_percent) / 100);
            $(this).closest('.quo_main_row').find('.quo_CGST_amount').val(CGST_amount);
        }

        if (!isNaN(IGST_percent) && IGST_percent.length != 0) {
            IGST_amount = parseFloat((amount * IGST_percent) / 100);
            $(this).closest('.quo_main_row').find('.quo_IGST_amount').val(IGST_amount);
        }

        if (!isNaN(this.value) && this.value.length != 0) {

            var total = parseFloat(amount) + parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount);
        }

        if ((total - Math.floor(total)) != 0) {
            if ($(this).closest('.quo_main_row').find('.quo_round_check').is(":checked")) {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));
            } else {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));
            }
        } else {
            $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);
        }



    });
    if ($('.tax_app').val() == "yes") {
        $(document).on('keyup', '.GST_percent', function() {
            var value = 0;

            var amount = $(this).closest('.main_row').find('.amount').val();
            var total_amount = $(this).closest('.main_row').find('.total_amount').val();
            var CGST_amount = $(this).closest('.main_row').find('.CGST_amount').val();
            var IGST_amount = $(this).closest('.main_row').find('.IGST_amount').val();
            var discount = $(this).closest('.main_row').find('.discount').val();


            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount) + parseFloat(value);
            }

            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.main_row').find('.GST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.GST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.main_row').find('.round_check').is(":checked")) {
                    $(this).closest('.main_row').find('.total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));
                }
            } else {


                $(this).closest('.main_row').find('.total_amount').val(total);


            }




        });
        $(document).on('keyup', '.CGST_percent', function() {
            var value = 0;



            var amount = $(this).closest('.main_row').find('.amount').val();
            var total_amount = $(this).closest('.main_row').find('.total_amount').val();
            var GST_amount = $(this).closest('.main_row').find('.GST_amount').val();
            var IGST_amount = $(this).closest('.main_row').find('.IGST_amount').val();
            var discount = $(this).closest('.main_row').find('.discount').val();
            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(GST_amount) + parseFloat(IGST_amount) + parseFloat(value);
            }
            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.main_row').find('.CGST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.CGST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.main_row').find('.round_check').is(":checked")) {
                    $(this).closest('.main_row').find('.total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.main_row').find('.total_amount').val(total);
            }



        });
        $(document).on('keyup', '.IGST_percent', function() {
            var value = 0;


            var amount = $(this).closest('.main_row').find('.amount').val();
            var GST_amount = $(this).closest('.main_row').find('.GST_amount').val();
            var CGST_amount = $(this).closest('.main_row').find('.CGST_amount').val();
            var discount = $(this).closest('.main_row').find('.discount').val();

            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(value);
            }
            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.main_row').find('.IGST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.IGST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.main_row').find('.round_check').is(":checked")) {
                    $(this).closest('.main_row').find('.total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.main_row').find('.total_amount').val(total);
            }



        });
       

        $(document).on('keyup', '.quo_GST_percent', function() {
            var value = 0;




            var amount = $(this).closest('.quo_main_row').find('.quo_amount').val();
            var total_amount = $(this).closest('.quo_main_row').find('.quo_total_amount').val();
            var CGST_amount = $(this).closest('.quo_main_row').find('.quo_CGST_amount').val();
            var IGST_amount = $(this).closest('.quo_main_row').find('.quo_IGST_amount').val();
            var discount = $(this).closest('.quo_main_row').find('.quo_discount').val();


            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount) + parseFloat(value);
            }

            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.row').find('.quo_GST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.row').find('.quo_GST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.quo_main_row').find('.quo_round_check').is(":checked")) {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);
            }




        });
        $(document).on('keyup', '.quo_CGST_percent', function() {
            var value = 0;



            var amount = $(this).closest('.quo_main_row').find('.quo_amount').val();
            var total_amount = $(this).closest('.quo_main_row').find('.quo_total_amount').val();
            var GST_amount = $(this).closest('.quo_main_row').find('.quo_GST_amount').val();
            var IGST_amount = $(this).closest('.quo_main_row').find('.quo_IGST_amount').val();
            var discount = $(this).closest('.quo_main_row').find('.quo_discount').val();
            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(GST_amount) + parseFloat(IGST_amount) + parseFloat(value);
            }
            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.quo_main_row').find('.quo_CGST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.quo_main_row').find('.quo_CGST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.quo_main_row').find('.quo_round_check').is(":checked")) {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);
            }



        });
        $(document).on('keyup', '.quo_IGST_percent', function() {
            var value = 0;



            var amount = $(this).closest('.quo_main_row').find('.quo_amount').val();
            var GST_amount = $(this).closest('.quo_main_row').find('.quo_GST_amount').val();
            var CGST_amount = $(this).closest('.quo_main_row').find('.quo_CGST_amount').val();
            var discount = $(this).closest('.quo_main_row').find('.quo_discount').val();

            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            amount = parseFloat(amount) - parseFloat(discount);
            if (!isNaN(this.value) && this.value.length != 0) {
                value = parseFloat((amount * this.value) / 100);
                var total = parseFloat(amount) + parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(value);
            }
            if ((value - Math.floor(value)) != 0) {
                $(this).closest('.quo_main_row').find('.quo_IGST_amount').val(value.toFixed(2));
            } else {
                $(this).closest('.quo_main_row').find('.quo_IGST_amount').val(value);
            }

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.quo_main_row').find('.quo_round_check').is(":checked")) {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);
            }



        });
   
    $(document).on('keyup', '.quo_amount', function() {
            var value = 0;




            var GST_percent = $(this).closest('.quo_main_row').find('.quo_GST_percent').val();
            var CGST_percent = $(this).closest('.quo_main_row').find('.quo_CGST_percent').val();
            var IGST_percent = $(this).closest('.quo_main_row').find('.quo_IGST_percent').val();
            var discount = $(this).closest('.quo_main_row').find('.quo_discount').val();
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var GST_value = 0;
            var CGST_value = 0;
            var IGST_value = 0;
            if (!isNaN(GST_percent) && GST_percent.length != 0) {
                GST_value = parseFloat((this.value * GST_percent) / 100);
            }
            if ((GST_value - Math.floor(GST_value)) != 0) {
                $(this).closest('.quo_main_row').find('.quo_GST_amount').val(GST_value.toFixed(2));
            } else {
                $(this).closest('.quo_main_row').find('.quo_GST_amount').val(GST_value);
            }

            if (!isNaN(CGST_percent) && CGST_percent.length != 0) {
                CGST_value = parseFloat((this.value * CGST_percent) / 100);
            }
            if ((CGST_value - Math.floor(CGST_value)) != 0) {
                $(this).closest('.quo_main_row').find('.quo_CGST_amount').val(CGST_value.toFixed(2));
            } else {
                $(this).closest('.quo_main_row').find('.quo_CGST_amount').val(CGST_value);
            }
            if (!isNaN(IGST_percent) && IGST_percent.length != 0) {
                IGST_value = parseFloat((this.value * IGST_percent) / 100);
            }
            if ((IGST_value - Math.floor(IGST_value)) != 0) {
                $(this).closest('.quo_main_row').find('.quo_IGST_amount').val(IGST_value.toFixed(2));
            } else {
                $(this).closest('.quo_main_row').find('.quo_IGST_amount').val(IGST_value);
            }
            var amount = parseFloat(this.value) - parseFloat(discount);
            var total = parseFloat(amount) + parseFloat(GST_value) + parseFloat(CGST_value) + parseFloat(IGST_value);

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.quo_main_row').find('.quo_round_check').is(":checked")) {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);
            }

        });
    $(document).on('click', '.round_check', function() {
            var amount = $(this).closest('.main_row').find('.amount').val();
            var discount = $(this).closest('.main_row').find('.discount').val();
            var GST_amount = $(this).closest('.main_row').find('.GST_amount').val();
            var CGST_amount = $(this).closest('.main_row').find('.CGST_amount').val();
            var IGST_amount = $(this).closest('.main_row').find('.IGST_amount').val();

            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var total = (parseFloat(amount) - parseFloat(discount)) + (parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount));
            if ($(this).is(":checked")) {
                $(this).val('yes');

            } else {
                $(this).val('no');

            }
            if ((total - Math.floor(total)) != 0) {
                if ($(this).is(":checked")) {
                    $(this).closest('.main_row').find('.total_amount').val(Math.round(total));

                } else {
                    $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));

                }
            } else {


                $(this).closest('.main_row').find('.total_amount').val(total);


            }
        });
    $(document).on('click', '.quo_round_check', function() {

            var amount = $(this).closest('.quo_main_row').find('.quo_amount').val();
            var discount = $(this).closest('.quo_main_row').find('.quo_discount').val();
            var GST_amount = $(this).closest('.quo_main_row').find('.quo_GST_amount').val();
            var CGST_amount = $(this).closest('.quo_main_row').find('.quo_CGST_amount').val();
            var IGST_amount = $(this).closest('.quo_main_row').find('.quo_IGST_amount').val();

            if (isNaN(GST_amount) || GST_amount == "") {
                GST_amount = 0;
            }
            if (isNaN(CGST_amount) || CGST_amount == "") {
                CGST_amount = 0;
            }
            if (isNaN(IGST_amount) || IGST_amount == "") {
                IGST_amount = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var total = (parseFloat(amount) - parseFloat(discount)) + (parseFloat(GST_amount) + parseFloat(CGST_amount) + parseFloat(IGST_amount));
            if ($(this).is(":checked")) {
                $(this).val('yes');

            } else {
                $(this).val('no');

            }
            if ((total - Math.floor(total)) != 0) {
                if ($(this).is(":checked")) {

                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(Math.round(total));

                } else {

                    $(this).closest('.quo_main_row').find('.quo_total_amount').val(total.toFixed(2));


                }
            } else {


                $(this).closest('.quo_main_row').find('.quo_total_amount').val(total);


            }
        });
     }
     $(document).on('keyup', '.amount', function() {
            var value = 0;
            var GST_percent = $(this).closest('.main_row').find('.GST_percent').val();
            var CGST_percent = $(this).closest('.main_row').find('.CGST_percent').val();
            var IGST_percent = $(this).closest('.main_row').find('.IGST_percent').val();
            var discount = $(this).closest('.main_row').find('.discount').val();
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }
            var GST_value = 0;
            var CGST_value = 0;
            var IGST_value = 0;
            if (!isNaN(GST_percent) && GST_percent.length != 0) {
                GST_value = parseFloat((this.value * GST_percent) / 100);
            }
            if ((GST_value - Math.floor(GST_value)) != 0) {
                $(this).closest('.main_row').find('.GST_amount').val(GST_value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.GST_amount').val(GST_value);
            }

            if (!isNaN(CGST_percent) && CGST_percent.length != 0) {
                CGST_value = parseFloat((this.value * CGST_percent) / 100);
            }
            if ((CGST_value - Math.floor(CGST_value)) != 0) {
                $(this).closest('.main_row').find('.CGST_amount').val(CGST_value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.CGST_amount').val(CGST_value);
            }
            if (!isNaN(IGST_percent) && IGST_percent.length != 0) {
                IGST_value = parseFloat((this.value * IGST_percent) / 100);
            }
            if ((IGST_value - Math.floor(IGST_value)) != 0) {
                $(this).closest('.main_row').find('.IGST_amount').val(IGST_value.toFixed(2));
            } else {
                $(this).closest('.main_row').find('.IGST_amount').val(IGST_value);
            }
            var amount = parseFloat(this.value) - parseFloat(discount);
            var total = parseFloat(amount) + parseFloat(GST_value) + parseFloat(CGST_value) + parseFloat(IGST_value);

            if ((total - Math.floor(total)) != 0) {
                if ($(this).closest('.main_row').find('.round_check').is(":checked")) {
                    $(this).closest('.main_row').find('.total_amount').val(Math.round(total));
                } else {
                    $(this).closest('.main_row').find('.total_amount').val(total.toFixed(2));
                }
            } else {
                $(this).closest('.main_row').find('.total_amount').val(total);
            }
        });
</script>

@endsection