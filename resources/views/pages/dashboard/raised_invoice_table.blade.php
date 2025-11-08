<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Raised Invoice
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="invoice-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:;" data-value="today_invoice" data-display="Today" onclick="filter_today_invoice('today_quotation','Today')">Today</a>
                        <a class="dropdown-item" href="javascript:;" data-value="weekly_invoice" data-display="This Week" onclick="filter_today_invoice('weekly_invoice','This Week')">This Week</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center">
                <a href="invoice_list" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body  invoice_div">
        <div class="table-responsive">
            <table class="table raised_invoice_datatable">

                <thead>
                    <th>Client Name</th>
                    <th>Amount</th>
                    <th>Bill Date</th>
                    <th>Due Date</th>
                </thead>

                <tbody>
                    @if($raised_invoice=="[]")
                    <tr>
                        <td colspan="4">No records found!! </td>
                    </tr>
                    @else
                    @foreach ($raised_invoice as $inv)
                    <tr>
                        <td>{{ $inv->case_no }}
                            <small>({{ $inv->client_name }})</small>
                        </td>
                        <td><small>{{$inv->total_amount}}</small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($inv->bill_date)); ?></small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($inv->due_date)); ?></small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".raised_invoice_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>