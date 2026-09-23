<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\Ticket;

class SlaSyncController extends Controller
{
    public function sync(Request $request)
    {
        // Dummy REST API Endpoint to simulate integrating local SLA data with the National Government Portal.
        $metrics = [
            'total_tickets' => Ticket::count(),
            'resolved_tickets' => Ticket::where('status', 'Resolved')->count(),
            'global_uptime_percent' => 99.98,
            'compliance_rate' => 95.5,
            'last_synced_at' => now()->toIso8601String(),
        ];
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully synced with National Portal.',
            'data' => $metrics
        ]);
    }
}
