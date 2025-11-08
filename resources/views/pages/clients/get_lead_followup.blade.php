@if ($detail == 'Follow-up')
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
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
                <td>{{$row->contact_by_name}}</td>
                <td>{{date('d-m-Y', strtotime($row->followup_date))}}</td>
                @if ($row->next_followup_date == "")
                <td></td>
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