@extends('spbe-sla::layout.app', ['title' => 'Tickets Monitoring'])

@section('content')
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
    <h2 class="text-xl font-bold text-navy">Tickets Monitoring</h2>
    
    <div class="flex gap-2">
        <a href="{{ route('spbe-sla.tickets.create') }}" class="bg-indigo text-white px-4 py-2 rounded shadow hover:bg-indigo/90 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> New Ticket
        </a>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('spbe-sla.tickets.index') }}" class="bg-white rounded-xl shadow-sm border p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-xs font-semibold text-slate mb-1">Status</label>
        <select name="status" class="border rounded-md px-3 py-1.5 text-sm focus:ring focus:ring-indigo/20">
            <option value="">All Statuses</option>
            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </div>
    
    <div>
        <label class="block text-xs font-semibold text-slate mb-1">Priority</label>
        <select name="priority" class="border rounded-md px-3 py-1.5 text-sm focus:ring focus:ring-indigo/20">
            <option value="">All Priorities</option>
            <option value="Urgent/P1" {{ request('priority') == 'Urgent/P1' ? 'selected' : '' }}>Urgent/P1</option>
            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
        </select>
    </div>
    
    <button type="submit" class="bg-slate text-white px-4 py-1.5 rounded text-sm hover:bg-slate-600">Filter</button>
    <a href="{{ route('spbe-sla.tickets.index') }}" class="text-indigo text-sm hover:underline ml-2">Clear</a>
</form>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate">
            <thead class="bg-gray-50 text-navy font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Ticket</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Priority</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">SLA Deadline</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-navy">{{ $ticket->ticket_number }}</div>
                        <div class="text-xs truncate max-w-[150px]" title="{{ $ticket->title }}">{{ $ticket->title }}</div>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $ticket->digitalService->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $pColor = match($ticket->priority) {
                                'Urgent/P1' => 'bg-red-100 text-red-700',
                                'High' => 'bg-orange-100 text-orange-700',
                                'Medium' => 'bg-blue-100 text-blue-700',
                                'Low' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="{{ $pColor }} px-2 py-1 rounded-md text-xs font-bold">{{ $ticket->priority }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $sColor = match($ticket->status) {
                                'Resolved', 'Closed' => 'bg-green-100 text-green-700 border border-green-200',
                                'Open' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                default => 'bg-blue-100 text-blue-700 border border-blue-200'
                            };
                        @endphp
                        <span class="{{ $sColor }} px-2 py-1 rounded-md text-xs font-bold">{{ $ticket->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($ticket->resolution_due_at)
                            @if(in_array($ticket->status, ['Resolved', 'Closed']))
                                <span class="text-green-600 text-xs font-semibold"><i class="fas fa-check-circle"></i> On Time</span>
                            @else
                                @php
                                    $isBreached = \Carbon\Carbon::now()->gt($ticket->resolution_due_at);
                                @endphp
                                @if($isBreached)
                                    <span class="text-red-600 text-xs font-bold bg-red-100 px-2 py-1 rounded flex items-center gap-1 w-max"><i class="fas fa-exclamation-triangle"></i> Breached</span>
                                @else
                                    <span class="text-orange-600 text-xs font-bold bg-orange-100 px-2 py-1 rounded flex items-center gap-1 w-max"><i class="fas fa-clock"></i> {{ $ticket->resolution_due_at->diffForHumans() }}</span>
                                @endif
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <button onclick="openStatusModal('{{ $ticket->id }}', '{{ $ticket->status }}')" class="text-sm bg-gray-100 hover:bg-gray-200 text-navy px-3 py-1 rounded border transition">
                            Update
                        </button>
                        <a href="{{ route('spbe-sla.tickets.show', $ticket) }}" class="text-indigo hover:text-indigo/80 bg-indigo/10 p-1.5 rounded inline-flex items-center justify-center">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No tickets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform scale-95 opacity-0 transition-all duration-200" id="statusModalContent">
        <h3 class="text-lg font-bold text-navy mb-4">Update Ticket Status</h3>
        <form id="statusForm" method="POST" action="">
            @csrf
            <input type="hidden" name="ticket_id" id="modalTicketId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate mb-1">New Status</label>
                <select name="status" id="modalStatusSelect" class="w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-indigo/20">
                    <option value="Open">Open</option>
                    <option value="Assigned">Assigned</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Pending">Pending</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate hover:bg-gray-100 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo text-white rounded-md hover:bg-indigo/90">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openStatusModal(ticketId, currentStatus) {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        const form = document.getElementById('statusForm');
        
        // Use string replacement for route parameters
        let routeTemplate = "{{ route('spbe-sla.tickets.update-status', ':id') }}";
        form.action = routeTemplate.replace(':id', ticketId);
        
        document.getElementById('modalTicketId').value = ticketId;
        document.getElementById('modalStatusSelect').value = currentStatus;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endpush
