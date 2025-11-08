<div class="table-responsive">
    <table class="table staff-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Id</th>
                <th>Staff</th>
                <th class="from-time-column">From Time</th>
                <th class="to-time-column">To Time</th>
                <th>Total Working Hour</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($staff_shift as $val)
            <tr>
                <td><a href="#" class="updateModal btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-toggle="modal" data-target="#updateModal" data-id="{{$val->id}}" data-staff_id="{{$val->staff_id}}" data-from_time="{{$val->from_time}}" data-to_time="{{$val->to_time}}" data-tooltip="Edit"><i class="bx bx-edit"></i></a></td>
                <td>{{$i++}}</td>
                <td>{{$val->name}}</td>
                <td class="from-time-column">{{substr_replace($val->from_time, ' ', -2, 0)}}</td>
                <td class="to-time-column">{{substr_replace($val->to_time, ' ', -2, 0)}}</td>
                <td>{{$val->total_working_hours}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>