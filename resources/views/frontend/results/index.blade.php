@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ rezultatai - @stop

@section('scripts')
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<br class="visible-sm visible-xs">
			<small>Anketos rezultatai</small>
		</h1>
	</div>

	<div class="row">
		@if (count($entry->results) > 0)
			<div class="col-sm-8">
				@if (count($entry->questions) > 0)
					@foreach ($entry->questions as $question)
						<p class="lead">
							{{ $question->title }}
							{!! $errors->first($question->id, '<small><label class="control-label">:message</label></small>') !!}

							@if ($question->photo)
								<small><a class="visible-xs visible-sm" href="{{ asset($question->photo) }}" target="_blank">Paveikslėlis</a></small>
							@endif
						</p>

						@if ($question->photo)
							<p class="hidden-xs hidden-sm">
								<img src="{{ asset($question->photo) }}" alt="{{ $question->title }}" class="img-thumbnail">
							</p>
						@endif

						@if (in_array($question->type, ['radio', 'select', 'check']))
							<div id="chart-{{ $question->id }}" class="col-sm-12" style="height: 250px;"></div>

							<script type="text/javascript">
                                google.load('visualization', '1.0', {'packages': ['corechart']});
								google.setOnLoadCallback(drawChart);

								function drawChart() {
									var data = google.visualization.arrayToDataTable([
										['Option', 'Count'],
										@foreach ($question->options as $option)
											['{{ $option->title }}', {{ $question->answers()->where('option_id', $option->id)->count() }}],
										@endforeach
										['Kitas variantas', {{ $question->answers()->where('type', '=', 'custom')->count() }}],
									]);

									var chart = new google.visualization.PieChart(document.getElementById('chart-{{ $question->id }}'));

									chart.draw(data);
								}
							</script>

							<div class="clearfix"></div>
							
							@if ($question->answers()->where('type', '=', 'custom')->count())
								<div class="label label-primary">Kiti variantai</div><br>

								@foreach ($question->answers()->where('type', '=', 'custom')->get() as $custom_answer)
									<div class="label label-default">{{ $custom_answer->value }}</div><br>
								@endforeach
							@endif
						@elseif ($question->type == 'matrix')
							<div id="chart-{{ $question->id }}" class="col-sm-12" style="height: 350px;"></div>

							<script type="text/javascript">
								google.load("visualization", "1", {packages:["corechart"]});
								google.setOnLoadCallback(drawChart);

								function drawChart() {
									var data = google.visualization.arrayToDataTable([
										[
											'Option_x',

											@foreach ($question->options()->where('matrix', '=', 'x')->get() as $option_x)
												'{{ $option_x->title }}',
											@endforeach

											{ role: 'annotation' }
										],

										@foreach ($question->options()->where('matrix', '=', 'y')->get() as $option_y)
											[
												'{{ $option_y->title }}',

												@foreach ($question->options()->where('matrix', '=', 'x')->get() as $option_x)
													{{ $question->answers()->where('option_id', '=', $option_y->id)->where('value', '=', $option_x->id)->count() }},
												@endforeach

												'',
											],
										@endforeach
									]);

									var options = {
									legend: { position: 'top' },
									bar: { groupWidth: '75%' },
									isStacked: true
									};

									var chart = new google.visualization.BarChart(document.getElementById('chart-{{ $question->id }}'));

									chart.draw(data, options);
								}
							</script>
						@elseif (in_array($question->type, ['string', 'text']))
							@foreach ($question->answers as $answer)
								<div class="label label-default">{{ $answer->value }}</div><br>
							@endforeach
						@endif

						<hr>
					@endforeach
				@else
					<div class="alert alert-warning">
						Klausimų nėra.
					</div>
				@endif
			</div>

			<div class="col-sm-4">
				<div class="well">
					<a href="{{ route('campaigns.answer', $entry->id) }}" class="btn btn-primary btn-block btn-lg">
						<span class="glyphicon glyphicon-check"></span> Atsakyti į šią anketą
					</a>

					<p></p>
					
					<div class="btn-group-vertical btn-block">
						<div class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-stats"></span>
							Atsakymų: {{ $entry->results->count() }}
						</div>

						<div class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-user"></span>
							Autorius: {{ $entry->user->username }}
						</div>

						<div class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-calendar"></span>
							Sukurta: {{ $entry->created_at }}
						</div>

						@if (count($entry->tags))
							<div class="btn btn-sm btn-default" data-toggle="popover" data-placement="bottom" title="Anketos žymės" data-content="@foreach ($entry->tags as $tag)<a href='{{ route('campaigns.search') }}?search={{ $tag->title }}'>{{ $tag->title }}</a><br>@endforeach" data-html="true">
								<span class="glyphicon glyphicon-tags"></span>
								Žymės
							</div>
						@endif
					</div>
				</div>

				@if (count($recommended) > 0)
					<div class="well">
						<p class="lead">Siūlome atsakyti</p>

						@foreach ($recommended as $entry)
							<h4>
								<a href="{{ route('campaigns.answer', $entry->id) }}">{{ $entry->title }}</a>

								<div style="width: 100%; overflow: hidden; white-space: nowrap;">
									<small title="{{ $entry->description }}">{{ $entry->description }}</small>
								</div>
							</h4>
							
							<a href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-tasks"></span>
								{{ count($entry->results) }}
							</a>

							<a href="#" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-calendar"></span>
								{{ $entry->created_at }}
							</a>
						@endforeach
					</div>
				@endif
			</div>
		@else
			<div class="col-sm-12">
				<div class="alert alert-warning">
					Kol kas niekas neužpildė šios anketos.
				</div>
			</div>
		@endif
	</div>
@stop