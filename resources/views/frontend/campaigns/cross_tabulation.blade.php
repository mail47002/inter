@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ rezultatų poriniai stebėjimai - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('Frontend') }}"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<small>Rezultatų poriniai stebėjimai</small>
		</h1>
	</div>

	@include('frontend.campaigns.tabs')

	<div class="row">
		<div class="col-sm-9">
			<div class="alert alert-info">
				<h4>Klausimai</h4>

				<p>Poriniai stebėjimai yra vykdomi pagal pasirinktų klausimų rezultatus. Pasirinkti klausimai:</p>

				<p>
					<strong>{{ $first->title }}</strong> 
					ir
					<strong>{{ $second->title }}</strong>
				</p>
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

		<table class="table table-condensed">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>

					<th colspan="{{ $second->options()->count() }}">{{ $second->title }}</th>
					
					<th>Viso</th>
				</tr>
			</thead>

			<tr>
				<td></td>
				<td></td>
				<td></td>
				
				@foreach ($second->options()->get() as $option)
					<td>{{ $option->title }}</td>
				@endforeach
	
				<td></td>
			</tr>

			@foreach ($first->options as $key => $foption)
				<tr>
					@if ($key == 0)
						<th rowspan="{{ $first->options()->count() * 2 }}">{{ $first->title }}</th>
					@endif

					<td rowspan="2">
						{{ $foption->title }}
					</td>

					<td>Suma</td>

					@foreach ($second->options as $soption)
						<td>
							{{ isset($counts[$foption->id][$soption->id]) ? $counts[$foption->id][$soption->id] : 0 }}
						</td>
					@endforeach

					<td>{{ isset($totals['x'][$foption->id]) ? $totals['x'][$foption->id] : 0 }}</td>
				</tr>

				<tr>
					<td>
						Dalis, proc.
					</td>

					@foreach ($second->options as $soption)
						<td>
							{{ @round($counts[$foption->id][$soption->id] / $totals['y'][$soption->id] * 100, 2) }}%
						</td>
					@endforeach

					<td>
						{{ @round($totals['x'][$foption->id] / array_sum($totals['y']) * 100, 2) }}%
					</td>
				</tr>
			@endforeach

			<tr>
				<td></td>
				<td>Viso</td>
				<td>Suma</td>
				
				@foreach ($second->options()->get() as $option)
					<td>{{ isset($totals['y'][$option->id]) ? $totals['y'][$option->id] : 0 }}</td>
				@endforeach

				<td>{{ array_sum($totals['y']) }}</td>
			</tr>
		</table>

		<hr>

		<p class="lead text-center">Chi-Square testas</p>

		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th>Value</th>
					<th>df</th>
					<th>Asymp. Sig. (2-sided)</th>
				</tr>
			</thead>

			<tr>
				<td>Pearson Chi-Square</td>
				<td>{{ round($results['chi'], 3) }} (a)</td>
				<td>{{ $results['df'] }}</td>
				<td>{{ round($probability, 3) }}</td>
			</tr>

			<tr>
				<td>
					Likelihood Ratio
				</td>
				<td>
					{{ round($likelihood, 3) }}
				</td>
				<td>
					{{ $results['df'] }}
				</td>
				<td>
					{{ round($lprobability, 3) }}
				</td>
			</tr>

			<tr>
				<td>
					Linear-by-linear Association
				</td>
				<td>
					{{ round($linear, 3) }}
				</td>
				<td>
					1
				</td>
				<td>
					{{ round($linear_probability, 3) }}
				</td>
			</tr>

			<tr>
				<td>N of Valid Cases</td>
				<td colspan="3">{{ array_sum($totals['y']) }}</td>
			</tr>
		</table>

		<p>
			a. {{ $cells_count }} cells ({{ round($cells_count * 100 / $cells, 2) }}%) have expected count less than 5.
			The minimum expected count is {{ round($min_expected, 2) }}.
		</p>

		<div class="alert alert-info">
			<h4>Išvada</h4>

			@if (round($probability, 3) > 0.05)
				Vertinant ar skiriasi atsakymai į klausimą <strong>„{{ $first->title }}“</strong> priklausomai nuo atsakymų į <strong>„{{ $second->title }}“</strong> matyti, 
				jog statistinis rodiklis Asymp. Sig (2-sided) yra didesnis  už 0.05, todėl vertinti statistinio reikšmingumo 
				negalime. Teigtina, jog reikšmingos priklausomybės tarp atsakymų į <strong>„{{ $first->title }}“</strong> ir atsakymų  į <strong>„{{ $second->title }}“</strong> nėra.
			@elseif (round($probability, 3) < 0.05)
				Vertinant ar skiriasi atsakymai į klausimą <strong>„{{ $first->title }}“</strong> priklausomai nuo atsakymų į <strong>„{{ $second->title }}“</strong> matyti, 
				jog statistinis rodiklis Asymp. Sig (2-sided) yra mažesnis uz 0.05, todėl teigtina, kad egzistuoja reikšminga 
				priklausomybė tarp atsakymų į <strong>„{{ $first->title }}“</strong> ir atsakymų į <strong>„{{ $second->title }}“</strong>.
			@endif
		</div>
	</div>
@stop