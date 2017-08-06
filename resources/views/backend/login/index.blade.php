@extends('backend.layouts.default')

@section('title')
    Admin Panel - Page - Create
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="page-header">
                <h1>Sign In</h1>
            </div>
            {{ Form::open(['route' => 'backend.login', 'method' => 'post', 'class' => 'form-horizontal']) }}
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <label class="control-label col-sm-4">E-mail</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="email">
                    </div>
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <label class="control-label col-sm-4">Password</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="password" name="password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8 col-md-offset-4">
                        <button class="btn btn-primary" type="submit">Sign In</button>
                    </div>
                </div>
                <hr>
            {{ Form::close()}}
        </div>
    </div>
@stop