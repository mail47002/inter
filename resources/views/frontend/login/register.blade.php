@extends('frontend.layouts.default')
@include('frontend.layouts.navigation')

@section('title')Prisijungimas - @stop

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="page-header">
				<h1>Registracija</h1>
			</div>

			{{ Form::open([ 'route' => 'login.register', 'class' => 'form-horizontal' ]) }}
				<div class="form-group {{ $errors->first('email', 'has-error') }}">
					{{ Form::label('email', 'El. paštas', ['class' => 'control-label col-sm-3']) }}

					<div class="col-sm-9">
						{{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}
						<span class="label label-info">Nebus rodomas. Bus naudojamas prisijungimui.</span>
						
						{!! $errors->first('email', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('username', 'has-error') }}">
					{{ Form::label('username', 'Vartotojas', ['class' => 'control-label col-sm-3']) }}

					<div class="col-sm-9">
						{{ Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Vartotojo vardas']) }}
						<span class="label label-info">Bus rodomas kartu su jūsų anketomis</span>

						{!! $errors->first('username', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('password') || $errors->first('password_confirmation') ? 'has-error' : NULL) }}">
					{{ Form::label('password', 'Slaptažodis', ['class' => 'control-label col-sm-3']) }}
	
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-6">
								{{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Sugalvokite slaptažodį']) }}

								{!! $errors->first('password', '<label class="control-label">:message</label>') !!}
							</div>

							<div class="col-sm-6">
								{{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Pakartokite slaptažodį']) }}

								{!! $errors->first('password_confirmation', '<label class="control-label">:message</label>') !!}
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-9 col-sm-offset-3">
						<button class="btn btn-primary btn-lg">Registruotis</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop