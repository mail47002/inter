@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Paskyros nustatymai - @stop

@section('content')
	{{ Form::open( ['route' => ['account.update'], 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				Paskyros nustatymai
				
				<div class="pull-right hidden-sm hidden-xs">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
				</div>
			</h1>

			<div class="visible-xs visible-sm">
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
			</div>
		</div>

		@if (session('updated'))
			<div class="alert alert-success">
				Paskyra sėkmingai atnaujinta.
			</div>
		@endif

		<div class="row">
			<div class="col-sm-7">
				<p class="lead">Bendrieji duomenys</p>

				<div class="form-group {{ $errors->first('email', 'has-error') }}">
					{{ Form::label('email', 'El. paštas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::email('email', auth()->user()->email, [ 'class' => 'form-control', 'disabled', 'placeholder' => 'El. pašto adresas']) }}

						{!! $errors->first('email', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('username', 'has-error') }}">
					{{ Form::label('username', 'Vartotojo vardas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('username', auth()->user()->username, [ 'class' => 'form-control', 'placeholder' => 'Unikalus vartotojo vardas']) }}

						{!! $errors->first('username', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<hr>

				<p class="lead">Naujas slaptažodis</p>

				<div class="form-group {{ $errors->first('password', 'has-error') }}">
					{{ Form::label('password', 'Slaptažodis', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::password('password', [ 'class' => 'form-control', 'placeholder' => 'Naujas slaptažodis']) }}

						{!! $errors->first('password', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('password_confirmation', 'has-error') }}">
					{{ Form::label('password_confirmation', 'Pakartokite', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::password('password_confirmation', [ 'class' => 'form-control', 'placeholder' => 'Pakartokite naują slaptažodį']) }}

						{!! $errors->first('password_confirmation', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="alert alert-info">Jei nenorite keisti slaptažodžio - laukelių nepildykite!</div>
			</div>

			<div class="col-sm-5">
				<p class="lead">Įkelkite profilio nuotrauką</p>

				<div class="form-group {{ $errors->first('photo', 'has-error') }}">
					@if (auth()->user()->photo)
						<div class="col-sm-3">
							<p>
								<img src="{{ asset(auth()->user()->photo) }}" alt="Anketos paveikslėlis" class="img-thumbnail" style="width: 124px;">
							</p>
						</div>
					@endif

					<div class="col-sm-9">
						<p>
							<strong>Pasirinkite naują</strong>
						</p>

						<p>
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Anketos paveikslėlis']) }}
							
							{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
						</p>
					</div>
				</div>
			</div>
		</div>

		<p></p>
	{{ Form::close() }}
@stop