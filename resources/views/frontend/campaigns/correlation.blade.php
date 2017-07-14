@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ rezultatų koreliacinė analizė - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('Frontend') }}"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<small>Rezultatų koreliacinė analizė</small>
		</h1>
	</div>

	@include('frontend.campaigns.tabs')

	<div class="row">
		<div class="col-sm-9">
			<div class="alert alert-info">
				<h4>Klausimai</h4>

				<p>Koreliacinė analizė yra vykdoma pagal pasirinktų klausimų rezultatus.</p>
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

					@foreach ($questions as $question)
						<th>{{ $question->title }}</th>
					@endforeach
				</tr>
			</thead>
			
			@foreach ($questions as $key => $question)
				<tr>
					<th rowspan="4">{{ $question->title }}</th>

					<td>Spearman Correlation Cofficient</td>

					@foreach ($questions as $q)
						<td>
							{{ $correlation['spearman'][$question->id][$q->id] }}
						</td>
					@endforeach
				</tr>

				<tr>
					<td>Pearson Correlation Cofficient</td>

					@foreach ($questions as $q)
						<td>
							{{ $correlation['pearson'][$question->id][$q->id] }}
						</td>
					@endforeach
				</tr>

				<tr>
					<td>Sig. (2-tailed)</td>

					@foreach ($questions as $q)
						<td>
							- <!-- {{ $sig[$question->id][$q->id] }} -->
						</td>
					@endforeach
				</tr>

				<tr>
					<td>N</td>

					@foreach ($questions as $q)
						<td>{{ $totals[$question->id][$q->id] }}</td>
					@endforeach
				</tr>
			@endforeach
		</table>
	</div>
@stop