<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id) {
            return abort(403, 'Unauthorized action.');
        }

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'notification' => 'required|boolean',
            'objective'    => 'required|numeric',
            'alert'        => 'required|numeric',
        ]);

        $user->update([
            'name'                    => $request->get('name'),
            'email'                   => $request->get('email'),
            'notification'            => $request->get('notification'),
            'objective'               => $request->get('objective'),
            'alert'                   => $request->get('alert'),
        ]);

        if ($request->get('password') !== null) {
            $request->validate([
                'password' => 'min:6',
            ]);

            $user->update([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        return redirect()->route('user.edit', $user)->with('success', 'Saved.');
    }
}
