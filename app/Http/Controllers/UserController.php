<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return json_encode($users);
    }

    public function show($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json('Unauthorized', 401);
        }

        if ($user->role === User::ROLE_ADMIN) {
            $userShow = User::findOrFail($id);
            return response()->json($userShow);
        }

        if ($user->id !== intval($id)) {
            return response()->json('Page not found', 404);
        }

        return response()->json('Error', 404);
    }

    public function update(Request $request, $id)
    {
        if (!auth('sanctum')->check()) {
            return response()->json('Unauthorized', 401);
        }

        if (auth('sanctum')->user()->role !== User::ROLE_ADMIN) {
            return response()->json('You are not an admin', 401);
        }

        $user = User::findOrFail($id);

        foreach ($request->all() as $key => $value) {
            if ($key === 'password') {
                $value = Hash::make($value);
            }
            $user->$key = $value;
        }

        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $users = User::all();

        return response()->json($users);
    }
}
