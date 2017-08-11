@extends('backend.layouts.default')

@section('title')
    Admin Panel - Settings
@endsection

@section('content')
    <div class="page-header">
        <div class="pull-right">
            <button class="btn btn-primary" type="submit" form="form-settings"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
        </div>
        <h1>Settings</h1>
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('status') }}
        </div>
    @endif
    {!! Form::open(['route' => 'settings.update', 'method' => 'put', 'id' => 'form-settings', 'class' => 'form-horizontal']) !!}
        <h4>Credits</h4>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="col-md-3 control-label">Per registration</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[registration_credits]" value="{{ config('settings.registration_credits') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">For campaigns</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[campaigns_credits]" value="{{ config('settings.campaigns_credits') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">For featured</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[featured_credits]" value="{{ config('settings.featured_credits') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    {!! Form::close() !!}
@endsection