<header class="topnavbar-wrapper">
    <!-- START Top Navbar-->
    <nav class="navbar topnavbar">        
        <!-- START navbar header-->
        <div class="navbar-header">
            <a class="navbar-brand" href="#/">
                <div class="brand-logo">
                    {{-- <img class="img-fluid" src="{{asset('img/logo.png')}}" alt="App Logo">                     --}}
                    <h2 style="color:white;">TextCall</h2>
                </div>
                <div class="brand-logo-collapsed">
                    {{-- <img class="img-fluid" src="{{asset('img/logo-single.png')}}" alt="App Logo">                     --}}
                    <p style="color:white;">T-C</p>
                </div>
            </a>
        </div>
        <!-- END navbar header-->

        <!-- START Left navbar-->
        <ul class="navbar-nav mr-auto flex-row">
            <li class="nav-item">
                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                <a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize="" data-toggle-state="aside-collapsed">
                    <em class="fas fa-bars"></em>
                </a>
                <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                <a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled" data-no-persist="true">
                    <em class="fas fa-bars"></em>
                </a>
            </li>
            
            <!-- START User avatar toggle-->
            {{-- <li class="nav-item d-none d-md-block">
                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                <a class="nav-link" id="user-block-toggle" href="#user-block" data-toggle="collapse">
                    <em class="icon-user"></em>
                </a>
            </li> --}}
            <!-- END User avatar toggle-->
            
            <!-- START lock screen-->
            {{-- <li class="nav-item d-none d-md-block">
                <a class="nav-link" href="lock.html" title="Lock screen"><em class="icon-lock"></em></a>
            </li> --}}
            <!-- END lock screen-->
        
        </ul>
        <!-- END Left navbar-->

        <!-- START Right Navbar-->
        <ul class="navbar-nav flex-row">
            <!-- Search icon-->
            {{-- <li class="nav-item">
                <a class="nav-link" href="#" data-search-open="">
                    <em class="icon-magnifier"></em>
                </a>
            </li> --}}
            
            <!-- Fullscreen (only desktops)-->
            <li class="nav-item d-none d-md-block">
                <a class="nav-link" href="#" data-toggle-fullscreen="">
                    <em class="fas fa-expand"></em>
                </a>
            </li>
            
            <!-- START Alert menu-->
            {{-- <li class="nav-item dropdown dropdown-list">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-toggle="dropdown">
                    <em class="icon-bell"></em>
                    <span class="badge badge-danger">11</span>
                </a>
                <!-- START Dropdown menu-->
                <div class="dropdown-menu dropdown-menu-right animated flipInX">
                    <div class="dropdown-item">
                        <!-- START list group-->
                        <div class="list-group">
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2">
                                        <em class="fab fa-twitter fa-2x text-info"></em>
                                    </div>
                                    <div class="media-body">
                                        <p class="m-0">New followers</p>
                                        <p class="m-0 text-muted text-sm">1 new follower</p>
                                    </div>
                                </div>
                            </div>
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2">
                                        <em class="fas fa-envelope fa-2x text-warning"></em>
                                    </div>
                                    <div class="media-body">
                                        <p class="m-0">New e-mails</p>
                                        <p class="m-0 text-muted text-sm">You have 10 new emails</p>
                                    </div>
                                </div>
                            </div>
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2">
                                        <em class="fas fa-tasks fa-2x text-success"></em>
                                    </div>
                                    <div class="media-body">
                                        <p class="m-0">Pending Tasks</p>
                                        <p class="m-0 text-muted text-sm">11 pending task</p>
                                    </div>
                                </div>
                            </div>
                            <!-- last list item-->
                            <div class="list-group-item list-group-item-action">
                                <span class="d-flex align-items-center">
                                    <span class="text-sm">More notifications</span>
                                    <span class="badge badge-danger ml-auto">14</span>
                                </span>
                            </div>
                        </div><!-- END list group-->
                    </div>
                </div><!-- END Dropdown menu-->
            </li> --}}
            <!-- END Alert menu-->
            
            <!-- START Offsidebar button-->
            {{-- <li class="nav-item">
                <a class="nav-link" href="#" data-toggle-state="offsidebar-open" data-no-persist="true">
                    <em class="icon-notebook"></em>
                </a>
            </li> --}}
            
            <!-- START Logout button-->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" title="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <em class="icon-logout"></em>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

            <!-- END Offsidebar menu-->
        </ul>
        <!-- END Right Navbar-->

        <!-- START Search form-->
        <form class="navbar-form" role="search" action="search.html">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Type and hit enter ...">
                <div class="fas fa-times navbar-form-close" data-search-dismiss=""></div>
            </div>
            <button class="d-none" type="submit">Submit</button>
        </form><!-- END Search form-->
    </nav><!-- END Top Navbar-->
</header>