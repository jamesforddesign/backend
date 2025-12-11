@extends('nodes.backend::reset-password.reset-template')

@section('feedback-header')
    <h3 class="panel-title">Forgot password</h3>
@endsection

@section('feedback-message')
    <p class="description text-center">Enter the e-mail address of the user who's password you wish to reset. Here after enter the user's new password.</p>

    {{ html()->form()->route('nodes.backend.reset-password.change') }}
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group">
        {{ html()->label('E-mail address', 'email') }}
        {{ html()->email('email', Session::get('email'))
            ->class('form-control')
            ->attributes(['placeholder' => 'your@email.com', 'autocomplete' => config('nodes.backend.general.disable_autocomplete', false) ? 'on' : 'off'])
        }}
    </div>
    <div class="form-group">
        {{ html()->label('New password', 'password') }}
        {{ html()->password('password')
            ->class('form-control')
        }}
    </div>
    <div class="form-group">
        {{ html()->label('New password confirmation', 'password_confirmation') }}
        {{ html()->password('password_confirmation')
            ->class('form-control')
        }}
    </div>
    <div class="form-group">
        {{ html()->submit('Change password')
            ->class('btn btn-primary form-control')
        }}
    </div>
    {{ html()->form()->close() }}
@endsection