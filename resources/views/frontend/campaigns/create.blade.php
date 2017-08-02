@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Sukurti naują anketą - @stop

@section('content')
	{{ Form::open(['route' => 'campaigns.store', 'class' => 'form-horizontal', 'files' => true]) }}
		<div class="page-header">
			<h1>
				Sukurti naują anketą
				
				<div class="pull-right">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
				</div>
			</h1>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Bendrieji duomenys</p>

				<div class="form-group {{ $errors->first('title', 'has-error') }}">
					{{ Form::label('title', 'Pavadinimas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('title', '', [ 'class' => 'form-control', 'placeholder' => 'Anketos pavadinimas']) }}

						{!! $errors->first('title', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('description', 'has-error') }}">
					{{ Form::label('description', 'Aprašymas', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::textarea('description', '', [ 'class' => 'form-control', 'placeholder' => 'Anketos aprašymas']) }}

						{!! $errors->first('description', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('respondents', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('respondents', 1) }} Rodyti rezultatus respondentams
							</label>
						</div>

						{!! $errors->first('respondents', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('send_email', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('send_email', 1) }} Kiekvieną atsakymą siųsti man el. paštu
							</label>
						</div>

						{!! $errors->first('send_email', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('same_computer', 'has-error') }}">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('same_computer', 1) }} Leisti atsakyti kelis kartus iš vieno kompiuterio
							</label>
						</div>

						{!! $errors->first('same_computer', '<label class="control-label">:message</label>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->first('tags', 'has-error') }}">
					{{ Form::label('tags', 'Žymės', [ 'class' => 'col-sm-3 control-label']) }}

					<div class="col-sm-9">
						{{ Form::text('tags', '', [ 'class' => 'form-control', 'placeholder' => 'Anketos žymės']) }}

						<span class="label label-info">Žymes atskirkite tarpais.</span>
						
						{!! $errors->first('tags', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<p class="lead">Įkelkite paveikslėlį</p>

				<div class="form-group {{ $errors->first('photo', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::file('photo', [ 'class' => 'form-control', 'placeholder' => 'Paveikslėlis']) }}

						<span class="label label-info">Bus rodomas po anketos aprašymu.</span>

						{!! $errors->first('photo', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>


				<div class="clearfix"></div>
			
				<hr>

				<p class="lead">Vaizdo įrašas</p>

				<div class="form-group {{ $errors->first('video', 'has-error') }}">
					<div class="col-sm-12">
						{{ Form::text('video', '', [ 'class' => 'form-control', 'placeholder' => 'http://']) }}

						<span class="label label-info">Nuoroda į <em>YouTube</em> vaizdo įrašą. Bus rodomas po anketos aprašymu.</span>

						{!! $errors->first('video', '<br><label class="control-label">:message</label>') !!}
					</div>
				</div>


				<div class="clearfix"></div>
			</div>
		</div>

		<div class="pull-right" style="margin-bottom: 100px;">
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Išsaugoti duomenis</button>
		</div>
	{{ Form::close() }}
@stop