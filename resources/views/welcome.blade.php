<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
    
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 bg-blue-400">
            <div class = "grid grid-cols-2 gap-10 items-baseline">
                <div class="mt-72"><img src="{{ asset('images/download-removebg-preview.png') }}"></div>
                <div class = "mt-80 sm:content-center"><h1 class = "animate-bounce" style="font-size: 22px">Welcome to Samir's To Do Application</h1></div>
            </div>
            @if (Route::has('login'))
                <div class="mt-30 absolute top-6 right-15 px-10 py-7 sm:block">
                    @auth 
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-lg text-gray-700 dark:text-gray-500 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-lg text-gray-700 dark:text-gray-500 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        @include('components/footer')
    </body>
</html>
