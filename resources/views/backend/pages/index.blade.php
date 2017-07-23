@extends('backend.layouts.default')

@section('title')
	Admin Panel - Pages
@endsection

@section('content')
<div class="col-md-10">
	<div class="content-box-large">
		<div class="panel-heading">
			<div class="pull-right">
				<a href="{{ route('pages.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New Page</a>
			</div>
			<h1 class="panel-title">Pages</h1>
		</div>
		<div class="panel-body">
			@if (session('status'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ session('status') }}
				</div>
			@endif
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
				<thead>
					<tr>
						<th>Id</th>
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
							<td>{{ $page->slug }}</td>
							<td>
								{!! $page->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}
							</td>
							<td>{{ $page->created_at }}</td>
							<td>
								<a href="{{ route('pages.edit', $page->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
								{{ Form::open(['route' => ['pages.destroy', $page->id], 'method' => 'delete', 'class' => 'form-inline']) }}
									<button class="btn btn-danger" type="submit" onclick="return confirm('Do you want to delete this page?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
								{{ Form::close() }}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			{{ $pages->links() }}
		</div>
	</div>
</div>
@stop