@extends('spbe-sla::layout.app', ['title' => 'App Development'])

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-navy">App Development Projects</h2>
        <p class="text-sm text-slate">Manage application development lifecycles.</p>
    </div>
    <a href="{{ route('spbe-sla.app-dev.create') }}" class="bg-indigo text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo/90 shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> New Project
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-4 text-xs font-bold text-slate uppercase tracking-wider">Digital Service / App</th>
                <th class="p-4 text-xs font-bold text-slate uppercase tracking-wider">Status</th>
                <th class="p-4 text-xs font-bold text-slate uppercase tracking-wider">Progress</th>
                <th class="p-4 text-xs font-bold text-slate uppercase tracking-wider">IT Staff</th>
                <th class="p-4 text-xs font-bold text-slate uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($apps as $app)
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4">
                    <p class="font-bold text-navy text-sm">{{ $app->digitalService->name ?? 'N/A' }}</p>
                    @if($app->ticket)
                    <p class="text-xs text-indigo mt-1"><i class="fas fa-ticket-alt"></i> {{ $app->ticket->ticket_number }}</p>
                    @endif
                </td>
                <td class="p-4">
                    <span class="bg-indigo/10 text-indigo px-2.5 py-1 rounded-md text-xs font-bold">{{ $app->status }}</span>
                </td>
                <td class="p-4 w-48">
                    <div class="flex items-center gap-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $app->progress_percentage }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-navy">{{ $app->progress_percentage }}%</span>
                    </div>
                </td>
                <td class="p-4">
                    <p class="text-sm text-slate">{{ $app->it_staff ?: '-' }}</p>
                </td>
                <td class="p-4 text-right">
                    <a href="{{ route('spbe-sla.app-dev.edit', $app) }}" class="text-indigo hover:text-indigo/80 text-sm font-semibold">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-slate">
                    <i class="fas fa-code text-3xl mb-3 text-gray-300"></i>
                    <p>No App Development projects found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
