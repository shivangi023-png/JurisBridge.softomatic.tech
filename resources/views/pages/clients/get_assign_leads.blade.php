<div class="card">
    <div class="card-header">
        <h6>
            Lead Assigned
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="cursor-pointer">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item filter_add_client" href="javascript:;" data-value="today_client" data-display="Today">Today</a>
                        <a class="dropdown-item filter_add_client" href="javascript:;" data-value="weekly_client" data-display="This Week">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="client_list" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body  client_div">
        <div class="table-responsive">
            <table class="table zero-configuration">
                <thead>
                    <th>S.No.</th>
                    <th>Client Name</th>
                    <th>Case Number</th>
                </thead>
                <tbody>
                    @if(sizeof($today_lead_assigned))
                    <?php $i = 1; ?>
                    @foreach ($today_lead_assigned as $cl)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><small>{{$cl->client_name}}</small></td>
                        <td><small>{{$cl->case_no}}</small></td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="">No records found!! </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>