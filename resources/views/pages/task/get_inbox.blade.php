@foreach($inboxDataArr as $row)
      <?php
          $sentence = $row['comment'];
          $tagname=json_decode($row['tagname'],true);
          foreach($tagname as $row1)
          {
          
            $names_array[]='@'.$row1;
          }
          $replacement_names =$names_array;
          foreach ($names_array as $index => $name) {
          if (isset($replacement_names[$index])) {
            $sentence = str_replace($name, '<span style="color: #5a8dee;">' . htmlspecialchars($replacement_names[$index]) . '</span>', $sentence);
          }
        }
      ?>
      @foreach($row['task_list'] as $row1)
      <div class="row project_task"  data-project_id="{{$row1->project_id}}" data-time="{{$row1->time}}" data-task_id="{{$row1->id}}" data-task_title="{{$row1->title}}" data-file_link="{{$row1->file_link}}" data-task_description="{{$row1->description}}" data-task_type="{{$row1->type}}" data-task_priority="{{$row1->priority}}" data-task_assignee="{{$row1->assignee}}" data-task_status="{{$row1->status}}" data-task_is_milestone="{{$row1->is_milestone}}" data-task_start_date="{{($row1->start_date != null) ? date('d/m/Y',strtotime($row1->start_date)) : '' }}" data-task_end_date="{{($row1->end_date != null) ? date('d/m/Y',strtotime($row1->end_date)) : '' }}" data-office_id="{{$row1->office_id}}" data-working_hr="{{$row1->working_hr}}">
        <div class="col-sm-1 inoutcheckIcon">
          <center><i class="bx bxs-check-square"></i></center>
        </div>
        <div class="col-sm-8 inoutContent">
          <h4>{{$row['task_title']}}</h4>
          <p><?php echo $sentence; ?></p>
          <h6>sent by <span>{{$row['sended_by']}}</span></h6>
        </div>
        <div class="col-sm-3 inoutTimeDate">
          <p><i class="bx bx-time"></i>{{$row['datetime']}}</p>
        </div>
      </div>
      @endforeach
      <hr>
@endforeach
