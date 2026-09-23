@extends('spbe-sla::layout.app', ['title' => 'Create Digital Service'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('spbe-sla.services.index') }}"
            class="text-indigo hover:underline flex items-center gap-1 text-sm">
            <i class="fas fa-arrow-left"></i> Back to Services
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-8 max-w-12xl">
        <h2 class="text-xl font-bold text-navy mb-6">New Digital Service</h2>

        <form action="{{ route('spbe-sla.services.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service Name *</label>
                    <input type="text" name="name" required
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service Code *</label>
                    <input type="text" name="code" required
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                    <p class="text-xs text-gray-500 mt-1">Unique identifier (e.g. SIPP-01)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service URL</label>
                    <input type="url" name="url" placeholder="https://..."
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Unit Kerja (Owner)</label>
                    <select name="unit_kerja_id"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                        <option value="">-- No Unit --</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Status</label>
                    <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Development">Development</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="pt-4 border-t flex justify-end">
                    <button type="submit"
                        class="bg-indigo text-white px-6 py-2 rounded shadow hover:bg-indigo/90 transition">
                        Save Service
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection