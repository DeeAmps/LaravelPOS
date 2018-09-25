@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center" style="padding-top:10%">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info" style="color:#ffffff">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group mb-3 mt-4">
                            {{--  <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Username') }}</label>  --}}

                            <div class="col-md-12">
                                <input placeholder="USERNAME" id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {{--  <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>  --}}

                            <div class="col-md-12">
                                <input placeholder="PASSWORD" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--  <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>  --}}

                        <div class="form-group mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-block">
                                    {{ __('Login') }}
                                </button>

                                {{--  <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>  --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
