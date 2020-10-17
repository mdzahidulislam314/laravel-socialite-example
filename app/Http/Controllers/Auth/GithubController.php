<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Socialite;

class GithubController extends Controller
{

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
       $socialUser = Socialite::driver('github')->stateless()->user();

        $findOldUser = User::where('email',$socialUser->email)->first();

        if ($findOldUser){

            Auth::login($findOldUser);
            return redirect('/home');

        }else{

            $user = new User();
            $user->name = $socialUser->nickname;
            $user->email = $socialUser->email;
            $user->github_id = $socialUser->id;
            $user->avatar = $socialUser->avatar;
            $user->password = Hash::make('123456');
            $user->save();

            Auth::login($user);

            return redirect('/home');
        }
    }
}
