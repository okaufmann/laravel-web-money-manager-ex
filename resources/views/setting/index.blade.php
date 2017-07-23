@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>@lang('mmex.settings')</h2>

            <div class="row">
                <div class="col-md-8 ">
                    <div class="panel panel-default">
                        <div class="panel-heading">@lang('mmex.user-settings')</div>

                        <div class="panel-body">
                            <form action="{{url('settings')}}" method="POST" enctype="multipart/form-data">
                                {!! csrf_field() !!}

                                @include('partials.form-errors')

                                @include('partials.form-status')

                                <div class="form-group">
                                    <label class="control-label col-md-3">@lang('mmex.webpapp-guid')</label>
                                    <div class="col-md-9">
                                        <input name="mmex_guid" class="form-control"
                                               value="{{old('authGuid',$authGuid)}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">@lang('mmex.ui-language')</label>
                                    <div class="col-md-9">
                                        <select name="user_locale" class="common-dropdown-list">
                                            @if(!$userLocale)
                                                <option>@lang('mmex.please-choose')</option>
                                            @endif
                                            @foreach(config('money-manager.locales') as $code => $name)
                                                <option value="{{$code}}"
                                                        @if($userLocale == $code) selected @endif>@lang($name)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">@lang('mmex.disable_status')</label>
                                    <div class="col-md-9">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="disable_status" value="true"
                                                       @if($disableStatus)checked @endif>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group label-static is-empty">
                                    <button type="submit" class="btn btn-primary btn-raised"><i
                                                class="fa fa-floppy-o"></i> @lang('mmex.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4>@lang('mmex.common')</h4>
                    <div class="settings-common">

                        <dl class="dl-horizontal">
                            <dt>@lang('mmex.app-version')</dt>
                            <dd>{{$version}}</dd>
                            <dt>@lang('mmex.api-version')</dt>
                            <dd>{{$apiVersion}}</dd>
                        </dl>
                    </div>

                    <h4>@lang('mmex.used-packages')</h4>

                    <ul class=list-unstyled>
                        @foreach($packages as $package)
                            <li><a href="https://packagist.org/packages/{{$package["name"]}}"
                                   target="_blank">{{$package["name"]}}{{'@'}}{{ $package["version"] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop