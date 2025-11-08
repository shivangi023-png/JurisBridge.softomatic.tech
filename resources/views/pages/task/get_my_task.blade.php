
<div style="gap: 10px;display: inline-flex;margin: 4px 10px 10px 8px;">
@foreach($task_status as $row)
          <div class="task_div" >
            <div class="todoHead bg_todo">
              <h4>{{$row->status}} - {{sizeof($row->task)}}</h4>
              
            </div>
            <div class="todoMain" >
            @foreach($row->task as $row2)
            <div class="col-sm-12 todoCBox ">

<div class="TaskBox" style="margin-bottom:0px" >
      <div class="project_task"  data-project_id="{{$row2->project_id}}" data-task_id="{{$row2->id}}" data-task_title="{{$row2->title}}" data-file_link="{{$row2->file_link}}"  data-task_type="{{$row2->type}}" data-task_priority="{{$row2->priority}}" data-task_assignee="{{$row2->assignee}}" data-task_status="{{$row2->status}}" data-task_is_milestone="{{$row2->is_milestone}}" data-task_start_date="{{($row2->start_date != null) ? date('d/m/Y',strtotime($row2->start_date)) : '' }}" data-task_end_date="{{($row2->end_date != null) ? date('d/m/Y',strtotime($row2->end_date)) : '' }}" data-task_description="{{$row2->description}}" data-office_id="{{$row2->office_id}}" data-working_hr="{{$row2->working_hr}}" data-time="{{$row2->time}}">
      <div class="row top">
          <div class="pull-left col-sm-12"><span style="font-size:12px; font-family:'Open Sans'; font-weight: 500; font-size: 12px; color: #454169;">{{$row2->project_name}}
          </span>
          </div>
        </div>
      <h4>{{$row2->title}}</h4>
      <p><?php echo nl2br($row2->description) ?></p>

      <div class="row">
        <div class="col-sm-9">
         @if($row2->start_date!='' || $row2->end_date!='')
      <div class="date">
          <h6>{{date('d-M-Y',strtotime($row2->start_date))}} | {{date('d-M-Y',strtotime($row2->end_date))}}</h6>
          
      </div>
      @endif
        </div>
        <div class="col-sm-3 TotalWorkingDay">
          <i class="bx bx-time"></i><h6>{{$row2->total_working_hr}}</h6>
        </div>
      </div>


      @if($row2->priority_name!='' ||  $row2->type_name!='')
      <div class="BtnBox">
        @if($row2->priority_name!='')
          <div class="LeftBtn">
              <a class="Mbtn" href="#">{{$row2->priority_name}}</a>
          </div>
          @endif
          @if($row2->type_name!='')
          <div class="RightBtn">
              <a class="NewBtn" href="#">{{$row2->type_name}}</a>
          </div>
          @endif
      </div>
      @endif
      </div>
      <div class="commentBox row">
        <div class="CommentAssign col-sm-6">
          <i class="bx bx-user-circle"></i><h6>{{$row2->assignee_name}}</h6>
        </div>  
        <div class="col-sm-5 CommentHead" >
          <i class="bx bx-comment"></i><h6><a href="#" class="comment_modal_btn"  data-target="#commentBox" data-toggle="modal" data-status_name="{{$row->status}}" data-priority_name="{{$row2->priority_name}}" data-assignee_name="{{$row2->assignee_name}}" data-type_name="{{$row2->type_name}}" data-project_name="{{$row2->project_name}}" data-task_id="{{$row2->id}}" data-task_title="{{$row2->title}}" data-file_link="{{$row2->file_link}}"  data-task_type="{{$row2->type}}" data-task_priority="{{$row2->priority}}" data-task_assignee="{{$row2->assignee}}" data-task_status="{{$row2->status}}" data-task_is_milestone="{{$row2->is_milestone}}" data-task_start_date="{{($row2->start_date != null) ? date('d/m/Y',strtotime($row2->start_date)) : '' }}" data-task_end_date="{{($row2->end_date != null) ? date('d/m/Y',strtotime($row2->end_date)) : '' }}" data-task_description="{{$row2->description}}">Comments</a></h6>
        </div>
      </div>
      @if($row2->dept_address != null)
      <hr style="margin-top: 6px; margin-bottom: 6px;">
      <div class="AddDateBox row">
        <div class="col-12 AddContent">
          <i class="bx bx-map"></i> 
        @if($row2->lat_long != null)
        <!-- <a href="https://www.google.com/maps/?q=" target="_blank"> -->
        @else
        <!-- <a href="#"> -->
        @endif   
        <a href="https://www.google.com/maps/search/?api=1&query={{$row2->dept_address}}" target="_blank">{{$row2->dept_address}}</a>
          </a>
        </div>
      </div>
      @endif
     
  </div>
</div>

 
                        @endforeach
                    
                        </div>    
          </div>
        @endforeach
        

         
  

  @section('page-scripts')
<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<!-- <script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script> -->
<script type="text/javascript">
  
</script>

@endsection
  
<script>

  $(document).ready(function(){
    
  var newTaskEditor = new Quill('.new_task_editor', {
    modules: {
      toolbar: '.new_task_quill_toolbar'
    },
    placeholder: 'Add Description..... ',
    theme: 'snow'
    });
  });
</script>