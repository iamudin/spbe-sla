<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\AppDevelopment;
use App\Models\Plugins\SpbeSla\DigitalService;
use App\Models\Plugins\SpbeSla\Ticket;
use Illuminate\Support\Str;

class AppDevelopmentController extends Controller
{
    public function index()
    {
        $apps = AppDevelopment::with('digitalService', 'ticket')->orderBy('created_at', 'desc')->get();
        return view('spbe-sla::admin.app-dev.index', compact('apps'));
    }

    public function create(Request $request)
    {
        $services = DigitalService::orderBy('name')->get();
        
        $ticket = null;
        if ($request->has('ticket_id')) {
            $ticket = Ticket::find($request->ticket_id);
        }
        
        return view('spbe-sla::admin.app-dev.create', compact('services', 'ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'digital_service_id' => 'required|uuid',
            'ticket_id' => 'nullable|uuid',
            'specification' => 'required|string',
            'it_staff' => 'nullable|string',
            'status' => 'required|string',
            'modules_data' => 'nullable|json',
        ]);

        $payload = $request->all();
        if ($request->has('modules_data')) {
            $payload['modules_data'] = json_decode($request->modules_data, true);
        }

        AppDevelopment::create($payload);

        return redirect()->route('spbe-sla.app-dev.index')->with('success', 'App Development project created successfully.');
    }

    public function edit(AppDevelopment $appDev)
    {
        $services = DigitalService::orderBy('name')->get();
        return view('spbe-sla::admin.app-dev.edit', compact('appDev', 'services'));
    }

    public function update(Request $request, AppDevelopment $appDev)
    {
        $request->validate([
            'digital_service_id' => 'required|uuid',
            'ticket_id' => 'nullable|uuid',
            'specification' => 'required|string',
            'it_staff' => 'nullable|string',
            'status' => 'required|string',
            'modules_data' => 'nullable|json',
        ]);

        $payload = $request->all();
        if ($request->has('modules_data')) {
            $payload['modules_data'] = json_decode($request->modules_data, true);
        }

        $appDev->update($payload);

        return redirect()->route('spbe-sla.app-dev.index')->with('success', 'App Development project updated successfully.');
    }
}
