@extends('backend.layouts.default')

@section('title')
	Admin Panel - Page - Create
@endsection

@section('content')
	<div class="page-header">
		<div class="pull-right">
			<button class="btn btn-primary" type="submit" form="form-page"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
		</div>
		<h1>Page <small>- Create</small></h1>
	</div>
	{!! Form::open(['route' => 'pages.store', 'method' => 'post', 'id' => 'form-page']) !!}
		<div class="row">
			<div class="col-md-8">
				<div class="form-group {{ $errors->first('title', 'has-error') }}">
					{!! Form::label('title', 'Title:') !!}
					{!! Form::text('title', null, ['class' => 'form-control']) !!}
					{!! $errors->first('title', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group {{ $errors->first('slug', 'has-error') }}">
					{!! Form::label('slug', 'Slug:') !!}
					{!! Form::text('slug', null, ['class' => 'form-control']) !!}
					{!! $errors->first('slug', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group">
					{!! Form::label('content', 'Content:') !!}
					{!! Form::textarea('content', null, ['id' => 'editor']) !!}
				</div>
				<div class="form-group {{ $errors->first('meta_title', 'has-error') }}">
					{!! Form::label('meta_title', 'Meta title:') !!}
					{!! Form::text('meta_title', null, ['class' => 'form-control']) !!}
					{!! $errors->first('meta_title', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group {{ $errors->first('meta_description', 'has-error') }}">
					{!! Form::label('meta_description', 'Meta description:') !!}
					{!! Form::text('meta_description', null, ['class' => 'form-control', 'rows' => 5]) !!}
					{!! $errors->first('meta_description', '<label class="control-label text-danger">:message</label>') !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('status', 'Status:') !!}
					{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], 1, ['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
	{!! Form::close() !!}
@endsection

@push('styles')
	<link href="{{ asset('js/backend/summernote/summernote.css') }}" rel="stylesheet">
@endpush

@push('scripts')
	<script src="{{ asset('js/backend/summernote/summernote.min.js') }}"></script>
	<script type="text/javascript">
		$('#editor').summernote({
			disableDragAndDrop: true,
			height: 300,
		});
	</script>
@endpush