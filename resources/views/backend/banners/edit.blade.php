@extends('backend.layouts.default')

@section('title')
	Admin Panel - {{ $banner->name }} - Edit
@endsection

@section('content')
	<div class="page-header">
		<div class="pull-right">
			<button class="btn btn-primary" type="submit" form="form-banner"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
		</div>
		<h1 class="panel-title">{{ $banner->name }} <small>- Edit</small></h1>
	</div>
	{!! Form::open(['route' => ['banners.update', $banner->id], 'method' => 'put', 'id' => 'form-banner']) !!}
		<div class="row">
			<div class="col-md-8">
				<div class="form-group {{ $errors->first('name', 'has-error') }}">
					{!! Form::label('name', 'Name:') !!}
					{!! Form::text('name', $banner->sort_order, ['class' => 'form-control']) !!}
					{!! $errors->first('name', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group {{ $errors->first('body', 'has-error') }}">
					{!! Form::label('body', 'Body:') !!}
					{!! Form::textarea('body', $banner->body, ['id' => 'editor']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->first('position', 'has-error') }}">
					{!! Form::label('position_id', 'Position:') !!}
					{!! Form::select('position', ['left' => 'Left', 'right' => 'Right', 'top' => 'Top', 'bottom' => 'Bottom'], $banner->position, ['class' => 'form-control']) !!}
					{!! $errors->first('position', '<label class="control-label text-danger">:message</label>') !!}
				</div>
				<div class="form-group">
					{!! Form::label('status', 'Status:') !!}
					{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], $banner->status, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('sort_order', 'Sort order:') !!}
					{!! Form::text('sort_order', $banner->sort_order, ['class' => 'form-control']) !!}
					{!! $errors->first('sort_order', '<label class="control-label text-danger">:message</label>') !!}
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