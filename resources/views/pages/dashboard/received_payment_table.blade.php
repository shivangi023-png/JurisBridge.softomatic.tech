<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Received Payment
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="payment-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_payment" data-display="Today" onclick="filter_today_payment('today_payment','Today')">Today</a>
                        <a class="dropdown-item" href="javascript:;" data-value="weekly_payment" data-display="This Week" onclick="filter_today_payment('weekly_payment','This Week')">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="payment_list" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body payment_div">
        <div class="table-responsive">
            <table class="table received_payment_datatable">

                <thead>
                    <th>Client Name</th>
                    <th>Amount</th>
                    <th>Mode of Payment</th>
                    <th>Payment Date</th>
                </thead>

                <tbody>
                    @if($received_payment=="[]")
                    <tr>
                        <td colspan="4">No records found!! </td>
                    </tr>
                    @else
                    @foreach ($received_payment as $pay)
                    <tr>
                        <td>{{ $pay->case_no }}
                            <small>({{ $pay->client_name }})</small>
                        </td>
                        <td><small>{{$pay->payment}}</small></td>
                        <td><small>{{$pay->mode_of_payment}}</small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($pay->payment_date)); ?></small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".received_payment_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>