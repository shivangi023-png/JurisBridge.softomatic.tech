<div class="card">
    <div class="card-header">
        <h6>
            Raised Attendance
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="raise_attendance-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_raise_attendance" data-display="Today" onclick="filter_today_raise_attendance('today_raise_attendance','Today')">Today</a>
                        <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_raise_attendance" onclick="filter_today_raise_attendance('weekly_raise_attendance','This Week')">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="raise_attendance-list" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table raise_attendance_datatable">

                <thead>
                    <th>S.No.</th>
                    <th>Date</th>
                    <th>Staff Name</th>
                    <th>Sign In Time</th>
                    <th>Sign Out Time</th>
                    <th>Remark</th>

                </thead>

                <tbody>
                    @if(empty($all_raise_attendance))
                    <tr>
                        <td colspan="5">No records found!! </td>
                    </tr>
                    @else
                    <?php $i = 1; ?>
                    @foreach($all_raise_attendance as $att)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><small><?php echo date("d-m-Y", strtotime($att->created_at)); ?></small></td>
                        <td><small>{{$att->name}}</small></td>
                        <td><small>{{$att->signin_time}}</small></td>
                        <td><small>{{$att->signout_time}}</small></td>
                        <td><small>{{ $att->remark }}</small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".raise_attendance_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>