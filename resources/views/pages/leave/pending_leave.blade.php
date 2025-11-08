  <div class="table-responsive">
      <table class="table pending-leave-data-table" style="width:100%">
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
              @foreach ($pending_leaves as $leave1)
              <tr>
                  <td></td>
                  <td>
                      <div class="action">
                          <div class="done_edit_div" style="display:none;">
                              <button data-id="{{$leave1->id}}" class="btn btn-icon rounded-circle glow btn-primary mr-1 mb-1 done_edit_btn" data-tooltip="Done">
                                  <i class="bx bx-check"></i>
                              </button>

                              <button data-id="{{$leave1->id}}" class="btn btn-icon rounded-circle glow btn-secondary mr-1 mb-1 close_edit_btn" data-tooltip="Close">
                                  <i class="bx bx-x"></i>
                              </button>
                          </div>
                          <button data-id="{{$leave1->id}}" data-response="pending" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1 edit_btn" data-tooltip="Edit">
                              <i class="bx bx-edit"></i>
                          </button>
                          <button data-id="{{$leave1->id}}" data-response="pending" class="btn btn-icon rounded-circle glow btn-danger mr-1 mb-1 delete_btn" data-tooltip="Delete">
                              <i class="bx bx-trash-alt"></i>
                          </button>
                          <button type="button" value="Approved" data-response="pending" data-id="{{$leave1->id}}" class="btn btn-icon rounded-circle glow btn-success app_rej_btn mr-1 mb-1" data-tooltip="Approve">
                              <i class="bx bx-check"></i></button>
                          <button type="button" value="Rejected" data-response="pending" data-id="{{$leave1->id}}" class="btn btn-icon rounded-circle glow btn-danger app_rej_btn mr-1 mb-1" data-tooltip="Reject">
                              <i class="bx bx-x"></i></button>
                      </div>
                  </td>
                  <td>{{$leave1->name}}</td>
                  <td>
                      <div class="leave_type_val">
                          {{$leave1->type}}
                      </div>
                      <div class="leave_type_input" style="display:none;">
                          <div class="form-group">
                              <select name="leave_type" id="leave_type" class="form-control leave_type">
                                  <option value="">Leave Type</option>
                                  @foreach($leave_type as $val)
                                  <option value="{{$val->id}}" {{ $leave1->leave_id == $val->id ? 'selected' : '' }}>{{$val->type}}</option>
                                  @endforeach
                              </select>
                              <span class="valid_err leave_type_err"></span>
                          </div>
                      </div>
                  </td>
                  <td data-sort="{{strtotime($leave1->start_date)}}">
                      <div class="start_date_val">
                          {{date('d-m-Y',strtotime($leave1->start_date))}}
                      </div>
                      <div class="start_date_input" style="display:none;">
                          <div class="form-group">
                              <input type="text" class="form-control pickadate start_date" name="start_date" placeholder="Start Date" value="{{date('d-m-Y',strtotime($leave1->start_date))}}">
                              <span class="valid_err start_date_err"></span>
                          </div>
                      </div>
                  </td>
                  <td data-sort="{{strtotime($leave1->end_date)}}">
                      <div class="end_date_val">
                          {{date('d-m-Y',strtotime($leave1->end_date))}}
                      </div>
                      <div class="end_date_input" style="display:none;">
                          <div class="form-group">
                              <input type="text" class="form-control pickadate end_date" name="end_date" placeholder="End Date" value="{{date('d-m-Y',strtotime($leave1->end_date))}}">
                              <span class="valid_err end_date_err"></span>
                          </div>
                      </div>
                  </td>
                  <td>{{$leave1->reason}}</td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  <script>
      if ($(".pickadate").length) {
          $(".pickadate").pickadate({
              format: "dd-mm-yyyy",
          });
      }
  </script>