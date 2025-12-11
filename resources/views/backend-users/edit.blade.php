@extends('nodes.backend::layouts.base')

@section('breadcrumbs')
    <li>
        <a href="{{ route('nodes.backend.users', ['page' => 1]) }}">Backend users</a>
    </li>
    <li class="active">Edit backend user</li>
@endsection

@section('page-header-top')
    <h3>
        @if (!empty($user))
            Edit backend user
        @else
            Create backend user
        @endif
    </h3>
@endsection

@section('content')
        @if (!empty($user))
            {{ html()->modelFrom('patch')
                ->route('nodes.backend.users.update')
                ->attribute('enctype', 'multipart/form-data')
                ->open()
            }}
            <input type="hidden" name="id" value="{{ $user->id }}">
        @else
            {{ html()->form()
                ->route('nodes.backend.users.store')
                ->attribute('enctype', 'multipart/form-data')
                ->open()
            }}
        @endif

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <h4 class="margin-top">User details</h4>
                <hr/>
                <div class="margin-vertical-sm">
                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <div class="@if($errors->has('name')) has-error @endif}}">
                            {{ html()->text('name')
                                ->class('form-control')
                            }}
                        </div>
                    </div>

                    {{-- E-mail --}}
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <div class="@if($errors->has('email')) has-error @endif}}">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->attribute('autocomplete', config('nodes.backend.general.disable_autocomplete', false) ? 'on' : 'off')
                            }}
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="form-group">
                        <label for="user_role">Role</label>
                        @if($errors->has('user_role'))
                            {{ html()->select('user_role', $roles, !empty($user) ? $user->user_role : $roleDefault)
                                ->class('form-control has-error')
                            }}
                        @else
                            {{ html()->select('user_role', $roles, !empty($user) ? $user->user_role : $roleDefault)
                                ->class('form-control')
                            }}
                        @endif
                    </div>
                    @if(empty($user))
                        <div class="form-group">
                            <input name="send_mail" value="false" type="hidden">
                            <label>
                                {{ html()->checkbox('send_mail') }} Send email with information
                            </label>
                        </div>
                    @endif
                </div>
                <br>
                @can('backend-edit-backend-user', !empty($user) ? $user : null)
                <h4 class="margin-top">
                    @if (!empty($user))
                        Change password
                    @else
                        Choose password <small class="text-gray-dark">(leave empty for random) / </small>
                    @endif
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
                </h4>
                <hr/>
                <div class="margin-top">
                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="@if($errors->has('password')) has-error @endif}}">
                            {{ html()->password('password')
                                ->class('form-control')
                            }}
                        </div>
                    </div>

                    {{-- Password confirm --}}
                    <div class="form-group">
                        <label for="password_confirmation">Repeat password</label>
                        <div class="@if($errors->has('password')) has-error @endif}}">
                            {{ html()->password('password_confirmation')
                                ->class('form-control')
                            }}
                        </div>
                    </div>

                    {{-- Force user to reset pw on next login --}}
                    @can('backend-admin')
                    <div class="form-group">
                        <input name="should_reset_password" value="false" type="hidden">

                        <label class="@if($errors->has('change_password')) has-error @endif}}">
                            {{ html()->checkbox('change_password', true, empty($user) ? true : $user->change_password) }} Reset password on login
                        </label>

                    </div>
                    @endcan
                </div>
                @endcan
            </div>
            <div class="col-xs-12 col-md-6">
                {{-- Image --}}
                <h4 class="margin-top">Image</h4>
                <hr>
                <div class="margin-vertical-sm">
                    <div class="form-group">
                        <label for="image">Upload image</label>
                        <div class="@if ($errors->has('image')) has-error @endif">
                            {{ html()->file('image')
                                ->class('form-control')
                            }}
                        </div>
                    </div>
                    @if (!empty($user) && !empty($user->getImageUrl()))
                        <div class="form-group">
                            <img class="img-thumbnail" src="{{ $user->getImageUrl(250, 250) }}" alt="Image of {{ $user->name }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <hr>
                @if (!empty($user))
                    <input type="submit" class="btn btn-primary form-control" value="Update backend user">
                @else
                    <input type="submit" class="btn btn-primary form-control" value="Create backend user">
                @endif
            </div>
        </div>
        {{ html()->form()->close() }}
@endsection
