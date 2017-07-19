@extends('backend.layouts.default')

@section('content')
<div class="col-md-10">
	<div class="content-box-large">
		<div class="panel-heading">
            <div class="panel-title">Pages</div>
			<div class="panel-title"><a href="{{route('pages.create')}}" class="btn btn-success"><i class="glyphicon glyphicon-certificate"></i> New page</a></div>
		</div>
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
				<thead>
					<tr>
						<th>Id</th>
						<th>Title</th>
						<th>Slug</th>
						<th>Date</th>
						<th>Published</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach($pages as $page)
					<tr class="odd gradeX">
						<td>{{$page->id}}</td>
						<td>{{$page->title}}</td>
						<td>{{$page->slug}}</td>
						<td class="center">{{$page->created_at}}</td>
						<td class="center">
							{!! ($page->published == 1) ? '<i class="glyphicon glyphicon-hand-up">' : '<i class="glyphicon glyphicon-hand-down">' !!}
						</td>
						<td class="center"> <a href="{{route('pages.edit', ['id' => $page->id])}}" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> Edit</a> <a href="{{ route('pages.destroy', ['id' => $page->id]) }}" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Delete</a> </td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop