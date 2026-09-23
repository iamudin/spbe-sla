@extends('spbe-sla::layout.app', ['title' => 'Create App Development'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('spbe-sla.app-dev.index') }}"
            class="text-indigo hover:underline flex items-center gap-1 text-sm font-semibold">
            <i class="fas fa-arrow-left"></i> Back to App Development
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-12xl">
        <h2 class="text-xl font-bold text-navy mb-6 border-b pb-4">Create App Development Project</h2>

        <form action="{{ route('spbe-sla.app-dev.store') }}" method="POST">
            @csrf

            @if($ticket)
                <div class="mb-6 bg-indigo/5 border border-indigo/20 rounded-lg p-4">
                    <p class="text-sm font-bold text-navy mb-1"><i class="fas fa-ticket-alt text-indigo"></i> Linked to Ticket:
                        {{ $ticket->ticket_number }}</p>
                    <p class="text-xs text-slate">{{ $ticket->title }}</p>
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Digital Service *</label>
                    <select name="digital_service_id" required
                        class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">
                        <option value="">Select Digital Service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ ($ticket && $ticket->digital_service_id == $service->id) ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">If the digital service doesn't exist, create it first in the <a
                            href="{{ route('spbe-sla.services.create') }}" class="text-indigo underline">Digital
                            Services</a> menu.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Status *</label>
                    <select name="status" required
                        class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo max-w-xs">
                        <option value="Planning">Planning</option>
                        <option value="Designing">Designing</option>
                        <option value="Developing">Developing</option>
                        <option value="Testing">Testing</option>
                        <option value="Deployed">Deployed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Development Progress</label>
                    <div
                        class="p-3 bg-blue-50 border border-blue-100 text-blue-700 text-sm rounded-lg flex items-start gap-2">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <p>Progress is calculated automatically. After creating the project, you can add Modules and
                            Features in the <strong>Edit</strong> menu to update the progress.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">IT Staff (Tenaga IT)</label>
                    <input type="text" name="it_staff" placeholder="e.g. John Doe, Jane Smith"
                        class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">
                    <p class="text-xs text-gray-500 mt-1">List the names of IT personnel responsible for this development.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Specification / Details *</label>
                    <textarea name="specification" rows="5" required
                        class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">{{ $ticket ? strip_tags($ticket->description) : '' }}</textarea>
                </div>

                <div class="pt-4 border-t flex justify-end">
                    <button type="submit"
                        class="bg-indigo text-white font-bold px-6 py-2.5 rounded-lg shadow hover:bg-indigo/90 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Project
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection