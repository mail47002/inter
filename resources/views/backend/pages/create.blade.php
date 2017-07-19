@extends('backend.layouts.default')

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<h1 class="panel-title">Page - Create</h1>
			</div>
			<div class="panel-body">
				{!! Form::open(['route' => 'pages.store', 'method' => 'post']) !!}
				<div class="row">
					<div class="col-md-9">
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
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('status', 'Status:') !!}
							{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], 1, ['class' => 'form-control']) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Create</button>
					<a class="btn btn-danger" href="{{ route('pages.index') }}"><i class="glyphicon glyphicon-remove"></i> Cancel</a>
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