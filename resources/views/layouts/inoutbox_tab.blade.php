<ul class="nav nav-tabs" role="tablist" style=" margin-bottom: -20px !important;">
<li class="nav-item">
        @if ('inbox'== Request::path() || 'inbox'== Request::path())
        <a class="nav-link active" href="inbox" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">inbox</span>
        </a>
        @else
        <a class="nav-link" href="inbox" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">inbox</span>
        </a>
        @endif
    </li>

    <li class="nav-item">
        @if ('outbox'== Request::path())
        <a class="nav-link active" href="outbox" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Outbox</span>
        </a>
        @else
        <a class="nav-link" href="outbox" aria-controls="task" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Outbox</span>
        </a>
        @endif
    </li>
    

</ul>

