@extends('backend.layouts.default')

@section('title')
    Admin Panel - Login
@endsection

@section('content')
<div class="page-content container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-wrapper">
                <div class="box">
                    <div class="content-wrap">
                        <h6>Sign In</h6>
                        {{ Form::open(['route' => 'backend.login', 'method' => 'post']) }}
                            <input class="form-control" type="text" name="email" placeholder="E-mail address">
                            <input class="form-control" type="password" name="password" placeholder="Password">
                            <div class="action">
                                <button class="btn btn-primary signup" type="submit">Login</button>
                            </div>
                        {{ Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
