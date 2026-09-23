@extends('spbe-sla::public.layout', ['title' => 'Dashboard - SPBE SLA Portal'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-navy">My Requested Tickets</h2>
    <a href="{{ plugin_route('spbe-sla.public.tickets.create') }}" class="bg-indigo text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-indigo/90 transition flex items-center gap-2 font-semibold">
        <i class="fas fa-plus-circle"></i> Request Ticket
    </a>
</div>

@if(isset($appDevelopments) && $appDevelopments->count() > 0)
<div class="mb-10">
    <h2 class="text-xl font-bold text-navy mb-4">Applications in Development</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($appDevelopments as $app)
        <div x-data="{ expanded: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-indigo/20 flex flex-col gap-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-indigo text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">
                {{ $app->status }}
            </div>
            
            <div class="flex items-start gap-4">
                <div class="bg-indigo/10 p-3 rounded-lg text-indigo">
                    <i class="fas fa-code text-xl"></i>
                </div>
                <div class="flex-1 pr-16">
                    <h3 class="font-bold text-navy text-lg leading-tight">{{ $app->digitalService->name ?? 'New Application' }}</h3>
                    @if($app->ticket)
                    <p class="text-xs text-indigo mt-1"><a href="{{ plugin_route('spbe-sla.public.tickets.show', $app->ticket) }}" class="hover:underline"><i class="fas fa-ticket-alt"></i> {{ $app->ticket->ticket_number }}</a></p>
                    @endif
                </div>
            </div>
            
            <div class="mt-2">
                <div class="flex justify-between items-end mb-1">
                    <span class="text-xs font-bold text-slate">Development Progress</span>
                    <span class="text-xs font-bold text-indigo">{{ $app->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo h-2 rounded-full" style="width: {{ $app->progress_percentage }}%"></div>
                </div>
            </div>
            
            @if($app->it_staff)
            <div class="mt-2 pt-4 border-t text-xs text-slate flex items-center gap-2">
                <i class="fas fa-users text-indigo"></i>
                <span class="font-semibold text-navy">IT Staff:</span> {{ $app->it_staff }}
            </div>
            @endif

            @if(!empty($app->modules_data) && count($app->modules_data) > 0)
            <div class="mt-2 border-t pt-3">
                <button @click="expanded = !expanded" class="text-xs font-bold text-indigo hover:text-navy flex items-center gap-1 w-full justify-center bg-indigo/5 py-2 rounded">
                    <span x-text="expanded ? 'Sembunyikan Rincian Modul' : 'Lihat Rincian Modul'"></span>
                    <i class="fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                
                <div x-show="expanded" x-collapse class="mt-4 space-y-3">
                    @foreach($app->modules_data as $module)
                    <div class="bg-gray-50 border border-gray-100 rounded p-3">
                        <h4 class="font-bold text-sm text-navy mb-2">{{ $module['title'] ?? 'Unnamed Module' }}</h4>
                        @if(isset($module['features']) && is_array($module['features']))
                        <ul class="space-y-1.5 pl-1">
                            @foreach($module['features'] as $feature)
                            @php
                                $isCompleted = isset($feature['is_completed']) && filter_var($feature['is_completed'], FILTER_VALIDATE_BOOLEAN);
                            @endphp
                            <li class="flex items-start gap-2 text-xs">
                                @if($isCompleted)
                                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                    <span class="text-gray-500 line-through">{{ $feature['title'] ?? 'Unnamed Feature' }}</span>
                                @else
                                    <i class="far fa-circle text-gray-300 mt-0.5"></i>
                                    <span class="text-navy font-medium">{{ $feature['title'] ?? 'Unnamed Feature' }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="mb-10">
    <h2 class="text-xl font-bold text-navy mb-4">Our Digital Services</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($digitalServices as $service)
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 hover:shadow-md transition">
            <div class="bg-indigo/10 p-3 rounded-lg text-indigo">
                <i class="fas fa-laptop-code text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-navy text-lg leading-tight">{{ $service->name }}</h3>
                <p class="text-xs font-mono text-slate mt-1 mb-2">{{ $service->code }}</p>
                @if($service->url)
                <a href="{{ $service->url }}" target="_blank" class="text-xs text-indigo hover:underline flex items-center gap-1 mb-2">
                    <i class="fas fa-external-link-alt"></i> Buka Aplikasi
                </a>
                @endif
                <div class="mt-3">
                    @php
                        $statusColors = [
                            'Active' => 'bg-green-100 text-green-700',
                            'Inactive' => 'bg-red-100 text-red-700',
                            'Development' => 'bg-blue-100 text-blue-700',
                            'Maintenance' => 'bg-yellow-100 text-yellow-700',
                        ];
                        $sColor = $statusColors[$service->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="{{ $sColor }} px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $service->status }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-gray-100 text-center text-slate">
            <i class="fas fa-server text-3xl mb-2 text-gray-300"></i>
            <p>No digital services assigned to this unit yet.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate">
            <thead class="bg-gray-50 text-navy font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Ticket Number</th>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Digital Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="px-6 py-4 font-bold text-indigo">{{ $ticket->ticket_number }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ plugin_route('spbe-sla.public.tickets.show', $ticket) }}" class="font-bold text-navy hover:text-indigo transition block mb-1">
                            {{ $ticket->title }}
                        </a>
                        @php
                            $attachment = $ticket->files()->where('purpose', 'attachment')->first();
                        @endphp
                        @if($attachment)
                        <div class="mt-1">
                            <a href="{{ url('media/' . $attachment->file_name) }}" target="_blank" class="text-[10px] bg-gray-100 text-indigo px-2 py-1 rounded inline-flex items-center gap-1 hover:bg-gray-200">
                                <i class="fas fa-paperclip"></i> View Attachment
                            </a>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $ticket->digitalService->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $sColor = match($ticket->status) {
                                'Resolved', 'Closed' => 'bg-green-100 text-green-700',
                                'Open' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-blue-100 text-blue-700'
                            };
                        @endphp
                        <span class="{{ $sColor }} px-2.5 py-1 rounded-md text-xs font-bold">{{ $ticket->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-inbox"></i></div>
                        <p>No tickets found. Request a new ticket to get started.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
