@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <label for="name"> {{ __('Name') }}</label>

    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

    @error('name')
    <span role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    <label for="email">{{ __('E-Mail Address') }}</label>

    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

    @error('email')
    <span role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
        required autocomplete="new-password">

    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
        autocomplete="new-password">

    <button type="submit" class="btn btn-primary">
        {{ __('Register') }}
    </button>
</form>
@endsection