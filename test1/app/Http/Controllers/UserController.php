<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        // get all users
        $users = User::all();
        //return json data on json format
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
     // data validation
    //$this->
    /*$this->validate($request, [
        'first_name' => 'required|max:100',
        'last_name' => 'required|max:100',//'max:100',
        'type' => 'required|max:1',
        'city' => 'required|max:100',//'max:1000',
        'state' => 'required|max:100',//'max:1000',//quartier
        'postal_code' => 'required|max:100',//'max:1000',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8'
    ]);
 return "hhhh";*/
    // Create new user
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'type' => $request->type,
        'city' => $request->city,
        'state' => $request->state,
        'postal_code' => $request->postal_code,
        'email' => $request->email,
        'password' => bcrypt($request->password)
    ]);

    // On return the new user 

    return response()->json($user, 201);
    


    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
    //  return user information JSON
    return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
          // La validation de données
    $this->validate($request, [
        'first_name' => 'required|max:100',
        'last_name' => 'max:100',
        'type' => 'required|max:1',
        'city' => 'max:1000',
        'state' => 'max:1000',//quartier
        'postal_code' => 'max:1000',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8'
    ]);

    // change user information
    $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'type' => $request->type,
        'city' => $request->city,
        'state' => $request->state,
        'postal_code' => $request->postal_code,
        'email' => $request->email,
        "password" => bcrypt($request->password)
    ]);

    // On retourne la réponse JSON
    return response()->json();
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
    return response()->json();
    }
}
