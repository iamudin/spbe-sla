@extends('spbe-sla::layout.app', ['title' => 'Digital Services'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-navy">Manage Digital Services</h2>
    <a href="{{ route('spbe-sla.services.create') }}" class="bg-indigo text-white px-4 py-2 rounded shadow hover:bg-indigo/90 transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Service
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-left text-sm text-slate">
        <thead class="bg-gray-50 text-navy font-semibold uppercase text-xs">
            <tr>
                <th class="px-6 py-4">Service Name</th>
                <th class="px-6 py-4">Code</th>
                <th class="px-6 py-4">Unit Kerja</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($services as $service)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-navy">{{ $service->name }}</td>
                <td class="px-6 py-4">{{ $service->code }}</td>
                <td class="px-6 py-4">{{ $service->unitKerja->name ?? '-' }}</td>
                <td class="px-6 py-4">
                    @php
                        $statusColors = [
                            'Active' => 'bg-green-100 text-green-700',
                            'Inactive' => 'bg-red-100 text-red-700',
                            'Development' => 'bg-blue-100 text-blue-700',
                            'Maintenance' => 'bg-yellow-100 text-yellow-700',
                        ];
                        $sColor = $statusColors[$service->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="{{ $sColor }} px-3 py-1 rounded-full text-xs font-medium">{{ $service->status }}</span>
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <a href="{{ route('spbe-sla.services.edit', $service) }}" class="text-indigo hover:text-indigo/80 bg-indigo/10 p-2 rounded">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No digital services found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($services->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection
