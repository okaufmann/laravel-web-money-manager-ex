@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                {{ __('Verify Your Email Address') }}
            </div>
            <div class="panel-body">
                @if (session('resent'))
                    <div class="bg-green-lightest border border-green-light text-green-dark text-sm px-4 py-3 rounded mb-4">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}" class="no-underline hover:underline text-teal-darker">{{ __('click here to request another') }}</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
