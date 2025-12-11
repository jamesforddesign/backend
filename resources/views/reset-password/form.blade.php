@extends('nodes.backend::reset-password.reset-template')

@section('feedback-header')
    <h3 class="panel-title">Forgot password</h3>
@endsection

@section('feedback-message')
    {{ html()->form()->route('nodes.backend.reset-password.token')->open() }}
    <div class="form-group action-wrapper">
        {{ html()->label('E-mail address', 'email')
            ->class('sr-only')
        }}
        {{ html()->email('email', Session::get('email'))
            ->class('form-control')
            ->attributes(['placeholder' => 'E-mail address', 'autocomplete' => config('nodes.backend.general.disable_autocomplete', false) ? 'on' : 'off'])
        }}
        <span class="action-wrap-action action-wrap-right">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
        </span>
    </div>
    <div class="form-group">
        {{ html()->submit('Reset my password')
            ->class('btn btn-primary form-control')
        }}
    </div>
    {{ html()->form()->close() }}
@endsection
