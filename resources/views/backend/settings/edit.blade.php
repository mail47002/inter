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
        <div class="row">
            <div class="col-md-8">
                <h4>Credits</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Credits per registration</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[registration_credits]" value="{{ $settings['registration_credits'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Credits course</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[one_credits]" value="{{ $settings['one_credits']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">For campaigns</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[campaigns_credits]" value="{{ $settings['campaigns_credits'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">For featured</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="settings[featured_credits]" value="{{ $settings['featured_credits'] }}">
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection