<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
				<span class="sr-only">Navigacija</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="{{ route('home') }}">Apklausos internetu</a>
		</div>

		<div class="collapse navbar-collapse" id="navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['home']) ? "active" : NULL }}">
					<a href="{{ route('home') }}">Apie</a>
				</li>

				<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['campaigns']) ? "active" : NULL }}">
					<a href="{{ route('campaigns') }}">Apklausos</a>
				</li>

				<li class="{{ request()->path() == 'duk' ? "active" : NULL }}">
					<a href="{{ url('duk') }}">DUK</a>
				</li>

				<li class="{{ request()->path() == 'kontaktai' ? "active" : NULL }}">
					<a href="{{ url('kontaktai') }}">Kontaktai</a>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				@if (auth()->check())
					<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['campaigns.my']) ? "active" : NULL }}">
						<a href="{{ route('campaigns.my') }}">Mano anketos</a>
					</li>

					<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['credits']) ? "active" : NULL }}">
						<a href="{{ route('credits') }}"><span class="glyphicon glyphicon-credit-card"></span> Mano kreditai</a>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							{{ auth()->user()->username }}
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ route('account.index') }}">Paskyros nustatymai</a></li>
							<li><a href="{{ route('login.logout') }}">Atsijungti</a></li>
						</ul>
					</li>
				@else
					<li><a href="{{ route('login') }}">Prisijungti</a></li>
					<li><a href="{{ route('login.registration') }}">Registruotis</a></li>
				@endif
			</ul>

			{{ Form::open( ['route' => ['campaigns.search'], 'method' => 'get', 'class' => 'navbar-form navbar-right'] ) }}
				<div class="form-group">
					<input type="text" name="search" class="form-control input-sm" style="margin-top: 2px;" placeholder="Anketos paieška">
				</div>
			{{ Form::close() }}
		</div>
	</div>
	</nav>