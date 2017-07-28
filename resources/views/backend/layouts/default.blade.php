<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset('css/backend/bootstrap.min.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('css/backend/styles.css') }}" rel="stylesheet">
</head>
<body>
    @include('backend.layouts.navigation')

    <div class="container">
        @yield('content')
    </div>

    <footer>
        <div class="container">
            <small>{{ date('Y') }} &copy; All right reserved</small>
        </div>
    </footer>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="{{ asset('js/backend/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('js/backend/bootstrap.min.js') }}"></script>
    @stack('scripts')
    <script src="{{ asset('js/backend/custom.js') }}"></script>
</body>
</html>