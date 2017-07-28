@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ rezultatai - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('js/frontend/campaigns/questions.js') }}"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<small>Anketos rezultatai</small>
		</h1>
	</div>

	@include('frontend.campaigns.tabs')

	@if (count($bad_results) > 1)
		<div class="alert alert-info">
			<h4>Rezultatai filtruojami pagal šiuos atsakymus:</h4>
			
			@foreach (json_decode(request()->get('questions')) as $question => $option)
				<strong>{{ \App\CampaignQuestion::find($question)->title }}</strong> -> {{ \App\CampaignQuestionOption::find($option)->title }}<br>
			@endforeach
		</div>
	@endif

	<div class="row">
		@if (count($entry->results))
			<div class="col-sm-9">
				@if (count($entry->questions()->whereNotIn('id', $bad_questions)->get()))
					@foreach ($entry->questions()->whereNotIn('id', $bad_questions)->get() as $question)
						<p class="lead">
							{{ $question->title }}
							{{ $errors->first($question->id, '<small><label class="control-label">:message</label></small>') }}
						</p>

						@if ($question->photo)
							<p>
								<img src="{{ asset($question->photo) }}" alt="{{ $question->title }}" class="img-thumbnail">
							</p>
						@endif

						@if (in_array($question->type, ['radio', 'select', 'check']))
							<div id="chart-{{ $question->id }}" class="col-sm-12" style="height: 250px;"></div>

							<script type="text/javascript">
								google.load("visualization", "1", {packages:["corechart"]});
								google.setOnLoadCallback(drawChart);

								function drawChart() {
									var data = google.visualization.arrayToDataTable([
										['Option', 'Count'],
										@foreach ($question->options as $option)
											['{{ $option->title }}', {{ $question->answers()->whereNotIn('result_id', $bad_results)->where('option_id', '=', $option->id)->count() }}],
										@endforeach
										['Kitas variantas', {{ $question->answers()->whereNotIn('result_id', $bad_results)->where('type', '=', 'custom')->count() }}],
									]);

									var chart = new google.visualization.PieChart(document.getElementById('chart-{{ $question->id }}'));

									chart.draw(data);
								}
							</script>

							<div class="clearfix"></div>

							@if ($question->answers()->whereNotIn('result_id', $bad_results)->where('type', '=', 'custom')->count())
								<div class="label label-primary">Kiti variantai</div><br>

								@foreach ($question->answers()->whereNotIn('result_id', $bad_results)->where('type', '=', 'custom')->get() as $custom_answer)
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
													{{ $question->answers()->whereNotIn('result_id', $bad_results)->where('option_id', '=', $option_y->id)->where('value', '=', $option_x->id)->count() }},
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
							@foreach ($question->answers()->whereNotIn('result_id', $bad_results)->get() as $answer)
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

			<div class="col-sm-3">
				<div class="well">
					<p class="lead">Anketos meniu</p>

					<div class="list-group">
						<a href="{{ route('campaigns.results.xlsx', $entry->id) }}" class="list-group-item">Eksportuoti į Excel</a>
					</div>
				</div>
		
				<div class="well">
					<p class="lead">Statistinė analizė</p>

					<div class="list-group">
						<a href="#" data-toggle="modal" data-target="#duomenu-aprasymas" class="list-group-item">Duomenų aprašymas</a>
						<a href="#" data-toggle="modal" data-target="#poriniai-stebejimai" class="list-group-item">Poriniai stebėjimai</a>
						<a href="#" data-toggle="modal" data-target="#koreliacine-analize" class="list-group-item">Koreliacinė analizė</a>
						<a href="#" data-toggle="modal" data-target="#regresine-analize" class="list-group-item">Regresinė analizė</a>
					</div>
				</div>
			</div>

			<div class="modal fade" id="duomenu-aprasymas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Uždaryti</span></button>
							<h4 class="modal-title" id="myModalLabel">Duomenų aprašymas</h4>
						</div>

						<div class="modal-body">
							@foreach ($entry->questions()->where('type', '=', 'radio')->get() as $question)
								<div class="row">
									<div class="col-sm-12">
										<strong>{{ $question->title}}</strong>
									</div>
								</div>
								
								@foreach ($question->options as $option)
									<div class="row">
										<div class="col-sm-11 col-sm-offset-1">
											<div class="checkbox">
												<label>
													<input type="radio" class="filter-1-radio" name="{{ $question->id }}" value="{{ $option->id }}"> {{ $option->title }}
												</label>
											</div>
										</div>
									</div>
								@endforeach

								<p></p>
							@endforeach
						</div>

						<script type="text/javascript">
							var prefix 	= '{{ route('campaigns.results', $entry->id) }}';
							var postfix = '?filter=1&questions=';

							$(document).on('change', '.filter-1-radio', function() {
								var ending = {};

								$(".filter-1-radio:checked").each(function() {
									ending[ $(this).attr('name') ] = $(this).val();
								});

								ending = JSON.stringify(ending);

								$("#filter-url").attr('href', prefix + postfix + ending);
							});

							$(document).ready(function() {
								$("#filter-url").attr('href', prefix);
							});
						</script>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
							<a href="#" id="filter-url" class="btn btn-primary">Filtruoti</a>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="poriniai-stebejimai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Uždaryti</span></button>
							<h4 class="modal-title" id="myModalLabel">Poriniai stebėjimai</h4>
						</div>
						
						{{ Form::open( ['route' => ['campaigns.cross_tabulation', $entry->id], 'class' => 'form-horizontal'] ) }}
							<div class="modal-body">
								<p class="lead">Pirmas klausimas</p>

								@foreach ($entry->questions()->where('type', '<>', 'matrix')->where('type', '<>', 'string')->where('type', '<>', 'text')->get() as $question)
									<div class="checkbox">
										<label>
											<input type="radio" name="question_first" value="{{ $question->id }}"> {{ $question->title }}
										</label>
									</div>
								@endforeach

								<hr>

								<p class="lead">Antras klausimas</p>

								@foreach ($entry->questions()->where('type', '<>', 'matrix')->where('type', '<>', 'string')->where('type', '<>', 'text')->get() as $question)
									<div class="checkbox">
										<label>
											<input type="radio" name="question_second" value="{{ $question->id }}"> {{ $question->title }}
										</label>
									</div>
								@endforeach
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
								<button class="btn btn-primary">Analizuoti</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>

			<div class="modal fade" id="koreliacine-analize" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Uždaryti</span></button>
							<h4 class="modal-title" id="myModalLabel">Koreliacinė analizė</h4>
						</div>
						
						{{ Form::open( ['route' => ['campaigns.correlation', $entry->id], 'class' => 'form-horizontal'] ) }}
							<div class="modal-body">
								<p class="lead">Pasirinkite klausimus</p>

								@foreach ($entry->questions()->whereIn('type', ['radio', 'select', 'check', 'matrix'])->get() as $question)
									<div class="checkbox">
										<label>
											<input type="checkbox" name="questions[]" value="{{ $question->id }}"> {{ $question->title }}
										</label>
									</div>
								@endforeach
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
								<button class="btn btn-primary">Analizuoti</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>

			<div class="modal fade" id="regresine-analize" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Uždaryti</span></button>
							<h4 class="modal-title" id="myModalLabel">Regresinė analizė</h4>
						</div>
						
						{{ Form::open( ['route' => ['campaigns.regression', $entry->id], 'class' => 'form-horizontal'] ) }}
							<div class="modal-body">
								<p class="lead">Pasirinkite klausimus</p>

								@foreach ($entry->questions()->whereIn('type', ['radio', 'select'])->get() as $question)
									<div class="checkbox">
										<label>
											<input type="checkbox" name="questions[]" value="{{ $question->id }}"> {{ $question->title }}
										</label>
									</div>
								@endforeach
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
								<button class="btn btn-primary">Analizuoti</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
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