<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset('packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @stack('styles')
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
            <div class="col-md-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button">Search</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="navbar navbar-inverse" role="banner">
                    <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
                                <ul class="dropdown-menu animated fadeInUp">
                                    <li><a href="#">Profile</a></li>
                                    <li><a href="{{ route('backend.logout') }}">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            @include('backend.layouts.sidebar')
            @yield('content')
        </div>
    </div>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="{{ asset('js/backend/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('packages/bootstrap/js/bootstrap.min.js') }}"></script>
    @stack('scripts')
    <script src="{{ asset('js/backend/custom.js') }}"></script>
</body>
</html>