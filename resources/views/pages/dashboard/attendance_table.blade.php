<div class="card widget-todo">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Today's Attendance
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table attendance_datatable">

                <thead>
                    <th>S.No.</th>
                    <th>Staff Name</th>
                    <th>Sign In</th>
                    <th>Sign Out</th>
                </thead>

                <tbody>
                    @if($today_attendance=="[]")
                    <tr>
                        <td colspan="4">No records found!! </td>
                    </tr>
                    @else
                    <?php $i = 1; ?>
                    @foreach($today_attendance as $att)
                    <tr>
                        <td><small>{{$i++}}</small></td>
                        <td><small>({{ $att->staff_name }})</small></td>
                        <td><small>{{date('d-m-Y',strtotime($att->signin_date))}} {{$att->signin_time}}</small></td>
                        <td><small>
                                @if($att->signin_date!='')
                                {{date('d-m-Y',strtotime($att->signin_date))}}{{$att->signout_time}}
                                @endif
                            </small>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".attendance_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>