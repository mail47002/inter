@extends('backend.layouts.default')

@section('title')
	Admin Panel - Banners
@endsection

@section('content')
	<div class="page-header">
		<div class="pull-right">
			<a href="{{ route('banners.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New Banner</a>
		</div>
		<h1>Banners</h1>
	</div>
		@if (session('status'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ session('status') }}
			</div>
		@endif
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Position</th>
					<th>Status</th>
					<th>Created At</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($banners as $banner)
					<tr>
						<td>{{ $banner->id }}</td>
						<td>{{ $banner->name }}</td>
						<td><span class="badge text-uppercase">{{ $banner->position }}</span></td>
						<td>
							{!! $banner->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}
						</td>
						<td>{{ $banner->created_at }}</td>
						<td>
							<div class="btn-group">
								<a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
								<a href="{{ route('banners.delete', $banner->id) }}" class="btn btn-sm btn-default" onclick="return confirm('Do you want to delete this banner?');"><i class="glyphicon glyphicon-trash"></i> Delete</a>
							</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{{ $banners->links() }}
@stop