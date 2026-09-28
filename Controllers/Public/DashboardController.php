<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\Ticket;
use App\Models\Plugins\SpbeSla\DigitalService;
use App\Models\Plugins\SpbeSla\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Plugins\SpbeSla\Middleware\RedirectMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RedirectMiddleware::handle(), only: ['index', 'createTicket', 'storeTicket', 'showTicket', 'storeComment']),
        ];
    }
    private function getSessionUser()
    {
        $userId = session('spbe_sla_user_id');
        if (!$userId)
            return null;
        return User::find($userId);
    }

    public function index()
    {
        plugin_page_name('SPBE SLA Dashboard');
        $user = $this->getSessionUser();
        if (!$user)
            return redirect(plugin_route('spbe-sla.public.login'));

        $unitKerja = $user->unitKerja;
        $digitalServices = $unitKerja ? $unitKerja->digitalServices : collect();

        $appDevelopments = collect();
        if ($unitKerja) {
            $appDevelopments = \App\Models\Plugins\SpbeSla\AppDevelopment::whereHas('digitalService', function ($q) use ($unitKerja) {
                $q->where('unit_kerja_id', $unitKerja->id);
            })
                ->where('progress_percentage', '<', 100)
                ->with('digitalService', 'ticket')
                ->orderBy('created_at', 'desc')->get();
        }

        $tickets = Ticket::where('user_id', $user->id)
            ->orWhereHas('digitalService', function ($q) use ($unitKerja) {
                if ($unitKerja) {
                    $q->where('unit_kerja_id', $unitKerja->id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('spbe-sla::public.dashboard', compact('unitKerja', 'tickets', 'user', 'digitalServices', 'appDevelopments'));
    }

    public function createTicket()
    {
        $user = $this->getSessionUser();
        if (!$user)
            return redirect(plugin_route('spbe-sla.public.login'));

        $services = DigitalService::where('status', '!=', 'Inactive')->get();
        return view('spbe-sla::public.tickets.create', compact('services', 'user'));
    }

    public function storeTicket(Request $request)
    {
        $user = $this->getSessionUser();
        if (!$user)
            return redirect(plugin_route('spbe-sla.public.login'));

        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ];

        if ($request->category !== 'Pembuatan Aplikasi') {
            $rules['digital_service_id'] = 'required|exists:spbe_sla_digital_services,id';
        }

        $data = $request->validate($rules);

        $data['user_id'] = $user->id;
        $data['status'] = 'Open';

        $ticket = Ticket::create($data);

        if ($request->hasFile('attachment')) {
            $ticket->addFile([
                'file' => $request->file('attachment'),
                'purpose' => 'attachment',
                'mime_type' => ['image/webp', 'application/pdf'],
                'random_name' => true
            ]);
        }

        return redirect(plugin_route('spbe-sla.public.dashboard'))->with('success', 'Ticket submitted successfully.');
    }

    public function showTicket(Ticket $ticket)
    {
        $user = $this->getSessionUser();
        if (!$user)
            return redirect(plugin_route('spbe-sla.public.login'));

        // Check if user is authorized to view this ticket (belongs to them or their unit)
        $unitKerjaId = $user->unitKerja->id ?? null;
        if ($ticket->user_id !== $user->id && ($ticket->digitalService->unit_kerja_id ?? null) !== $unitKerjaId) {
            return redirect(plugin_route('spbe-sla.public.dashboard'))->withErrors(['error' => 'Unauthorized access.']);
        }
        plugin_page_name($ticket->ticket_number . ' | ' . $ticket->title);
        $ticket->load('comments.user');

        return view('spbe-sla::public.tickets.show', compact('ticket', 'user'));
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $user = $this->getSessionUser();
        if (!$user)
            return redirect(plugin_route('spbe-sla.public.login'));

        // Check authorization
        $unitKerjaId = $user->unitKerja->id ?? null;
        if ($ticket->user_id !== $user->id && ($ticket->digitalService->unit_kerja_id ?? null) !== $unitKerjaId) {
            return redirect(plugin_route('spbe-sla.public.dashboard'))->withErrors(['error' => 'Unauthorized access.']);
        }

        $data = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => $user->id,
            'message' => $data['message'],
        ]);

        if ($request->hasFile('attachment')) {
            $comment->addFile([
                'file' => $request->file('attachment'),
                'purpose' => 'comment_attachment',
                'mime_type' => ['image/webp', 'application/pdf', 'image/jpeg', 'image/png'],
                'random_name' => true
            ]);
        }

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }
}
