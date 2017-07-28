@extends('backend.layouts.default')

@section('title')
	Admin Panel - Users
@endsection

@section('content')
	<div class="page-header">
		<div class="pull-right">
			<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New user</a>
		</div>
		<h1>Users</h1>
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
					<th>User</th>
					<th>E-mail</th>
					<th>Status</th>
					<th>Registered At</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td>{{ $user->id }}</td>
						<td>{{ $user->username }}</td>
						<td>{{ $user->email }}</td>
						<td>
							{!! $user->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}
						</td>
						<td>{{ $user->created_at }}</td>
						<td>
							<div class="btn-group">
								@if ($user->status == 1)
									<a href="{{ route('users.status', $user->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-eye-close"></i> Disabled</a>
								@else
									<a href="{{ route('users.status', $user->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-eye-open"></i> Enabled</a>
								@endif
								<a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-user"></i> Profile</a>
								<a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
								<a href="{{ route('users.delete', $user->id) }}" class="btn btn-sm btn-default" onclick="return confirm('Do you want to delete this user?');"><i class="glyphicon glyphicon-trash"></i> Delete</a>
							</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{{ $users->links() }}
@stop