<div class="card">
    <div class="card-header ">
        <h6 class="d-flex">
            Leave
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="leave-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_leave" data-display="Today" onclick="filter_today_leave('today_leave','Today')">Today</a>
                        <a class="dropdown-item" href="javascript:;" data-value="weekly_leave" data-display="This Week" onclick="filter_today_leave('weekly_leave','This Week')">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="staff_leave" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body  leave_div">
        <div class="table-responsive">
            <table class="table leave_datatable">

                <thead>
                    <th>S.No.</th>
                    <th>Staff Name</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                </thead>

                <tbody>
                    @if(sizeof($leave))
                    <?php $i = 1; ?>
                    @foreach ($leave as $lev)
                    <tr>
                        <td><small>{{$i++}}</small></td>
                        <td><small>{{$lev->name}}</small></td>
                        <td><small>{{date('d-M-Y',strtotime($lev->start_date))}}</small></td>
                        <td><small>{{date('d-M-Y',strtotime($lev->end_date))}}</small></td>
                        <td><small>{{$lev->reason}}</small></td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">No records found!! </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".leave_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>