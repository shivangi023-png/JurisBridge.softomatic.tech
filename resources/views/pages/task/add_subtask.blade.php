<!-- sub task right side form -->
<div class="todo-new-task-sidebar add_subtask_sidebar">
  <div class="card shadow-none p-0 m-0">
  <div class="card-header border-bottom" style="flex-wrap:unset !important; height:60px">
      <div class="task-header d-flex justify-content-between align-items-center w-100">
        <h5 class="sub-task-title mb-0">Create Sub Task</h5>
      </div>
      <button type="button" id="save_subtask_submit_btn" class="btn btn-primary ml-1 px-5 subtask_btn" style="margin:10px">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block task_btn_name">Save</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary px-5 subtask_cancel" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 
    </div>
      <div class="card-body py-0">
<form enctype="multipart/form-data" method="POST">
           <p id="taskTitle"></p>
        <div class="row mt-1">
          <input type="hidden" id="sub_project_id">
          <input type="hidden" id="sub_template_id">
          <input type="hidden" id="_task_id">
          <input type="hidden" id="subtask_id">
         <!--  <div class="col-12">
         </div> -->
          <div class="col-12 TForm">
            <input type="text" class="form-control mr-2 mb-50 mb-sm-0 subtask_title" placeholder="Title">
            <span class="valid_err subtask_title_err"></span>
          </div>

           <div class="col-5 TForm">
            <select class="form-control subtask_assignee">
              <option value="">Assignee</option>
              @foreach($staff_list as $staff)
              <option value="{{$staff->sid}}">{{$staff->name}}</option>
              @endforeach
            </select>
            <span class="valid_err subtask_assignee_err"></span>
          </div>

         
          <div class="col-4 TForm">
            <select class="form-control subtask_priority">
              <option value="">Priority</option>
              @foreach($task_priority as $row)
              <option value="{{$row->id}}">{{$row->priority}}</option>
              @endforeach
            </select>
            <span class="valid_err subtask_priority_err"></span>
          </div>
         
            <div class="col-3 TForm">
            <select class="form-control subtask_type">
              <option value="">Type</option>
               @foreach($task_type as $row)
              <option value="{{$row->id}}">{{$row->type}}</option>
              @endforeach
            </select>
            <span class="valid_err subtask_type_err"></span>
          </div>
         
          <div class="col-6 TForm">
            <input type="text" class="form-control autoapply mr-2 mb-50 mb-sm-0 subtask_date" placeholder="Start Date - End Date">
            <span class="valid_err subtask_date_err"></span>
          </div>
           <div class="col-6 TForm">
            <select class="form-control subtask_status">
              <option value="">Status</option>
              @foreach($task_status_master as $row)
              <option value="{{$row->id}}">{{$row->status}}</option>
              @endforeach
            </select>
            <span class="valid_err subtask_status_err"></span>
          </div>

          <div class="col-4 TForm">
             <div class="custom-file div_subtask_file_upload">
                <input type="file" class="custom-file-input file f_cou subtask_file" name="subtask_file">
                <span class="custom-file-label">Add File</span>
                 </div>
                 <input type="hidden" id="subtask_file_link">
                    <div class="custom-file div_subtask_file_link" style="margin-top: 8px !important;">
                    <a href="#" class="subtask_file_link"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20h14v-2H5zM19 9h-4V3H9v6H5l7 7z"/></svg> file_link</a> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style=" color: #df1717ab;cursor: pointer;" class="subtask_file_delete"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m5 13.59L15.59 17L12 13.41L8.41 17L7 15.59L10.59 12L7 8.41L8.41 7L12 10.59L15.59 7L17 8.41L13.41 12z"/></svg>
                 </div>
           </div> 

          <div class="col-6 TForm">
            <div style="margin-top: 8px !important;">
              <input class="form-check-input subtask_is_milestone" type="checkbox" value="yes" id="defaultCheck111">
              <label class="form-check-label" for="defaultCheck111">
                Is Milestone
              </label>
              </div>
            <span class="valid_err subtask_is_milestone_err"></span>
          </div>

           <div class="col-12 TForm">
            <!-- <textarea class="form-control mr-2 mb-50 mb-sm-0 description" placeholder="Description"></textarea> -->
             <!--  Quill editor for task description -->
        <div class="snow-container border rounded p-50">
           <!-- <div class="d-flex"> -->
            <div class="subtask_quill_toolbar pb-0">
              <span class="ql-formats mr-0">
                <button class="ql-bold"></button>
                <button class="ql-underline"></button>
                <!-- <button class="ql-link"></button> -->
                <button class="ql-header" value="1"></button>
                <button class="ql-header" value="2"></button>
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
                <button type="button" class="ql-indent" value="-1"></button>
                <button type="button" class="ql-indent" value="+1"></button>
              </span>
            </div>
          <!-- </div> -->

          <div class="subtask_editor mx-75"></div>
        </div>
            <span class="valid_err subtask_description_err"></span>
          </div>

        </div>
      </div>

     
</form>
  </div>
</div>
<!--end subtask task right side form -->