<style>
    .leave_tr td 
    {
        border-top: 1px solid #dfe3e7;
    }
</style>

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
   
    <table class="table statistics-leave-data-table table-bordered" style="width:100%">
        <thead>
        <tr>
        <th  colspan="<?php echo sizeof($leaveTypes)+2 ?>" style="text-align: center">Available Leave   </th>
        <th></th>
        <th colspan="<?php echo sizeof($leaveTypes) ?>" style="text-align: center">Approved Leave</th>
        <th></th>
        <th colspan="5" style="text-align: center">Penalty</th>
        </tr>
        <tr>
        <th>Sno.</th>
        <th>staff</th>
        @foreach ($leaveTypes as $lt)
            <th>{{ $lt->type }}</th>
            
            @endforeach
            <th></th>
            @foreach ($leaveTypes as $lt)
            <th>{{ $lt->type }}</th>
            
            @endforeach
            <th></th>
            <th>Leave taken</th>
            <th>Not Marked</th>
            <th>Late Marked</th>
            <th>Half Day</th>
            <th>Quarter Day</th>
         </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            
            @foreach ($staff as $stf)
            
            <tr class="leave_tr">
            <td>{{$i++}}</td>
            <td>{{$stf->name}}</td>
               @foreach ($leaveTypes as $lt)
                <td>{{$stf->leave_available[$lt->type.' available']}}</td>
                @endforeach
                <td></td>
                @foreach ($leaveTypes as $lt)
                <td>{{$stf->leave_taken[$lt->type.' taken']}}</td>
                @endforeach
                <td></td>
                <td>{{$stf->total_leave}}</td>
                <td>{{$stf->not_mark}}</td>
                 <td>{{$stf->late_mark}}</td>
                <td>{{$stf->half_day}}</td>
                <td>{{$stf->quarter_day}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>