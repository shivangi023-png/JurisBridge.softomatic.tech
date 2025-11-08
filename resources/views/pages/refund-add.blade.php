@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Refund')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
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
            <div class="card">
                <div class="card-header">
                    <h6>Add Refund</h6>
                </div>
                <div class="card-body">
                    <form class="form" id="form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="client_id" id="client_id" class="form-control">
                                                    <option value="">Select Client</option>
                                                    @foreach($clients as $cl)
                                                    <option value="{{$cl->id}}">{{$cl->client_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="client_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="bank_id" id="bank_id" class="form-control">
                                                    <option value="">Select Bank</option>
                                                    @foreach($bank as $b)
                                                    <option value="{{$b->id}}">{{$b->bankname}}-{{$b->accnumber}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="bank_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="amount" name="amount"
                                            placeholder="Amount">
                                        <label for="amount">Amount</label>
                                        <span class="amount_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control pickadate" id="deposite_date"
                                            name="deposite_date" placeholder="Date">
                                        <label for="deposite_date">Date</label>
                                        <span class="deposite_date_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="mode_of_payment" id="mode_of_payment" class="form-control"
                                                    onchange="getpaymentMode(this.value)">
                                                    <option value="">Mode of Payment</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="online">Online</option>
                                                </select>
                                            </div>
                                            <span class="mode_of_payment_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <textarea class="form-control" id="remark" name="remark" autocomplete="off"
                                            placeholder="Remark"></textarea>
                                        <label for="remark">Remark</label>
                                        <span class="remark_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4 bank_div" style="display:none;">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="deposite_bank" name="deposite_bank"
                                            placeholder="Bank Name">
                                        <label for="deposite_bank">Bank Name</label>
                                        <span class="deposite_bank_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4 cheque_div" style="display:none;">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" placeholder="Cheque Number"
                                            id="cheque_no" name="cheque_no">
                                        <label for="cheque_no">Cheque Number</label>
                                        <span class="cheque_no_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4 reference_div" style="display:none;">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" placeholder="Reference Number"
                                            id="ref_no" name="ref_no">
                                        <label for="ref_no">Reference Number</label>
                                        <span class="password_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" id="submit" name="submit"
                                        class="btn btn-primary mr-3 px-5">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($(".pickadate").length) {
        $(".pickadate").pickadate({
            format: "dd/mm/yyyy",
            onStart: function() {
                this.set({
                    select: new Date()
                });
            },
        });
    }


    $("#client_id").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Select Client",
    });

    $("#bank_id").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Select Bank",
    });

    $(document).on('click', '#submit', function() {
        $('.valid_err').html('');
        var arr = [];
        var client_id = $('#client_id').val();
        var bank_id = $('#bank_id').val();
        var amount = $('#amount').val();
        var mode_of_payment = $('#mode_of_payment').val();
        var deposite_bank = $('#deposite_bank').val();
        var deposite_date = $('#deposite_date').val();
        var cheque_no = $('#cheque_no').val();
        var ref_no = $('#ref_no').val();
        var remark = $('#remark').val();

        if (client_id == '') {
            arr.push('client_err');
            arr.push('Client name required');
        }

        if (bank_id == '') {
            arr.push('bank_err');
            arr.push('Bank required');
        }

        if (amount == '') {
            arr.push('amount_err');
            arr.push('Amount required');
        }

        if (deposite_date == '') {
            arr.push('deposite_date_err');
            arr.push('Date required');
        }

        if (mode_of_payment == '') {
            arr.push('mode_of_payment_err');
            arr.push('Mode of payment required');
        }

        if (remark == '') {
            arr.push('remark_err');
            arr.push('Remark required');
        }

        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;
                $('.' + arr[i]).html(arr[j]).css('color', 'red');
                i = j;
            }
        } else {
            $.ajax({
                type: 'POST',
                url: "refund-add",
                data: {
                    client_id: client_id,
                    bank_id: bank_id,
                    amount: amount,
                    mode_of_payment: mode_of_payment,
                    deposite_bank: deposite_bank,
                    deposite_date: deposite_date,
                    cheque_no: cheque_no,
                    ref_no: ref_no,
                    remark: remark
                },
                success: function(data) {
                    console.log(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    var res = JSON.parse(data);
                    if (res.status == 'success') {
                        $("#form").trigger('reset');
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>');

                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    } else {
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            res.msg + '</span></div></div>');

                    }
                },
                error: function(data) {
                    console.log(data);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                    );
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            });
        }
    });
});

function getpaymentMode(val) {
    if (val == 'cash') {
        $('.bank_div').hide();
        $('.cheque_div').hide();
        $('.reference_div').hide();
    }

    if (val == 'cheque') {
        $('.bank_div').show();
        $('.cheque_div').show();
        $('.reference_div').hide();
    }

    if (val == 'online') {
        $('.bank_div').show();
        $('.cheque_div').hide();
        $('.reference_div').show();
    }
}
</script>

@endsection