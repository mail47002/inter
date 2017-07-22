@extends('backend.layouts.default')

@section('title')
	Admin Panel - {{ $user->username }} - Show
@endsection

@section('content')
<div class="col-md-10">
	<div class="content-box-large">
		<div class="panel-heading">
      <div class="panel-title">Show - {{$user->username}}</div>
        <div class="panel-options">
          <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
          <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
        </div>
      </div>
		<div class="row">
			<div class="col-sm-6">
				<p class="lead">General data</p>
				<div class="form-group ">
							<label for="email">E-mail:</label>
							<label>{{$user->email}}</label>
				</div>
				<div class="form-group ">
							<label for="email">Username: </label>
							<label>{{$user->username}}</label>
				</div>
			</div>
		</div>
		<div class="row">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
				<thead>
					<tr>
						<th>Id</th>
						<th>Title</th>
						<th>Created</th>
						<th class="text-center">Active</th>
						<th></th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach($campaigns as $campaign)
					<tr class="odd gradeX">
						<td>{{ $campaign->id }}</td>
						<td>{{ $campaign->title }}</td>
						<td>{{ $campaign->created_at }}</td>
						<td class="text-center">
							{!! $user->active == 1 ? '<span class="status status-success"></span>' : '<span class="status"></span>' !!}
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
</div>
@stop