<div class="col-md-2">
	<div class="sidebar content-box">
        <ul class="nav">
            <!-- Main menu -->
            <li><a href="#"><i class="glyphicon glyphicon-stats"></i> Dashboard</a></li>
            <li class="{{ request()->is('admin/pages*') ? 'current' : '' }}"><a href="{{ route('pages.index') }}"><i class="glyphicon glyphicon-list"></i> Pages</a></li>
            <li class="{{ request()->is('admin/users*') ? 'current' : '' }}"><a href="{{ route('users.index') }}"><i class="glyphicon glyphicon-user"></i> Users</a></li>
            <li><a href="{{ route('banners.index') }}"><i class="glyphicon glyphicon-tasks"></i> Baners</a></li>
        </ul>
    </div>
</div>