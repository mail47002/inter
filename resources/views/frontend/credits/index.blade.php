@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Mano kreditai - @stop

@section('content')
	<div class="page-header">
		<h1>Mano kreditai</h1>
	</div>

	<div class="row">
		<div class="col-sm-5">
			<h3>Apie kreditus</h3>

			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio sit, esse perspiciatis ullam aut eveniet rerum modi blanditiis inventore vero ad sed incidunt tempora quis fugiat, ab cum voluptatibus facilis. Voluptatem officiis molestias odio, debitis dolore itaque rerum, odit! Quas, in! Harum sapiente expedita fugit quaerat ipsa laborum quas minima dolorum repellat ad, in rerum, soluta. Ducimus et neque adipisci, perferendis, distinctio provident vero veritatis numquam esse commodi quasi facere quos maxime fugiat quo accusamus voluptatum a qui? Veritatis magnam itaque nisi atque facilis officia laudantium, consequuntur nostrum esse. Repellendus deserunt a asperiores ipsum! Accusantium fugiat fuga dolore incidunt neque.
			</p>

			<h3>Pirkti internetu</h3>

			{{-- <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="business" value="winartas@yahoo.com">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="item_name" value="Hot Sauce-12oz. Bottle">
				<input type="hidden" name="amount" value="">
				<input type="hidden" name="currency_code" value="EUR">
				<input type="hidden" name="email" value="{{ auth()->user()->email }}">
				<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png" alt="Buy Now">
				<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
			</form> --}}

			<table class="table table-condensed table-striped">
				<thead>
					<th>Kaina</th>
					<th>Kreditų kiekis</th>
					<th></th>
				</thead>
{{$one_credits}}
				{{-- @foreach ($prices as $price)
					<tr>
						<td style="vertical-align: middle;">{{ $price['ammount'] }},00 EUR</td>
						<td style="vertical-align: middle;">{{ number_format($price['credits'], 0, ',', ' ') }}</td>
						<td>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="business" value="winartas@yahoo.com">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="item_name" value="Hot Sauce-12oz. Bottle">
							<input type="hidden" name="amount" value="{{$price['ammount']}}">
							<input type="hidden" name="currency_code" value="EUR">
							<input type="hidden" name="email" value="{{ auth()->user()->email }}">
							<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png" alt="Buy Now">
							<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
						</form>
						</td>
					</tr>
				@endforeach --}}
			</table>
		</div>

		<div class="col-sm-7">
			<h3>Kreditų likutis</h3>

			<button class="btn btn-default">
				{{ auth()->user()->credits()->sum('credits') }}
				<br>
				<small>Viso kreditų</small>
			</button>

			<button class="btn {{ auth()->user()->credits()->sum('credits') - auth()->user()->campaigns()->sum('advertise_credits') > 0 ? 'btn-success' : 'btn-default' }}">
				{{ auth()->user()->credits()->sum('credits') - auth()->user()->campaigns()->sum('advertise_credits') }}
				<br>
				<small>Laisvų kreditų</small>
			</button>

			<a href="{{ route('campaigns.my') }}" class="btn {{ auth()->user()->campaigns()->sum('advertise_credits') > 0 ? 'btn-warning' : 'btn-default' }}">
				{{ auth()->user()->campaigns()->sum('advertise_credits') }}
				<br>
				<small>Anketoms priskirtų kreditų</small>
			</a>

			<h3>Kreditų istorija</h3>

			<table class="table table-condensed">
				<thead>
					<th>Data</th>
					<th>Šaltinis</th>
					<th>Kreditai</th>
				</thead>

				@foreach (auth()->user()->credits()->orderBy('id', 'desc')->take(10)->get() as $entry)
					<tr class="{{ $entry->credits >= 0 ? 'success' : 'danger' }}">
						<td>{{ $entry->created_at }}</td>
						<td>{{ $entry->description }}</td>
						<td>{{ $entry->credits }}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
@stop