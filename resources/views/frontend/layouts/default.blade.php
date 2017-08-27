<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="verify-paysera" content="97bd17a9030d033b9ecae8c2853994b4">

		<title>@yield('title')Apklausos internetu</title>

        <link href="{{ asset('css/frontend/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/frontend/style.css') }}" rel="stylesheet">

        @yield('styles')

        <script type="text/javascript" src="{{ asset('js/jquery-2.2.4.min.js') }}"}></script>
		<script type="text/javascript" src="{{ asset('js/holder.js') }}"></script>

        @yield('scripts')
    </head>

    <body>
        @include('frontend.layouts.navigation')

        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    @if (session('registered'))
                        <div class="alert alert-success">
                            Jūs sėkmingai užsiregistravote. Dabar galite prisijungti.
                        </div>
                    @endif

                    @if (session('logned'))
                        <div class="alert alert-success">
                            Sėkmingai prisijungėte.
                        </div>
                    @endif

                    @if (session('logned_social'))
                        <div class="alert alert-success">
                            Sėkmingai prisijungėte naudojantis {{ session('logned_social') == 1 ? 'Facebook' : 'Google' }}
                        </div>
                    @endif

                    @if (session('logout'))
                        <div class="alert alert-success">
                            Sėkmingai atsijungėte nuo sistemos.
                        </div>
                    @endif

                    @yield('content')

                    <p style="margin-top: 100px;"></p>

                    <div class="well text-center">
                        <h1>Reklama</h1>
                    </div>
                </div>
                <div class="col-sm-3">
                    @section('sidebar')
                        <div class="well col-sm-12" style="height: 660px; min-width: 120px!important;">
                            @yield('sidebar')
                        </div>
                    @show
                </div>
            </div>
        </div>

        <div class="well" style="margin-top: 100px; margin-bottom: 0; border-bottom: 0; border-left: 0; border-right: 0;">
            <div class="container text-center">
                <p>
                    UAB „ACVK“
                </p>

                <p>
                    <a href="{{ url('naudojimosi-taisykles') }}">Naudojimosi taisyklės</a>
                </p>
            </div>
        </div>

		<script type="text/javascript" src="{{ asset('js/frontend/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
            $('[data-toggle="popover"]').popover();
        </script>
    </body>
</html>