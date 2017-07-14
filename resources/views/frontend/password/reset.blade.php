@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Atkurti slaptažodį - @stop

@section('content')
	{{ Form::open( ['route' => 'password.reset.post', 'class' => 'form-horizontal'] ) }}
		<div class="page-header">
			<h1>
				Atkurti slaptažodį
			</h1>
		</div>

		@if (Session::has('error'))
			<div class="alert alert-danger">
				Klaida.
			</div>
		@elseif (Session::has('status'))
			<div class="alert alert-success">
				Instrukcija sėkmingai išsiųsta nurodytu el. paštu.
			</div>
		@endif

		{{ Form::text('token', $token, [ 'class' => 'form-control hide', 'placeholder' => 'El. pašto adresas']) }}

		<div class="form-group {{ ( $errors->first('email') ? 'has-error' : NULL) }}">
			{{ Form::label('email', 'El. paštas', [ 'class' => 'col-sm-3 control-label']) }}

			<div class="col-sm-9">
				{{ Form::email('email', '', [ 'class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}

				{{ $errors->first('email', '<label class="control-label">:message</label>') }}
			</div>
		</div>

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

		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-3">
				<button class="btn btn-primary">Patvirtinti</button>
			</div>
		</div>
	{{ Form::close() }}
@stop