<!-- BEGIN: Body-->

<body class="horizontal-layout navbar-sticky 2-columns light-layout content-left-sidebar footer-static todo-application pace-done menu-expanded horizontal-menu" data-open="hover" data-menu="horizontal-menu" data-col="2-columns" data-framework="laravel">

  <!-- BEGIN: Header-->
  @include('panels.horizontal-navbar')
  <!-- END: Header-->

  <!-- BEGIN: Main Menu-->
  {{-- @include('panels.sidebar') --}}
  <!-- END: Main Menu-->

  <!-- BEGIN: Content-->
  <div class="app-content content">
    {{-- Application page structure --}}
    @if(true)
  
    <div class="content-area-wrapper">
      <!-- <div class="content-area-wrapper" style="margin-top: 5rem !important;"> -->
      <div class="sidebar-left">
        <div class="sidebar">
           @include('pages.task.task_sidebar')
        </div>
      </div>
      <div class="content-right">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body">

            <div class="app-content-overlay"></div>
<div class="todo-app-area">
  <div class="todo-app-list-wrapper">
    <div class="todo-app-list" style="background: #fff">
      <div class="todo-task-list list-group ps ps--active-x" style="height: 90vh; !important">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
          </div>
        </div>
      </div>
    </div>
    @else
    {{-- others page structures --}}
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        @if($configData['pageHeader'] === true && isset($breadcrumbs))
        @include('panels.breadcrumbs')
        @endif
      </div>
      <div class="content-body">
        @yield('content')
      </div>
    </div>
    @endif
  </div>
  <!-- END: Content-->
  @if($configData['isCustomizer'] === true && isset($configData['isCustomizer']))
  <!-- BEGIN: Customizer-->

  <!-- End: Customizer-->

  <!-- Buynow Button-->

  @endif
  <!-- demo chat-->
  <!-- BEGIN: Footer-->
  <!-- END: Footer-->

  @include('panels.scripts')
  @yield('custom_js_files')
</body>
<!-- END: Body-->
