<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|max:50',
            'password' => 'required|min:6',
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->password_hash = Hash::make($request->password);
        $user->save();

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        foreach ($user->reservations as $reservation) {
            $book = $reservation->book;
            $book->available = true;
            $book->save();
        }

        $user->delete();

        return response()->json(null, 204);
    }
}