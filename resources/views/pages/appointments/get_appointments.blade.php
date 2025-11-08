<div class="action-dropdown-btn d-none">
    <div class="dropdown client-filter-action">
        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if($status)
            <span class="selection">{{ucwords(strtoupper($status))}}</span>
            @else
            <span class="selection">Filter Appointment</span>
            @endif
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
            <a type="button" href="#" class="dropdown-item active_btn" data-value="finalize">Finalize</a>
            <a type="button" href="#" class="dropdown-item active_btn" data-value="pending">Pending</a>

        </div>
    </div>
    <div class="client-options">
        <a href="appointment-add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
            <i class="bx bx-plus"></i>New Appointment</a>

    </div>
</div>
<div class="card">
    <div class="card-body">
        <input class="appointment_filter" type="hidden" value="{{$status}}">
        <div class="table-responsive">
            <table class="table client-data-table wrap">
                <thead>
                    @if ($status == 'finalize')
                    <tr>
                        <th></th>
                        <th width="20%">Action</th>
                        <th>Client Name </th>
                        <th>Meeting Type</th>
                        <th>Online Meeting</th>
                        <th>Status</th>
                        <th>Mode of payment</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Scheduled BY</th>
                        <th>Attended by</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointmentsData as $item)
                    <tr>
                        <td></td>
                        <td>
                            <div class="row client-action">
                                @if ($item->status == 'finalize')
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-success mr-1 mb-1 view_consulting_btn" data-toggle="modal" data-target="#viewConsultingFee" data-appointment_id="{{$item->id}}" data-tooltip="View Fee">
                                        <i class="bx bx-down-arrow-alt"></i></button>
                                </div>
                                @else
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-success mr-1 mb-1 consulting_pay_btn" data-appointment_id="{{$item->id}}" data-fees="{{$item->charges}}" data-toggle="modal" data-target="#modalconsultingfee" data-tooltip="Pay Fee">
                                        <i class="bx bx-credit-card"></i></button>
                                </div>
                                @endif
                                @if ($item->status == 'finalize')
                                <div class="col-2">
                                    @if(session('header_footer')=='no')
                                    hello{{session('company_id')}}<a href="consulting_fee_reciept-{{$item->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-appointment_id="{{$item->id}}" data-tooltip="Generate Reciept">
                                        <i class="bx bx-printer"></i></a>
                                    @else
                                    <a href="consulting_fee_reciept_UT-{{$item->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-appointment_id="{{$item->id}}" data-tooltip="Generate Reciept">
                                        <i class="bx bx-printer"></i></a>
                                    @endif
                                </div>
                                @else
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" disabled="disabled">
                                        <i class="bx bx-printer"></i></button>
                                </div>
                                @endif
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-info mr-1 mb-1 remodalbtn" data-appointment_id="{{$item->id}}" data-meeting_with="{{$item->meeting_with}}" data-place="{{$item->place}}" data-time="{{$item->meeting_time}}" data-date="{{$item->meeting_date}}" data-online_meeting="{{$item->online_meeting}}" data-toggle="modal" data-target="#reshcheduleModal" data-tooltip="Reschedule">
                                        <i class="bx bx-reset"></i></button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_appointment_btn" data-appointment_id="{{$item->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash"></i></button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-secondary mr-1 mb-1 view_notes" data-appointment_id="{{$item->id}}" data-meeting_notes="{{$item->meeting_notes}}" data-tooltip="Meeting Notes">
                                        <i class="bx bx-note"></i></button>
                                </div>
                            </div>
                        </td>
                        <td><span class="client-customer">{{$item->client_case_no}}</span></td>
                        <td>{{$item->aname}}</td>
                        <td>{{$item->online_meeting}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->payment_mode}}</td>
                        <td data-sort="{{strtotime($item->meeting_date)}}">{{date("d-m-Y", strtotime($item->meeting_date))}}</td>
                        <td>{{$item->meeting_time}}</td>
                        <td>{{$item->scheduled_by_staff}}</td>
                        <td>{{$item->meetname}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <th></th>
                        <th width="20%">Action</th>
                        <th>Client Name </th>
                        <th>Meeting Type</th>
                        <th>Online Meeting</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Scheduled By</th>
                        <th>Attended by</th>
                    </tr>
                    </thead>
                <tbody>
                    @foreach ($appointmentsData as $item)
                    <tr>
                        <td></td>
                        <td>
                            <div class="row client-action">
                                @if ($item->status == 'finalize')
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-success mr-1 mb-1 view_consulting_btn" data-toggle="modal" data-target="#viewConsultingFee" data-appointment_id="{{$item->id}}" data-tooltip="View Fee">
                                        <i class="bx bx-down-arrow-alt"></i></button>
                                </div>
                                @else
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-success mr-1 mb-1 consulting_pay_btn" data-appointment_id="{{$item->id}}" data-fees="{{$item->charges}}" data-toggle="modal" data-target="#modalconsultingfee" data-tooltip="Pay Fee">
                                        <i class="bx bx-credit-card"></i></button>
                                </div>
                                @endif
                                @if ($item->status == 'finalize')
                                 <div class="col-2">
                                    @if(session('header_footer')=='no')
                                    hello{{session('company_id')}}<a href="consulting_fee_reciept-{{$item->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-appointment_id="{{$item->id}}" data-tooltip="Generate Reciept">
                                        <i class="bx bx-printer"></i></a>
                                    @else
                                    <a href="consulting_fee_reciept_UT-{{$item->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-appointment_id="{{$item->id}}" data-tooltip="Generate Reciept">
                                        <i class="bx bx-printer"></i></a>
                                    @endif
                                </div>
                                @else
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" disabled="disabled">
                                        <i class="bx bx-printer"></i></button>
                                </div>
                                @endif
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-info mr-1 mb-1 remodalbtn" data-appointment_id="{{$item->id}}" data-meeting_with="{{$item->meeting_with}}" data-place="{{$item->place}}" data-time="{{$item->meeting_time}}" data-date="{{$item->meeting_date}}" data-online_meeting="{{$item->online_meeting}}" data-toggle="modal" data-target="#reshcheduleModal" data-tooltip="Reschedule">
                                        <i class="bx bx-reset"></i></button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_appointment_btn" data-appointment_id="{{$item->id}}" data-tooltip="Delete">
                                        <i class="bx bx-trash"></i></button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-icon rounded-circle glow btn-secondary mr-1 mb-1 view_notes" data-appointment_id="{{$item->id}}" data-meeting_notes="{{$item->meeting_notes}}" data-tooltip="Meeting Notes">
                                        <i class="bx bx-note"></i></button>
                                </div>
                            </div>
                        </td>
                        <td><span class="client-customer">{{$item->client_case_no}}</span></td>
                        <td>{{$item->aname}}</td>
                        <td>{{$item->online_meeting}}</td>
                        <td>{{$item->status}}</td>
                        <td data-sort="{{strtotime($item->meeting_date)}}">{{date("d-m-Y", strtotime($item->meeting_date))}}</td>
                        <td>{{$item->meeting_time}}</td>
                        <td>{{$item->scheduled_by_staff}}</td>
                        <td>{{$item->meetname}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    if ($(".client-data-table").length) {
        var dataListView = $(".client-data-table").DataTable({

            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            language: {
                search: "",
                searchPlaceholder: "Search Appointment"
            },

            select: {
                style: "multi",
                selector: "td:first-child",
                items: "row"
            },
            responsive: {
                details: {
                    type: "column",
                    target: 0
                },


            },
        });
    }

    // To append actions dropdown inside action-btn div
    var clientFilterAction = $(".client-filter-action");
    var clientOptions = $(".client-options");
    $(".action-btns").append(clientFilterAction, clientOptions);
</script>