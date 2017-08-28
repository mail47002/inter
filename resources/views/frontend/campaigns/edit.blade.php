@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ nustatymai - @stop

@section('content')
	{{ Form::open( ['route' => ['campaigns.update', $entry->id], 'class' => 'form-horizontal', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				{{ $entry->title }}
				<br class="visible-xs visible-sm">
				<small>Anketos nustatymai</small>
				
				<div class="pull-right hidden-sm hidden-xs">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
				</div>
			</h1>

			<div class="visible-xs visible-sm">
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
			</div>
		</div>

		@include('frontend.campaigns.tabs')

		@if (session('updated'))
			<div class="alert alert-success">
				Anketa sėkmingai atnaujinta.
			</div>
		@endif

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Bendrieji duomenys</p>

				<div class="form-group {{ $errors->first('title', 'has-error') }}">
					{{ Form::label('title', 'Pavadinimas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('title', $entry->title, [ 'class' => 'form-control', 'placeholder' => 'Anketos pavadinimas']) }}

						{!! $errors->first('title', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('description', 'has-error') }}">
					{{ Form::label('description', 'Aprašymas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::textarea('description', $entry->description, [ 'class' => 'form-control', 'placeholder' => 'Anketos aprašymas']) }}

						{!! $errors->first('description', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('tags', 'has-error') }}">
					{{ Form::label('tags', 'Žymės', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('tags', $entry->tags, [ 'class' => 'form-control', 'placeholder' => 'Anketos žymės']) }}

						<span class="label label-info">Žymes atskirkite tarpais.</span>
						
						{!! $errors->first('tags', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('public', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('public', 1, $entry->public) }} Vieša, gali rasti bet kas
							</label>
						</div>

						{!! $errors->first('public', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('active', 'has-error') }}" id="activator">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('active', 1, $entry->active) }} Aktyvi

								@if ($entry->active == 1)
									<span class="text-success"><strong>Dabar anketa yra aktyvi</strong></span>
								@else
									<span class="text-danger"><strong>Dabar anketa yra neaktyvi</strong></span>
								@endif
							</label>
						</div>

						{!! $errors->first('active', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ ( $errors->first('respondents') ? 'has-error' : NULL) }}">
					<div class="col-sm-9 col-sm-offset-3">
						<hr>
						<div class="checkbox">
							<label>
								{{ Form::checkbox('respondents', 1, $entry->respondents) }} Rodyti rezultatus respondentams
							</label>
						</div>

						{!! $errors->first('respondents', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('send_email', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('send_email', 1, $entry->send_email) }} Kiekvieną atsakymą siųsti man el. paštu
							</label>
						</div>

						{!! $errors->first('send_email', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('same_computer', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('same_computer', 1, $entry->same_computer) }} Leisti atsakyti kelis kartus iš vieno kompiuterio
							</label>
						</div>

						{!! $errors->first('same_computer', '<label class="control-label">:message</label>') !!}
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<hr class="visible-xs visible-sm">

				@if ($entry->active)
					<div class="well">
						<p class="lead">Tiesioginė nuoroda į anketą</p>

						<p>
							<a href="{{ route('campaigns.answer', $entry->id) }}" target="_blank">{{ route('campaigns.answer', $entry->id) }}</a>
						</p>

						<p><em>Šią nuorodą galite nusikopijuoti ir pasiųsti respondentams el. paštu, skype, patalpinti forumuose.</em></p>
					</div>
					
					<div class="alert alert-{{ ( $errors->first('respondents') ? 'danger' : 'warning') }}">
						<p class="lead">Reklamuokite anketą</p>
						
						@if ($entry->public)
							<p>
								Jūs turite <strong>{{ $entry->credits }}</strong> nepanaudotų kreditų už kuriuos galite reklamuoti anketą.
								Šios anketos vieno reklamuojamo atsakymo kaina <strong>{{ config('settings.featured_credits') }}</strong>.
								Įveskite reklamuojamų atsakymų kiekį kurį norėtumėte gauti šios anketos reklamos metu.
							</p>
							
							<p></p>
							
							<div class="form-inline">
								{{ Form::text('advertise_results', 0, [ 'class' => 'form-control', 'placeholder' => 'Atsakymų kiekis']) }}

								<button type="submit" class="btn btn-default">Reklamuoti</button>
							</div>

							{!! $errors->first('advertise_results', '<label class="label label-danger">:message</label>') !!}

							<div class="clearfix"></div>

							@if ($entry->used_results)
								<hr>

								<p>
									Gavote reklamuotų atsakymų: <strong>{{ $entry->used_results }}</strong><br>
									Panaudota kreditų: <strong>{{ $entry->used_credits }}</strong>
								</p>
							@endif
						@else
							<p>
								Jei norite reklamuoti anketą, padarykite ją viešą.
							</p>
						@endif
					</div>
				@endif

				<hr class="visible-xs visible-sm">

				<p class="lead">Įkelkite paveikslėlį</p>

				<div class="form-group {{ $errors->first('photo', 'has-error') }}">
					<div class="col-sm-3">
						@if (!empty($entry->photo))
							<p>
								<img src="{{ asset($entry->photo) }}" alt="Anketos paveikslėlis" class="img-thumbnail" style="width: 124px;">
							</p>
						@endif
					</div>

					<div class="col-sm-9">
						<p>
							<strong>Pasirinkite naują</strong>
						</p>

						<p>
							{{ Form::file('photo', [ 'class' => 'form-control ', 'placeholder' => 'Anketos paveikslėlis']) }}
							
							<span class="label label-info">Bus rodomas po anketos aprašymu.</span>

							{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
						</p>
					</div>
				</div>

				<hr>

				<p class="lead">Vaizdo įrašas</p>

				<div class="form-group {{ $errors->first('video', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('video', $entry->video, [ 'class' => 'form-control', 'placeholder' => 'http://']) }}

						<span class="label label-info"><span class="hidden-sm hidden-xs">Nuoroda į <em>YouTube</em> vaizdo įrašą.</span> Bus rodomas po anketos aprašymu.</span>

						{!! $errors->first('video', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>
			</div>
		</div>

		<div class="pull-right hidden-sm hidden-xs" style="margin-bottom: 100px;">
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
		</div>

		<div class="visible-sm visible-xs">
			<hr>

			<button type="submit" class="btn btn-block btn-lg btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
		</div>

		<p></p>
	{{ Form::close() }}
@stop