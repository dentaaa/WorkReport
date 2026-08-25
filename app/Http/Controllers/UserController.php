<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // public function index()
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     if (!$user->isAdmin()) {
    //         abort(403);
    //     }

    //     $users = User::all();
    //     return view('user.index', compact('users'));
    // }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $query = User::query();

        if ($request->filled('search')) {

            $keyword = trim($request->search);

            $query->where(function ($q) use ($keyword) {

                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('role', 'like', "%{$keyword}%");
            });
        }

        $users = $query
            ->orderBy('name')
            ->get();

        return view('user.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:Mekanik,Foreman,Supervisor,Dept. Head,Trainer,Admin'
        ]);

        /** @var \App\Models\User $targetUser */
        $targetUser = User::findOrFail($id);

        $targetUser->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role berhasil diupdate');
    }
}
