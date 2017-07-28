<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Navigacija</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('admin') }}">Admin panel</a>
        </div>

        @if (auth()->check())
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}"><a href="#">Dashboard</a></li>
                    <li class="{{ request()->is('admin/pages*') ? 'active' : '' }}"><a href="{{ route('pages.index') }}">Pages</a></li>
                    <li class="{{ request()->is('admin/users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="{{ request()->is('admin/banners*') ? 'active' : '' }}"><a href="{{ route('banners.index') }}">Banners</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><a href="{{ route('backend.logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>

                {{ Form::open( ['route' => 'backend.search', 'method' => 'post', 'class' => 'navbar-form navbar-right'] ) }}
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">Search</button>
                    </span>
                </div>
                {{ Form::close() }}
            </div>
        @endif
    </div>
</nav>