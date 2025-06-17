<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu"></span></h3>
        </div>
        <div class="user-profile">

        </div>
        <ul class="nav" id="side-menu">
            <li> <a href="{{ route('dashboard') }}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a></li>

            <li> <a href="{{route('setting.index')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Setting </span></a></li>

            <li> <a href="{{route('ads.index')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Ads </span></a></li>

            <li> <a href="{{route('news.index')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> News </span></a></li>

            <li> <a href="{{route('users.index')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Manage Users </span></a></li>

            <li> <a href="{{route('memberSupportTickets')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Support Tickets </span></a></li>

            <li> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Reports <span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level collapse">
                    <li> <a href="{{route('investment_report')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Investment Report </span></a></li>
                    <li> <a href="{{route('withdraw_report')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Withdraw Report </span></a></li>
                    <li> <a href="{{route('transferBalanceReport')}}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Transfer Balance Report </span></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Left Sidebar -->
<!-- ============================================================== -->