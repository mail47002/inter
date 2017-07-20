@extends('backend.layouts.default')

@section('title', $title)

@section('content')
	<div class="col-md-10">
	<div class="content-box-large">
		<div class="panel-heading">
          <div class="panel-title">Edit - {{$user->username}}</div>

          <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
          </div>
      </div>
		{{ Form::open( ['route' => ['users.update', $user->id], 'method' => 'put', 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="row">
			<div class="col-sm-6">
				<p class="lead">General data</p>
{{-- @php(dd($user->email)) --}}
				<div class="form-group {{ ( $errors->first('email') ? 'has-error' : NULL) }}">
					{{ Form::label('email', 'Email') }}
					{{ Form::email('email', $user->email, [ 'class' => 'form-control', 'placeholder' => 'Email adress']) }}
					{{ $errors->first('email', '<label class="control-label">:message</label>') }}
				</div>
				<div class="form-group {{ ( $errors->first('username') ? 'has-error' : NULL) }}">
					{{ Form::label('username', 'Username') }}

						{{ Form::text('username', $user->username, [ 'class' => 'form-control', 'placeholder' => 'Unique username']) }}
						{{ $errors->first('username', '<label class="control-label">:message</label>') }}
				</div>

				<p class="lead">New password</p>

				<div class="form-group {{ ( $errors->first('password') ? 'has-error' : NULL) }}">
					{{ Form::label('password', 'Password') }}
					{{ Form::password('password', [ 'class' => 'form-control', 'placeholder' => 'Naujas slaptažodis']) }}
					{{ $errors->first('password', '<label class="control-label">:message</label>') }}
				</div>
				<div class="form-group {{ ( $errors->first('password_confirmation') ? 'has-error' : NULL) }}">
					{{ Form::label('password_confirmation', 'Repeat') }}
					{{ Form::password('password_confirmation', [ 'class' => 'form-control', 'placeholder' => 'Repeat the new password']) }}
					{{ $errors->first('password_confirmation', '<label class="control-label">:message</label>') }}
				</div>
				<div class="form-group">
					{!! Form::label('status', 'Status') !!}
					{!! Form::checkbox('status', 1, true); !!}
				</div>
				<div class="alert alert-info">If you do not want to change your password - do not fill in the fields!</div>
				<div class="form-group">
		    	{!!Form::submit('Save', ['class' => 'btn btn-primary'])!!}
		  	</div>
			</div>

			<div class="col-sm-3">
				<p class="lead">Upload a profile photo</p>

				<div class="form-group {{ ( $errors->first('photo') ? 'has-error' : NULL) }}">
					@if ( ! empty($user->photo))
						<div class="col-sm-3">
							<p>
								<img src="{{ asset($user->photo) }}" alt="Picture of the profile" class="img-thumbnail" style="width: 124px;">
							</p>
						</div>
					@endif
					<div class="col-sm-9">
						<p>
							<strong>Select a new one</strong>
						</p>

						<p>
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Picture of the profile']) }}

							{{ $errors->first('photo', '<br><label class="control-label">:message</label>') }}
						</p>
					</div>
				</div>
			</div>
		</div>

		<p></p>
	{{ Form::close() }}
	</div>
	</div>
@stop