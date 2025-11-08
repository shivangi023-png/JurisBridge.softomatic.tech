<div class="action-dropdown-btn d-none">
    <div class="dropdown statistics-leave-filter-action">
        <button class="btn border dropdown-toggle mr-1" type="button" id="statistics-leave-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="selection">Filter leave</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="statistics-leave-filter-btn">
            <a class="dropdown-item filter_approve_btn" href="javascript:void(0);" data-value="this_year">This
                Year</a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table statistics-leave-data-table" style="width:100%">
        <thead>
            <th>Sr.No.</th>
            <th>Staff</th>
            <th>Total Leaves</th>
            @foreach ($leaveTypes as $leaveTypeId => $leaveType)
            <th>{{ $leaveType }}</th>
            <th>Applied {{ $leaveType }}</th>
            @endforeach
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($statistics as $staff)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$staff->name}}</td>
                <td>{{$staff->total_leaves}}</td>
                @foreach ($leaveTypes as $leaveTypeId => $leaveType)
                <td>{{ $leaveTypeTotalsByStaff[$staff->name]['totalLeaves'][$leaveTypeId] ?? 0 }}</td>
                <td>{{ $leaveTypeTotalsByStaff[$staff->name]['pendingLeaves'][$leaveTypeId] ?? 0 }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>