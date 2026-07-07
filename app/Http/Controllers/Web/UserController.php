<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Puskesmas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            ->orderByDesc('id')
            ->get();

        $puskesmas = Puskesmas::with([
                'village.district.regency'
            ])
            ->orderBy('name')
            ->get();

        return view('users', compact(
            'title',
            'users',
            'puskesmas'
        ));
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

    public function save(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'gender' => [
                'required',
                Rule::in(['L', 'P']),
            ],
            'phone' => [
                'required',
                'regex:/^[0-9]{10,15}$/',
            ],
            'role' => [
                'required',
                Rule::in(['konseli', 'konselor']),
            ],
            'puskesmas_id' => [
                'required',
                'exists:puskesmas,id',
            ],
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | TAMBAH / UBAH
            |--------------------------------------------------------------------------
            */

            if ($request->filled('id')) {

                $user = User::findOrFail(
                    decrypt($request->id)
                );

                $message = 'Pengguna berhasil diperbarui.';
            } else {

                $user = new User();

                /*
                |--------------------------------------------------------------------------
                | GENERATE USERNAME
                |--------------------------------------------------------------------------
                */

                $baseUsername = Str::lower(
                    preg_replace(
                        '/[^a-zA-Z0-9]/',
                        '',
                        Str::ascii(trim($request->name))
                    )
                );

                $username = $baseUsername;
                $counter = 1;

                while (
                    User::where('username', $username)->exists()
                ) {
                    $username = $baseUsername . $counter++;
                }

                $user->username = $username;

                /*
                |--------------------------------------------------------------------------
                | DEFAULT PASSWORD
                |--------------------------------------------------------------------------
                */

                $user->password = Hash::make('123456');

                $message = 'Pengguna berhasil ditambahkan.';
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA
            |--------------------------------------------------------------------------
            */

            $user->name = trim($request->name);
            $user->gender = $request->gender;
            $user->phone = trim($request->phone);
            $user->role = $request->role;
            $user->puskesmas_id = $request->puskesmas_id;

            $user->save();

            DB::commit();

            return redirect()
                ->route('users')
                ->with('success', $message);

        } catch (DecryptException $e) {

            DB::rollBack();

            return redirect()
                ->route('users')
                ->with('error', 'Data pengguna tidak valid.');

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->route('users')
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');

        }
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
