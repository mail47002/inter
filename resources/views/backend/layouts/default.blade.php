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
            @include('backend.layouts.search')
            @include('backend.layouts.account')
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            @include('backend.layouts.menu')
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