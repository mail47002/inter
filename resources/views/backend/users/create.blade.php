@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Vartotojo sukūrimas - @stop

@section('content')
	{{ Form::open( ['route' => ['users.store'], 'method' => 'post', 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				Vartotojo sukūrimas
				
				<div class="pull-right">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
				</div>
			</h1>
		</div>

		<div class="row">
			<div class="col-sm-7">
				<p class="lead">Bendrieji duomenys</p>

				<div class="form-group {{ ( $errors->first('email') ? 'has-error' : NULL) }}">
					{{ Form::label('email', 'El. paštas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::email('email', null, [ 'class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}

						{{ $errors->first('email', '<label class="control-label">:message</label>') }}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('username') ? 'has-error' : NULL) }}">
					{{ Form::label('username', 'Vartotojo vardas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('username', null, [ 'class' => 'form-control', 'placeholder' => 'Unikalus vartotojo vardas']) }}

						{{ $errors->first('username', '<label class="control-label">:message</label>') }}
					</div>
				</div>

				<hr>

				<p class="lead">Naujas slaptažodis</p>

				<div class="form-group {{ ( $errors->first('password') ? 'has-error' : NULL) }}">
					{{ Form::label('password', 'Slaptažodis', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::password('password', [ 'class' => 'form-control', 'placeholder' => 'Naujas slaptažodis']) }}

						{{ $errors->first('password', '<label class="control-label">:message</label>') }}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('password_confirmation') ? 'has-error' : NULL) }}">
					{{ Form::label('password_confirmation', 'Pakartokite', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::password('password_confirmation', [ 'class' => 'form-control', 'placeholder' => 'Pakartokite naują slaptažodį']) }}

						{{ $errors->first('password_confirmation', '<label class="control-label">:message</label>') }}
					</div>
				</div>

				<div class="alert alert-info">Jei nenorite keisti slaptažodžio - laukelių nepildykite!</div>
			</div>

			<div class="col-sm-5">
				<p class="lead">Įkelkite profilio nuotrauką</p>

				<div class="form-group {{ ( $errors->first('photo') ? 'has-error' : NULL) }}">
					<div class="col-sm-12">
						<p>
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Anketos paveikslėlis']) }}
							
							{{ $errors->first('photo', '<br><label class="control-label">:message</label>') }}
						</p>
					</div>
				</div>
			</div>
		</div>

		<p></p>
	{{ Form::close() }}
@stop