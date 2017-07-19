<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
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
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.html">Admin Panel</a></h1>
	              </div>
	           </div>
	           	@include('backend.layouts.search')
	           	@include('backend.layouts.account')
	        </div>
	     </div>
		</div>

	  <div class="page-content">
	  	<div class="row">
			  @include('backend.layouts.menu')
			  @yield('content')
	  	</div>
		</div>

	  <footer>
	  	<div class="container">
	      <div class="copy text-center">
	         Copyright 2017 <a href='#'>Question</a>
	      </div>
	     </div>
	  </footer>

    <link href="{{ asset('packages/vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" media="screen">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{{ asset('packages/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('packages/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('packages/vendors/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('js/backend/custom.js') }}"></script>
    <script src="{{ asset('js/backend/tables.js') }}"></script>
    <script type="text/javascript">
    	$(function () {

			  $.ajaxSetup({
			    headers: {
			      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			  });
			  //Delete record
			  $('.btn-danger').on('click', function (e) {
			  	var url = $(this).attr('href');
			  	console.log(url);
			    if (!confirm('Are you sure you want to delete?')) return false;
			  e.preventDefault();
			    $.post({
			        type: 'DELETE',  // destroy Method
			        url: url
			    }).done(function (data) {
			        console.log(data);
			        location.reload(true);
			    });
			  });

			});

	</script>
  </body>
</html>