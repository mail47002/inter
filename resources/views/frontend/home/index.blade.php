@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Pradinis - @stop

@section('content')
	<div class="jumbotron text-center">
		<h1>
			Apklausos internetu
		</h1>

		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor consequuntur perferendis nisi molestias, dolores 
			provident vel eos tenetur aperiam debitis, cupiditate tempora. Incidunt, cumque reprehenderit nostrum fugit unde, 
			consequuntur rerum aperiam dolor molestias vel nulla. Officiis rerum libero ratione laudantium, voluptate excepturi. 
			Sit, atque commodi sint distinctio ratione rem temporibus.
		</p>

		<br>

		<p>
			<a href="{{ route('campaigns.create') }}" class="btn btn-lg btn-success">Sukurk anketą</a>
		</p>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<p class="lead">Rekomenduojamos anketos</p>

			@if (count($entries) > 0)
				@foreach ($entries as $entry)
					<h4>
						<a href="{{ route('campaigns.answer', $entry->id) }}">{{ $entry->title }}</a>

						<div style="width: 100%; overflow: hidden; white-space: nowrap;">
							<small title="{{ $entry->description }}">{{ $entry->description }}</small>
						</div>
					</h4>

					@if ($auth_check && $entry->advertise_results)
						<div class="btn btn-xs btn-success" title="Uždarbis">
							<span class="glyphicon glyphicon-usd"></span>
							{{ $entry->questions()->count() * 2 }}
						</div>
					@endif
					
					<a href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-tasks"></span>
						{{ count($entry->results) }}
					</a>

					<a href="#" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-user"></span>
						{{ $entry->user->username }}
					</a>

					<a href="#" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-calendar"></span>
						{{ $entry->created_at }}
					</a>
				@endforeach
			@else
				<div class="alert alert-warning">
					Anketų nėra.
				</div>
			@endif

			<hr>

			<p class="lead">Naujausios viešos anketos</p>

			@if (count($public_entries) > 0)
				@foreach ($public_entries as $entry)
					<h4>
						<a href="{{ route('campaigns.answer', $entry->id) }}">{{ $entry->title }}</a>

						<div style="width: 100%; overflow: hidden; white-space: nowrap;">
							<small title="{{ $entry->description }}">{{ $entry->description }}</small>
						</div>
					</h4>

					@if ($auth_check && $entry->advertise_results)
						<div class="btn btn-xs btn-success" title="Uždarbis">
							<span class="glyphicon glyphicon-usd"></span>
							{{ $entry->questions()->count() * 2 }}
						</div>
					@endif
					
					<a href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-tasks"></span>
						{{ count($entry->results) }}
					</a>

					<a href="#" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-user"></span>
						{{ $entry->user->username }}
					</a>

					<a href="#" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-calendar"></span>
						{{ $entry->created_at }}
					</a>
				@endforeach
			@else
				<div class="alert alert-warning">
					Anketų nėra.
				</div>
			@endif
		</div>
	</div>
@stop