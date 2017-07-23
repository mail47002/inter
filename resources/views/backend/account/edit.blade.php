@extends('backend.layouts.default')

@section('title')
    Admin Panel - User - Edit
@endsection

@section('content')
    <div class="col-md-10">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="pull-right">
                    <button class="btn btn-primary" type="submit" form="form-user" data-toggle="tooltip" data-placement="top" title="Save"><i class="glyphicon glyphicon-floppy-disk"></i></button>
                </div>
                <div class="panel-title">My Account</div>
            </div>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('status') }}
                    </div>
                @endif
                {{ Form::open(['route' => ['profile.update', auth()->user()->id], 'method' => 'put', 'files' => true, 'id' => 'form-user']) }}
                <div class="row">
                    <div class="col-sm-8">
                        <h4>General</h4>
                        <div class="form-group {{ $errors->first('email', 'has-error') }}">
                            {{ Form::label('email', 'E-mail:') }}
                            {{ Form::email('email', auth()->user()->email, [ 'class' => 'form-control', 'placeholder' => 'Email adress']) }}
                            {!! $errors->first('email', '<label class="control-label text-danger">:message</label>') !!}
                        </div>
                        <div class="form-group {{ $errors->first('username', 'has-error') }}">
                            {{ Form::label('username', 'Username:') }}
                            {{ Form::text('username', auth()->user()->username, [ 'class' => 'form-control', 'placeholder' => 'Enter username']) }}
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
                    </div>
                    <div class="col-sm-4">
                        <h4>Select a new one</h4>
                        @if (auth()->user()->photo)
                            <div class="form-group">
                                <img class="img-responsive" src="{{ asset(auth()->user()->photo) }}">
                            </div>
                        @endif
                        <div class="form-group {{ $errors->first('photo', 'has-error') }}">
                            {{ Form::label('photo', 'Photo:') }}
                            {{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Picture of the profile']) }}
                            {!! $errors->first('photo', '<label class="control-label text-danger">:message</label>') !!}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop