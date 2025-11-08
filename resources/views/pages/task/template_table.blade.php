<h4 class="project_heading">Template List</h4>
     <section id="table-success">
  <div class="card _card">
    <div class="table-responsive">
      <div><table id="table-extended-success" class="table dataTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @php $i = 1; @endphp
           @foreach($template_list as $row)
        <tr>
            <td>{{$i++}}</td>
            <td class="project_name"><a href="template_task-{{$row->id}}">{{$row->template_name}}</a></td>
            <td>{{($row->status != null) ? ucfirst($row->status) : '' }}</td>
            <td>
               <a href="#" title="Create Task" class="new_task_Template_btn" data-main_template_id="{{$row->id}}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;margin-bottom: 12px;"><path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path></svg>
               </a>
               <a href="#" title="Update Template" class="update_main_template" data-main_template_id="{{$row->id}}" data-main_template_name="{{$row->template_name}}" data-status="{{$row->status}}"><i class="bx bx-edit action_edit_icon"></i></a>
               <a href="#" class="delete_template" title="Delete Template" data-main_template_id="{{$row->id}}"><i class="bx bx-trash-alt action_delete_icon"></i></a>
             </td>
        </tr>
          @endforeach
        </tbody>
      </table></div>
    </div>
   
  </div>
</section>