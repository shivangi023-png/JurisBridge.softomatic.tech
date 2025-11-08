
<style>
  #suggestionPopup {
    background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 70%;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000000;
            position: absolute;
            bottom: -150px;
            top: -142.8px;
            left: 14.75px;
          
            display: none;
            list-style-type: none;
            padding:12px;
            cursor: pointer;
      
        }
        .mentioned
        {
          list-style-type: none;
        }
        .mentioned {
            color: blue; /* Change the color as desired */
        }

</style>
<!-- new task right side form -->
<div class="todo-new-task-sidebar new_task_modal12">
  <div class="card shadow-none p-0 m-0">
    <div class="card-header border-bottom" style="flex-wrap:unset !important; height:60px">
      <div class="task-header d-flex justify-content-between align-items-center w-100">
        <h5 class="new-task-title mb-0">New Task</h5>
      </div>
      
      <button type="button" id="save_task_submit_btn" class="btn btn-primary task_btn" style="margin:10px">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block task_btn_name">Save</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary pull-right new_task_cancel" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 
    </div>
      <div class="card-body py-0">
<form enctype="multipart/form-data" method="POST">
        <div class="row mt-1">
         
          <input type="hidden" id="template_id">
          <input type="hidden" id="task_id">
          <div class="col-12 TForm input_proj_id_div">
          <input type="hidden" id="project_id">
            
          
          </div>
          <div class="col-12 TForm select_proj_id_div" >
          <select class="form-control project_select">
            <option value=""></option>
              @foreach($project_list as $proj)
              <option value="{{$proj->id}}">{{$proj->project_name}}</option>
              @endforeach
            </select>
            
            <span class="valid_err project_err"></span>
          </div>
           <div class="col-12 TForm" >
          <select class="form-control office_id">
            <option value=""></option>
              @foreach($office_list as $office)
              <option value="{{$office->id}}">{{$office->department_name}}</option>
              @endforeach
            </select>
            <span class="valid_err office_err"></span>
          </div>
          <div class="col-12 TForm">
            <input type="text" class="form-control mr-2 mb-50 mb-sm-0 task_title" placeholder="Title">
            <span class="valid_err task_title_err"></span>
          </div>
           
           <div class="col-12 TForm">
            <select class="form-control task_assignee" multiple="multiple">
              
              @foreach($staff_list as $staff)
              <option value="{{$staff->sid}}">{{$staff->name}}</option>
              @endforeach
            </select>
            <span class="valid_err task_assignee_err"></span>
          </div>

         
             <div class="col-3 TForm">
            <select class="form-control task_priority">
              <option value="">Priority</option>
              @foreach($task_priority as $row)
              <option value="{{$row->id}}">{{$row->priority}}</option>
              @endforeach
            </select>
            <span class="valid_err task_priority_err"></span>
          </div>
         
            <div class="col-3 TForm">
            <select class="form-control task_type">
              <option value="">Type</option>
               @foreach($task_type as $row)
              <option value="{{$row->id}}">{{$row->type}}</option>
              @endforeach
            </select>
            <span class="valid_err task_type_err"></span>
          </div>
          <div class="col-3 TForm">
            <select class="form-control task_status" >
              
              @foreach($task_status_master as $row)
              <option value="{{$row->id}}" <?php if($row->id==1) echo "selected";  ?>>{{$row->status}}</option>
              @endforeach
            </select>
            <span class="valid_err task_status_err"></span>
          </div>
            <div class="col-3 TForm">
            <select class="form-control view" >
              <option value="internal">Internal</option>
              <option value="external">External</option>
             
            </select>
            <span class="valid_err view_err"></span>
          </div>
          <div class="col-4 TForm">
            <input type="text" class="form-control datepicker mr-2 mb-50 mb-sm-0 task_start_date" placeholder="Start Date">
            <span class="valid_err task_date_err"></span>
          </div>
          <div class="col-4 TForm">
            <input type="text" class="form-control  datepicker mr-2 mb-50 mb-sm-0 task_end_date" placeholder="End Date">
            <span class="valid_err task_date_err"></span>
          </div>
          <div class="col-4 TForm">
            <div style="margin-top: 8px !important;">
              <input class="form-check-input task_is_milestone" type="checkbox" value="yes" id="defaultCheck1">
              <label class="form-check-label" for="defaultCheck1">
                Is Milestone
              </label>
              </div>
            <span class="valid_err is_milestone_err"></span>
          </div>
          <div class="col-10 TForm time_div" style="display:none">
          <fieldset class="form-group position-relative has-icon-left" >
          <input type="time" class="form-control task_hearing_time" id="timeInput"  pattern="[0-9]{2}:[0-9]{2}" required>
          <p id="error-message" style="color: red; display: none;">Please enter time between 1:00 and 12:59.</p>    
        </fieldset>
          </div>
          <div class="col-2 TForm time_div" style="display:none">
            <select class="form-control am_pm">
              <option value="">--</option>
              <option value="AM">AM</option>
              <option value="PM" selected>PM</option>
            </select>
          </div>
          <div class="col-8 mb-1">
          <div class="custom-file div_file_upload">
                <input type="file" class="custom-file-input file f_cou task_file" name="task_file">
                <span class="custom-file-label">Add File</span>
                 </div>
                    <input type="hidden" id="file_link">
                  <div class="custom-file div_file_link" style="margin-top: 8px !important;">
                    <a href="javascript:void(0)" class="file_link"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20h14v-2H5zM19 9h-4V3H9v6H5l7 7z"/></svg> file_link</a> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style=" color: #df1717ab;cursor: pointer;" class="file_delete"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m5 13.59L15.59 17L12 13.41L8.41 17L7 15.59L10.59 12L7 8.41L8.41 7L12 10.59L15.59 7L17 8.41L13.41 12z"/></svg>
                 </div>
        
           </div> 

           <div class="col-4 TForm">
            <select class="form-control working_hr">
              <option value=""></option>
              @for($h=1;$h<=12;$h++)
              <option value="{{$h}}">{{$h}}</option>
              @endfor
            </select>
            <span class="valid_err working_hr_err"></span>
          </div>
          
    

           <div class="col-12 TForm" style="margin-bottom:60px">
          
              <div class="snow-container border rounded p-50">
                <!-- <div class="d-flex"> -->
                  <div class="new_task_quill_toolbar pb-0">
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

            <div class="new_task_editor mx-75"></div>
          </div>
            <span class="valid_err description_err"></span>
        </div>
            <div class="col-12 TForm" id="task_comment_div">
               
                <div id="suggestionPopup" ></div>
                  <fieldset class="form-group">
                      <div class="form-control task_comment" id="myDiv" contenteditable="true"></div>
                               
                  </fieldset>
               
               
            </div>
          
          </div>
        </div>
       
      
      <div class="card-body ml-auto">
         
      </div>
</form>
  </div>
</div>
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>

<!--end new task right side form -->
<script>
  $(document).ready(function(){
    
    $(".task_assignee").val('');
    $(".task_assignee").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Assignee",

  }).trigger('change');


  });
 
   
</script>

<script>
    const editableDiv = document.getElementById('myDiv');

    // Function to show placeholder text
    function showPlaceholder() {
        if (!editableDiv.textContent.trim()) {
            editableDiv.innerHTML = '<span class="placeholder">Add your comment here..</span>';
        }
    }

    // Event listener to remove placeholder text when user starts typing
    editableDiv.addEventListener('input', function() {
        const placeholder = editableDiv.querySelector('.placeholder');
        if (placeholder) {
            placeholder.remove();
        }
    });

    // Event listener to show placeholder text when content is empty
    editableDiv.addEventListener('blur', showPlaceholder);

    // Show placeholder initially
    showPlaceholder();
    
</script>

@endsection
