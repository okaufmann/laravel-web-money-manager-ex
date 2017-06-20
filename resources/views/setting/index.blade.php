@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>@lang('Settings')</h2>

            <div class="col-md-8 ">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('User Settings')</div>

                    <div class="panel-body">
                        <form action="{{url('transactions/store')}}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}


                            <div class="form-group label-static is-empty">
                                <button type="button" class="btn btn-primary btn-raised">@lang('Save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Used Packages</h4>
                <ul class="list-unsyled">
                @foreach($packages as $package)

                    <li>{{$package["name"]}}@{{ $package["version"] }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop