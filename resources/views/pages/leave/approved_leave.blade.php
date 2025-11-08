 <div class="table-responsive">
     <table class="table approved-leave-data-table" style="width:100%">
         <thead>
             <th></th>
             <th>Action</th>
             <th>Staff</th>
             <th>Leave Type</th>
             <th>From </th>
             <th>To</th>
             <th>Reason</th>
         </thead>
         <tbody>
             @foreach ($approved_leaves as $leave2)
             <tr>
                 <td></td>
                 <td>
                     <div class="action">
                         <button data-id="{{$leave2->id}}" data-response="approved" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_btn" data-tooltip="Delete">
                             <i class="bx bx-trash-alt"></i>
                         </button>
                         <button type="button" value="Approved" data-response="approved" data-id="{{$leave2->id}}" class="btn btn-icon rounded-circle glow btn-success app_rej_btn mr-1 mb-1" data-tooltip="Approve" disabled>
                             <i class="bx bx-check"></i></button>
                         <button type="button" value="Rejected" data-response="approved" data-id="{{$leave2->id}}" class="btn btn-icon rounded-circle glow btn-danger app_rej_btn mr-1 mb-1" data-tooltip="Reject">
                             <i class="bx bx-x"></i></button>
                     </div>
                 </td>
                 <td>{{$leave2->name}}</td>
                 <td>{{$leave2->type}}</td>
                 <td>
                     {{date('d-m-Y',strtotime($leave2->start_date))}}
                 </td>
                 <td>
                     {{date('d-m-Y',strtotime($leave2->end_date))}}
                 </td>
                 <td>{{$leave2->reason}}</td>
             </tr>
             @endforeach
         </tbody>
     </table>
 </div>