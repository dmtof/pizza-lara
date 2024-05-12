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
        $user = User::findOrFail($id);

        return json_encode($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        foreach ($request->all() as $key => $value) {
            if ($key === 'password') {
                $value = Hash::make($value);
            }
            $user->$key = $value;
        }

        $user->save();

        return json_encode($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $users = User::all();

        return json_encode($users);
    }
}
