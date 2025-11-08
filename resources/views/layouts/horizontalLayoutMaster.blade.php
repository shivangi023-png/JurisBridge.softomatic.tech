<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu @if(isset($configData['navbarType']) && ($configData['navbarType'] !== 'navbar-hidden') ){{$configData['navbarType']}} @else {{'navbar-sticky'}}@endif 2-columns
@if($configData['theme'] === 'dark'){{'dark-layout'}} @elseif($configData['theme'] === 'semi-dark'){{'semi-dark-layout'}} @else {{'light-layout'}} @endif
@if($configData['isContentSidebar']=== true) {{'content-left-sidebar'}} @endif
@if(isset($configData['footerType'])) {{$configData['footerType']}} @endif {{$configData['bodyCustomClass']}}
@if($configData['isCardShadow'] === false){{'no-card-shadow'}}@endif" data-open="hover" data-menu="horizontal-menu"
    data-col="2-columns" data-framework="laravel">
    <div id="cover-spin"></div>
    <!-- BEGIN: Header-->
    @include('panels.horizontal-navbar')
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    @if (session('role_id') != 9)
    @include('panels.sidebar')
    @endif
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        {{-- Application page structure --}}
        @if($configData['isContentSidebar'] === true)
        <div class="content-area-wrapper">
            <div class="sidebar-left">
                <div class="sidebar">
                    @yield('sidebar-content')
                </div>
            </div>
            <div class="content-right">
                <div class="content-overlay"></div>
                <div class="content-wrapper">
                    <div class="content-header row">
                    </div>
                    <div class="content-body">
                        @yield('content')
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
    <div class="customizer d-none d-md-block">
        <!-- <a class="customizer-toggle" href="#"><i class="bx bx-cog bx bx-spin white"></i></a> -->
        @include('pages.customizer-content')
    </div>
    <!-- End: Customizer-->

    <!-- Buynow Button-->

    @endif
    <!-- demo chat-->


    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include('panels.footer')
    <!-- END: Footer-->

    @include('panels.scripts')
    <!-- END: Body-->

</body>
<style>
#cover-spin {
    position: fixed;
    width: 100%;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    display: none;
}

@-webkit-keyframes spin {
    from {
        -webkit-transform: rotate(0deg);
    }

    to {
        -webkit-transform: rotate(360deg);
    }
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

#cover-spin::after {
    content: '';
    display: block;
    position: absolute;
    left: 48%;
    top: 40%;
    width: 40px;
    height: 40px;
    border-style: solid;
    border-color: black;
    border-top-color: transparent;
    border-width: 4px;
    border-radius: 50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}
</style>