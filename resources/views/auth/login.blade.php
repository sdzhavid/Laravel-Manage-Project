<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{ asset('images/SS-Dzhavid-logos_black.png') }}" alt="" style="height: 250px" style="width: 150px">
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me and Forgotten password -->
            <div class="block mt-4 grid grid-flow-col auto-cols-max">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
                <label for="forgot_password" class="ml-40">
                    <a href="{{ route('password.request') }}" class="underline text-blue-300 text-right">
                        Forgot Password?
                    </a>
            </div>

            <div class="flex items-center justify-end mt-1">

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
            <div class="flex items-center justify-end mt-2">

                <p>Don't have an account?</p>

                <x-button class="ml-4">
                    <a href="/register">Register</a>
                </x-button>
            </div>

            <div class="flex flex-col justify-center items-center mt-3">
                <p class="text-xl font-bold">Or Login With</p>
            </div>
        </form>

        <div class="flex justify-center items-center mt-6">
            <a href="{{ route('login.facebook') }}">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded mr-5">
                    Facebook
                </button>
            </a>
            <a href="{{ route('login.google') }}">
                <button class="bg-white hover:bg-blue-100 font-bold py-2 px-4 border border-blue-700 rounded inline mr-5">
                    <p class="inline text-blue-500">G</p>
                    <p class="inline text-red-500">O</p>
                    <p class="inline text-yellow-500">O</p>
                    <p class="inline text-blue-500">G</p>
                    <p class="inline text-green-500">L</p>
                    <p class="inline text-red-500">E</p>
                </button>
            </a>

            <a href="{{ route('login.github') }}">
                <button class="bg-black hover:bg-black-700 text-white font-bold py-2 px-4 border border-blue-700 rounded mr-5">
                    Github
                </button>
            </a>
        </div>
    </x-auth-card>
</x-guest-layout>



<script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '{your-app-id}',
        cookie     : true,
        xfbml      : true,
        version    : '{api-version}'
      });
        
      FB.AppEvents.logPageView();   
        
    };
  
    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "https://connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));
  </script>