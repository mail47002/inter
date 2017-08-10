@extends('backend.layouts.default')

@section('title')
	Admin Panel - Pages
@endsection

@section('content')
	<div class="page-header">
		<div class="pull-right">
			<a href="{{ route('pages.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New page</a>
		</div>
		<h1>Pages</h1>
	</div>
	@if (session('success'))
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ session('success') }}
		</div>
	@endif
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Slug</th>
				<th>Status</th>
				<th>Created At</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($pages as $page)
				<tr>
					<td>{{ $page->id }}</td>
					<td>{{ $page->title }}</td>
					<td>/{{ $page->slug }}</td>
					<td>
						{!! $page->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}
					</td>
					<td>{{ $page->created_at }}</td>
					<td>
						<div class="btn-group">
							<a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
							<a href="{{ route('pages.delete', $page->id) }}" class="btn btn-sm btn-default" onclick="return confirm('Do you want to delete this page?');"><i class="glyphicon glyphicon-trash"></i> Delete</a>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	{{ $pages->links() }}
@stop