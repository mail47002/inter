@extends('backend.layouts.default')

@section('title')
	Admin Panel - Users
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="pull-right">
					<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New User</a>
				</div>
				<h1 class="panel-title">Users</h1>
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
								<td><a href="{{route('users.show', ['id' => $user->id])}}">{{ $user->username }}</a></td>
								<td>{{ $user->email }}</td>
								<td>
									{!! $user->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}
								</td>
								<td>{{ $user->created_at }}</td>
								<td>
									@if ($user->status == 1)
										<a href="#" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Disabled"><i class="glyphicon glyphicon-eye-close"></i></a>
									@else
										<a href="#" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Enabled"><i class="glyphicon glyphicon-eye-open"></i></a>
									@endif
									<a href="{{route('users.edit', ['id' => $user->id])}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
									{{ Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'form-inline']) }}
										<button class="btn btn-danger" type="submit" onclick="return confirm('Do you want to delete this user?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
									{{ Form::close() }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $users->links() }}
			</div>
		</div>
	</div>
@stop