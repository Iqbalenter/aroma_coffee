<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show()
    {
        if (session()->has('staff')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'staff' => [
                    'type' => 'admin',
                    'id' => $admin->id_admin,
                    'nama' => $admin->nama,
                    'username' => $admin->username,
                    'level_akses' => $admin->level_akses,
                ],
            ]);

            return redirect()->route('dashboard');
        }

        $operator = Operator::where('username', $request->username)->first();
        if ($operator && Hash::check($request->password, $operator->password)) {
            session([
                'staff' => [
                    'type' => 'operator',
                    'id' => $operator->id_operator,
                    'nama' => $operator->nama,
                    'username' => $operator->username,
                    'level_akses' => $operator->level_akses,
                ],
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    public function logout()
    {
        session()->forget('staff');

        return redirect()->route('login');
    }
}
