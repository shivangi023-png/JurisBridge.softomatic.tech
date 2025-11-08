 <style>
.clients_list_project[data-toggle].collapsed:after {
    content: " +";
    float: right;
    font-size: 18px;
    color: #5a8dee !important;
}
.clients_list_project[data-toggle]:not(.collapsed):after {
    content: " -";
    float: right;
    font-size: 18px;
    color: #5a8dee !important;
}
.datepicker 
{
    z-index:100000;
}
 </style>
 @section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/extensions/dragula.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-todo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/task.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
@endsection
 <div class="todo-sidebar d-flex" style="height: 100vh; !important; margin-top:35px">
  <span class="sidebar-close-icon">
    <i class="bx bx-x"></i>
  </span>
  <!-- todo app menu -->
  <div class="todo-app-menu">
      <div class=" text-center add-task">
      <!-- new task button -->
      <button type="button" class="btn btn-primary btn-block my-1" data-toggle="dropdown" aria-expanded="false" fdprocessedid="w2sak4">
        <i class="bx bx-plus"></i>
        <span>Create</span>
      </button>
      <div class="dropdown-menu" style="position: absolute;will-change: transform;z-index: 15;top: 0px;left: 0px;transform: translate3d(-55px, 38px, 0px);">
          <a class="dropdown-item new_modal_template text-capitalize font-weight-normal" href="javascript:void(0);">Template</a>
           <a data-toggle="modal" data-target="#NewProjectModal" href="javascript:void(0);" class="dropdown-item text-capitalize font-weight-normal" href="#">Project</a> 
           <!--<a href="javascript:void(0);" class="dropdown-item new_modal_project1 text-capitalize font-weight-normal">Project</a>-->
           <a href="javascript:void(0);" class="dropdown-item new_modal_task text-capitalize font-weight-normal common_add_task_btn">Task</a>
        </div>
    </div>
    <div class="form-group text-center add-task">
<div class="form-group has-search">
        <span class="bx bx-search form-control-feedback"></span>
        <input type="text" class="form-control" placeholder="Search" id="searchMenu">
        <!-- onkeyup="search_task(this.value)" -->
</div>

    </div>
    <!-- sidebar list start -->
    <div class="sidebar-menu-list">
          <ul class="nav flex-column flex-nowrap overflow-hidden menu_align" id="taskSideMenuList">
                <li class="nav-item">
                    <a class="nav-link text-truncate {{ request()->is('task') ? 'menu_active' : '' }}" href="task"> <i class="livicon-evo" data-options="name: dashboard.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i> <span class="d-none d-sm-inline menu_title">Dashboard</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-truncate {{ request()->is('projects') ? 'menu_active' : '' }}" href="projects"> <i class="livicon-evo" data-options="name: diagram.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i> <span class="d-none d-sm-inline menu_title">Projects</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-truncate {{ request()->is('projects_task-*') ? 'menu_active' : 'collapsed' }} clients_list_project" href="#submenu1" data-toggle="collapse" data-target="#submenu1"> <i class="livicon-evo" data-options="name: list.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i> <span class="d-none d-sm-inline menu_title">Clients</span></a>
                    <div class="collapse client_project_list {{ request()->is('projects_task-*') ? 'show' : '' }}" id="submenu1" aria-expanded="false">
                    </div>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link text-truncate {{ request()->is('template') ? 'menu_active' : '' }}" href="template"> <i class="livicon-evo" data-options="name: thumbnails-small.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i> <span class="d-none d-sm-inline menu_title">Template List</span></a>
                </li> -->

              <li class="nav-item">
                 <a class="nav-link text-truncate clients_list_project {{ request()->is('template_task-*') ? 'menu_active' : 'collapsed' }}" href="#temp_submenu" data-toggle="collapse" data-target="#temp_submenu"><i class="livicon-evo" data-options="name: grid.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent;"></i> <span class="d-none d-sm-inline menu_title">Task Templates</span></a>
                      <div class="collapse {{ request()->is('template_task-*') ? 'show' : '' }} template_task_list" id="temp_submenu">
                     <!-- Template_list -->
                     </div>
              </li>
              <li class="nav-item">
                <a class="nav-link text-truncate {{ request()->is('inbox') ? 'menu_active' : '' }}" href="inbox"> <i class=""><img style="margin-bottom: 8px; margin-right: 4px; margin-left: 4px;" src="{{asset('images/svg/InOutIcon.svg')}}"></i> <span class="d-none d-sm-inline menu_title">InOut Box</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-truncate {{ request()->is('task-report') ? 'menu_active' : '' }}" href="task-report"> <i class=""><img style="margin-bottom: 8px; margin-right: 4px; margin-left: 4px;" src="{{asset('images/svg/InOutIcon.svg')}}"></i> <span class="d-none d-sm-inline menu_title">Report</span></a>
            </li>
<!-- collapsed -->
<!-- collapse show -->
            </ul>
    </div>
    <!-- sidebar list end -->
  </div>
</div>



@include('pages.task.add_template_modal')

@include('pages.task.add_project_modal')

@include('pages.task.add_task')

@include('pages.task.add_subtask')
@include('pages.task.comment_modal')
@section('custom_js_files')
<script src="{{ asset('js/scripts/pages/task-side-menu-search.js') }}"></script>
@endsection




