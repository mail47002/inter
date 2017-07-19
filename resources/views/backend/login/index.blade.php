<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/backend/styles.css') }}" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="container-fluid">
            <div class="col-md-5">
                <div class="logo">
                    <h1><a href="#">Admin Panel</a></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
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
        </div>
    </div>
</body>
</html>