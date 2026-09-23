<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\UnitKerja;
use App\Models\Plugins\SpbeSla\User;

class AssignUserController extends Controller
{
    public function index()
    {
        plugin_page_name('Assign Unit Kerja Users');
        $users = User::with('unitKerja')->paginate(20);
        $unitKerjas = UnitKerja::all();
        return view('spbe-sla::admin.unit-kerja.users', compact('users', 'unitKerjas'));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'unit_kerja_id' => 'nullable|exists:spbe_sla_unit_kerjas,id'
        ]);

        $user = User::findOrFail($data['user_id']);
        $user->unit_kerja_id = $data['unit_kerja_id'];
        $user->save();

        return redirect()->back()->with('success', 'User assigned to Unit Kerja successfully.');
    }
}
