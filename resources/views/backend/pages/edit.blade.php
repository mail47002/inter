@extends('backend.layouts.default')

@section('title')
	Admin Panel - {{ $page->title }} - Edit
@endsection

@section('content')
		<div class="page-header">
			<div class=" pull-right">
				<button class="btn btn-primary" type="submit" form="form-page"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
			</div>
			<h1>{{ $page->title }} <small>- Edit</small></h1>
		</div>
		{!! Form::open(['route' => ['pages.update', $page->id], 'method' => 'put', 'id' => 'form-page']) !!}
			<div class="row">
				<div class="col-md-8">
					<div class="form-group {{ $errors->first('title', 'has-error') }}">
						{!! Form::label('title', 'Title:') !!}
						{!! Form::text('title', $page->title, ['class' => 'form-control']) !!}
						{!! $errors->first('title', '<label class="control-label text-danger">:message</label>') !!}
					</div>
					<div class="form-group {{ $errors->first('slug', 'has-error') }}">
						{!! Form::label('slug', 'Slug:') !!}
						{!! Form::text('slug', $page->slug, ['class' => 'form-control']) !!}
						{!! $errors->first('slug', '<label class="control-label text-danger">:message</label>') !!}
					</div>
					<div class="form-group">
						{!! Form::label('content', 'Content:') !!}
						{!! Form::textarea('content', $page->content, ['id' => 'editor']) !!}
					</div>
					<div class="form-group {{ $errors->first('meta_title', 'has-error') }}">
					{!! Form::label('meta_title', 'Meta title:') !!}
					{!! Form::text('meta_title', $page->meta_title, ['class' => 'form-control']) !!}
					{!! $errors->first('meta_title', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group {{ $errors->first('meta_description', 'has-error') }}">
<<<<<<< Updated upstream
					{!! Form::label('meta_discription', 'Meta description:') !!}
					{!! Form::textarea('meta_description', $page->meta_description, ['class' => 'form-control', 'rows' => 5]) !!}
=======
					{!! Form::label('meta_description', 'Meta description:') !!}
					{!! Form::text('meta_description', $page->meta_description, ['class' => 'form-control', 'rows' => 5]) !!}
>>>>>>> Stashed changes
					{!! $errors->first('meta_description', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('status', 'Status:') !!}
						{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], $page->status, ['class' => 'form-control']) !!}
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