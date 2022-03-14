<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ThirdPartyAuthController extends Controller
{

    /**
     * Redirects to the google login section
     * 
     * @return \Laravel\Socialite\Facades\Socialite;
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handles the google call back and creates a user if succesful.
     * If already created, logs it in.
     * 
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);

        return redirect()->route('dashboard');
    }

    /**
     * Redirects to the gitHub login section
     * 
     * @return \Laravel\Socialite\Facades\Socialite;
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

     /**
     * Handles the github call back and creates a user if succesful.
     * If already created, logs it in.
     * 
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $this->_registerOrLoginUser($user);

        return redirect()->route('dashboard');
    }

    /**
     * Redirects to the facebook login section
     * 
     * @return \Laravel\Socialite\Facades\Socialite;
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handles the google call back and creates a user if succesful.
     * If already created, logs it in.
     * 
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);

        return redirect()->route('dashboard');
    }

    /**
     * Checks whether a user is in the database.
     * If it is = logs the user in.
     * If it isn't = it creates an account and logs it in.
     * 
     * @param User  $userToBeChecked
     * @return \Illuminate\Support\Facades\Auth
     */
    protected function _registerOrLoginUser($userToBeChecked)
    {
        $user = User::where('email', '=', $userToBeChecked->email)->first();

        if (!$user) {
            $user = new User();
            $user->name = $userToBeChecked->name;
            $user->email = $userToBeChecked->email;
            $user->avatar = $userToBeChecked->avatar;
            $user->save();
        }
        Auth::login($user);
    }
}
