@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Mano anketos - @stop

@section('sidebar')
@if (count($anketos) > 0)
		@foreach ($anketos as $anketa)
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>
						<a href="{{ route('campaigns.answer', $anketa->id) }}">
							<img src="{{ $anketa->user->photo ? $anketa->user->photo : 'holder.js/32x32/text:&nbsp;' }}" alt="{{ $anketa->user->username }}" style="height: 32px" class="img-circle">

							{{ $anketa->title }}
						</a>

						<div>
							<small title="{{ $anketa->description }}">{{ $anketa->description }}</small>
						</div>
					</h4>

					<a href="{{ route('campaigns.answers', $anketa->id) }}" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-tasks"></span>
						{{ count($anketa->results) }}
					</a>

					<a href="#" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-user"></span>
						{{ $anketa->user->username }}
					</a>

					<a href="#" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-calendar"></span>
						{{ $anketa->created_at }}
					</a>
				</div>
			</div>
		@endforeach

		<div class="text-center">
			{{ $anketos->links() }}
		</div>
	@else
		<div class="alert alert-warning">
			Anketų nėra.
		</div>
	@endif
@stop

@section('content')
	<div class="page-header">
		<h1>
			Mano anketos

			<div class="pull-right hidden-xs hidden-sm">
				<a href="{{ route('campaigns.create') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Sukurti naują anketą</a>
			</div>
		</h1>

		<div class="visible-xs visible-sm">
			<a href="{{ route('campaigns.create') }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Sukurti naują anketą</a>
		</div>
	</div>

	@if (session('created'))
		<div class="alert alert-success">
			Anketa sėkmingai pridėta.
		</div>
	@endif

	@if (session('copied'))
		<div class="alert alert-success">
			Anketos kopija sėkmingai sukurta.
		</div>
	@endif

	@if (session('deleted'))
		<div class="alert alert-success">
			Anketa sėkmingai ištrinta.
		</div>
	@endif

	@if (count($entries) > 0)
		<table class="table hidden-sm hidden-xs">
			<thead>
				<th></th>
				<th>Pavadinimas</th>
				<th>Data</th>
				<th>Veiksmai</th>
			</thead>

			@foreach ($entries as $entry)
				<tr {{ (session('created') == $entry->id || session('updated') == $entry->id || session('copied') == $entry->id ? 'class="success"' : NULL) }}>
					<td style="vertical-align: middle; text-align: center; white-space: nowrap">
						@if ($entry->active)
							<a href="{{ route('campaigns.edit', $entry->id) }}#activator" class="btn btn-sm btn-success" title="Anketa aktyvi">
								<span class="glyphicon glyphicon-play"></span>
							</a>
						@else
							<a href="{{ route('campaigns.edit', $entry->id) }}#activator" class="btn btn-sm btn-default" title="Anketa neaktyvi">
								<span class="glyphicon glyphicon-pause"></span>
							</a>
						@endif
					</td>

					<td style="vertical-align: middle;" title="{{ $entry->title }}">
						<div style="overflow: hidden; width: 220px; white-space: nowrap;">
							{{ $entry->title }}
						</div>
					</td>

					<td style="vertical-align:middle;">
						{{ $entry->created_at }}
					</td>

					<td style="vertical-align: middle;">
						<div class="btn-group">
							<a href="{{ route('campaigns.edit', $entry->id) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-cog"></span> Anketos valdymas</a>
							<a href="{{ route('campaigns.results', $entry->id) }}" class="btn btn-sm btn-default">
								<span class="glyphicon glyphicon-tasks"></span>
								Rezultatai ({{ count($entry->results) }})
							</a>
							<a href="{{ route('campaigns.destroy', $entry->id) }}" class="btn btn-sm btn-default remove"><span class="glyphicon glyphicon-trash"></span> Ištrinti</a>
						</div>
					</td>
				</tr>
			@endforeach
		</table>

		<div class="visible-sm visible-xs">
			@foreach ($entries as $entry)
				<div class="panel panel-default">
					<div class="panel-body">
						<h4>
							<a href="{{ route('campaigns.edit', $entry->id) }}">{{ $entry->title }}</a>

							<div style="width: 100%; overflow: hidden; white-space: nowrap;">
								<small title="{{ $entry->description }}">{{ $entry->description }}</small>
							</div>
						</h4>

						@if ($entry->active)
							<a href="{{ route('campaigns.edit', $entry->id) }}" class="btn btn-sm btn-success" title="Anketa aktyvi">
								<span class="glyphicon glyphicon-play"></span>
							</a>
						@else
							<a href="{{ route('campaigns.edit', $entry->id) }}" class="btn btn-sm btn-default" title="Anketa neaktyvi">
								<span class="glyphicon glyphicon-pause"></span>
							</a>
						@endif

						<a href="{{ route('campaigns.results', $entry->id) }}" href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-tasks"></span>
							{{ count($entry->results) }}
						</a>

						<a href="{{ route('campaigns.edit', $entry->id) }}" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-calendar"></span>
							{{ $entry->created_at }}
						</a>

						<p></p>

						<div class="btn-group">
							<a href="{{ route('campaigns.edit', $entry->id) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-cog"></span> Anketos valdymas</a>
							<a href="{{ route('campaigns.destroy', $entry->id) }}" class="btn btn-sm btn-default remove"><span class="glyphicon glyphicon-trash"></span> Ištrinti</a>
						</div>
					</div>
				</div>
			@endforeach
		</div>

		<script>
			$(".remove").click(function ()
			{
				var c = confirm('Ar tikrai norite ištrinti?');

				return c;
			});
		</script>

		<div class="text-center">
			{{ $entries->links() }}
		</div>
	@else
		<div class="alert alert-warning">
			<h4>Tuščia!</h4>

			<a href="{{ route('campaigns.create') }}">Pridėkite</a> dabar.
		</div>
	@endif
@stop