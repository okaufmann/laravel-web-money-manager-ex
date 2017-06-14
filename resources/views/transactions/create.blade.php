@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2>@lang('Add new Transaction')</h2>

        <form action="{{url('transactions/store')}}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}

            @include('transactions.partials.form')

            <div class="form-group label-static is-empty">
                <button type="button" class="btn btn-primary btn-raised">@lang('Add')</button>
            </div>
        </form>
    </div>
@stop