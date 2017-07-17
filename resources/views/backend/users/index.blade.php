@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Vartotojų administravimas - @stop

@section('content')
	<div class="page-header">
		<h1>
			Vartotojų administravimas
			
			<div class="pull-right">
				<a href="{{ route('users.create') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Sukurti naują vartotoją</a>
			</div>
		</h1>
	</div>

	@if (session('created'))
		<div class="alert alert-success">
			Vartotojas sėkmingai pridėtas.
		</div>
	@endif

	@if (session('updated'))
		<div class="alert alert-success">
			Vartotojas sėkmingai atnaujintas.
		</div>
	@endif

	@if (session('deleted'))
		<div class="alert alert-success">
			Vartotojas sėkmingai pašalintas.
		</div>
	@endif
	
	@if (count($entries) > 0)
		<table class="table">
			<thead>
				<th>El. paštas</th>
				<th>Vartotojo vardas</th>
				<th>Registruotas</th>
				<th>Veiksmai</th>
			</thead>

			@foreach ($entries as $entry)
				<tr {{ (session('created') == $entry->id || session('updated') == $entry->id ? 'class="success"' : NULL) }}>
					<td style="vertical-align: middle;">
						{{ $entry->email }}<br>
						<span class="label label-default">{{ $entry->isAdmin() ? 'Administratorius' : 'Vartotojas' }}</span>
					</td>

					<td style="vertical-align: middle;">
						{{ $entry->username }}
					</td>

					<td style="vertical-align:middle;">
						{{ $entry->created_at }}
					</td>

					<td style="vertical-align: middle;">
						<div class="btn-group">
							<a href="{{ route('users.edit', $entry->id) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-edit"></span> Redaguoti</a>
							<a href="{{ route('users.destroy', $entry->id) }}" class="btn btn-sm btn-default remove"><span class="glyphicon glyphicon-trash"></span> Ištrinti</a>
						</div>
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