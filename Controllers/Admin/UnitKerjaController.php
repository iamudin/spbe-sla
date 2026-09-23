<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\UnitKerja;

class UnitKerjaController extends Controller
{
    public function index()
    {
        plugin_page_name('Unit Kerja');
        $unitKerjas = UnitKerja::withCount('digitalServices', 'user')->orderBy('name')->paginate(10);
        return view('spbe-sla::admin.unit-kerja.index', compact('unitKerjas'));
    }

    public function create()
    {
        plugin_page_name('Add Unit Kerja');
        $users = \App\Models\Plugins\SpbeSla\User::whereLevel('spbe-sla')->get();
        return view('spbe-sla::admin.unit-kerja.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:spbe_sla_unit_kerjas',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'new_user_name' => 'nullable|string|max:255',
            'new_user_username' => 'nullable|string|max:255|unique:users,username',
            'new_user_email' => 'nullable|email|unique:users,email',
            'new_user_password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('new_user_email') && $request->filled('new_user_password')) {
            $user = \App\Models\Plugins\SpbeSla\User::create([
                'name' => $data['new_user_name'],
                'username' => $data['new_user_username'] ?? explode('@', $data['new_user_email'])[0],
                'email' => $data['new_user_email'],
                'slug' => str($data['new_user_name'])->slug(),
                'password' => \Illuminate\Support\Facades\Hash::make($data['new_user_password']),
            ]);
            $user->level = 'spbe-sla';
            $user->save();

            $data['user_id'] = $user->id;
        }

        unset($data['new_user_name'], $data['new_user_username'], $data['new_user_email'], $data['new_user_password']);

        UnitKerja::create($data);

        return redirect()->route('spbe-sla.unit-kerja.index')->with('success', 'Unit Kerja created successfully.');
    }

    public function edit(UnitKerja $unit_kerja)
    {
        plugin_page_name('Edit Unit Kerja');
        $users = \App\Models\Plugins\SpbeSla\User::whereLevel('spbe-sla')->get();
        return view('spbe-sla::admin.unit-kerja.edit', compact('unit_kerja', 'users'));
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:spbe_sla_unit_kerjas,code,' . $unit_kerja->id,
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'new_user_name' => 'nullable|string|max:255',
            'new_user_username' => 'nullable|string|max:255|unique:users,username',
            'new_user_email' => 'nullable|email|unique:users,email',
            'new_user_password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('new_user_email') && $request->filled('new_user_password')) {
            $user = \App\Models\Plugins\SpbeSla\User::create([
                'name' => $data['new_user_name'],
                'username' => $data['new_user_username'] ?? explode('@', $data['new_user_email'])[0],
                'email' => $data['new_user_email'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['new_user_password']),
            ]);
            $user->level = 'spbe-sla';
            $user->save();

            $data['user_id'] = $user->id;
        }

        unset($data['new_user_name'], $data['new_user_username'], $data['new_user_email'], $data['new_user_password']);

        $unit_kerja->update($data);

        return redirect()->route('spbe-sla.unit-kerja.index')->with('success', 'Unit Kerja updated successfully.');
    }
}
