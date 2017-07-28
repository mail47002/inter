@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Redaguoti klausimą „{{ $entry->title }}“ anketai - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('Frontend') }}"></script>
@stop

@section('content')
	{{ Form::open( ['route' => ['campaigns.questions.update', $entry->id, $question->id], 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				Redaguoti klausimą „{{ $question->title }}“
				<small>„{{ $entry->title }}“ anketai</small>
			</h1>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">
					Klausimo pavadinimas
				</p>

				<div class="form-group {{ $errors->first('title', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('title', $question->title, [ 'class' => 'form-control', 'placeholder' => 'Klausimo pavadinimas']) }}

						{!! $errors->first('title', '<label class="control-label">:message</label>') !!}
					</div>
				</div>
	
				@if ($type == 'radio' || $type == 'select' || $type == 'check')
					<hr>

					<p class="lead">Atsakymo variantai</p>

					<div class="variantai">
						@foreach ($question->options as $option)
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option[]" class="form-control" placeholder="Atsakymo variantas" value="{{ $option->title }}">
								</div>

								<div class="col-sm-3 btn-spot">
									@if ($question->options->first() == $option)
										<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
									@else
										<button type="button" class="btn btn-default remove-it"><span class="glyphicon glyphicon-minus"></span> Pašalinti</button>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@elseif ($type == 'matrix')
					<hr>

					<p class="lead">Atsakymo variantai</p>

					<div class="variantai">
						<p>Atsakymai (y)</p>

						@foreach ($question->options()->where('matrix', '=', 'y')->get() as $key => $option)
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option_y[]" class="form-control" placeholder="Reikšmė (y)" value="{{ $option->title }}">
								</div>

								<div class="col-sm-3 btn-spot">
									@if ($key == 0)
										<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
									@else
										<button type="button" class="btn btn-default remove-it"><span class="glyphicon glyphicon-minus"></span> Pašalinti</button>
									@endif
								</div>
							</div>
						@endforeach
					</div>

					<div class="variantai">
						<p>Reitingavimas (x)</p>

						@foreach ($question->options()->where('matrix', '=', 'x')->get() as $key => $option)
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option_x[]" class="form-control" placeholder="Reikšmė (y)" value="{{ $option->title }}">
								</div>

								<div class="col-sm-3 btn-spot">
									@if ($key == 0)
										<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
									@else
										<button type="button" class="btn btn-default remove-it"><span class="glyphicon glyphicon-minus"></span> Pašalinti</button>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@endif
			</div>

			<div class="col-sm-6">
				<p class="lead">Daugiau nustatymų</p>

				<div class="form-group {{ $errors->first('note', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('note', $question->note, [ 'class' => 'form-control', 'placeholder' => 'Klausimo pastaba']) }}

						{!! $errors->first('note', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('required', 'has-error') }}">
					{{ Form::label('required', 'Privalomas klausimas', [ 'class' => 'col-sm-5 control-label']) }}

					<div class="col-sm-7">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('required', '1', $question->required) }} &nbsp;
							</label>
						</div>

						{!! $errors->first('required', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('private', 'has-error') }}">
					{{ Form::label('private', 'Privatus klausimas', [ 'class' => 'col-sm-5 control-label']) }}

					<div class="col-sm-7">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('private', '1', $question->private) }} &nbsp;
							</label>
						</div>

						{!! $errors->first('private', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				@if ($type == 'radio' || $type == 'select' || $type == 'check')
					<div class="form-group {{ ( $errors->first('custom_answer') ? 'has-error' : NULL) }}">
						{{ Form::label('custom_answer', 'Leisti įvesti savo variantą', [ 'class' => 'col-sm-5 control-label']) }}

						<div class="col-sm-7">
							<div class="checkbox">
								<label>
									{{ Form::checkbox('custom_answer', '1', $question->custom_answer) }} &nbsp;
								</label>
							</div>

							{!! $errors->first('custom_answer', '<label class="control-label">:message</label>') !!}
						</div>
					</div>
				@endif

				<hr>

				<p class="lead">Įkelkite paveikslėlį</p>

				<div class="form-group {{ ( $errors->first('photo') ? 'has-error' : NULL) }}">
					@if ( ! empty($question->photo))
						<div class="col-sm-3">
							<p>
								<img src="{{ asset($question->photo) }}" alt="Anketos paveikslėlis" class="img-thumbnail" style="width: 124px;">
							</p>
						</div>

						<div class="col-sm-9">
							<p>
								<strong>Pasirinkite naują</strong>
							</p>

							<p>
								{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Anketos paveikslėlis']) }}
								
								<span class="label label-info">Bus rodomas po klausimu.</span>

								{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
							</p>
						</div>
					@else
						<div class="col-sm-12">
							{{ Form::file('photo', [ 'class' => 'form-control', 'placeholder' => 'Paveikslėlis']) }}

							<span class="label label-info">Bus rodomas po klausimu.</span>

							{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
						</div>
					@endif
				</div>

				<div class="clearfix"></div>
			
				<hr>

				<p class="lead">Vaizdo įrašas</p>

				<div class="form-group {{ ( $errors->first('video') ? 'has-error' : NULL) }}">
					<div class="col-sm-12">
						{{ Form::text('video', $question->video, [ 'class' => 'form-control', 'placeholder' => 'http://']) }}

						<span class="label label-info">Nuoroda į <em>YouTube</em> vaizdo įrašą. Bus rodomas po klausimu.</span>

						{!! $errors->first('video', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>
			</div>
		</div>

		<hr>

		<button type="submit" class="btn btn-primary add-question-confirm">Atnaujinti klausimą</button>
		<a href="{{ route('campaigns.questions', $entry->id) }}" class="btn btn-default add-question-cancel">Atšaukti</a>
	{{ Form::close() }}
@stop