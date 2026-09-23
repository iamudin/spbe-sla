@extends('spbe-sla::layout.app', ['title' => 'Edit Digital Service'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('spbe-sla.services.index') }}"
            class="text-indigo hover:underline flex items-center gap-1 text-sm">
            <i class="fas fa-arrow-left"></i> Back to Services
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-8 max-w-12xl">
        <h2 class="text-xl font-bold text-navy mb-6">Edit Digital Service</h2>

        <form action="{{ route('spbe-sla.services.update', $service) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service Name *</label>
                    <input type="text" name="name" value="{{ $service->name }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service Code *</label>
                    <input type="text" name="code" value="{{ $service->code }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Service URL</label>
                    <input type="url" name="url" value="{{ $service->url }}" placeholder="https://..."
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Unit Kerja (Owner)</label>
                    <select name="unit_kerja_id"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                        <option value="">-- No Unit --</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ $service->unit_kerja_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">{{ $service->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate mb-1">Status</label>
                    <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                        <option value="Active" {{ $service->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $service->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Development" {{ $service->status == 'Development' ? 'selected' : '' }}>Development
                        </option>
                        <option value="Maintenance" {{ $service->status == 'Maintenance' ? 'selected' : '' }}>Maintenance
                        </option>
                    </select>
                </div>

                <div class="pt-4 border-t flex justify-end">
                    <button type="submit"
                        class="bg-indigo text-white px-6 py-2 rounded shadow hover:bg-indigo/90 transition">
                        Update Service
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection