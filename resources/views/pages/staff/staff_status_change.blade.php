<div class="table-responsive">
    <table class="table staff-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>id</th>
                <th>Staff Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="data_div">
            <?php $i = 1; ?>
            @foreach($staff as $staff_item)
            <tr>
                <td><a href="staff_edit-{{$staff_item->sid}}" class="btn btn-icon rounded-circle glow btn-warning" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                    @if($staff_item->status=='inactive')
                    <a href="javascript:void(0);" class="btn btn-icon rounded-circle glow btn-success mx-2 staff_status_change" data-tooltip="Convert to Active" data-staff_id="{{$staff_item->sid}}" data-status="{{$staff_item->status}}"><i class="bx bx-check"></i></a>
                    @else
                    <a href="javascript:void(0);" class="btn btn-icon rounded-circle glow btn-danger mx-2 staff_status_change" data-tooltip="Convert to Inactive" data-staff_id="{{$staff_item->sid}}" data-status="{{$staff_item->status}}"><i class="bx bx-x"></i></a>
                    @endif
                <td>{{$i++}}</td>
                <td>{{$staff_item->name}}</td>
                <td>{{$staff_item->emailid}}</td>
                <td>{{$staff_item->mobile}}</td>
                <td>{{$staff_item->role}}</td>
                <td>
                    @if($staff_item->status=='active')
                    <span class="badge badge-light-success badge-pill">{{$staff_item->status}}</span>
                    @else
                    <span class="badge badge-light-danger badge-pill">{{$staff_item->status}}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>