 <div class="card">
     <div class="card-header">
         <h6>
             Assigned leads's due
         </h6>
         <ul class="list-inline d-flex mb-0">
             <li class="d-flex align-items-center mr-1">
                 <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                 <div class="dropdown client-input">
                     <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="client-text">Today</span>
                     </div>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby=" dropdownMenuButton">
                         <a class="dropdown-item" href="javascript:;" data-value="today_client" data-display="Today" onclick="filter_today_client('today_client','Today')">Today</a>
                         <a class="dropdown-item" href="javascript:;" data-value="weekly_client" data-display="This Week" onclick="filter_today_client('weekly_client','This Week')">This Week</a>
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
             <table class="table assign_due_datatable">
            
                 <thead>
                     <th>Case Number</th>
                     <th>Client Name</th>
                     <th>Assign to</th>
                     <th>Service</th>
                     <th>Amount</th>
                     <th>Due Amt</th>
                     <th>Status</th>
                     <th>Bill Date</th>
                     <th>Due Date</th>
                     <th>type</th>
                 </thead>
                 <tbody>
                   
                     
                     @foreach ($due_payment_list as $row)
                     @if($row->payable>0)
                     <tr>
                         <td><small>{{$row->case_no}}</small></td>
                         <td><small>{{$row->client_name}}</small></td>
                         <td><small>{{$row->assign_to}}</small></td>
                         <th><small>{{$row->service}}</small></th>
                         <th><small>{{$row->total_amount}}</small></th>
                         <th><small>{{$row->due_amount}}</small></th>
                         <th><small>{{$row->status}}</small></th>
                         <th><small>{{$row->bill_date}}</small></th>
                         <th><small>{{$row->due_date}}</small></th>
                         <th><small>{{$row->type}}</small></th>  
                     </tr>
                     @endif
                     @endforeach
                     @foreach ($due_pro_payment_list as $row1)
                     @if($row1->payable>0)
                     <tr>
                         <td><small>{{$row1->case_no}}</small></td>
                         <td><small>{{$row1->client_name}}</small></td>
                         <td><small>{{$row1->assign_to}}</small></td>
                         <th><small>{{$row1->service}}</small></th>
                         <th><small>{{$row1->total_amount}}</small></th>
                         <th><small>{{$row1->due_amount}}</small></th>
                         <th><small>{{$row1->status}}</small></th>
                         <th><small>{{$row1->bill_date}}</small></th>
                         <th><small>{{$row1->due_date}}</small></th>
                         <th><small>{{$row1->type}}</small></th>  
                     </tr>
                     @endif
                     @endforeach
                    
                 </tbody>
             </table>
         </div>
     </div>
 </div>
 <script>
     $(".assign_due_datatable").DataTable({
         ordering: false,
         lengthChange: false,
         bFilter: false,
         searching: false,
         info: false,
         pageLength: 5,
     });
 </script>