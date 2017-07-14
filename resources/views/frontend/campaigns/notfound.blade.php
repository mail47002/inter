@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketa nerasta - @stop

@section('content')
	<div class="alert alert-warning">
		<h4>Anketa nerasta!</h4>

		Atsiprašome, tačiau anketa, kurią norite užpildyti, neegzistuoja.
	</div>
@stop