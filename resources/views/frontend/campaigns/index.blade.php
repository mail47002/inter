@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketų sąrašas - @stop

@section('content')
	<div class="page-header">
		<h1>
			Anketų sąrašas
		</h1>
	</div>

	@if (count($entries) > 0)
		@foreach ($entries as $entry)
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>
						<a href="{{ route('campaigns.answer', $entry->id) }}">
							<img src="{{ $entry->user->photo ? $entry->user->photo : 'holder.js/32x32/text:&nbsp;' }}" alt="{{ $entry->user->username }}" style="height: 32px" class="img-circle">

							{{ $entry->title }}
						</a>

						<div>
							<small title="{{ $entry->description }}">{{ $entry->description }}</small>
						</div>
					</h4>

					@if (auth()->check() && $entry->advertise_results)
						<span class="btn btn-sm btn-success" title="Uždarbis">
							<span class="glyphicon glyphicon-usd"></span>
							{{ $entry->questions()->count() * 2 }}
						</span>
					@endif

					<a href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-tasks"></span>
						{{ count($entry->results) }}
					</a>

					<a href="#" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-user"></span>
						{{ $entry->user->username }}
					</a>

					<a href="#" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-calendar"></span>
						{{ $entry->created_at }}
					</a>
				</div>
			</div>
		@endforeach

		<div class="text-center">
			{{ $entries->links() }}
		</div>
	@else
		<div class="alert alert-warning">
			Anketų nėra.
		</div>
	@endif
@stop