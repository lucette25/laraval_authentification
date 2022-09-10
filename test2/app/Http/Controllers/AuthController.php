<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'max:100',
            'type' => 'required|max:1',
            'city' => 'max:1000',
            'state' => 'max:1000',//quartier
            'postal_code' => 'max:1000',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'type' => $request->type,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
/*
 the me method returns the user profile or 
 in this case the user object
*/
    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    ///////////////////////////////////////////////////////////
    public function index()
    {

        // get all users
        if(Auth::user()->type=='A'){
            $users = User::all();
        }else{
            $users = User::all()->where('type', 'B');
        }
        //$users = User::all();
        //return json data on json format
        return response()->json($users);    
        
    }

        public function show( $id)
        {
            $user = User::all()->where('id', $id)->first();
            
            // On retourne les informations de l'utilisateur en JSON
         if (is_null($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'user not found.',
            
            ]);
            }

        return response()->json($user);
        }

    

public function update(Request $request, User $user)
    {
        //if( Auth::user()!=$user && ())
           // return "yes";

          // La validation de données
    $this->validate($request, [
        'first_name' => 'max:100',
        'last_name' => 'max:100',
        'type' => 'max:1',
        'city' => 'max:1000',
        'state' => 'max:1000',//quartier
        'postal_code' => 'max:1000',
        'email' => 'email|unique:users',
        'password' => 'min:8'
    ]);

    // change user information
    $user->update([
        'first_name' => $request->first_name? $request->first_name  : $user->first_name,
        'last_name' => $request->last_name ? $request->last_name  : $user->last_name,
        'type' => $request->type ? $request->type  : $user->type,
        'city' => $request->city ? $request->city  : $user->city,
        'state' => $request->state ? $request->state  : $user->state,
        'postal_code' => $request->postal_code ? $request->postal_code  : $user->postal_code,
        'email' => $request->email ? $request->email  : $user->email,
        'password' => Hash::make($user->password),
    ]);

    // On retourne la réponse JSON
    return response()->json([
        'status' => 'success',
        'message' => 'User updated successfully',
        'user' => $user,
        
    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // On supprime l'utilisateur
    $user->delete();

    // On retourne la réponse JSON
    return response()->json([
        'status' => 'success',
        'message' => 'User delated successfully'
        
    ]);    }

}