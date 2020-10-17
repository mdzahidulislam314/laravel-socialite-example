<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Socialite;
use App\User;

class UserController extends Controller
{
    
     public function redirectToProvider()
   {
      return Socialite::with('github')->redirect();
   }
public function handleProviderCallback()
   {
    $user = Socialite::with('github')->stateless()->user();
    $result = User::create([
         'email' => $user->email ,
         'name' => $user->nickname,
         'github_user_token' => $user->uid
   ]);
 		if (!$result)
		   {
		      return redirect()->back();
		   }
      return 'You are authenticated!!'; //Or redirect to dashboard
   }
}
