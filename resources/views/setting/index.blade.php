@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>@lang('Settings')</h2>

            <div class="col-md-8 ">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('User Settings')</div>

                    <div class="panel-body">
                        <form action="{{url('settings')}}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            @if (session('status'))
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                    </button>
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="alert alert-info">

                                <strong>@lang('Types and Status')</strong><br/> @lang('Please adopt the values for status and type with values from your MMEX Client (New Transaction -> Dropdowns Status and Type).')
                            </div>

                            <p class="lang">@lang('Status')</p>
                            @foreach($status as $s)
                                <div class="form-group label-static is-empty">
                                    <input type="hidden" name="status_ids[]" value="{{$s->id}}">
                                    <input class="form-control" value="{{$s->name}}" name="status_values[]">
                                </div>

                            @endforeach

                            <p class="lang">@lang('Types')</p>
                            @foreach($types as $s)
                                <div class="form-group label-static is-empty">
                                    <input type="hidden" name="type_ids[]" value="{{$s->id}}">
                                    <input class="form-control" value="{{$s->name}}" name="type_values[]">
                                </div>

                            @endforeach

                            <div class="form-group label-static is-empty">
                                <button type="submit" class="btn btn-primary btn-raised">@lang('Save')</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">@lang('System Settings')</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group is-focused">
                                <label class="control-label col-md-3">@lang('Auth GUID')</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{$authGuid}}</p>
                                    <p class="help-block ">
                                        @lang('Set the correct GUID from your MMEX Client in the .env file in the projects root.')
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>@lang('Common')</h4>
                <div class="settings-common">

                    <dl class="dl-horizontal">
                        <dt>@lang('App Version')</dt>
                        <dd>{{$version}}</dd>
                        <dt>@lang('API Version')</dt>
                        <dd>{{$apiVersion}}</dd>
                    </dl>
                </div>

                <h4>@lang('Used Packages')</h4>

                <ul class=list-unstyled>
                    @foreach($packages as $package)
                        <li><a href="https://packagist.org/packages/{{$package["name"]}}"
                               target="_blank">{{$package["name"]}}{{'@'}}{{ $package["version"] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop