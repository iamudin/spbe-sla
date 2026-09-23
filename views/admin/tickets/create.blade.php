@extends('spbe-sla::layout.app', ['title' => 'Create Ticket'])

@section('content')
<div class="mb-6">
    <a href="{{ route('spbe-sla.tickets.index') }}" class="text-indigo hover:underline flex items-center gap-1 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Tickets
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border p-8 max-w-3xl">
    <h2 class="text-xl font-bold text-navy mb-6">Create New Ticket</h2>
    
    <form action="{{ route('spbe-sla.tickets.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate mb-1">Ticket Title *</label>
                <input type="text" name="title" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20" placeholder="Brief summary of the issue">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Digital Service *</label>
                <select name="digital_service_id" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                    <option value="">Select Service...</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->code }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Category *</label>
                <select name="category" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                    <option value="">Select Category...</option>
                    <option value="Backend">Backend</option>
                    <option value="UI/UX">UI/UX</option>
                    <option value="Database">Database</option>
                    <option value="Network">Network</option>
                    <option value="Software">Software</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate mb-1">Priority *</label>
                <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-indigo focus:ring focus:ring-indigo/20">
                    <option value="">Select Priority...</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent/P1">Urgent/P1</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">SLA times will be calculated based on this priority.</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate mb-1">Description *</label>
                <div id="editor-container" class="bg-white rounded-b-md border-gray-300 border-x border-b shadow-sm" style="height: 150px;"></div>
                <input type="hidden" name="description" id="description-input" required>
            </div>
        </div>
            
        <div class="pt-4 border-t flex justify-end">
            <button type="submit" class="bg-indigo text-white px-6 py-2 rounded shadow hover:bg-indigo/90 transition flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Submit Ticket
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-radius: 0.375rem 0.375rem 0 0;
        border-color: #d1d5db;
        background-color: #f9fafb;
    }
    .ql-container.ql-snow {
        border-color: #d1d5db;
        border-radius: 0 0 0.375rem 0.375rem;
    }
    .ql-editor {
        font-family: inherit;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Detailed description of the issue...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });
    
    var form = document.querySelector('form');
    if(form) {
        form.onsubmit = function() {
            var descriptionInput = document.querySelector('#description-input');
            var htmlContent = quill.root.innerHTML;
            
            if (quill.getText().trim().length === 0) {
                descriptionInput.value = '';
            } else {
                descriptionInput.value = htmlContent;
            }
        };
    }
</script>
@endpush
