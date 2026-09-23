@extends('spbe-sla::public.layout', ['title' => 'Request Ticket - SPBE SLA Portal'])

@section('content')
    <div class="mb-6">
        <a href="{{ plugin_route('spbe-sla.public.dashboard') }}"
            class="text-indigo hover:underline flex items-center gap-1 text-sm font-semibold">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl">
        <h2 class="text-2xl font-bold text-navy mb-6 border-b pb-4">Request New Ticket</h2>

        <form id="ticket-form" action="{{ plugin_route('spbe-sla.public.tickets.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Issue Title *</label>
                    <input type="text" name="title" required
                        class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition"
                        placeholder="Brief summary of the issue">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-navy mb-2">Digital Service *</label>
                        <select name="digital_service_id" required
                            class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition">
                            <option value="">Select Service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-navy mb-2">Category *</label>
                        <select name="category" id="category-select" required
                            class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition">
                            <option value="">Select Category...</option>
                            <option value="Backend">Backend</option>
                            <option value="UI/UX">UI/UX</option>
                            <option value="Database">Database</option>
                            <option value="Network">Network</option>
                            <option value="Software">Software</option>
                            <option value="Pembuatan Aplikasi">Pembuatan Aplikasi</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Priority *</label>
                    <select name="priority" required
                        class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition max-w-xs">
                        <option value="">Select Priority...</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent/P1">Urgent/P1</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-navy mb-2">Detailed Description *</label>
                    <div id="editor-container" class="bg-white rounded-b-lg border-gray-300 border-x border-b transition"
                        style="height: 150px;"></div>
                    <input type="hidden" name="description" id="description-input" required>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 mb-4">
                    <div class="flex items-center gap-2 text-sm text-slate">
                        <i class="fas fa-paperclip ml-2"></i>
                        <input type="file" name="attachment"
                            class="text-xs file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo/10 file:text-indigo hover:file:bg-indigo/20 transition cursor-pointer">
                    </div>
                </div>

                <div class="pt-6 border-t flex justify-end">
                    <button type="submit"
                        class="bg-indigo text-white font-bold px-8 py-3 rounded-lg shadow hover:bg-indigo/90 transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border-radius: 0.5rem 0.5rem 0 0;
            border-color: #d1d5db;
            background-color: #f9fafb;
        }

        .ql-container.ql-snow {
            border-color: #d1d5db;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .ql-editor {
            font-family: inherit;
            font-size: 0.875rem;
        }
    </style>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Please provide as much detail as possible to help our team resolve your issue quickly.',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        var form = document.querySelector('#ticket-form');
        if (form) {
            form.onsubmit = function () {
                var descriptionInput = document.querySelector('#description-input');
                var htmlContent = quill.root.innerHTML;

                if (quill.getText().trim().length === 0) {
                    descriptionInput.value = '';
                } else {
                    descriptionInput.value = htmlContent;
                }
            };
        }

        // Handle Pembuatan Aplikasi category
        var categorySelect = document.getElementById('category-select');
        var dsSelect = document.querySelector('select[name="digital_service_id"]');
        if (categorySelect && dsSelect) {
            categorySelect.addEventListener('change', function () {
                if (this.value === 'Pembuatan Aplikasi') {
                    dsSelect.required = false;
                    dsSelect.value = '';
                    dsSelect.disabled = true;
                    dsSelect.parentElement.style.opacity = '0.5';
                } else {
                    dsSelect.required = true;
                    dsSelect.disabled = false;
                    dsSelect.parentElement.style.opacity = '1';
                }
            });
        }
    </script>
@endsection