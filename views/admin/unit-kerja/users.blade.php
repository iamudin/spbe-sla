@extends('spbe-sla::layout.app', ['title' => 'Assign Unit Kerja'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-navy">Assign Users to Unit Kerja</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-left text-sm text-slate">
        <thead class="bg-gray-50 text-navy font-semibold uppercase text-xs">
            <tr>
                <th class="px-6 py-4">User Name</th>
                <th class="px-6 py-4">Email</th>
                <th class="px-6 py-4">Role / Level</th>
                <th class="px-6 py-4">Assigned Unit Kerja</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-navy">{{ $user->name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4">{{ $user->level ?? 'User' }}</td>
                <td class="px-6 py-4">
                    @if($user->unitKerja)
                        <span class="bg-indigo/10 text-indigo px-3 py-1 rounded-md text-xs font-semibold">{{ $user->unitKerja->name }}</span>
                    @else
                        <span class="text-gray-400 italic">Not Assigned</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <button onclick="openAssignModal('{{ $user->id }}', '{{ $user->unit_kerja_id }}', '{{ $user->name }}')" class="text-sm bg-gray-100 hover:bg-gray-200 text-navy px-3 py-1 rounded border transition">
                        Assign
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No users found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($users->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Assign Modal -->
<div id="assignModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform scale-95 opacity-0 transition-all duration-200" id="assignModalContent">
        <h3 class="text-lg font-bold text-navy mb-1">Assign User</h3>
        <p class="text-sm text-slate mb-4" id="modalUserName"></p>
        
        <form id="assignForm" method="POST" action="{{ route('spbe-sla.users.assign') }}">
            @csrf
            <input type="hidden" name="user_id" id="modalUserId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate mb-1">Unit Kerja</label>
                <select name="unit_kerja_id" id="modalUnitKerjaId" class="w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-indigo/20">
                    <option value="">-- None (Remove Assignment) --</option>
                    @foreach($unitKerjas as $uk)
                        <option value="{{ $uk->id }}">{{ $uk->name }} ({{ $uk->code }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 text-slate hover:bg-gray-100 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo text-white rounded-md hover:bg-indigo/90">Save Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAssignModal(userId, unitKerjaId, userName) {
        const modal = document.getElementById('assignModal');
        const content = document.getElementById('assignModalContent');
        
        document.getElementById('modalUserId').value = userId;
        document.getElementById('modalUnitKerjaId').value = unitKerjaId || '';
        document.getElementById('modalUserName').innerText = 'User: ' + userName;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignModal');
        const content = document.getElementById('assignModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endpush
