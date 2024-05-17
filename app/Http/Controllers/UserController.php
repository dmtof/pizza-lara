<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        $user = auth('sanctum')->user();

        if ($user->role === User::ROLE_ADMIN) {
            $userShow = User::findOrFail($id);
            return [
                $userShow
            ];
        }

        if ($user->id !== intval($id)) {
            return response()->json('Page not found', 404);
        }

        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth('sanctum')->user()->role !== User::ROLE_ADMIN && $user->id !== auth('sanctum')->user()->id) {
            return response()->json('Error', 500);
        }

        foreach ($request->all() as $key => $value) {
            if ($key === 'password') {
                $value = Hash::make($value);
            }
            $user->$key = $value;
        }

        $user->save();

        return $user;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return User::all();
    }
}
