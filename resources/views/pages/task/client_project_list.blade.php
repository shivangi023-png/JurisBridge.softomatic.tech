    <!-- client_project_list -->
    <ul class="flex-column pl-2 nav" id="list_clients">
        @foreach($clients_list as $row)
        <li class="nav-item">
            <a class="nav-link text-truncate collapsed sub_menu clients_list_project" href="#submenu1sub{{$row->id}}" data-toggle="collapse" data-target="#submenu1sub{{$row->id}}"><span>{{$row->client_name}}</span></a>
            <div class="collapse div_project{{$row->id}} custom_expand" id="submenu1sub{{$row->id}}" aria-expanded="false">
                <ul class="flex-column nav _sub_menu">
                     @foreach($row->project_list as $row2)
                    <li class="nav-item">
                        <a class="nav-link text-truncate _sub_menu_link client_project_link" data-client_id="{{$row->id}}" href="projects_task-{{$row2->id}}">
                            <i class="fa fa-fw fa-clock-o"></i>{{$row2->project_name}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </li>
        @endforeach
    </ul>
