 <div class="card">
     <div class="card-header">
         <h6 class="d-flex">
             Appointments
         </h6>
         <ul class="list-inline d-flex mb-0">
             <li class="d-flex align-items-center mr-1">
                 <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                 <div class="dropdown">
                     <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="appointment-text">Today</span>
                     </div>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                         <a class="dropdown-item" href="javascript:;" data-value="today_appointment" data-display="Today" onclick="filter_today_appointment('today_appointment','Today')">Today</a>
                         <a class="dropdown-item" data-display="This Week" href="javascript:;" data-value="weekly_appointment" onclick="filter_today_appointment('weekly_appointment','This Week')">This Week</a>
                     </div>
                 </div>
             </li>
             <li class="d-flex align-items-center">
                 <a href="appointment-list" class="btn btn-primary btn-sm round mr-1">
                     View More
                     <i class='bx bx-chevrons-right'></i>
                 </a>

             </li>
         </ul>
     </div>
     <div class="card-body  appointment_div">
         <div class="table-responsive">
             <table class="table appointment_datatable">

                 <thead>
                     <th>S.No.</th>
                     <th>Client Name</th>
                     <th>Meeting with</th>
                     <th>Scheduled by</th>
                     <th>Meeting Date</th>
                     <th>Time</th>
                     <th>Visit Type</th>
                 </thead>

                 <tbody>
                     @if(sizeof($appointments))
                     <?php $i = 1; ?>
                     @foreach($appointments as $val)
                     <tr>
                         <td><small>{{$i++}}</small></td>
                         <td><small>{{ $val->case_no }}
                                 ({{ $val->cname }})</small>
                         </td>
                         <td><small>{{$val->meetname}}</small></td>
                         <td><small>{{$val->schedule_by_name}}</small></td>
                         <td><small><?php echo date("d-M-Y", strtotime($val->meeting_date)); ?></small></td>
                         <td><small>{{$val->meeting_time}}<small></td>
                         <td><small>{{$val->aname}}</small></td>
                     </tr>
                     @endforeach
                     @else
                     <tr>
                         <td colspan="5">No records found!! </td>
                     </tr>
                     @endif
                 </tbody>
             </table>
         </div>
     </div>
 </div>
 <script>
     $(".appointment_datatable").DataTable({
         ordering: false,
         lengthChange: false,
         bFilter: false,
         searching: false,
         info: false,
         pageLength: 5,
     });
 </script>