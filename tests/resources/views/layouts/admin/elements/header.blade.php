<!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                        </ul>
                        <ul class="nav navbar-nav bookmark-icons">
                            <li class="nav-item d-none d-lg-block">
                                <a class="nav-link" href="{{route('home')}}" data-toggle="tooltip" data-placement="top" title="Dashboard"><i class="ficon feather icon-home"></i></a>
                            </li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                        @php
                            $noti_count = App\Models\Notification::where(['user_id'=> Auth::user()->id, 'is_read' => "0"])->count();  
                        @endphp
                        
                        @can('notification-list')
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="{{route('notifications.index')}}"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up">{{$noti_count}}</span></a>
                        </li>
                        @endcan
                          <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">

                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600">
                                    {{@Auth::user()->first_name}} {{@Auth::user()->last_name}}
                                    </span>
                                    <span class="user-status">
                                        {{ ucfirst(@Auth::user()->user_type) }}
                                    </span>
                                </div>
                                <span>
                                @if(Auth::user()->profile_image != null)
                                    <img class="round" src="{{asset(Auth::user()->profile_image) }}" alt="avatar" height="40" width="40">
                                @else
                                    <img src="{{asset('admin_assets/app-assets/images/user.jpg') }}" class="round" alt="User Image" height="40" width="40">
                                @endif    
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('profile')}}"><i class="feather icon-user"></i> Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();" ><i class="feather icon-power"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                     </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->