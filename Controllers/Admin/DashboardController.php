<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\Ticket;
use App\Models\Plugins\SpbeSla\SlaReview;
use App\Models\Plugins\SpbeSla\DigitalService;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function index()
    {
        plugin_page_name('SPBE SLA Dashboard');

        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereIn('status', ['Open', 'Assigned', 'In Progress', 'Pending'])->count();

        $resolvedWithinSla = Ticket::where('status', 'Resolved')
            ->whereColumn('resolved_at', '<=', 'resolution_due_at')
            ->count();

        $resolvedTotal = Ticket::where('status', 'Resolved')->count();
        $complianceRate = $resolvedTotal > 0 ? round(($resolvedWithinSla / $resolvedTotal) * 100, 2) : 100;

        // Dummy values for executive dashboard
        $globalUptime = 99.98;
        $avgResponseTime = 15; // minutes
        $avgResolutionTime = 2.5; // hours

        return view('spbe-sla::admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'complianceRate',
            'globalUptime',
            'avgResponseTime',
            'avgResolutionTime'
        ));
    }

    public function settings(Request $request)
    {
        plugin_page_name('Settings - SPBE SLA');
        if (!auth()->user() || !auth()->user()->isAdmin())
            abort(403, 'Access denied');

        $customDomain = get_option('spbe-sla-domain');

        if ($request->isMethod('PUT')) {
            $data = $request->validate([
                'custom_domain' => 'nullable|string|max:100',
            ]);

            $domain = $request->custom_domain;
            if (filter_var($domain, FILTER_VALIDATE_URL)) {
                $domain = parse_url($domain, PHP_URL_HOST);
            }

            if ($domain) {
                \Leazycms\Web\Models\Option::updateOrCreate(
                    ['name' => 'spbe-sla-domain'],
                    ['value' => $domain, 'autoload' => 1]
                );
            } else {
                \Leazycms\Web\Models\Option::where('name', 'spbe-sla-domain')
                    ->delete();
            }

            cache()->flush(); // Flush cache so new domain is immediately recognized

            return back()->with('success', 'Settings updated successfully');
        }
        return view('spbe-sla::admin.settings', compact('customDomain'));
    }
}
