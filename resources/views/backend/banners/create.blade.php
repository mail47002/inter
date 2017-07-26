@extends('backend.layouts.default')

@section('title')
	Admin Panel - Banner - Create
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="pull-right">
					<a class="btn btn-default" href="{{ route('banners.index') }}" data-toggle="tooltip" data-placement="top" title="Back"><i class="glyphicon glyphicon-share-alt flip-horizontal"></i></a>
					<button class="btn btn-primary" type="submit" form="form-banner" data-toggle="tooltip" data-placement="top" title="Save"><i class="glyphicon glyphicon-floppy-disk"></i></button>
				</div>
				<h1 class="panel-title">Banner - Create</h1>
			</div>
			<div class="panel-body">
				{!! Form::open(['route' => 'banners.store', 'method' => 'post', 'id' => 'form-banner']) !!}
					<div class="row">
						<div class="col-md-10">
							<div class="form-group {{ $errors->first('position_id', 'has-error') }}">
								{!! Form::label('position_id', 'Position:') !!}
								{!! Form::select('position_id', ['1' => 'Left', '2' => 'Right', '3' => 'Top', '4' => 'Bottom'], null, ['class' => 'form-control']) !!}
								{!! $errors->first('position_id', '<label class="control-label text-danger">:message</label>') !!}
							</div>
							<div class="form-group {{ $errors->first('body', 'has-error') }}">
								{!! Form::label('body', 'Body:') !!}
								{!! Form::textarea('body', null, ['id' => 'editor']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('sort_order', 'Sort order:') !!}
								{!! Form::text('sort_order', null, ['class' => 'form-control']) !!}
								{!! $errors->first('sort_order', '<label class="control-label text-danger">:message</label>') !!}
							</div>
							<div class="form-group">
								{!! Form::label('status', 'Status:') !!}
								{!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], null, ['class' => 'form-control']) !!}
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