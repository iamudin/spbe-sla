<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\DigitalService;
use App\Models\Plugins\SpbeSla\UnitKerja;

class DigitalServiceController extends Controller
{
    public function index()
    {
        plugin_page_name('Digital Services');
        $services = DigitalService::with('unitKerja')->orderBy('created_at', 'desc')->paginate(10);
        return view('spbe-sla::admin.services.index', compact('services'));
    }

    public function create()
    {
        plugin_page_name('Add Digital Service');
        $unitKerjas = UnitKerja::all();
        return view('spbe-sla::admin.services.create', compact('unitKerjas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:spbe_sla_digital_services',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'unit_kerja_id' => 'nullable|exists:spbe_sla_unit_kerjas,id',
            'status' => 'required|string|in:Active,Inactive,Development,Maintenance',
        ]);
        
        DigitalService::create($data);
        
        return redirect()->route('spbe-sla.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(DigitalService $service)
    {
        plugin_page_name('Edit Digital Service');
        $unitKerjas = UnitKerja::all();
        return view('spbe-sla::admin.services.edit', compact('service', 'unitKerjas'));
    }

    public function update(Request $request, DigitalService $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:spbe_sla_digital_services,code,'.$service->id,
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'unit_kerja_id' => 'nullable|exists:spbe_sla_unit_kerjas,id',
            'status' => 'required|string|in:Active,Inactive,Development,Maintenance',
        ]);
        
        $service->update($data);
        
        return redirect()->route('spbe-sla.services.index')->with('success', 'Service updated successfully.');
    }
}
