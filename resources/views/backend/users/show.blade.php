@extends('backend.layouts.default')

@section('title')
	Admin Panel - User
@endsection

@section('content')
	<div class="page-header">
		<h1>{{ $user->username }} <small>- Profile</small></h1>
	</div>
	<div class="row">
		<div class="col-md-5">
			@if ($user->photo)
				<div class="photo">
					<img class="img-responsive" src="{{ asset($user->photo) }}">
				</div>
			@endif
			<h3>General</h3>
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
		<div class="col-md-7">
			<h3>Campaigns</h3>
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

			<h3>Payments</h3>
			<p>There are no payments!</p>
		</div>
	</div>
@stop