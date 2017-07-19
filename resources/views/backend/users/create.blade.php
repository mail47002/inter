@extends('backend.layouts.default')

@section('title')
	Admin Panel - User - Create
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="panel-title">User - Create</div>
			</div>
			<div class="panel-body">
				{{ Form::open(['route' => 'users.store', 'method' => 'post', 'files' => true]) }}
					<div class="row">
					<div class="col-sm-8">
						<h4>General</h4>
						<div class="form-group {{ $errors->first('email', 'has-error') }}">
							{{ Form::label('email', 'E-mail:') }}
							{{ Form::email('email', null, [ 'class' => 'form-control', 'placeholder' => 'Email adress']) }}
							{!! $errors->first('email', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group {{ $errors->first('username', 'has-error') }}">
							{{ Form::label('username', 'Username:') }}
							{{ Form::text('username', null, [ 'class' => 'form-control', 'placeholder' => 'Enter username']) }}
							{!! $errors->first('username', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<h4>Password</h4>
						<div class="form-group {{ $errors->first('password', 'has-error') }}">
							{{ Form::label('password', 'Password:') }}
							{{ Form::password('password', [ 'class' => 'form-control', 'placeholder' => 'Enter password']) }}
							{!! $errors->first('password', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group {{ $errors->first('password_confirmation','has-error') }}">
							{{ Form::label('password_confirmation', 'Confirm password:') }}
							{{ Form::password('password_confirmation', [ 'class' => 'form-control', 'placeholder' => 'Confirm password']) }}
							{!! $errors->first('password_confirmation', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Create</button>
							<a class="btn btn-danger" href="{{ route('users.index') }}"><i class="glyphicon glyphicon-remove"></i> Cancel</a>
						</div>
					</div>
					<div class="col-sm-4">
						<h4>Select a new one</h4>
						<div class="form-group {{ $errors->first('photo', 'has-error') }}">
							{!! Form::label('photo', 'Photo:') !!}
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Picture of the profile']) }}
							{!! $errors->first('photo', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group">
							{!! Form::label('status', 'Status:') !!}
							{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], 1, ['class' => 'form-control']) !!}
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@stop