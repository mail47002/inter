@extends('backend.layouts.default')

@section('title')
	Admin Panel - User
@endsection

@section('content')
	<div class="col-md-10">
		<div class="content-box-large">
			<div class="panel-heading">
				<div class="pull-right">
					@if ($user->status == 1)
						<a href="#" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Disabled"><i class="glyphicon glyphicon-eye-close"></i></a>
					@else
						<a href="#" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Enabled"><i class="glyphicon glyphicon-eye-open"></i></a>
					@endif
					<a href="{{route('users.edit', ['id' => $user->id])}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
					{{ Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'form-inline']) }}
						<button class="btn btn-danger" type="submit" onclick="return confirm('Do you want to delete this user?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
					{{ Form::close() }}
				</div>
				<div class="panel-title">Users</div>
			</div>
			<div class="panel-body">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
					<li role="presentation"><a href="#campaigns" aria-controls="messages" role="tab" data-toggle="tab">Campaigns</a></li>
					<li role="presentation"><a href="#payments" aria-controls="settings" role="tab" data-toggle="tab">Payments</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="profile">
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								@if ($user->photo)
									<div class="photo">
										<img class="img-responsive" src="{{ asset($user->photo) }}">
									</div>
								@endif
								<h3 class="title text-center">{{ $user->username }}</h3>
								<table class="table table-profile">
									<tbody>
										<tr>
											<th>E-mail:</th>
											<td>{{ $user->email }}</td>
										</tr>
										<tr>
											<th>Status:</th>
											<td>{!! $user->status == 1 ? '<span class="badge badge-success text-uppercase">Active</span>' : '<span class="badge text-uppercase">Disabled</span>' !!}</td>
										</tr>
										<tr>
											<th>Registered At:</th>
											<td>{{ $user->created_at }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="campaigns">
						@if (count($user->campaigns) > 0)
							<table class="table">
								<thead>
									<tr>
										<th>ID</th>
										<th>Title</th>
										<th class="text-center">Status</th>
										<th>Created At</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($user->campaigns as $campaign)
										<tr>
											<td>{{ $campaign->id }}</td>
											<td>{{ $campaign->title }}</td>
											<td class="text-center">{!! $user->status == 1 ? '<span class="status status-success"></span>' : '<span class="status"></span>' !!}</td>
											<td>{{ $campaign->created_at }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<p>There are no campaigns!</p>
						@endif
					</div>
					<div role="tabpanel" class="tab-pane" id="payments">
						<p>There are no payments!</p>
					</div>
				</div>

			</div>
	</div>
@stop