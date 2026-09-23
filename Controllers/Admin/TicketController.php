<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plugins\SpbeSla\Ticket;
use App\Models\Plugins\SpbeSla\DigitalService;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        plugin_page_name('Tickets');
        
        $query = Ticket::with('digitalService', 'user', 'assignedAgent')->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tickets = $query->paginate(15);
        
        return view('spbe-sla::admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        plugin_page_name('Create Ticket');
        $services = DigitalService::where('status', '!=', 'Inactive')->get();
        return view('spbe-sla::admin.tickets.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'digital_service_id' => 'required|exists:spbe_sla_digital_services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Backend,UI/UX,Database,Network,Software,Other',
            'priority' => 'required|in:Low,Medium,High,Urgent/P1',
        ]);
        
        $data['user_id'] = auth()->id();
        
        Ticket::create($data);
        
        return redirect()->route('spbe-sla.tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        plugin_page_name('Ticket Details - ' . $ticket->ticket_number);
        $ticket->load('digitalService', 'user', 'assignedAgent', 'comments.user');
        return view('spbe-sla::admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'status' => 'required|in:Open,Assigned,In Progress,Pending,Resolved,Closed'
        ]);
        
        $ticket->update(['status' => $data['status']]);
        
        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        $comment = $ticket->comments()->create([
            'user_id' => auth()->id(),
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
        
        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
