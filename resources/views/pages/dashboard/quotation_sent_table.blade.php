<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Quotations Sent
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="quotation-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_quotation" data-display="Today" onclick="filter_today_quotation('today_quotation','Today')">Today</a>
                        <a class="dropdown-item" href="javascript:;" data-value="weekly_quotation" data-display="This Week" onclick="filter_today_quotation('weekly_quotation','This Week')">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="quotation_list" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body  quotation_div">
        <div class="table-responsive">
            <table class="table quotation_sent_datatable">

                <thead>
                    <th>Client Name</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Send Date</th>
                </thead>

                <tbody>
                    @if($quotation=="[]")
                    <tr>
                        <td colspan="4">No records found!! </td>
                    </tr>
                    @else
                    @foreach ($quotation as $quot)
                    <tr>
                        <td>{{ $quot->case_no }}
                            <small>({{ $quot->client_name }})</small>
                        </td>
                        <td><small>{{$quot->task_name}}</small></td>
                        <td><small>{{$quot->amount}}</small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($quot->send_date)); ?></small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".quotation_sent_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>