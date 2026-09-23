@extends('spbe-sla::layout.app', ['title' => 'Unit Kerja'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-navy">Manage Unit Kerja</h2>
    <a href="{{ route('spbe-sla.unit-kerja.create') }}" class="bg-indigo text-white px-4 py-2 rounded shadow hover:bg-indigo/90 transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Unit Kerja
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-left text-sm text-slate">
        <thead class="bg-gray-50 text-navy font-semibold uppercase text-xs">
            <tr>
                <th class="px-6 py-4">Name</th>
                <th class="px-6 py-4">Code</th>
                <th class="px-6 py-4">Digital Services</th>
                <th class="px-6 py-4">Assigned Users</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($unitKerjas as $uk)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-navy">{{ $uk->name }}</td>
                <td class="px-6 py-4">{{ $uk->code }}</td>
                <td class="px-6 py-4">{{ $uk->digital_services_count }} services</td>
                <td class="px-6 py-4">{{ $uk->users_count }} users</td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <a href="{{ route('spbe-sla.unit-kerja.edit', $uk) }}" class="text-indigo hover:text-indigo/80 bg-indigo/10 p-2 rounded">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No Unit Kerja found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($unitKerjas->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $unitKerjas->links() }}
    </div>
    @endif
</div>
@endsection
