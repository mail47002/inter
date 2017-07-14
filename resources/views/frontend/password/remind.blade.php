@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Priminti slaptažodį - @stop

@section('content')
	<div class="page-header">
		<h1>
			Priminti slaptažodį
		</h1>
	</div>

	@if (Session::has('error'))
		<div class="alert alert-danger">
			Klaida, vartotojas tokiu el. pašto adresu neegzistuoja.
		</div>
	@elseif (Session::has('status'))
		<div class="alert alert-success">
			Instrukcija sėkmingai išsiųsta nurodytu el. paštu.
		</div>
	@endif

	{{ Form::open( ['route' => 'password.remind.post', 'method' => 'post', 'class' => 'form-horizontal'] ) }}
		<div class="form-group {{ ( $errors->first('email') ? 'has-error' : NULL) }}">
			{{ Form::label('email', 'El. paštas', [ 'class' => 'col-sm-3 control-label']) }}

			<div class="col-sm-9">
				{{ Form::email('email', null, [ 'class' => 'form-control', 'placeholder' => 'El. pašto adresas']) }}

				{!! $errors->first('email', '<label class="control-label">:message</label>') !!}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-3">
				<button class="btn btn-primary">Patvirtinti</button>
			</div>
		</div>
	{{ Form::close() }}
@stop