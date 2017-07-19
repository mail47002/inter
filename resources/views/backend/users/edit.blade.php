@extends('backend.layouts.default')

@section('title')
	Admin Panel - {{ $user->username }} - Edit
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="panel-title">{{ $user->username }} - Edit</div>
			</div>
			<div class="panel-body">
				{{ Form::open(['route' => ['users.update', $user->id], 'method' => 'put', 'files' => true]) }}
				<div class="row">
					<div class="col-sm-8">
						<h4>General</h4>
						<div class="form-group {{ $errors->first('email', 'has-error') }}">
							{{ Form::label('email', 'E-mail:') }}
							{{ Form::email('email', $user->email, [ 'class' => 'form-control', 'placeholder' => 'Email adress']) }}
							{!! $errors->first('email', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group {{ $errors->first('username', 'has-error') }}">
							{{ Form::label('username', 'Username:') }}
							{{ Form::text('username', $user->username, [ 'class' => 'form-control', 'placeholder' => 'Enter username']) }}
							{!! $errors->first('username', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<br>
						<h4>Password</h4>
						<div class="alert alert-info">
							If you do not want to change your password - do not fill in the fields!
						</div>
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
							<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Update</button>
							<a class="btn btn-danger" href="{{ route('users.index') }}"><i class="glyphicon glyphicon-remove"></i> Cancel</a>
						</div>
					</div>
					<div class="col-sm-4">
						<h4>Select a new one</h4>
						@if (!empty($user->photo))
							<div class="form-group">
								<img class="img-responsive" src="{{ asset($user->photo) }}">
							</div>
						@endif
						<div class="form-group {{ $errors->first('photo', 'has-error') }}">
							{{ Form::label('photo', 'Photo:') }}
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Picture of the profile']) }}
							{!! $errors->first('photo', '<label class="control-label text-danger">:message</label>') !!}
						</div>
						<div class="form-group">
							{{ Form::label('status', 'Status:') }}
							{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], $user->status, ['class' => 'form-control']) !!}
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@stop