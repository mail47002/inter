@extends('backend.layouts.default')

@section('title', $title)

@section('content')

<div class="col-md-10">
	<div class="content-box-large">
		<div class="panel-heading">
          <div class="panel-title">Create page</div>
          <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
          </div>
      </div>
		<div class="panel-body">
		<div class="panel-body">
		{!! Form::open(['route' => 'pages.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<div class="form-group">
				{!! Form::label('title', 'Tile') !!}
				{!! Form::text('title', '', ['class' => 'form-control']) !!}
				{{ $errors->first('title', '<label class="control-label">:message</label>') }}
			</div>
			<div class="form-group">
				{!! Form::label('slug', 'Slug') !!}
				{!! Form::text('slug', '', ['class' => 'form-control']) !!}
				{{ $errors->first('slug', '<label class="control-label">:message</label>') }}
			</div>
			<div class="form-group">
				{!! Form::label('content', 'Content') !!}
				{!! Form::textarea('content', '', ['class' => 'form-control']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('published', 'Published') !!}
				{!! Form::checkbox('published', 1, false); !!}
			</div>
		  <div class="form-group">
		    {!!Form::submit('Create', ['class' => 'btn btn-primary'])!!}
		  </div>
		{!! Form::close() !!}
		</div>
		</div>
	</div>
</div>
@stop