<ul class="nav nav-tabs" role="tablist" style=" margin-bottom: -20px !important;">
@if('get_task_list'== Request::path() || 'task'== Request::path() || 'task_analytics'== Request::path() || 'get_staff_wise_task'== Request::path() ||'staff_wise_task'== Request::path() || 'task_hearing'== Request::path() || 'overdue_task'== Request::path()
 || 'task_analytics'== Request::path() || 'overdue_task_grid'== Request::path() || 'my_task'== Request::path()  || 'my_task_grid'== Request::path())
<li class="nav-item">
        @if ('get_task_list'== Request::path() || 'task'== Request::path())
        <a class="nav-link active" href="task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Table View</span>
        </a>
        @else
        <a class="nav-link" href="task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Table View</span>
        </a>
        @endif
    </li>

    <li class="nav-item">
        @if ('task_analytics'== Request::path())
        <a class="nav-link active" href="task_analytics" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Analytics View</span>
        </a>
        @else
        <a class="nav-link" href="task_analytics" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Analytics View</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('get_staff_wise_task'== Request::path() ||'staff_wise_task'== Request::path() )
        <a class="nav-link active" href="staff_wise_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Staff Wise</span>
        </a>
        @else
        <a class="nav-link" href="staff_wise_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Staff Wise</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('task_hearing'== Request::path() )
        <a class="nav-link active" href="task_hearing" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Hearing</span>
        </a>
        @else
        <a class="nav-link" href="task_hearing" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Hearing</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('overdue_task'== Request::path() )
        <a class="nav-link active" href="overdue_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Overdue Task</span>
        </a>
        @else
        <a class="nav-link" href="overdue_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Overdue Task</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('my_task'== Request::path() )
        <a class="nav-link active" href="my_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">My Task</span>
        </a>
        @else
        <a class="nav-link" href="my_task" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">My Task</span>
        </a>
        @endif
    </li>
 @if ('task_analytics'== Request::path())
    <li class="MonthDropdownRight">
         <div class="text-right">
            <select id="donutChartMonth" class="form-control">
                @for($i=1;$i<=12;$i++) @if(date('m')==date('m', mktime(0, 0, 0, $i, 10))) <option value="{{date('m', mktime(0, 0, 0, $i, 10))}}" selected>{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                  @else
                  <option value="{{date('m', mktime(0, 0, 0, $i, 10))}}">{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                  @endif
                  @endfor
            </select>
         </div>
    </li>
    @endif
@endif
</ul>

