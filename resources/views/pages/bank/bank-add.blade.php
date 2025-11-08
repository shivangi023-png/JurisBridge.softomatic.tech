@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','New Bank')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-bank.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<style>
    .valid_err {

        font-size: 12px;
    }

    .valid_err1 {

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
                <div class="card-body">
                    @include('layouts.tabs')
                    <form class="form" id="form">

                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="bankname" name="bankname" placeholder="Bank Name">
                                        <label for="bankname">Bank Name</label>
                                        <span class="bankname_err valid_err"></span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="branchname" name="branchname" placeholder="Bank Branch">
                                        <label for="branchname">Branch</label>
                                        <span class="branchname_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="accnumber" name="accnumber" placeholder="Account Number">
                                        <label for="accnumber">Account Number</label>
                                        <span class="accnumber_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="ifsccode" name="ifsccode" placeholder="IFSC Code">
                                        <label for="ifsccode">IFSC Code</label>
                                        <span class="ifsccode_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <label for="">Select Company</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="company" id="company" class="form-control">
                                                    <option value="">Select Company</option>
                                                    @foreach($company as $com)
                                                    <option value="{{$com->id}}">{{$com->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="company_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <textarea type="text" class="form-control" id="bankaddress" name="bankaddress" placeholder="Bank address"></textarea>
                                        <label for="bankaddress">Address</label>
                                        <span class="bankaddress_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="default_bank_account" id="default_bank_account">
                                            <label class="custom-control-label" for="default_bank_account">Default Bank
                                                Account</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                            <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bank-list-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="data_div">
                            <div class="table-responsive">
                                <table class="table bank-data-table wrap">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>id</th>
                                            <th>Bank Name</th>
                                            <th>Branch</th>
                                            <th>Account Number</th>
                                            <th>IFSC Code</th>
                                            <th>Bank Address</th>
                                            <th>Default Bank Account</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($bank_details as $val)
                                        <tr>
                                            <td><a href="#" class="updateModal btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-toggle="modal" data-target="#updateModal" data-bank_id="{{$val->id}}" data-bankname="{{$val->bankname}}" data-branchname="{{$val->branchname}}" data-accnumber="{{$val->accnumber}}" data-ifsccode="{{$val->ifsccode}}" data-bankaddress="{{$val->bankaddress}}" data-company="{{$val->company}}" data-default_bank_account="{{$val->default_bank_account}}" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                                            </td>
                                            <td>{{$i++}}</td>
                                            <td>{{$val->bankname}}</td>
                                            <td>{{$val->branchname}}</td>
                                            <td>{{$val->accnumber}}</td>
                                            <td>{{$val->ifsccode}}</td>
                                            <td>{{$val->bankaddress}}</td>
                                            <td>
                                                @if($val->default_bank_account=='yes')
                                                <span class="badge badge-pill badge-light-success">{{$val->default_bank_account}}</span>
                                                @else
                                                <span class="badge badge-pill badge-light-danger">{{$val->default_bank_account}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" style="width:900px" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-pink">
                        <h4 class="modal-title" id="uModalLabel">Edit Bank</h4>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="hidden" class="form-control bank_id" id="bank_id" name="bank_id" value="">
                                        <input type="text" class="form-control bankname" id="bankname" name="bankname" placeholder="Bank Name">
                                        <label for="bankname">Bank Name</label>
                                        <span class="bankname_err1 valid_err1"></span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control branchname" id="branchname" name="branchname" placeholder="Bank Branch">
                                        <label for="branchname">Branch</label>
                                        <span class="branchname_err1 valid_err1"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control accnumber" id="accnumber" name="accnumber" placeholder="Account Number">
                                        <label for="accnumber">Account Number</label>
                                        <span class="accnumber_err1 valid_err1"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control ifsccode" id="ifsccode" name="ifsccode" placeholder="IFSC Code">
                                        <label for="ifsccode">IFSC Code</label>
                                        <span class="ifsccode_err1 valid_err1"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <label for="">Select Company</label>
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <select name="company" id="company" class="form-control company">
                                                    <option value="">Select Company</option>
                                                    @foreach($company as $com)
                                                    <option value="{{$com->id}}">{{$com->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="company_err1 valid_err1"></span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-label-group">
                                        <textarea type="text" class="form-control bankaddress" id="bankaddress" name="bankaddress" placeholder="Bank address"></textarea>
                                        <label for="bankaddress">Address</label>
                                        <span class="bankaddress_err1 valid_err1"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input default_bank_account" name="default_bank_account" id="default_bank_account1">
                                            <label class="custom-control-label" for="default_bank_account1">Default Bank
                                                Account</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary update" id="update">Update
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
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
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/bank.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit').click(function() {

            $('.valid_err').html('');
            var arr = [];

            var bankname = $('#bankname').val();
            var branchname = $('#branchname').val();
            var accnumber = $('#accnumber').val();
            var ifsccode = $('#ifsccode').val();
            var bankaddress = $('#bankaddress').val();
            var company = $('#company').val();
            var default_bank_account = $('#default_bank_account:checked').val();

            if (bankname == '') {
                arr.push('bankname_err');
                arr.push('Bank name required');
            }

            if (branchname == '') {
                arr.push('branchname_err');
                arr.push('Branch name required');
            }

            if (accnumber == '') {
                arr.push('accnumber_err');
                arr.push('Account Number required');
            }

            if (ifsccode == '') {
                arr.push('ifsccode_err');
                arr.push('IFSC code required');
            }

            if (bankaddress == '') {
                arr.push('bankaddress_err');
                arr.push('Bank address required');
            }

            if (company == '') {
                arr.push('company_err');
                arr.push('Company required');
            }

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {

                $.ajax({
                    type: 'post',
                    url: 'bank_add',
                    data: {
                        bankname: bankname,
                        branchname: branchname,
                        accnumber: accnumber,
                        ifsccode: ifsccode,
                        bankaddress: bankaddress,
                        company: company,
                        default_bank_account: default_bank_account
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
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');

                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            get_bank_update();
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
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }

                });
            }

        });

        $('#update').click(function() {

            $('.valid_err1').html('');
            var arr = [];
            var id = $('.bank_id').val();
            var bankname = $('.bankname').val();
            var branchname = $('.branchname').val();
            var accnumber = $('.accnumber').val();
            var ifsccode = $('.ifsccode').val();
            var bankaddress = $('.bankaddress').val();
            var company = $('.company').val();
            var default_bank_account = $('.default_bank_account:checked').val();

            if (bankname == '') {
                arr.push('bankname_err1');
                arr.push('Bank name required');
            }

            if (branchname == '') {
                arr.push('branchname_err1');
                arr.push('Branch name required');
            }

            if (accnumber == '') {
                arr.push('accnumber_err1');
                arr.push('Account Number required');
            }

            if (ifsccode == '') {
                arr.push('ifsccode_err1');
                arr.push('IFSC code required');
            }

            if (bankaddress == '') {
                arr.push('bankaddress_err1');
                arr.push('Bank address required');
            }

            if (company == '') {
                arr.push('company_err1');
                arr.push('Company required');
            }



            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {

                $.ajax({
                    type: 'post',
                    url: 'bank_update',
                    data: {
                        id: id,
                        bankname: bankname,
                        branchname: branchname,
                        accnumber: accnumber,
                        ifsccode: ifsccode,
                        bankaddress: bankaddress,
                        company: company,
                        default_bank_account: default_bank_account
                    },

                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: 'Updated!',
                                text: 'Bank has been updated.',
                                confirmButtonClass: 'btn btn-success',
                            })
                            $("#updateModal").modal("hide");
                            get_bank_update();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: 'Error!',
                                text: 'Bank can`t be updated.',
                                confirmButtonClass: 'btn btn-danger',
                            })

                        }

                    },
                    error: function(data) {
                        console.log(data);
                        Swal.fire({
                            icon: "error",
                            title: 'Error!',
                            text: 'something went wrong. try again later',
                            confirmButtonClass: 'btn btn-danger',
                        })
                    }

                });
            }

        });
    });

    $(document).on('click', '.updateModal', function() {
        $('.bank_id').val($(this).data('bank_id'));
        $('.bankname').val($(this).data('bankname'));
        $('.branchname').val($(this).data('branchname'));
        $('.accnumber').val($(this).data('accnumber'));
        $('.ifsccode').val($(this).data('ifsccode'));
        $('.company').val($(this).data('company'));
        $('.bankaddress').val($(this).data('bankaddress'));

        if ($(this).data('default_bank_account') == 'yes') {
            $('.default_bank_account').prop('checked', true);
        } else if ($(this).data('default_bank_account') == 'no') {
            $('.default_bank_account').prop('checked', false);
        }

        $('#updateModal').modal('show');
    });

    function get_bank_update() {
        $(".loader").css("display", "block");
        $.ajax({
            type: "get",
            url: "get_bank_update",
            datatype: "text",

            success: function(data) {
                console.log(data);
                $(".loader").css("display", "none");
                $(".data_div").html(data);
            },
            error: function(data) {
                $(".loader").css("display", "none");
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    }
</script>

@endsection