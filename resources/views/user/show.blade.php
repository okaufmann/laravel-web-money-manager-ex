@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12 ">
            <h1>{{$user->name}}</h1>
            <div class="row">
                <div class="col-md-8 ">
                    <div class="panel panel-default">
                        <div class="panel-heading">@lang('mmex.user-infos')</div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label>@lang('mmex.email')</label>
                                <p class="form-control-static">{{$user->email}}</p>
                            </div>

                            <div class="form-group">
                                <label>@lang('mmex.api-token')</label>
                                <p class="form-control-static">{{$user->api_token}}</p>
                            </div>

                            <div class="form-group is-focused">
                                <label>@lang('mmex.webpapp-guid')</label>
                                <p class="form-control-static">{{$user->mmex_guid}}</p>
                                <div class="help-block">
                                    <a href="{{url('settings')}}">@lang('mmex.change-in-settings')</a></div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">@lang('mmex.update-password')</div>

                        <div class="panel-body">
                            <form action="{{url('/user/password')}}" method="post" role="form">
                                {{csrf_field()}}

                                @include('partials.form-errors')
                                @include('partials.form-status')

                                <div class="form-group">
                                    <label>@lang('mmex.current-password')</label>
                                    <input type="password" name="current_password" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <label>@lang('mmex.new-password')</label>
                                    <input type="password" name="new_password" class="form-control"/>
                                </div>

                                <div class="form-group is-focused">
                                    <label>@lang('mmex.repeat-new-password')</label>
                                    <input type="password" name="new_repeat_password" class="form-control"/>
                                </div>

                                <button type="submit" class="btn btn-primary btn-raised"><i
                                            class="fa fa-floppy-o"></i> @lang('mmex.save')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection