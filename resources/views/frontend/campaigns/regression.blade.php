@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ rezultatų regresinė analizė - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('Frontend') }}"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<small>Rezultatų regresinė analizė</small>
		</h1>
	</div>

	@include('frontend.campaigns.tabs')

	<div class="row">
		<div class="col-sm-9">
			<div class="alert alert-info">
				<h4>Klausimai</h4>

				<p>Regresinė analizė yra vykdoma pagal pasirinktų klausimų rezultatus.</p>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="well">
				<p class="lead">Anketos meniu</p>

				<div class="list-group">
					<a href="{{ route('campaigns.results', $entry->id) }}" class="list-group-item">Anketos rezultatai</a>
				</div>
			</div>
		</div>

		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th rowspan="2">Model</th>
					<th colspan="2">Unstandardized Coefficients</th>
					<th>Standardized Coefficients</th>
					<th rowspan="2">t</th>
					<th rowspan="2">Sig.</th>
					<th colspan="2">Collinearity Statistics</th>
				</tr>

				<tr>
					<th>B</th>
					<th>Std. Error</th>
					<th>Beta</th>
					<th>Tolerance</th>
					<th>VIF</th>
				</tr>
			</thead>

			<tr>
				<td>(Constant)<br><small>{{ $main_question->title }}</small></td>
				<td>
					{{ round($main_question->b, 3) }}
					<br><br>

					<code>
						{{ $main_question->standart_deviation(true) }}
					</code>

					<br><br>


				</td>
				<td>{{ round($main_question->standart_deviation(), 3) }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			@foreach ($questions as $question)
				<tr>
					<td>{{ $question->title }}</td>
					<td>
						{{ round($question->b, 3) }}
						<br><br>

						<code>
							{{ $question->standart_deviation(true) }}
						</code>

						<br>

						<code>
							<h4>Correlation Coefficient</h4>

							{{ $question->correlation_coefficient }}
						</code>

						<br>

						<code>
							<h4>Calculating B</h4>

							<strong>Formula: </strong> b = r * (Sy / Sx)<br>
							<strong>Formula: </strong> b = {{ $question->correlation_coefficient }} * ({{ round($question->standart_deviation(), 3) }} / {{ round($main_question->standart_deviation(), 3) }})<br>

							<strong>Answer: </strong> b = {{ $question->b }}
						</code>
					</td>
					<td>
						{{ round($question->standart_deviation(), 3) }}
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		</table>

		<hr>

		<p class="lead text-center">
			{{ $main_question->title }} = {{ $main_question->b }} + 
			
			@foreach ($questions as $key => $question)
				@if ($key > 0)
					+
				@endif

				{!! '<strong>' . $question->b . '</strong> <small>' . $question->title . '</small>' !!}
			@endforeach
		</p>
	</div>
@stop