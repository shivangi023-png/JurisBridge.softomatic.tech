<ul class="nav nav-tabs" role="tablist">
    @if('leads'== Request::path()||'my_assign_leads'== Request::path()||'statistics_leads'== Request::path())
    <li class="nav-item">
        @if ('my_assign_leads'== Request::path())
        <a class="nav-link active" href="my_assign_leads" aria-controls="leads" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Assign Leads</span>
        </a>
        @else
        <a class="nav-link" href="my_assign_leads" aria-controls="leads" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Assign Leads</span>
        </a>
        @endif
    </li>

    <li class="nav-item">
        @if ('statistics_leads'== Request::path())
        <a class="nav-link active" href="statistics_leads" aria-controls="statistics" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Statistics</span>
        </a>
        @else
        <a class="nav-link" href="statistics_leads" aria-controls="statistics" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Statistics</span>
        </a>
        @endif
    </li>
    @endif
</ul>