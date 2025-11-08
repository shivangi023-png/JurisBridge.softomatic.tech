 <div class="card">
     <div class="card-header">
         <h6>
             Leads
         </h6>
         <ul class="list-inline d-flex mb-0">
             <li class="d-flex align-items-center mr-1">
                 <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                 <div class="dropdown">
                     <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="leads-text">Today</span>
                     </div>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                         <a class="dropdown-item" href="javascript:;" data-value="today_leads" data-display="Today" onclick="filter_leads_table('today_leads','Today')">Today</a>
                         <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_leads" onclick="filter_leads_table('weekly_leads','This Week')">This Week</a>
                          <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_leads" onclick="filter_leads_table('month_leads','This Month')">This Month</a>
                           <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_leads" onclick="filter_leads_table('previous_month_leads','Previous Month')">Previous Month</a>
                     </div>
                 </div>
             </li>
             <li class="d-flex align-items-center">
                 <a href="leads_details" class="btn btn-primary btn-sm round mr-1">
                     View More
                     <i class='bx bx-chevrons-right'></i>
                 </a>

             </li>
         </ul>
     </div>
     <div class="card-body leads_div">
         <div class="table-responsive">
             <table class="table leads_datatable">
                 <thead>
                     <th>S.No.</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Mobile No</th>
                     <th>Lead Source</th>
                     <th>Date</th>
                 </thead>

                 <tbody>
                     @if(sizeof($leads))
                     <?php $i = 1; ?>
                     @foreach ($leads as $row)
                     <tr>
                         <td><small>{{$i++}}</small></td>
                         <td><small>{{$row->name}}</small></td>
                         <td><small>{{$row->email}}</small></td>
                         <td><small>{{$row->mobile_no}}</small></td>
                         <td><small>{{$row->lead_source}}</small></td>
                         <td>
                             @if($row->created_at!='')
                             <small>{{date('d-M-Y',strtotime($row->created_at))}}</small>
                             @endif

                         </td>
                     </tr>
                     @endforeach
                     @else
                     <tr>
                         <td colspan="6">No records found!! </td>
                     </tr>
                     @endif
                 </tbody>
             </table>
         </div>
     </div>
 </div>
 <script>
     $(".leads_datatable").DataTable({
         ordering: false,
         lengthChange: false,
         bFilter: false,
         searching: false,
         info: false,
         pageLength: 5,
     });
 </script>