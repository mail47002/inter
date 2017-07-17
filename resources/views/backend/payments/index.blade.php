@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Mokėjimų istorija - @stop

@section('content')
	<div class="page-header">
		<h1>
			Mokėjimų istorija
			
			<!-- <div class="pull-right">
				<a href="{{ route('users.create') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Sukurti naują vartotoją</a>
			</div> -->
		</h1>
	</div>

	@if (session('created'))
		<div class="alert alert-success">
			Mokėjimas sėkmingai pridėtas.
		</div>
	@endif
	
	@if (count($entries) > 0)
		<table class="table">
			<thead>
				<th>Data</th>
				<th>Suma</th>
				<th>Būsena</th>
				<th>Vartotojas</th>
			</thead>

			@foreach ($entries as $entry)
				<tr {{ (session('created') == $entry->id ? 'class="success"' : NULL) }}>
					<td style="vertical-align: middle;">
						{{ $entry->updated_at }}
					</td>

					<td style="vertical-align: middle;">
						{{ $entry->ammount }} Lt
					</td>

					<td style="vertical-align:middle;">
						@if ($entry->paid)
							<span class="label label-success">Apmokėta</span>
						@else
							<span class="label label-default">Neapmokėta</span>
						@endif
					</td>

					<td style="vertical-align: middle;">
						<strong>{{ $entry->user->email }}</strong><br>
						{{ $entry->user->username }}
					</td>
				</tr>
			@endforeach
		</table>

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