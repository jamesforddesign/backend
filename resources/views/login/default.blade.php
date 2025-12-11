@extends('nodes.backend::base')

@section('layout')
    @if(config('nodes.backend.manager.active', true))
        <a href="{{ route('nodes.backend.login.sso') }}" id="alternative-login" class="sso">
            <span class="sr-only">Nodes SSO</span>
        </a>
    @endif

    <div class="layout vertical fit page-login">
        <div id="login" class="panel panel-default">
            <div class="panel-body">

                <div class="panel-heading">
                    <div class="logo">
                        @include('nodes.backend::partials.elements.logo-svg')
                    </div>
                    <h3 class="panel-title margin-bottom">Login</h3>
                </div>

                @include('nodes.backend::partials.alerts')

                {{ html()->form()
                    ->route('nodes.backend.login.authenticate')
                    ->open()
                }}
                    <div class="form-group action-wrapper">
                        {{  html()->label('E-mail address', 'email')
                            ->class('sr-only')
                        }}
                        {{ html()->email('email', Request::get('email') ? Request::get('email') : Session::get('email'))
                            ->class('form-control')
                            ->attributes(['placeholder' => 'E-mail address', 'autocomplete' => config('nodes.backend.general.disable_autocomplete', false) ? 'on' : 'off'])
                        }}
                        <span class="action-wrap-action action-wrap-right">
                            <i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="form-group action-wrapper">
                        {{ html()->label('Password', 'password')
                            ->class('sr-only')
                        }}
                        {{ html()->password('password')
                            ->class('form-control')
                            ->attribute('placeholder', 'Password')
                        }}
                        <span class="action-wrap-action action-wrap-right">
                            <i class="fa fa-lock fa-lg" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="form-group clearfix">
                        <div class="checkbox pull-left">
                            <label for="remember">
                                {{ html()->checkbox('remember') }}
                                Remember me
                            </label>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('nodes.backend.reset-password.form') }}">Forgot password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ html()->submit('Login')
                            ->class('btn btn-primary form-control')
                        }}
                    </div>
                {{ html()->form()->close() }}

            </div>
        </div>
    </div>

@stop
