@extends('backend.layouts.default')

@section('title')
	Admin Panel - {{ $page->title }} - Edit
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn btn-default" href="{{ route('pages.index') }}" data-toggle="tooltip" data-placement="top" title="Back"><i class="glyphicon glyphicon-share-alt flip-horizontal"></i></a>
					<button class="btn btn-primary" type="submit" form="form-page" data-toggle="tooltip" data-placement="top" title="Save"><i class="glyphicon glyphicon-floppy-disk"></i></button>
				</div>
				<h1 class="panel-title">Page - Edit</h1>
			</div>
			<div class="panel-body">
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
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{!! Form::label('status', 'Status:') !!}
								{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], $page->status, ['class' => 'form-control']) !!}
							</div>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
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