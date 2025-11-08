<ul class="nav nav-tabs" role="tablist" style=" margin-bottom: -20px !important;">

<li class="nav-item">
        @if ('overdue_task'== Request::path())
        <a class="nav-link active" href="overdue_task" aria-controls="overdue_task" role="tab" aria-selected="true">
            
            <span class="align-middle">Table View</span>
        </a>
        @else
        <a class="nav-link" href="overdue_task" aria-controls="overdue_task" role="tab" aria-selected="true">
            
            <span class="align-middle">Table View</span>
        </a>
        @endif
    </li>

    <li class="nav-item">
        @if ('overdue_task_grid'== Request::path())
        <a class="nav-link active" href="overdue_task_grid" aria-controls="task" role="tab" aria-selected="true">
            
            <span class="align-middle">Grid View</span>
        </a>
        @else
        <a class="nav-link" href="overdue_task_grid" aria-controls="task" role="tab" aria-selected="true">
            
            <span class="align-middle">Grid View</span>
        </a>
        @endif
    </li>
    @if(session('role_id')==1)
    <li class="nav-item">
        @if ('raised_overdue_task'== Request::path())
        <a class="nav-link active" href="raised_overdue_task" aria-controls="task" role="tab" aria-selected="true">
            
            <span class="align-middle">Raised Tasks</span>
        </a>
        @else
        <a class="nav-link" href="raised_overdue_task" aria-controls="task" role="tab" aria-selected="true">
            
            <span class="align-middle">Raised Tasks</span>
        </a>
        @endif
    </li>
    @endif
  </ul>