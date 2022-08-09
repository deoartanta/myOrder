@extends('layouts.auth.app-auth')
@section('title','Login')

@section('content')
<div class="container">
    <div class="card shadow px-3" style="min-width: 35rem;border-radius: 15px;">
        <div class="head-custom text-center">
            <div class="img-profile mt-3">
                <img src="{{ asset('img\avatar\avatar-1.png') }}" class="img-thumbnail rounded-circle" width="20%">
            </div>
            <h4 class="text-title mt-2 mb-0">MY ORDER</h4>
            <small class="text-secondary">login to start your session</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row my-3">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row my-3">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <div class="text-left" style="font-size: 13px;">
                            D<span class="text-lowercase">ON'T HAVE AN ACCOUNT?</span>
                            <a class="span" href="{{ route('register') }}">Register Now</a>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            {{ __('Login') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
