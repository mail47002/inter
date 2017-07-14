@extends('frontend.layouts.default')
@include('frontend.layouts.navigation')

@section('title')Prisijungimas - @stop

@section('content')
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="page-header">
				<h1>Prisijungimas</h1>
			</div>

			@if (session('login_error'))
				<div class="alert alert-danger">
					<h4>Klaida!</h4>

					@if ((int)session('login_error') == 1)
						Prisijungimo duomenys neteisingi.
					@endif
				</div>
			@endif

			{{ Form::open([ 'route' => 'login.session', 'class' => 'form-horizontal' ]) }}
				<div class="form-group">
					{{ Form::label('email', 'El. paštas', ['class' => 'control-label col-sm-4']) }}

					<div class="col-sm-8">
						{{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('password', 'Slaptažodis', ['class' => 'control-label col-sm-4']) }}

					<div class="col-sm-8">
						{{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Slaptažodis prisijungimui']) }}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-8 col-sm-offset-4">
						<button class="btn btn-success">Jungtis</button>
						<a href="{{ route('login.registration') }}" class="btn btn-default">Registruotis</a>
						<a href="{{ route('password.remind') }}" class="btn btn-default">Priminti slaptažodį</a>
					</div>
				</div>

				<hr>

				<div class="form-group text-center">
					<div class="col-sm-12">
						<a href="{{ route('login.register_facebook') }}" class="btn btn-link"><img src="{{ asset('images/facebook.png') }}" alt=""></a>
						<a href="{{ route('login.register_google') }}" class="btn btn-link"><img src="{{ asset('images/googlep.png') }}" alt=""></a>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop