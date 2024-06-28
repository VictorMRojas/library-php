<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve all users
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'username' => 'required|unique:users|max:50',
            'password' => 'required|min:6',
        ]);

        // Create a new user instance
        $user = new User;
        $user->username = $request->username;
        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Return JSON response with created user and 201 status code
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find a user by ID or throw 404 error if not found
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);
        
        // Update user with new data from request
        $user->update($request->all());

        // Return JSON response with updated user and 200 status code
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Set related books to available
        foreach ($user->reservations as $reservation) {
            $book = $reservation->book;
            $book->available = true;
            $book->save();
        }

        // Delete the user
        $user->delete();

        // Return null response with 204 status code
        return response()->json(null, 204);
    }
}
