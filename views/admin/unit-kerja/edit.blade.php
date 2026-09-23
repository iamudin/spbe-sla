@extends('spbe-sla::layout.app', ['title' => 'Edit Unit Kerja'])

@section('content')
<div class="mb-6">
    <a href="{{ route('spbe-sla.unit-kerja.index') }}" class="text-indigo hover:underline flex items-center gap-1 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Unit Kerja
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border p-8 max-w-2xl">
    <h2 class="text-xl font-bold text-navy mb-6">Edit Unit Kerja</h2>
    
    <form action="{{ route('spbe-sla.unit-kerja.update', $unit_kerja) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Name *</label>
                <input type="text" name="name" value="{{ $unit_kerja->name }}" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Code *</label>
                <input type="text" name="code" value="{{ $unit_kerja->code }}" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
            </div>
            
            <hr class="my-6 border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-navy">Assign User</h3>

            <div>
                <label class="block text-sm font-medium text-slate mb-1">Select Existing User</label>
                <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                    <option value="">-- None --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $unit_kerja->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-sm font-bold text-slate mb-3">OR Create New User</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate mb-1">Name</label>
                        <input type="text" name="new_user_name" class="w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:border-indigo focus:ring focus:ring-indigo/20" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate mb-1">Username</label>
                        <input type="text" name="new_user_username" class="w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:border-indigo focus:ring focus:ring-indigo/20" placeholder="johndoe">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate mb-1">Email</label>
                        <input type="email" name="new_user_email" class="w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:border-indigo focus:ring focus:ring-indigo/20" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate mb-1">Password</label>
                        <input type="password" name="new_user_password" class="w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:border-indigo focus:ring focus:ring-indigo/20" placeholder="Min 6 chars">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2 italic">* If email and password are provided, a new user will be created and automatically selected. Their level will be set to 'spbe-sla'.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">{{ $unit_kerja->description }}</textarea>
            </div>
            
            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="bg-indigo text-white px-6 py-2 rounded shadow hover:bg-indigo/90 transition">
                    Update Unit Kerja
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
