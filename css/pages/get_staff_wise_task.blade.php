@include('layouts.task_tabs')

<div class="tab-content">
  <div class="tab-pane active" id="tabs-1" role="tabpanel">
    <div class="todo-task-list list-group ps" style="height: 100%; !important;">


      <div class="task_list">

        <div style="gap: 10px;display: inline-flex;margin: 28px 10px 10px 8px;">
        @foreach($staff as $stf)
          <div class="task_div" >
            <div class="todoHead bg_todo">
              <h4>{{$stf->name}} - {{sizeof($stf->task)}}</h4>
              
            </div>
            <div class="todoMain" >
              @foreach($stf->task as $row2)
            <div class="col-sm-12 todoCBox project_task" class="" data-project_id="{{$row2->project_id}}" data-task_id="{{$row2->id}}" data-task_title="{{$row2->title}}" data-file_link="{{$row2->file_link}}"  data-task_type="{{$row2->type}}" data-task_priority="{{$row2->priority}}" data-task_assignee="{{$row2->assignee}}" data-task_status="{{$row2->status}}" data-task_is_milestone="{{$row2->is_milestone}}" data-task_start_date="{{($row2->start_date != null) ? date('d/m/Y',strtotime($row2->start_date)) : '' }}" data-task_end_date="{{($row2->end_date != null) ? date('d/m/Y',strtotime($row2->end_date)) : '' }}" data-task_description="{{$row2->description}}">

                        <div class="TaskBox" style="margin-bottom:10px">
                            <div class="row top">
                                <div class="pull-left col-sm-12"><span style="font-size:12px; font-weight:700">{{$row2->project_name}}
                        </span>
                                </div>
                              </div>
                            <h4>{{$row2->title}}</h4>
                            <p><?php echo nl2br($row2->description) ?></p>
                            @if($row2->start_date!='' || $row2->end_date!='')
                            <div class="date">
                                <h6>{{date('d-M-Y',strtotime($row2->start_date))}} | {{date('d-M-Y',strtotime($row2->end_date))}}</h6>
                                
                            </div>
                            @endif

                            <div class="BtnBox">
                                <div class="LeftBtn">
                                    <a class="Mbtn" href="#">{{$row2->priority_name}}</a>
                                </div>
                                <div class="RightBtn">
                                    <a class="NewBtn" href="#">{{$row2->type_name}}</a>
                                </div>

                            </div>
                            <div class="commentBox row">
                                
                                <i class="bx bxs-doughnut-chart"></i><h6>{{$row2->status_name}}</h6>
                               
                            </div>
                           
                        </div>
                        </div>
                      
                        @endforeach
                    
                        </div>    
          </div>
        @endforeach
        

         
        </div>
      </div>
    </div>
  </div>

 
  
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