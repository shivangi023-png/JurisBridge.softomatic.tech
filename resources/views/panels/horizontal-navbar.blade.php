<style>
    .view_profile {
        width: 550px;
    }
</style>
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-brand-center @if($configData['navbarBgColor'] !== 'bg-white' )) {{$configData['navbarBgColor']}} @else {{'bg-primary'}} @endif
@if($configData['navbarType'] === 'navbar-static') {{'navbar-static-top'}} @else {{'fixed-top'}} @endif
@if($configData['theme'] === 'light') {{"menu-light"}} @else {{'menu-dark'}} @endif">
    <div class="navbar-header d-xl-block d-none">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item">
                <a class="navbar-brand" href="{{asset('/')}}">
                    <div class="brand-logo">
                        <img src="{{asset('images/logo/new-logo.png')}}" class="logo" alt="">
                    </div>
                    <h2 class="brand-text mb-0">

                    </h2>
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <!-- Task dashboard - main Dashboard link -->
                         @if ('task'== Request::path() || 'projects'== Request::path() || 'inbox'== Request::path() || 'outbox'== Request::path() || request()->is('projects_task-*') || 'task_analytics'== Request::path() || 'staff_wise_task'== Request::path() || 'task_hearing'== Request::path() )
                             <li class="nav-item mobile-menu mr-auto">
                                <a class="nav-link nav-menu-main" href="{{asset('/')}}" style="border: 1px solid #727272;padding: 4px 10px;border-radius: 6px;">
                                    <i class="bx bx-home" style="font-size: 22px;color: #5a8dee!important;"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item mobile-menu mr-auto"><a class="nav-link nav-menu-main menu-toggle" href="#"><i class="bx bx-menu"></i></a></li>
                        @endif
                    </ul>


                </div>
                <ul class="nav navbar-nav float-right d-flex align-items-center">
                    <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selected-company d-lg-inline">{{session('company_name')}}<input type="hidden" value="{{session('company_id')}}" class="form-comtrol selected-company-id"></span></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            @foreach(session('company_full') as $com_full)
                            <a class="dropdown-item company_btn" href="#" data-company_name="{{$com_full->company_name}}" data-company="{{$com_full->id}}">
                                {{$com_full->company_name}}
                            </a>
                            @endforeach

                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
                    <!-- <li class="nav-item nav-search"><a class="nav-link nav-link-search pt-2"><i class="ficon bx bx-search"></i></a>
            <div class="search-input">
              <div class="search-input-icon"><i class="bx bx-search primary"></i></div>
              <input class="input" type="text" placeholder="Explore Frest..." tabindex="-1" data-search="template-search">
              <div class="search-input-close"><i class="bx bx-x"></i></div>
              <ul class="search-list"></ul>
            </div>
          </li> -->
                    @if (session('role_id') != 9)
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                            <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
                            <span class="badge badge-pill badge-danger badge-up">5</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header px-1 py-75 d-flex justify-content-between">
                                    <span class="notification-title">7 new Notification</span>
                                    <span class="text-bold-400 cursor-pointer">Mark all as read</span>
                                </div>
                            </li>
                            <li class="scrollable-container media-list">
                                <a class="d-flex justify-content-between" href="javascript:void(0)">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar mr-1 m-0"><img src="{{asset('images/portrait/small/avatar-s-11.jpg')}}" alt="avatar" height="39" width="39"></div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">Congratulate Socrates
                                                    Itumay</span> for work anniversaries</h6><small class="notification-text">Mar 15 12:32pm</small>
                                        </div>
                                    </div>
                                </a>
                                <div class="d-flex justify-content-between read-notification cursor-pointer">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar mr-1 m-0">
                                                <img src="{{asset('images/portrait/small/avatar-s-16.jpg')}}" alt="avatar" height="39" width="39">
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">New Message</span>
                                                received</h6>
                                            <small class="notification-text">You have 18 unread messages</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between cursor-pointer">
                                    <div class="media d-flex align-items-center py-0">
                                        <div class="media-left pr-0">
                                            <img class="mr-1" src="{{asset('images/icon/sketch-mac-icon.png')}}" alt="avatar" height="39" width="39">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">Updates
                                                    Available</span></h6>
                                            <small class="notification-text">Sketch 50.2 is currently newly
                                                added</small>
                                        </div>
                                        <div class="media-right pl-0">
                                            <div class="row border-left text-center">
                                                <div class="col-12 px-50 py-75 border-bottom">
                                                    <h6 class="media-heading text-bold-500 mb-0">Update</h6>
                                                </div>
                                                <div class="col-12 px-50 py-75">
                                                    <h6 class="media-heading mb-0">Close</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between cursor-pointer">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar bg-primary bg-lighten-5 mr-1 m-0 p-25"><span class="avatar-content text-primary font-medium-2">LD</span></div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">New customer</span> is
                                                registered</h6><small class="notification-text">1 hrs ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="media d-flex align-items-center justify-content-between">
                                        <div class="media-left pr-0">
                                            <div class="media-body">
                                                <h6 class="media-heading">New Offers</h6>
                                            </div>
                                        </div>
                                        <div class="media-right">
                                            <div class="custom-control custom-switch">
                                                <input class="custom-control-input" type="checkbox" checked id="notificationSwtich">
                                                <label class="custom-control-label" for="notificationSwtich"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between cursor-pointer">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar bg-danger bg-lighten-5 mr-1 m-0 p-25">
                                                <span class="avatar-content"><i class="bx bxs-heart text-danger"></i></span>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">Application</span> has
                                                been approved</h6>
                                            <small class="notification-text">6 hrs ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between read-notification cursor-pointer">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar mr-1 m-0"><img src="{{asset('images/portrait/small/avatar-s-4.jpg')}}" alt="avatar" height="39" width="39"></div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">New file</span> has
                                                been uploaded</h6>
                                            <small class="notification-text">4 hrs ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between cursor-pointer">
                                    <div class="media d-flex align-items-center">
                                        <div class="media-left pr-0">
                                            <div class="avatar bg-rgba-danger m-0 mr-1 p-25">
                                                <div class="avatar-content"><i class="bx bx-detail text-danger"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">Finance report</span>
                                                has been generated</h6>
                                            <small class="notification-text">25 hrs ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between cursor-pointer">
                                    <div class="media d-flex align-items-center border-0">
                                        <div class="media-left pr-0">
                                            <div class="avatar mr-1 m-0"><img src="{{asset('images/portrait/small/avatar-s-16.jpg')}}" alt="avatar" height="39" width="39"></div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><span class="text-bold-500">New customer</span>
                                                comment recieved</h6>
                                            <small class="notification-text">2 days ago</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown-menu-footer">
                                <a class="dropdown-item p-50 text-primary justify-content-center" href="javascript:void(0)">Read all notifications</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-lg-flex d-none">
                                <span class="user-name">{{session('username')}}</span>
                            </div>
                            <span>
                                @if(session('image')=='')
                                <img class="round" src="{{asset('images/pages/vector-image.png')}}" alt="avatar" height="40" width="40">
                                @else
                                <img class="round" src="{{asset(session('image'))}}" alt="avatar" height="40" width="40">
                                @endif

                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right pb-0">
                            <li class="dropdown dropdown-submenu"><a class="dropdown-item view_staff" href="javascript:void(0);"><i class="bx bx-user mr-50"></i> View
                                    Profile<i class="bx bx-chevron-down"></i></a>
                                <ul class="dropdown-menu dropdown-menu-right">

                                    <div class="card view_profile">
                                        <div class="card-body">
                                            <h5 class="card-title">BASIC DETAILS</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="cursor-pointer bx bx-user mb-1 mr-50"></i>{{session('name')}}
                                                </li>
                                                <li><i class="cursor-pointer bx bx-envelope mb-1 mr-50"></i>{{session('email')}}
                                                </li>
                                                <li><i class="cursor-pointer bx bx-phone-call mb-1 mr-50"></i>{{session('mobile')}}
                                                </li>
                                                <li><i class="cursor-pointer bx bx-map mb-1 mr-50"></i>{{session('address')}}
                                                    , {{session('city')}}
                                                </li>
                                                <li><i class="cursor-pointer bx bx-time mb-1 mr-50"></i>{{session('dob')}}
                                                </li>

                                            </ul>
                                            <div class="row">
                                                <div class="col-sm-6 col-12">
                                                    <h6><small class="text-primary">Role</small></h6>
                                                    <p>{{session('role')}}</p>
                                                </div>

                                                <div class="col-sm-6 col-12">
                                                    <h6><small class="text-primary">Date of Joining</small></h6>
                                                    <p>{{session('date_of_joining')}}</p>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <h6><small class="text-primary">Department</small></h6>
                                                    <p>{{session('designation')}}</p>
                                                </div>

                                                <div class="col-sm-6 col-12">
                                                    <h6><small class="text-primary">Company</small></h6>
                                                    <p>{{session('staff_company')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </ul>
                            </li>
                            @if (session('role_id') != 9)
                            <a class="dropdown-item" href="{{asset('app/email')}}"><i class="bx bx-envelope mr-50"></i>
                                My Inbox</a>
                            <a class="dropdown-item" href="{{asset('app/todo')}}"><i class="bx bx-check-square mr-50"></i> Task</a>
                            <a class="dropdown-item" href="{{asset('app/chat')}}"><i class="bx bx-message mr-50"></i>
                                Chats</a>
                            @endif
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item" href="logout"><i class="bx bx-power-off mr-50"></i> Logout</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
@section('vendor-scripts')
<script src="{{asset('vendors/js/jquery/jquery.min.js')}}"></script>
@endsection
@section('page-scripts')

<script>
    $(document).ready(function() {
    });
</script>
@endsection