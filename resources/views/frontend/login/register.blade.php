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
				<div class="form-group {{ ( $errors->first('r_email') ? 'has-error' : NULL) }}">
					{{ Form::label('r_email', 'El. paštas', ['class' => 'control-label col-sm-3']) }}

					<div class="col-sm-9">
						{{ Form::email('r_email', '', ['class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}
						<span class="label label-info">Nebus rodomas. Bus naudojamas prisijungimui.</span>
						
						{{ $errors->first('r_email', '<br><label class="control-label">:message</label>') }}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('r_username') ? 'has-error' : NULL) }}">
					{{ Form::label('r_username', 'Vartotojas', ['class' => 'control-label col-sm-3']) }}

					<div class="col-sm-9">
						{{ Form::text('r_username', '', ['class' => 'form-control', 'placeholder' => 'Vartotojo vardas']) }}
						<span class="label label-info">Bus rodomas kartu su jūsų anketomis</span>

						{{ $errors->first('r_username', '<br><label class="control-label">:message</label>') }}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('r_password') || $errors->first('r_password_confirmation') ? 'has-error' : NULL) }}">
					{{ Form::label('r_password', 'Slaptažodis', ['class' => 'control-label col-sm-3']) }}
	
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-6">
								{{ Form::password('r_password', ['class' => 'form-control', 'placeholder' => 'Sugalvokite slaptažodį']) }}

								{{ $errors->first('r_password', '<label class="control-label">:message</label>') }}
							</div>

							<div class="col-sm-6">
								{{ Form::password('r_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Pakartokite slaptažodį']) }}

								{{ $errors->first('r_password_confirmation', '<label class="control-label">:message</label>') }}
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