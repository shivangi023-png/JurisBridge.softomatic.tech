 <div class="table-responsive">
     <table class="table rejected-leave-data-table" style="width:100%">
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
             @foreach ($rejected_leaves as $leave3)
             <tr>
                 <td></td>
                 <td>
                     <div class="action">
                         <button data-id="{{$leave3->id}}" data-response="rejected" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_btn" data-tooltip="Delete">
                             <i class="bx bx-trash-alt"></i>
                         </button>
                         <button type="button" value="Approved" data-response="rejected" data-id="{{$leave3->id}}" class="btn btn-icon rounded-circle glow btn-success app_rej_btn mr-1 mb-1" data-tooltip="Approve">
                             <i class="bx bx-check"></i></button>
                         <button type="button" value="Rejected" data-response="rejected" data-id="{{$leave3->id}}" class="btn btn-icon rounded-circle glow btn-danger app_rej_btn mr-1 mb-1" data-tooltip="Reject" disabled>
                             <i class="bx bx-x"></i></button>
                 </td>
                 <td>{{$leave3->name}}</td>
                 <td>{{$leave3->type}}</td>
                 <td>
                     {{date('d-m-Y',strtotime($leave3->start_date))}}
                 </td>
                 <td>
                     {{date('d-m-Y',strtotime($leave3->end_date))}}
                 </td>
                 <td>{{$leave3->reason}}</td>
             </tr>
             @endforeach
         </tbody>
     </table>
 </div>