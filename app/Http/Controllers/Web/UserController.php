<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Pengguna';

        $users = User::with([
                'puskesmas.village.district.regency.province'
            ])
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('users', compact('title', 'users'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
        ]);

        $ids = collect($request->ids)
            ->map(function ($id) {
                try {
                    return decrypt($id);
                } catch (DecryptException $e) {
                    return null;
                }
            })
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return back()->with('error', 'Tidak ada pengguna yang dipilih.');
        }

        $deleted = User::query()
            ->whereIn('id', $ids)
            ->where('role', '!=', 'admin')
            ->whereKeyNot(Auth::id())
            ->delete();

        return back()->with(
            'success',
            "{$deleted} pengguna berhasil dihapus."
        );
    }

    // public function show($id)
    // {
    //     try {
    //         $id = decrypt($id);

    //         $title = 'Pengguna';

    //         $user = User::with([
    //             'puskesmas.village.district.regency.province'
    //         ])->findOrFail($id);

    //         return view('user_detail', compact('title', 'user'));
    //     } catch (DecryptException $e) {
    //         abort(404);
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $id = decrypt($id);

    //         $user = User::findOrFail($id);
    //         $user->delete();

    //         return redirect()->route('users')
    //             ->with('success', 'Pengguna berhasil dihapus.');
    //     } catch (DecryptException $e) {
    //         abort(404);
    //     }
    // }
}
