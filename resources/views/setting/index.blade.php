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

                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <strong>@lang('Types and Status')</strong> @lang('Please align the values for status and type with them in your MMEX Client (New Transaction -> Dropdowns Status and Type).')
                            </div>

                            <p class="lang">@lang('Status')</p>
                            <table class="table">
                                @foreach($status as $s)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="status_ids[]" value="{{$s->id}}">
                                            <input class="form-control" value="{{$s->name}}" name="status_values[]">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p class="lang">@lang('Types')</p>
                            <table class="table">
                                @foreach($types as $s)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="type_ids[]" value="{{$s->id}}">
                                            <input class="form-control" value="{{$s->name}}" name="type_values[]">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>


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
                        <dt>App Version</dt>
                        <dd>{{$version}}</dd>
                        <dt>API Version</dt>
                        <dd>{{\App\Services\Mmex\MmexConstants::$api_version}}</dd>
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