 <div class="table-responsive">
     <table class="table staff-data-table wrap">
         <thead>
             <tr>
                 <th>Action</th>
                 <th>id</th>
                 <th>Staff Name</th>
                 <th>Leave Type</th>
                 <th>Total Leaves</th>
                 <th>Available Leaves</th>
                 <th>Month</th>
                 <th>Year</th>
             </tr>
         </thead>
         <tbody>
             <?php $i = 1; ?>
             @foreach ($leaves as $leave)
             <tr>
                 <td><a href="#" class="updateModal btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-toggle="modal" data-target="#updateModal" data-leave_id="{{$leave->id}}" data-staff_id="{{$leave->staff_id}}" data-staff_name="{{$leave->name}}" data-leave_type="{{$leave->leave_type}}" data-total_leaves="{{$leave->total_leaves}}" data-available_leaves="{{$leave->available_leaves}}" data-month="{{$leave->month}}" data-session="{{$leave->session}}" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                 </td>
                 <td>{{$i++}}</td>
                 <td>{{$leave->name}}</td>
                 <td>{{$leave->type}}</td>
                 <td>{{$leave->total_leaves}}</td>
                 <td>{{$leave->available_leaves}}</td>
                 <td>{{date('F', mktime(0, 0, 0, $leave->month, 1))}}</td>
                 <td>{{$leave->session}}</td>
             </tr>
             @endforeach
         </tbody>
     </table>
 </div>
 <script>
     if ($(".staff-data-table").length) {
         var dataListView = $(".staff-data-table").DataTable({
             sorting: false,
             dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
             language: {
                 search: "",
                 searchPlaceholder: "Search Leave",
             },
             select: {
                 style: "multi",
                 selector: "td:first-child",
                 items: "row",
             },
         });
     }
 </script>