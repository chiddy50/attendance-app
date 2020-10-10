<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
      // dd([$request->name, $request->email, $request->password, $request->admin, $request->user]);
      
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);
      if (isset($request->user)) {
        $user->roles()->attach($request->user);  
      }
      if (isset($request->admin)) {
        $user->roles()->attach($request->admin);  
      }

      $token = auth()->login($user);
      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      // dd([$request->name, $request->password]);
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
      
      // try {
      //   if (!$token = auth()->attempt($credentials)) {
      //     return response()->json(['error' => 'Unauthorized'], 401);
      //   }
      // } catch (JWTException $e) {
      //     return response()->json(['error' => 'could_not_create_token'], 500);
      // }
      
      return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request)
    {
      return response()->json(auth()->user());
    }

    public function logout()
    {
      auth()->logout();
      return response()->json(['message'=>'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => auth()->user(),
        'roles' => Auth::user()->roles->pluck('name')
      ]);
    }

    public function allowed(){

    }

    public function restricted(){
      return response()->json(['message' => "Welcome admin"]);
    }
}
