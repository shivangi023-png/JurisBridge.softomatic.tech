 <div class="card">
     <div class="card-header">
         <h4>Raised Attendancee</h4>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table class="table client-data-table">
                 <thead>
                     <tr>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>S No.</th>
                         <th style="font-size:14px;">Action</th>
                         <th style="font-size:14px;">Staff Name </th>
                         <th style="font-size:14px;">Date</th>
                         <th style="font-size:14px;">In Time</th>
                         <th style="font-size:14px;">Out Time</th>
                         <th style="font-size:14px;">In Location</th>
                         <th style="font-size:14px;">Out Location</th>
                         <th style="font-size:14px;">Status

                         </th>
                         <th style="font-size:14px;">Remark</th>
                         <!-- <th style="font-size:14px;">Approved By</th> -->
                     </tr>
                 </thead>
                 <tbody>
                     @if(empty($attendanceData))
                     <tr>
                         <td colspan="13">No records found!! </td>
                     </tr>
                     @else
                     @foreach ($attendanceData as $row)
                     @if($row->status=='raised')
                     <tr>
                         <td></td>
                         <td></td>
                         <td><input type="hidden" class="form-control attendance_id" value="{{$row->id}}"></td>
                         <td>
                             <div class="client-action">
                                 <!-- <button type="button" class="btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 approve_btn" data-status="approved" data-attendance_id="{{$row->id}}" data-tooltip="Approve">
                                     <i class="bx bx-list-check"></i></button> -->
                                 <button type="button" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 edit_btn" id="edit_raise_attendance" data-attendance_id="{{$row->id}}" data-staff_id="{{$row->sid}}" data-signin_time="{{$row->signin_time}}" data-signout_time="{{$row->signout_time}}" data-signin_location="{{$row->signin_location}}" data-signout_location="{{$row->signout_location}}" data-toggle="modal" data-target="#attendanceModal" data-tooltip="Edit & Approve">
                                     <i width="22px" class="bx bx-edit"></i>
                                 </button>
                                 <button type="button" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 reject_btn" data-attendance_id="{{$row->id}}" data-tooltip="Reject">
                                     <!-- <i class="bx bx-trash-alt"></i> -->
                                     <img style="width:20px; height:22px;" src="images\ban.svg"></button>
                             </div>
                         </td>
                         <td>{{$row->name}}</td>
                         <td><?php echo date("d-m-Y", strtotime($row->created_at)); ?></td>
                         <td>{{$row->signin_time}}</td>
                         <td>{{$row->signout_time}}</td>
                         <td>{{$row->signin_location}}</td>
                         <td>{{$row->signout_location}}</td>
                         <td>{{$row->status}}</td>
                         <td>{{$row->remark}}</td>
                         <!-- <td> -->

                         <!-- </td> -->
                     </tr>
                     @endif
                     @endforeach
                     @endif
                 </tbody>
             </table>
         </div>
     </div>
 </div>
 <script>
     $(document).ready(function() {
         if ($(".client-data-table").length) {
             var dataListView = $(".client-data-table").DataTable({
                 columnDefs: [{
                         targets: 0,
                         className: "control",
                     },
                     {
                         orderable: false,
                         targets: 1,
                         checkboxes: {
                             selectRow: true
                         },
                     },
                     {
                         targets: [0, 1, 2, 3],
                         orderable: false,
                     },
                 ],
                 order: [4, "asc"],
                 dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                 select: {
                     style: "multi",
                     selector: "td:first-child",
                     items: "row"
                 }
             });
             // add class in row if checkbox checked
             $(".dt-checkboxes-cell")
                 .find("input")
                 .on("change", function() {
                     var $this = $(this);
                     if ($this.is(":checked")) {
                         $this.closest("tr").addClass("selected-row-bg");
                     } else {
                         $this.closest("tr").removeClass("selected-row-bg");
                     }
                 });
             // Select all checkbox
             $(document).on("change", ".dt-checkboxes-select-all input", function() {
                 if ($(this).is(":checked")) {
                     $(".dt-checkboxes-cell")
                         .find("input")
                         .prop("checked", this.checked)
                         .closest("tr")
                         .addClass("selected-row-bg");
                 } else {
                     $(".dt-checkboxes-cell")
                         .find("input")
                         .prop("checked", "")
                         .closest("tr")
                         .removeClass("selected-row-bg");
                 }
             });
         }
     });
 </script>