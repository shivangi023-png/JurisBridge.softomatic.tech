    <!-- template list -->
    <ul class="flex-column pl-2 nav" id="list_template">
        @foreach($template_task_list as $row)
        <li class="nav-item">
            <a class="nav-link text-truncate collapsed sub_menu" href="template_task-{{$row->id}}"><span>{{$row->template_name}}</span></a>
        </li>
        @endforeach
    </ul>
