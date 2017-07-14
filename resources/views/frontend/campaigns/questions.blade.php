@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ klausimai - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('Frontend') }}"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>
			{{ $entry->title }}
			<small>Anketos klausimai</small>
		</h1>
	</div>

	@include('frontend.campaigns.tabs')

	<div class="row">
		<div class="col-sm-8">
			@if (count($entry->results))
				<div class="alert alert-danger">
					<h4>Klausimų redaguoti negalima!</h4>

					<p>
						Jūsų anketa turi atsakymų, todėl klausimų koreguoti negalima.
					</p>

					<p>
						<a href="{{ route('campaigns.deactivate', $entry->id) }}" class="btn btn-danger">Ištrinti rezultatus ir deaktyvuoti anketą</a>
					</p>
				</div>
			@elseif ($entry->active)
				<div class="alert alert-danger">
					<h4>Klausimų redaguoti negalima!</h4>

					<p>
						Jūsų anketa yra pažymėta kaip <strong>aktyvi</strong>, todėl klausimų koreguoti negalima.
					</p>

					<p>
						<a href="{{ route('campaigns.deactivate', $entry->id) }}" class="btn btn-danger">Deaktyvuoti anketą</a>
					</p>
				</div>
			@endif

			@if (count($entry->questions))
				@foreach ($entry->questions as $question)
					<div class="panel panel-default">
						<div class="panel-body">
							<p class="lead">
								{{ $question->title }}

								<span class="pull-right">
									@if ($entry->active == 0)
										<span class="btn-group btn-group-sm">
											<a href="{{ route('campaigns.questions.edit', [$entry->id, $question->id]) }}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Redaguoti</a>
											<a href="{{ route('campaigns.questions.destroy', [$entry->id, $question->id]) }}" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Ištrinti</a>
										</span>
									@endif
								</span>
							</p>

							@if ($question->photo)
								<p>
									<img src="{{ asset($question->photo) }}" alt="{{ $question->title }}" class="img-thumbnail">
								</p>
							@endif
							
							@if ($question->type == 'radio')
								@foreach ($question->options as $option)
									<input disabled type="radio" name="question-{{ $question->id }}"> {{ $option->title }}<br>
								@endforeach

								@if ($question->custom_answer)
									<div class="form-inline">
										<input disabled type="radio" name="question-{{ $question->id }}"> <input disabled type="text" class="form-control" placeholder="Kitas variantas"><br>
									</div>
								@endif
							@elseif ($question->type == 'select')
								<select disabled>
									@foreach ($question->options as $option)
										<option>{{ $option->title }}</option>
									@endforeach
								</select>
							@elseif ($question->type == 'check')
								@foreach ($question->options as $option)
									<input disabled type="checkbox" name="question-{{ $question->id }}"> {{ $option->title }}<br>
								@endforeach

								@if ($question->custom_answer)
									<div class="form-inline">
										<input disabled type="checkbox" name="question-{{ $question->id }}"> <input disabled type="text" class="form-control" placeholder="Kitas variantas"><br>
									</div>
								@endif
							@elseif ($question->type == 'string')
								<input disabled type="text" class="form-control" placeholder="">
							@elseif ($question->type == 'text')
								<textarea disabled name="" id="" cols="30" rows="10" class="form-control"></textarea>
							@elseif ($question->type == 'matrix')
								<table class="table table-condensed table-bordered">
									<tr>
										<th class="active"></th>

										@foreach ($question->options()->where('matrix', '=', 'x')->get() as $option_x)
											<th class="text-center active">{{ $option_x->title }}</th>
										@endforeach
									</tr>

									@foreach ($question->options()->where('matrix', '=', 'y')->get() as $option_y)
										<tr>
											<th class="text-center active">{{ $option_y->title }}</th>

											@foreach ($question->options()->where('matrix', '=', 'x')->get() as $option_x)
												<td class="text-center">
													<input type="radio" disabled name="question-{{ $option_y->id }}" value="{{ $option_x->id }}">
												</td>
											@endforeach
										</tr>
									@endforeach
								</table>
							@endif
						</div>
					</div>
				@endforeach
			@else
				<div class="alert alert-warning">
					Klausimų nėra.
				</div>
			@endif
		</div>

		<div class="col-sm-4">
			@if ($entry->active == 0)
				<div class="well">
					<p class="lead">Pridėti klausimą</p>

					<div class="list-group">
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'radio']) }}" class="list-group-item">Vieno varianto pasirinkimas</a>
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'select']) }}" class="list-group-item">Iškrentantis atsakymų sąrašas</a>
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'check']) }}" class="list-group-item">Kelių variantų pasirinkimas</a>
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'string']) }}" class="list-group-item">Eilutė teksto įvedimui</a>
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'text']) }}" class="list-group-item">Langelis teksto įvedimui</a>
						<a href="{{ route('campaigns.questions.add', [$entry->id, 'matrix']) }}" class="list-group-item">Matrica</a>
					</div>
				</div>
			@endif
		</div>
	</div>
@stop