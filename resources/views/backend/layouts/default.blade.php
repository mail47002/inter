<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('css/backend/styles.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    @yield('styles')
    @yield('scripts')
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="logo">
                        <h1><a href="#">Admin Panel</a></h1>
                    </div>
                </div>
                @if (auth()->check())
                    @include('backend.layouts.search')
                    @include('backend.layouts.account')
                @endif
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            @if (auth()->check())
                @include('backend.layouts.menu')
            @endif

            @yield('content')
        </div>
    </div>

    <link href="{{ asset('packages/vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" media="screen">
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{{ asset('packages/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('packages/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('packages/vendors/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('js/backend/custom.js') }}"></script>
    <script src="{{ asset('js/backend/tables.js') }}"></script>
</body>
</html>