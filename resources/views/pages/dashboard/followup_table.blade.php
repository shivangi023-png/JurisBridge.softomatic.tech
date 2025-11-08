 <div class="card">
     <div class="card-header">
         <h6>
             Follow-Up
         </h6>
         <ul class="list-inline d-flex mb-0">
             <li class="d-flex align-items-center mr-1">
                 <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                 <div class="dropdown">
                     <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="followup-text">Today</span>
                     </div>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                         <a class="dropdown-item" href="javascript:;" data-value="today_followup" data-display="Today" onclick="filter_today_followup('today_followup','Today')">Today</a>
                         <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_followup" onclick="filter_today_followup('weekly_followup','This Week')">This Week</a>
                     </div>
                 </div>
             </li>
             <li class="d-flex align-items-center">
                 <a href="follow-up-list" class="btn btn-primary btn-sm round mr-1">
                     View More
                     <i class='bx bx-chevrons-right'></i>
                 </a>

             </li>
         </ul>
     </div>
     <div class="card-body followup_div">
         <div class="table-responsive">
             <table class="table followup_datatable">
                 <thead>
                     <th>S.No.</th>
                     <th>Contact To</th>
                     <th>Contact By</th>
                     <th>Follow-up</th>
                     <th>Next Follow-up</th>
                     <th>Method</th>
                 </thead>

                 <tbody>
                     @if(sizeof($today_follow_up))
                     <?php $i = 1; ?>
                     @foreach ($today_follow_up as $foll)
                     <tr>
                         <td><small>{{$i++}}</small></td>
                         <td><small>{{$foll->contact_to_data}}</small></td>
                         <td><small>{{$foll->staff_name}}</small></td>
                         <td><small>{{date('d-m-Y',strtotime($foll->followup_date))}}</small></td>
                         <td>
                             @if($foll->next_followup_date!='')
                             <small>{{date('d-m-Y',strtotime($foll->next_followup_date))}}</small>
                             @endif

                         </td>
                         <td><small>{{$foll->method_data}}</small></td>
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
     $(".followup_datatable").DataTable({
         ordering: false,
         lengthChange: false,
         bFilter: false,
         searching: false,
         info: false,
         pageLength: 5,
     });
 </script>