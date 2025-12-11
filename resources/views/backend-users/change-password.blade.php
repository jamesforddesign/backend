@extends('nodes.backend::layouts.base')

@section('breadcrumbs')
    <li>
        <a href="{{ route('nodes.backend.users', ['page' => 1]) }}">Backend users</a>
    </li>
    <li class="active">Update password</li>
@endsection

@section('page-header-top')
    <h3>
        Update password
        <br>
        <small class="text-gray-dark">The password requires three of the following five categories and be min 8 chars:</small>
        <br>
        <small class="text-gray-dark">- English uppercase characters (A – Z)</small>
        <br>
        <small class="text-gray-dark">- English lowercase characters (a – z)</small>
        <br>
        <small class="text-gray-dark">- Base 10 digits (0 – 9)</small>
        <br>
        <small class="text-gray-dark">- Non-alphanumeric (For example: !, $, #, or %)</small>
        <br>
        <small class="text-gray-dark">- Unicode characters</small>
    </h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-6 margin-top">
            {{ html()->modelForm(backend_user(), 'patch')
                ->route('nodes.backend.users.update-password')
                ->open()
            }}
            <input type="hidden" name="id" value="{{ backend_user()->id }}">

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password</label>
                <div class="@if(validation_key_failed('password')) has-error @endif}}">
                    {{ html()->password('password')
                        ->class('form-control')
                    }}
                </div>
            </div>
            {{-- Password confirm --}}
            <div class="form-group">
                <label for="password_confirmation">Repeat password</label>
                <div class="@if(validation_key_failed('password')) has-error @endif}}">
                    {{ html()->password('password_confirmation')
                        ->class('form-control')
                    }}
                </div>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary form-control" value="Save">
            </div>
            {{ html()->form()->close(s) }}
        </div>
    </div>

@endsection
