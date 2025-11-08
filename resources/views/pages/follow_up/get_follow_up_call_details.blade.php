<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Contact to</th>
                <th>Method</th>
                <th>Contact By</th>
                <th>Follow-Up Date</th>
                <th>Next Follow-Up date</th>
                <th>Finalized</th>
                <th>Lead closed</th>
                <th>discussion</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($follow_up as $row)
            <tr>
                <th>{{$i++}}</th>
                <td>{{$row->contact_to_data}}</td>
                <td>{{$row->method_data}}</td>
                <td>{{$row->contact_by}}</td>
                <td data-sort="{{strtotime($row->followup_date)}}">{{date('d-m-Y', strtotime($row->followup_date))}}</td>
                <td data-sort="{{strtotime($row->next_followup_date)}}">
                    @if($row->next_followup_date != '')
                    {{date('d-m-Y', strtotime($row->next_followup_date))}}
                    @endif
                </td>
                <td>{{$row->finalized}}</td>
                <td>{{$row->lead_closed}}</td>
                <td>{{$row->discussion}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>