@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-push-8 ">
            <div class="panel  @if(isset($transaction)) panel-info @else panel-default @endif">
                @isset($transaction)
                    <div class="panel-heading">@lang('Edit Transaction')</div>
                @endisset

                @empty($transaction)
                    <div class="panel-heading">@lang('Add Transaction')</div>
                @endempty

                <div class="panel-body">
                    <form action="{{url('transactions/' . ($transaction ? $transaction->id : null))}}" method="POST"
                          enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        @isset($transaction)
                            <input type="hidden" name="_method" value="PUT">
                        @endisset
                        @include('transactions.partials.form', compact('transaction'))


                        <div class="form-group label-static is-empty">
                            @empty($transaction)
                                <button type="submit" class="btn btn-primary btn-raised">
                                    <i class="fa fa-plus"></i> @lang('Add')</button>
                            @endempty

                            @isset($transaction)
                                <button type="submit" class="btn btn-primary btn-raised">
                                    <i class="fa fa-floppy-o"></i> @lang('Update')</button>
                                <a href="/" class="btn btn-warning">
                                    <i class="fa fa-times"></i> @lang('Cancel')</a>
                            @endisset
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
