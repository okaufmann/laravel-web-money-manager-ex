@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-push-8 ">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('Add Transaction')</div>

                <div class="panel-body">
                    <form action="{{url('transactions/store')}}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        @include('transactions.partials.form')

                        <div class="form-group label-static is-empty">
                            <button type="button" class="btn btn-primary btn-raised">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-md-pull-4">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('Transactions')</div>

                <div class="panel-body">
                    @include('transactions.partials.list')
                </div>
            </div>
        </div>
    </div>
@endsection
