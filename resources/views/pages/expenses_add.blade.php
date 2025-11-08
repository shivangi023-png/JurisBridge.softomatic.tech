<meta name="csrf-token" content="{{ csrf_token() }}">


@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add Expense')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{(asset('vendors/css/forms/select/select2.min.css'))}}">

@endsection
{{-- page styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/expense.css')}}">

@endsection

@section('content')


<section class="expense-edit-wrapper">
    <div class="row">

        <div class="col-xl-9 col-md-8 col-12">
            <div id="alert">


            </div>
            <div class="card">
                <div class="card-body pb-0 mx-25">

                    <form method="POST" action='' id="add_expense_form">
                        {{ csrf_field() }}
                        <div class="row mx-0 expense-info">
                            <div class="col-xl-6 col-md-12 d-flex align-items-center pl-0">
                                <h5 class="expense-number mb-0 mr-75">Expenses Entry</h6>

                            </div>
                            <div class="col-xl-6 col-md-12 px-0 pt-xl-0 pt-1">
                                <div class="expense-date-picker d-flex align-items-center justify-content-xl-end flex-wrap ">
                                    <div class="d-flex align-items-center">
                                        <fieldset class="d-flex" name="date">
                                            <input type="date" class="form-control date pickadate mr-2 mb-50 mb-sm-0" name="date" id="floating-label1" placeholder="Date">
                                            <span class="valid_err date_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row expense-info">
                            <div class="col-12 col-md-12">
                                <fieldset>
                                    <div class="input-group">

                                        <select class="form-control client" id="client" name="client">
                                            <option value="">Client name</option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$client->id == $client_id  ? 'selected' : ''}}>{{$client->case_no}} ({{$client->client_name}})</option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <span class="valid_err client_err"></span>
                                </fieldset>

                            </div>

                        </div><br>

                        <div class="row expense-info">
                            <div class="col-md-5 col-12">
                                <fieldset class="form-group">
                                    <div class="input-group">

                                        <select class="form-control ledger" id="ledger" name="ledger">
                                            <option value="">Ledger</option>
                                            @foreach($sub_heads as $head)
                                            <option value="{{$head->subhead_id}}">{{$head->sub_heads}}</option>
                                            @endforeach
                                        </select>
                                        <span class="valid_err ledger_err"></span>



                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-1 col-sm-1 col-12">

                                <button class="btn btn-light-primary btn-sm" data-repeater-create type="button" id="ledgeradd">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <div class="input-group">

                                            <select class="form-control by_whom" id="by_whom" name="by_whom">
                                                <option value=""></option>
                                                @foreach($staff as $stf)
                                                <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="valid_err by_whom_err"></span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="row expense-info" id="subhead_add">
                            <div class="col-md-5 col-12">
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">

                                            <span class="input-group-text">Head</span>
                                        </div>
                                        <select class="form-control head" id="head" name="head">
                                            <option value="">Choose...</option>
                                            @foreach($accounting_heads as $row)
                                            <option value="{{$row->id}}">{{$row->heads }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <span class="valid_err head_err"></span>
                                </fieldset>
                            </div>

                            <div class="col-md-5 col-12">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control subhead" id="subhead" placeholder="Subhead" name="subhead">
                                        <span class="valid_err subhead_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="col-md-1 col-12">
                                <button class="btn btn-light-success btn-sm" data-repeater-create type="button" id="ledgersubmit">
                                    Save
                                </button>
                            </div>
                            <div class="col-md-1 col-12">
                                <button class="btn btn-light-danger btn-sm" data-repeater-create type="button" id="ledgerdelete">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mode_of_payment">

                            <div class="col-md-10 col-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio radio-shadow">
                                                <input type="radio" class="form-control mode_of_payment" id="radioshadow1" name="mode_of_payment" value="cash">
                                                <label for="radioshadow1">Cash</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio radio-shadow">
                                                <input type="radio" class="form-control mode_of_payment" id="radioshadow2" name="mode_of_payment" value="cheque">
                                                <label for="radioshadow2">Cheque</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio radio-shadow">
                                                <input type="radio" class="form-control mode_of_payment" id="radioshadow3" name="mode_of_payment" value="online">
                                                <label for="radioshadow3">Online payment</label>
                                            </div>
                                        </fieldset>
                                    </li>

                                </ul>
                                <span class="valid_err mode_of_payment_err"></span>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-label-group">
                                    <fieldset>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input reimburse" id="checkbox1" value="yes" name="reimburse">
                                            <label for="checkbox1">Reimburse</label>
                                        </div>

                                </div>
                            </div>

                        </div>
                        <div class="row expense-info" id="amt_ref_div">
                            <div class="col-md-4 col-12" id="amountdiv">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control amount" id="floating-label1" placeholder="Amount" name="amount">
                                        <span class="valid_err amount_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-md-4 col-12" id="referencediv">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control ref_no" id="ref_no" placeholder="Reference no." name="ref_no">
                                        <span class="valid_err ref_no_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-md-4 col-12" id="cheque_div">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control chq_no" id="chq_no" placeholder="cheque no." name="chq_no">
                                        <span class="valid_err chq_no_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="col-md-4 col-12" id="bankdiv">
                                <div class="form-label-group">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control bank_name" id="bank_name" placeholder="Bank Name" name="bank_name">
                                        <span class="valid_err bank_name_err"></span>
                                    </fieldset>
                                </div>
                            </div>

                        </div>
                        <div class="row expense-info" id="remarksdiv">
                            <div class="col-12">
                                <div class="form-label-group">
                                    <fieldset class="form-group">

                                        <textarea class="form-control remarks" id="remarks" rows="3" placeholder="Remarks" name="remarks"></textarea>
                                        <span class="valid_err remarks_err"></span>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="row expense-info">

                            <div class="col-12">
                                <div class="form-label-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="expense_file" name="invoice">

                                        <span class="custom-file-label" for="expense_file">Upload Invoice</span>
                                    </div>
                                </div>
                                <span class="valid_err expense_file_err"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <a href="{{url()->previous()}}" class="btn btn-icon btn-warning mr-1 mb-1 px-5">Go Back</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" name="submit" class="btn btn-primary mr-3 px-5 submit">Submit</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                            </div>
                        </div>
                    </form>

                    <br><br>
                </div>
            </div>
        </div>

        <!-- expense action  -->
        <div class="col-xl-3 col-md-4 col-12">
            <div class="card expense-action-wrapper shadow-none border">
                <div class="card-body">

                    <div class="expense-action-btn mb-1">
                        <button type="button" id="download_expense" class="btn btn-light-primary btn-block">
                            <span>Download Expenses</span>
                        </button>
                    </div>
                    <div class="expense-action-btn mb-1">
                        <button type="button" id="preview" class="btn btn-light-primary btn-block">Preview</button>
                    </div>
                    <div class="expense-action-btn mb-1">
                        <button type="button" class="btn btn-light-primary btn-block submit" name="submit">Save</button>
                    </div>

                </div>
            </div>

        </div>
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
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        console.log("inside ajax")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#client").select2({

            dropdownAutoWidth: true,
            width: '100%',
            placeholder: "Select client"
        });

        $("#ledger").select2({

            dropdownAutoWidth: true,
            width: '100%',
            placeholder: "Ledger"
        });
        $("#by_whom").select2({

            dropdownAutoWidth: true,
            width: '100%',
            placeholder: "Expense By"
        });



        //hiding data entry fields for mode of payment

        $('#amt_ref_div').hide();
        $('#amountdiv').show();
        $('#cheque_div').hide();
        $('#subhead_add').hide();
        $('#remarksdiv').hide();
        $('#bankdiv').hide();

        //on clicking + sign near ledger
        $(document).on('click', '#ledgeradd', function() {
            console.log("inside ledgeradd");
            $('#subhead_add').show();
        });

        //save new ledger data
        $(document).on('click', '#ledgersubmit', function() {
            console.log('inside save button of ledger save');
            $('.valid_err').html('');
            var head = $('.head').val();
            var subhead = $('.subhead').val();
            var arr = [];
            if (subhead == '') {
                arr.push('subhead_err');
                arr.push('Sub-head Required');
            }
            if (head == '') {
                arr.push('head_err');
                arr.push('Head Required');
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
                    url: 'create_subhead',
                    data: {
                        subhead: subhead,
                        head: head
                    },
                    success: function(data) {
                        console.log(data);
                        $('.head').val('');
                        $('.subhead').val('');
                        $('#subhead_add').hide();

                        $('#ledger').empty().append(data);

                        $("#ledger").select2({

                            dropdownAutoWidth: true,
                            width: '100%',
                            placeholder: "Select Ledger"
                        });
                        var head = $('.head').val();
                        var subhead = $('.subhead').val();
                    },
                    error: function(data) {
                        console.log("data failed to pass");
                    }
                });
            }

        });

        //preview button clicked
        $(document).on('click', '#preview', function() {
            console.log("preview button clicked");
        });

        //ledger delete hides the field
        $(document).on('click', '#ledgerdelete', function() {
            console.log("inside ledgerdelete")
            $('#subhead_add').hide();
        });

        //on clicking cash radio btn
        $(document).on('click', '#radioshadow1', function() {
            console.log("inside radioshadow1")
            $('#amt_ref_div').show();
            $('#amountdiv').show();
            $('#referencediv').hide();
            $('#cheque_div').hide();
            $('#remarksdiv').show();
            $('#bankdiv').hide();
        });

        //on clicking cheque radio btn
        $(document).on('click', '#radioshadow2', function() {
            console.log("inside radioshadow2")
            $('#amt_ref_div').show();
            $('#amountdiv').show();
            $('#referencediv').hide();
            $('#cheque_div').show();
            $('#bankdiv').show();
            $('#remarksdiv').show();

        });

        //on clicking online radio btn
        $(document).on('click', '#radioshadow3', function() {
            console.log("inside radioshadow3")
            $('#amt_ref_div').show();
            $('#amountdiv').show();
            $('#referencediv').show();
            $('#cheque_div').hide();
            $('#remarksdiv').show();
            $('#bankdiv').show();
        });

        $(document).on('click', '.submit', function() {

            $('.valid_err').html(''); //function to display errors

            //variables created here-------------------->

            var mode_of_payment = $('.mode_of_payment:checked').val();
            var client = $('.client').val();
            var ledger = $('.ledger').val();
            var by_whom = $('.by_whom').val();
            var date = $('.date').val();
            var amount = $('.amount').val();
            var ref_no = $('.ref_no').val();
            var reimburse = $('.reimburse:checked').val();
            var bank_name = $('.bank_name').val();
            var remarks = $('.remarks').val();
            var expense_file = $('#expense_file').val();
            var cheque_no = $('.chq_no').val();
            var form = $('#add_expense_form')[0];
            var formdata = new FormData(form);
     
            var date = $('.date').val(); //getting date entered by user
            var today_date = new Date().toISOString().substr(0,
                10); //date variable converting to string for date validation
            var num = /^[0-9]+$/; //array for validating a number
            var arr = []; //array for storing error data
            var selected_company=$('.selected-company-id').val(); 
            formdata.append("selected_company", selected_company);
            //validating date below
            if (date == '') {
                arr.push('date_err');
                arr.push('date required');
            }

            if (date != '') {
                if (date.substr(6, 4) > today_date.substr(0, 4)) {
                    arr.push('date_err');
                    arr.push('Future Dates can\'t be used!');
                } else if (date.substr(6, 4) == today_date.substr(0, 4)) {

                    if (date.substr(3, 2) > today_date.substr(5, 2)) {
                        arr.push('date_err');
                        arr.push('Future Dates can\'t be used!');
                    } else if (date.substr(3, 2) == today_date.substr(5, 2)) {
                        if (date.substr(0, 2) > today_date.substr(8, 2)) {
                            arr.push('date_err');
                            arr.push('Future Dates can\'t be used!');
                        }
                    }
                }
            }

            //ledger validation
            if (ledger == '') {
                arr.push('ledger_err');
                arr.push('ledger required');
            }

            //Expense by validation
            if (by_whom == '') {
                arr.push('by_whom_err');
                arr.push('Expense By required');
            }

            //file validation
            if (expense_file != '') {
                var file_size = $('#expense_file')[0].files[0].size;
                if (file_size > 1000000) {

                    arr.push('expense_file_err');
                    arr.push('expense file must be less than 1-Mb');
                }
            }

            //amount validation
            if (amount == '') {
                arr.push('amount_err');
                arr.push('amount required');
            }

            if (amount != '') {
                if (num.test(amount) == false) {
                    alert('please enter amount');
                    arr.push('amount_err');
                    arr.push('Valid amount required');
                }
            }

            //mode of payment validation
            if (mode_of_payment == null) {
                arr.push('mode_of_payment_err');
                arr.push('Mode of payment required');
            }
            if (mode_of_payment != '') {
                if (mode_of_payment == 'online') {
                    if (ref_no == '') {
                        arr.push('ref_no_err');
                        arr.push('Reference no required');
                    }
                    if (bank_name == '') {
                        arr.push('bank_name_err');
                        arr.push('Bank name required');
                    }

                }
                if (mode_of_payment == 'cheque') {
                    if (cheque_no == '' || num.test(cheque_no) == false) {
                        arr.push('chq_no_err');
                        arr.push('Valid cheque no required');
                    }
                    if (bank_name == '') {
                        arr.push('bank_name_err');
                        arr.push('Bank name required');
                    }

                }

            }

            //displaying errors and checking for any remaining errors before sending data

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;


                    $('.' + arr[i]).html(arr[j]).css('color', 'red');



                    i = j;
                }
            } else {

                $.ajax({
                    type: 'POST',
                    url: 'expense_entry',
                    data: formdata,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {

                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                            $("#add_expense_form").trigger('reset');
                            $('#bankdiv').hide();
                            $('#referencediv').hide();
                            $('#cheque_div').hide();

                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>').focus();
                        }



                    },

                    error: function(data) {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        console.log(data);
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
                        ).focus();
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });

            }
        });
    });

    function validFile(ext) {

        var extension = ext;
        var msg = "";
        switch (extension) {
            case 'PDF':
            case 'jpg':
            case 'pdf':
            case 'peg':
            case 'png':
            case 'doc':
            case 'ocx':

                msg = ""; // There's was a typo in the example where
                return msg;
                break;
            default:
                msg = "File type must be pdf,doc or docx,jpg,png";
                return msg;
        }
    }
</script>
@endsection