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

                            @include('partials.form-errors')

                            @if (session('status'))
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                    </button>
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-md-3">@lang('Auth GUID')</label>
                                <div class="col-md-9">
                                    <input name="mmex_guid" class="form-control" value="{{old('authGuid',$authGuid)}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">@lang('UI Language')</label>
                                <div class="col-md-9">
                                    <select name="user_locale" class="common-dropdown-list">
                                        @if(!$userLocale)
                                            <option>@lang('Please choose...')</option>
                                        @endif
                                        @foreach(config('money-manager.locales') as $code => $name)
                                            <option value="{{$code}}"
                                                    @if($userLocale == $code) selected @endif>@lang($name)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group label-static is-empty">
                                <button type="submit" class="btn btn-primary btn-raised">@lang('Save')</button>
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