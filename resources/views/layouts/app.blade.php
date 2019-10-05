<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KnowledgeBase') }}</title>

    <style>
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-tint">
    <div>
        @guest
        <nav>
            <div class="flex justify-between px-4 pt-2 text-lg">
                <a href="{{ url('/') }}">
                    {{ config('app.name', 'Knowledge base') }}
                </a>

                <div>
                    <ul class="flex flex-end">
                        <li class="pr-4">
                            <a href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @else
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @endguest

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>