@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Pridėti klausimą „{{ $entry->title }}“ anketai - @stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('js/frontend/campaigns/questions.js') }}"></script>
@stop

@section('content')
	{{ Form::open( ['route' => ['campaigns.questions.store', $entry->id, $type], 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				Pridėti klausimą
				<small>"{{ $entry->title }}" anketai</small>
			</h1>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">
					Klausimo pavadinimas
				</p>

				<div class="form-group {{ $errors->first('title', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('title', '', [ 'class' => 'form-control', 'placeholder' => 'Klausimo pavadinimas']) }}

						{!! $errors->first('title', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				@if ($type == 'radio' || $type == 'select' || $type == 'check')
					<hr>

					<p class="lead">Atsakymo variantai</p>

					<div class="variantai">
						@if (Request::old('option'))
							@foreach (Request::old('option') as $key => $option)
								<div class="form-group option">
									<div class="col-sm-9">
										<input type="text" name="option[]" class="form-control" placeholder="Atsakymo variantas" value="{{ $option }}">
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
						@else
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option[]" class="form-control" placeholder="Atsakymo variantas">
								</div>

								<div class="col-sm-3 btn-spot">
									<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
								</div>
							</div>

							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option[]" class="form-control" placeholder="Atsakymo variantas">
								</div>

								<div class="col-sm-3 btn-spot">
									<button type="button" class="btn btn-default remove-it"><span class="glyphicon glyphicon-minus"></span> Pašalinti</button>
								</div>
							</div>
						@endif
					</div>
				@elseif ($type == 'matrix')
					<hr>

					<p class="lead">Atsakymo variantai</p>

					<div class="variantai">
						<p>Atsakymai (y)</p>

						@if (Request::old('option_y'))
							@foreach (Request::old('option_y') as $key => $option)
								<div class="form-group option">
									<div class="col-sm-9">
										<input type="text" name="option_y[]" class="form-control" placeholder="Reikšmė (y)" value="{{ $option }}">
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
						@else
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option_y[]" class="form-control" placeholder="Reikšmė (y)">
								</div>

								<div class="col-sm-3 btn-spot">
									<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
								</div>
							</div>
						@endif
					</div>

					<div class="variantai">
						<p>Reitingavimas (x)</p>

						@if (Request::old('option_x'))
							@foreach (Request::old('option_x') as $key => $option)
								<div class="form-group option">
									<div class="col-sm-9">
										<input type="text" name="option_x[]" class="form-control" placeholder="Reikšmė (y)" value="{{ $option }}">
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
						@else
							<div class="form-group option">
								<div class="col-sm-9">
									<input type="text" name="option_x[]" class="form-control" placeholder="Reikšmė (x)">
								</div>

								<div class="col-sm-3 btn-spot">
									<button type="button" class="btn btn-default add-more"><span class="glyphicon glyphicon-plus"></span> Pridėti dar</button>
								</div>
							</div>
						@endif
					</div>
				@endif
			</div>

			<div class="col-sm-6">
				<p class="lead">Daugiau nustatymų</p>

				<div class="form-group {{ $errors->first('note', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('note', '', [ 'class' => 'form-control', 'placeholder' => 'Klausimo pastaba']) }}

						{!! $errors->first('note', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('required', 'has-error') }}">
					{{ Form::label('required', 'Privalomas klausimas', [ 'class' => 'col-sm-5 control-label']) }}

					<div class="col-sm-7">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('required', '1') }} &nbsp;
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
								{{ Form::checkbox('private', '1') }} &nbsp;
							</label>
						</div>

						{!! $errors->first('private', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				@if ($type == 'radio' || $type == 'select' || $type == 'check')
					<div class="form-group {{ $errors->first('custom_answer', 'has-error') }}">
						{{ Form::label('custom_answer', 'Leisti įvesti savo variantą', [ 'class' => 'col-sm-5 control-label']) }}

						<div class="col-sm-7">
							<div class="checkbox">
								<label>
									{{ Form::checkbox('custom_answer', '1') }} &nbsp;
								</label>
							</div>

							{!! $errors->first('custom_answer', '<label class="control-label">:message</label>') !!}
						</div>
					</div>
				@endif

				<hr>

				<p class="lead">Įkelkite paveikslėlį</p>

				<div class="form-group {{ $errors->first('photo', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::file('photo', [ 'class' => 'form-control', 'placeholder' => 'Paveikslėlis']) }}

						<span class="label label-info">Bus rodomas po klausimu.</span>

						{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>


				<div class="clearfix"></div>

				<hr>

				<p class="lead">Vaizdo įrašas</p>

				<div class="form-group {{ $errors->first('video', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('video', '', [ 'class' => 'form-control', 'placeholder' => 'http://']) }}

						<span class="label label-info">Nuoroda į <em>YouTube</em> vaizdo įrašą. Bus rodomas po klausimu.</span>

						{!! $errors->first('video', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>
			</div>
		</div>

		<hr>

		<button type="submit" class="btn btn-primary add-question-confirm">Pridėti klausimą</button>
		<a href="{{ route('campaigns.questions', $entry->id) }}" class="btn btn-default add-question-cancel">Atšaukti</a>
	{{ Form::close() }}
@stop