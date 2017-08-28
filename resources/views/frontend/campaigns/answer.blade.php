@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title')Anketos „{{ $entry->title }}“ pildymas - @stop

@section('content')
	{{ Form::open( ['route' => ['campaigns.answer.store', $entry->id], 'class' => 'form', 'files' => true] ) }}
		<div class="page-header">
			<h1>
				{{ $entry->title }}
				<br class="visible-sm visible-xs">
				<small>Anketos pildymas</small>
			</h1>

			<a href="{{ route('campaigns.answers', $entry->id) }}" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-stats"></span>
				{{ $entry->results->count() }}
			</a>

			<a class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-user"></span>
				{{ $entry->user->username }}
			</a>

			<a class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-calendar"></span>
				{{ $entry->created_at }}
			</a>

			@if (count($entry->tags))
				<a href="#" class="btn btn-sm btn-default" data-toggle="popover" data-placement="bottom" title="Anketos žymės" data-content="@foreach ($entry->tags as $tag)<a href='{{ route('campaigns.search') }}?search={{ $tag->title }}'>{{ $tag->title }}</a><br>@endforeach" data-html="true">
					<span class="glyphicon glyphicon-tags"></span>
					Žymės
				</a>
			@endif
		</div>
	
		<div class="row">
			@if ($entry->photo)
				<div class="col-sm-2">
					<p class="text-center">
						<img src="{{ asset($entry->photo) }}" alt="{{ $entry->title }}" class="img-thumbnail">
					</p>
				</div>
			@endif

			@if ($entry->description)
				<div class="col-sm-10">
					<p>
						{{ $entry->description }}
					</p>
				</div>
			@endif
		</div>
		<div class="row">
			@if ($entry->photo || $entry->description)
				<div class="col-sm-12">
					<hr>
				</div>
			@endif
		</div>
	
		<div class="row">
			<div class="col-sm-12">
				@if (count($entry->questions) > 0)
					@foreach ($entry->questions as $key => $question)
						<div class="form-group {{ $errors->first($question->id, 'has-error') }}">
							<p class="lead">
								{{ $key + 1 }}. 
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
							
							@if ($question->type == 'radio')
								@foreach ($question->options as $option)
									<input type="radio" name="{{ $question->id }}" value="{{ $option->id }}" {{ request()->old($question->id) == $option->id ? 'checked' : NULL }}> {{ $option->title }}<br>
								@endforeach

								@if ($question->custom_answer)
									<div class="form-inline">
										<input type="radio" name="{{ $question->id }}" value="custom">

										<input type="text" name="custom-{{ $question->id }}" value="{{ request()->old('custom-' . $question->id) }}" class="form-control" placeholder="Kitas variantas"><br>
									</div>
								@endif
							@elseif ($question->type == 'select')
								<select name="{{ $question->id }}">
									@foreach ($question->options as $option)
										<option value="{{ $option->id }}" {{ request()->old($question->id) == $option->id ? 'selected' : NULL }}>{{ $option->title }}</option>
									@endforeach
								</select>
							@elseif ($question->type == 'check')
								@foreach ($question->options as $option)
									<input type="checkbox" name="{{ $question->id }}[{{ $option->id }}]" value="1" {{ isset(request()->old($question->id)[$option->id]) ? 'checked' : NULL }}> {{ $option->title }}<br>
								@endforeach

								@if ($question->custom_answer)
									<div class="form-inline">
										<input type="checkbox" name="{{ $question->id }}[custom]" value="custom" {{ isset(request()->old($question->id)['custom']) ? 'checked' : NULL }}> <input type="text" name="custom-{{ $question->id }}" value="{{ request()->old('custom-' . $question->id) }}" class="form-control" placeholder="Kitas variantas"><br>
									</div>
								@endif
							@elseif ($question->type == 'string')
								<input type="text" name="{{ $question->id }}" value="{{ request()->old($question->id) }}" class="form-control" placeholder="">
							@elseif ($question->type == 'text')
								<textarea name="{{ $question->id }}" cols="30" rows="10" class="form-control">{{ request()->old($question->id) }}</textarea>
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
													<input type="radio" name="{{ $question->id }}[{{ $option_y->id }}]" value="{{ $option_x->id }}" {{ isset(request()->old($question->id)[$option_y->id]) && request()->old($question->id)[$option_y->id] == $option_x->id ? 'checked' : NULL }}>
												</td>
											@endforeach
										</tr>
									@endforeach
								</table>
							@endif
						</div>

						<hr>
					@endforeach

					<button type="submit" class="btn btn-primary btn-block btn-lg">Pateikti atsakymus</button>

					<br>
				@else
					<div class="alert alert-warning">
						Klausimų nėra.
					</div>
				@endif
			</div>
		</div>
	{{ Form::close() }}
@stop