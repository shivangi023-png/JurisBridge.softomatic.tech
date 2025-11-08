<style>
    .datepicker-days {
        width: 235px !important;
        height: 220px !important;
    }
</style>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Client</th>
                <th>Place</th>
                <th>Fees</th>
                <th>Payment mode</th>
                <th>Cheque no</th>
                <th>Cheque date</th>
                <th>Reference</th>
                <th>Remark</th>
                <th>Bank</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($data as $row)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$row->client_case_no}}</td>
                <td>{{$row->place_name}}</td>
                <td>{{$row->fees}}</td>
                <td>
                    <div class="up_div" style="display:none">
                        <select class="form-control up_payment_mode">
                            <option value="">payment mode</option>
                            <option value="cash">cash</option>
                            <option value="cheque">cheque</option>
                            <option value="online">online</option>
                        </select>
                        <span class="valid_err up_payment_mode_err"></span>
                    </div>
                    <div class="nup_div">{{$row->payment_mode}}</div>
                </td>
                <td>
                    <div class="up_cheque_div" style="display:none">
                        <input type="text" placeholder="Cheque no" class="form-control up_cheque_no">
                        <span class="valid_err up_cheque_no_err"></span>
                    </div>
                    <div class="nup_cheque_div">{{$row->cheque_no}}</div>
                </td>
                <td>
                    <div class="up_cheque_div" style="display:none">
                        <input type="text" placeholder="Cheque Date" class="form-control datepicker up_cheque_date">
                        <span class="valid_err up_cheque_date_err"></span>
                    </div>
                    <div class="nup_cheque_div">{{$row->cheque_date}}</div>
                </td>
                <td>
                    <div class="up_online_div" style="display:none">
                        <input type="text" placeholder="Reference" class="form-control up_reference">
                        <span class="valid_err up_reference_err"></span>
                    </div>
                    <div class="nup_online_div">{{$row->reference}}</div>
                </td>
                <td>
                    <div class="up_online_div" style="display:none">
                        <textarea class="form-control up_remark" placeholder="Remark"></textarea>
                        <span class="valid_err up_remark_err"></span>
                    </div>
                    <div class="nup_online_div">{{$row->remark}}</div>
                </td>
                <td>
                    <div class="up_cheque_div" style="display:none"> <select class="form-control up_bank">
                            <option value="">Bank</option>

                            @foreach ($bank as $ban) {
                            <option value="{{$ban->id}}">{{$ban->bankname}}</option>
                            @endforeach
                        </select>
                        <span class="valid_err up_bank_err"></span>
                    </div>
                    <div class="nup_cheque_div">{{$row->bankname}}</div>
                </td>
                <td>
                    <div class="row">
                        <span class="edit_con">
                            <div class="col-2">
                                <button type="button" data-id="{{$row->id}}" class="btn btn-icon btn-xs rounded-circle glow btn-success edit_consulting_btn" data-tooltip="Edit Fee">
                                    <i class="bx bx-edit"></i></button>
                            </div>
                        </span>

                        <span class="up_con" style="display:none">
                            <div class="col-2">
                                <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary up_consulting_btn" data-tooltip="Done">
                                    <i class="bx bx-check-square"></i></button>
                            </div>
                            <div class="col-2">
                                <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-warning cancle_btn" data-tooltip="Cancel">
                                    <i class="bx bx-window-close"></i></button>
                            </div>
                        </span>

                        <span>
                            <div class="col-2">
                                <button type="button" data-id="{{$row->id}}" data-appointment_id="{{$row->appointment_id}}" class="btn btn-xs btn-icon rounded-circle glow btn-danger delete_consulting_btn" data-tooltip="Delete Fee">
                                    <i class="bx bx-trash"></i></button>
                            </div>
                        </span>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $(".datepicker")
            .datepicker()
            .on("changeDate", function(ev) {
                $(".datepicker.dropdown-menu").hide();
            });
    });
</script>