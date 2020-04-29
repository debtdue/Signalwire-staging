<aside class="aside-container">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar" data-sidebar-anyclick-close="">

            <!-- START sidebar nav-->
            <ul class="sidebar-nav">

                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status"><img class="img-thumbnail rounded-circle" src="img/user/02.jpg" alt="Avatar" width="60" height="60">
                                    <div class="circle bg-success circle-lg"></div>
                                </div>
                            </div><!-- Name and Job-->
                            <div class="user-block-info"><span class="user-block-name">Hello, Mike</span><span class="user-block-role">Designer</span></div>
                        </div>
                    </div>
                </li>
                <!-- END user info-->

                <!-- Iterates over all sidebar items-->
                <li class="nav-heading ">
                    {{-- <span data-localize="sidebar.heading.HEADER">Main Navigation</span> --}}
                </li>

                {{--Dashboard--}}
                <li class="{{ (\Request::route()->getName() == 'home') ? 'active' : '' }}">
                    <a href="{{route('home')}}">
                        <em class="icon-speedometer"></em>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Businesses--}}
	            <?php $businessPages = ['businesses.index','businesses.create','businesses.edit','businesses.show','businesses.update','businesses.destroy']; ?>
                <li @if(in_array( \Request::route()->getName(), $businessPages)) class="active" @endif>
                    <a href="#businesses" title="Pages" data-toggle="collapse">
                        <em class="icon-briefcase"></em>
                        <span data-localize="sidebar.nav.pages.AGENTS">Businesses</span>
                    </a>
                    <ul class="nav sidebar-subnav collapse @if(in_array( \Request::route()->getName(), $businessPages)) in @endif "
                        id="businesses">

                        <li class="sidebar-subnav-header">Businesses</li>
	                    <?php $businessManagePages = ['businesses.index','businesses.edit','businesses.show','businesses.update','businesses.destroy']; ?>
                        <li @if(in_array( \Request::route()->getName(), $businessManagePages)) class="active" @endif>
                            <a href="{{ route('businesses.index') }}" title="Manage Businesses">
                                <span data-localize="sidebar.nav.pages.BUSINESSES">Manage Businesses</span>
                            </a>
                        </li>

                        <li class="{{ (\Request::route()->getName() == 'businesses.create') ? 'active' : '' }}">
                            <a href="{{ route('businesses.create') }}" title="Create Business">
                                <span data-localize="sidebar.nav.pages.BUSINESS">Create Business</span>
                            </a>
                        </li>

                    </ul>
                </li>

                {{--Agents--}}
	            <?php $agentPages = ['agents.index','agents.create','agents.edit','agents.update','agents.destroy']; ?>
                <li @if(in_array( \Request::route()->getName(), $agentPages)) class="active" @endif>
                    <a href="#agents" title="Agents" data-toggle="collapse">
                        <em class="icon-people"></em>
                        <span>Agents</span>
                    </a>
                    <ul class="nav sidebar-subnav collapse @if(in_array( \Request::route()->getName(), $agentPages)) in @endif " id="agents">
                        <li class="sidebar-subnav-header">Agents</li>
                        <li class="{{ (\Request::route()->getName() == 'agents.index') ? 'active' : '' }}">
                            <a href="{{ route('agents.index') }}" title="Manage Agents">
                                <span data-localize="sidebar.nav.pages.Manage Agents">Manage Agents</span>
                            </a>
                        </li>

                        <li class="{{ (\Request::route()->getName() == 'agents.create') ? 'active' : '' }}">
                            <a href="{{ route('agents.create') }}" title="Create Agent">
                                <span data-localize="sidebar.nav.pages.Create Agents">Create Agent</span>
                            </a>
                        </li>

                    </ul>
                </li>

                {{--Do not Contact List--}}
                <li class="{{ (\Request::route()->getName() == 'dnc-numbers.index') ? 'active' : '' }}">
                    <a href="{{route('dnc-numbers.index')}}">
                        <em class="icon-ban"></em>
                        <span>DNC #s</span>
                    </a>
                </li>

                {{--VoIP Numbers--}}
	            <?php $voipNumberPages = ['voip-accounts.index','voip-numbers.index','voip-numbers.show','voip-numbers.edit','voip-numbers.update','voip-numbers.destroy',
                    'voip-numbers.search','voip-numbers.list']; ?>
                <li @if(in_array( \Request::route()->getName(), $voipNumberPages)) class="active" @endif>
                    <a href="#viop_numbers" title="Pages" data-toggle="collapse">
                        <em class="icon-grid"></em>
                        <span data-localize="sidebar.nav.pages.voipNumbers">VoIP Accounts</span>
                    </a>
                    <ul class="nav sidebar-subnav collapse @if(in_array( \Request::route()->getName(), $voipNumberPages)) in @endif "
                        id="viop_numbers">
                        <li class="sidebar-subnav-header">VoIP Accounts</li>
	                    <?php $voipNumberSubPages = ['voip-accounts.index','voip-accounts.edit','voip-numbers.show','voip-numbers.edit','voip-numbers.update']; ?>
                        <li @if(in_array( \Request::route()->getName(), $voipNumberSubPages)) class="active" @endif>
                            <a href="{{ route('voip-accounts.index') }}" title="Manage Accounts">
                                <span data-localize="sidebar.nav.pages.voipNumbers">Manage Accounts</span>
                            </a>
                        </li>


                        <li class="{{ (\Request::route()->getName() == 'voip-accounts.create') ? 'active' : '' }}">
                            <a href="{{ route('voip-accounts.create') }}" title="Create Twilio Sub Account">
                                <span data-localize="sidebar.nav.pages.voipNumber">Add Account</span>
                            </a>
                        </li>

	                    <?php $purchaseNumbersPages = ['voip-numbers.search','voip-numbers.list'] ?>
                        <li @if(in_array( \Request::route()->getName(), $purchaseNumbersPages)) class="active" @endif>
                            <a href="{{ route('voip-numbers.search') }}" title="Purchase Number">
                                <span data-localize="sidebar.nav.pages.voipNumber">Purchase Numbers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--Calls--}}
                <li class="{{ (\Request::route()->getName() == 'calls.logs') ? 'active' : '' }}">
                    <a href="{{route('calls.logs')}}">
                        <em class="icon-phone"></em>
                        <span>Call Logs</span>
                    </a>
                </li>

                {{--Reports--}}
	            <?php $reportsPages = ['reports.index','reports.search','reports.searched_call_details'] ?>
                <li @if(in_array( \Request::route()->getName(), $reportsPages)) class="active" @endif>
                    <a href="{{route('reports.index')}}">
                        <em class="fas fa-newspaper"></em>
                        <span>Reports</span>
                    </a>
                </li>

                {{--Settings--}}
                <li class="{{ (\Request::route()->getName() == 'show_settings') ? 'active' : '' }}">
                    <a href="{{route('show_settings')}}">
                        <em class="icon-settings"></em>
                        <span>Settings</span>
                    </a>
                </li>

                {{--Profile--}}
                <li class="{{ (\Request::route()->getName() == 'show_profile') ? 'active' : '' }}">
                    <a href="{{route('show_profile')}}">
                        <em class="icon-user"></em>
                        <span>Profile</span>
                    </a>
                </li>

                {{--Logout--}}
                <li class="{{ (\Request::route()->getName() == 'logout') ? 'active' : '' }}">
                    <a href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <em class="icon-logout"></em>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

            </ul>
            <!-- END sidebar nav-->

        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>
