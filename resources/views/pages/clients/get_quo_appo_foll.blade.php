@if ($detail == 'Quotation')
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>Quotation no</th>
                <th>Services</th>
                <th>Amount</th>
                <th>Finalize</th>
                <th>Finalize date</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td><a href="{{ $row->file}}">{{$row->quotation_no}}</a></td>
                <td>{{$row->service_name}}</td>
                <td>{{$row->amount}}</td>
                @if ($row->finalize == 'yes')
                <td>
                    <div class=" badge badge-success mr-1 mb-1">{{$row->finalize}}
                    </div>
                </td>
                @else
                <td>
                    <div class="badge badge-warning mr-1 mb-1">{{$row->finalize}}</div>
                </td>
                @endif

                @if ($row->finalize_date == "")
                <td>{{$row->finalize_date}}</td>
                @else
                <td>{{ date('d-m-Y', strtotime($row->finalize_date))}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if ($detail == 'Appointment')
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>Place</th>
                <th>Meeting_with</th>
                <th>Meeting date</th>
                <th>Meeting time</th>
                <th>Schedule by</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{$row->place_name}}</td>
                <td>{{$row->meeting_with_name}}</td>
                @if ($row->meeting_date == "")
                <td>{{$row->meeting_date}}</td>
                @else
                <td>{{date('d-m-Y', strtotime($row->meeting_date))}}</td>
                @endif

                <td>{{$row->meeting_time}}</td>
                <td>{{$row->schedule_by_name}}</td>
                @if ($row->status == 'finalize')
                <td>
                    <div class="badge badge-success mr-1 mb-1">{{$row->status}}</div>
                </td>
                @else
                <td>
                    <div class="badge badge-warning mr-1 mb-1">{{$row->status}}</div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if ($detail == 'Follow-Up')
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>Contact to</th>
                <th>Contact By</th>
                <th>Follow up date</th>
                <th>Next Follow up date</th>
                <th>Finalize</th>
                <th>Lead closed</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{$row->contact_to_name}}</td>
                <td>{{$row->contact_by_name}}</td>
                <td>{{date('d-m-Y', strtotime($row->followup_date))}}</td>
                @if ($row->next_followup_date == "")
                <td>{{$row->next_followup_date}}</td>
                @else
                <td>{{date('d-m-Y', strtotime($row->next_followup_date))}}</td>
                @endif
                @if ($row->finalized == 'yes')
                <td>
                    <i class="bx bx-check-square text-primary"></i>
                </td>
                @else
                <td>
                    <i class="bx bx-window-close text-danger"></i>
                </td>
                @endif
                @if ($row->lead_closed == 'yes')
                <td>
                    <i class="bx bx-check-square text-primary"></i>
                </td>
                @else
                <td>
                    <i class="bx bx-window-close text-danger"></i>
                </td>
                @endif
                <td>{{$row->discussion}}</td>
                @endforeach
        </tbody>
    </table>
</div>
@endif