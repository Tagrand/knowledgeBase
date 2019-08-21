@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="flex pt-12 pb-4">
        <label for="email" class="px-4">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

        @error('email')
        <span role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>


    <div class="flex pb-4">
        <label for="password" class="px-4">Password:</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">

        @error('password')
        <span role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="pl-4 pb-4 flex items-centre">
        <input class="mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

        <label for="remember">Remember me</label>
    </div>

    <button type="submit" class="px-4">Login</button>
    <a href="{{ route('password.request') }}">Forgot your password</a>
</form>
@endsection