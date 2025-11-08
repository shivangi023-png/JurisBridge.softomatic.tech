<style>
    .select_control {
        border: 0px !important;
        font-size: 13px;
        width: 50%;
        border-bottom: 1px solid #000 !important;
        border-radius: 0rem !important;
    }
</style>
<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Leads By Sales
        </h6>
        <ul class="list-inline d-flex mb-0">
            <li class="d-flex align-items-center mr-1">
                <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                <div class="dropdown">
                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sales-text">Today</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="today_sales_lead" data-display="Today" onclick="filter_today_sales_lead('today_sales_lead','Today')">Today</a>
                        <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="monthly_sales_lead" data-display="This Month" onclick="filter_today_sales_lead('monthly_sales_lead','This Month')">This Month</a>
                        <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="quarterly_sales_lead" data-display="This Quarter" onclick="filter_today_sales_lead('quarterly_sales_lead','This Quarter')">This
                            Quarter</a>
                    </div>
                </div>
            </li>
            <li class="d-flex align-items-center ml-3">
                <select name="staff_id" id="staff_id" class="form-control select_control">
                    <option value="">Select Staff</option>
                    @foreach($staff as $stf)
                    <option value="{{$stf->sid}}">{{$stf->name}}</option>
                    @endforeach
                </select>
            </li>
            <li class="d-flex align-items-center">
                <a href="#" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body sales_div">
        <div class="table-responsive">
            <table class="table leads_by_sales_datatable">

                <thead>
                    <th>Client Name</th>
                    <th>No of units</th>
                    <th>area</th>
                    <th>Property type</th>
                    <th>Date</th>
                </thead>

                <tbody>
                    @if($sales_lead=="[]")
                    <tr>
                        <td colspan="5">No records found!! </td>
                    </tr>
                    @else
                    @foreach ($sales_lead as $slead)
                    <tr>
                        <td><small>{{ $slead->case_no }}({{ $slead->client_name }})</small>
                        </td>
                        <td><small>{{$slead->no_of_units}}</small></td>
                        <td><small>{{$slead->area}}</small></td>
                        <td><small>{{$slead->property_type_name}}</small></td>
                        <td><small><?php echo date("d-M-Y", strtotime($slead->date)); ?></small></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".leads_by_sales_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>