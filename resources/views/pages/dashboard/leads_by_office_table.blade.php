<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Leads By Office
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="office-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_office_lead" data-display="Today" onclick="filter_today_office_lead('today_office_lead','Today')">Today</a>
                        <a class="dropdown-item" href="javascript:;" data-value="monthly_office_lead" data-display="This Month" onclick="filter_today_office_lead('monthly_office_lead','This Month')">This
                            Month</a>
                        <a class="dropdown-item" href="javascript:;" data-value="quarterly_office_lead" data-display="This Quarter" onclick="filter_today_office_lead('quarterly_office_lead','This Quarter')">This
                            Quarter</a>
                    </div>
                </div>
            </li>

            <li class="d-flex align-items-center">
                <a href="#" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body office_div">
        <div class="table-responsive">
            <table class="table leads_by_office_datatable">

                <thead>
                    <th>Client Name</th>
                    <th>No of units</th>
                    <th>area</th>
                    <th>Property type</th>
                    <th>Date</th>
                </thead>

                <tbody>
                    @if($office_lead=="[]")
                    <tr>
                        <td colspan="5">No records found!! </td>
                    </tr>
                    @else
                    @foreach ($office_lead as $olead)
                    <tr>
                        <td><small>{{ $olead->case_no }}({{ $olead->client_name }})</small>
                        </td>
                        <td><small>{{$olead->no_of_units}}</small></td>
                        <td><small>{{$olead->area}}</small></td>
                        <td><small>{{$olead->property_type_name}}</small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($olead->date)); ?></small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".leads_by_office_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>