@extends('spbe-sla::layout.app', ['title' => 'Ticket Details - ' . $ticket->ticket_number])

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('spbe-sla.tickets.index') }}" class="text-indigo hover:underline flex items-center gap-1 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Tickets
    </a>
    
    <div>
        <form action="{{ route('spbe-sla.tickets.update-status', $ticket) }}" method="POST" class="flex gap-2 items-center">
            @csrf
            <select name="status" class="border rounded-md px-3 py-1.5 text-sm focus:ring focus:ring-indigo/20 font-bold bg-white text-navy">
                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="Assigned" {{ $ticket->status == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Pending" {{ $ticket->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit" class="bg-indigo text-white px-3 py-1.5 rounded text-sm hover:bg-indigo/90 shadow-sm">
                Update Status
            </button>
            
            @if($ticket->category == 'Pembuatan Aplikasi' && !$ticket->appDevelopment)
            <a href="{{ route('spbe-sla.app-dev.create', ['ticket_id' => $ticket->id]) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-sm hover:bg-green-700 shadow-sm ml-2 flex items-center gap-1 font-bold">
                <i class="fas fa-code"></i> Create App Project
            </a>
            @elseif($ticket->appDevelopment)
            <a href="{{ route('spbe-sla.app-dev.edit', $ticket->appDevelopment->id) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700 shadow-sm ml-2 flex items-center gap-1 font-bold">
                <i class="fas fa-code"></i> View App Project
            </a>
            @endif
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex gap-2 mb-4">
                <span class="bg-indigo text-white px-2 py-1 rounded text-xs font-bold">{{ $ticket->ticket_number }}</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">{{ $ticket->category }}</span>
            </div>
            <h2 class="text-2xl font-bold text-navy mb-4">{{ $ticket->title }}</h2>
            <div class="prose max-w-none text-slate text-sm mb-6 ticket-content">
                {!! $ticket->description !!}
            </div>
            
            @php
                $attachment = $ticket->files()->where('purpose', 'attachment')->first();
            @endphp
            @if($attachment)
            <div class="mt-4 pt-4 border-t">
                <p class="text-sm font-bold text-navy mb-2"><i class="fas fa-paperclip"></i> Attachment</p>
                <a href="{{ url('media/' . $attachment->file_name) }}" target="_blank" class="inline-flex items-center gap-2 bg-gray-50 border px-4 py-2 rounded-lg text-sm text-indigo hover:bg-gray-100 transition">
                    <i class="fas fa-file-download"></i> Download / View Attached File
                </a>
            </div>
            @endif
        </div>
        
        <!-- Discussion Thread -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-navy mb-4 border-b pb-2">Discussion Thread</h3>
            <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                @forelse($ticket->comments as $comment)
                <div class="flex gap-4 {{ $comment->user->level == 'admin' ? 'flex-row-reverse' : '' }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-xs {{ $comment->user->level == 'admin' ? 'bg-indigo' : 'bg-slate' }}">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                    <div class="max-w-[80%] {{ $comment->user->level == 'admin' ? 'text-right' : '' }}">
                        <p class="text-xs text-slate mb-1">
                            <span class="font-bold text-navy">{{ $comment->user->name }}</span>
                            &bull; {{ $comment->created_at->format('d M Y H:i') }}
                        </p>
                        <div class="bg-gray-50 border p-3 rounded-xl text-sm text-navy inline-block text-left ticket-content">
                            {!! $comment->message !!}
                            
                            @php
                                $cAttach = $comment->files()->where('purpose', 'comment-attachment')->first();
                            @endphp
                            @if($cAttach)
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <a href="{{ url('media/' . $cAttach->file_name) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo hover:text-indigo/80">
                                    <i class="fas fa-paperclip"></i> View Attached File
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-slate text-sm italic text-center py-4">No comments yet. Start the discussion below.</p>
                @endforelse
            </div>
            
            <form action="{{ route('spbe-sla.tickets.comment', $ticket) }}" method="POST" enctype="multipart/form-data" id="comment-form">
                @csrf
                <div class="mb-2">
                    <div id="editor-container" class="bg-white rounded-b-lg border border-gray-300 border-t-0" style="height: 120px;"></div>
                    <input type="hidden" name="message" id="message-input" required>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg border mb-3">
                    <input type="file" name="attachment" class="text-xs text-slate file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo/10 file:text-indigo hover:file:bg-indigo/20 transition cursor-pointer">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-indigo/90 shadow-sm flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Sidebar Details -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-navy mb-4 border-b pb-2">Ticket Details</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Status</p>
                    @php
                        $sColor = match($ticket->status) {
                            'Resolved', 'Closed' => 'bg-green-100 text-green-700',
                            'Open' => 'bg-yellow-100 text-yellow-700',
                            default => 'bg-blue-100 text-blue-700'
                        };
                    @endphp
                    <span class="{{ $sColor }} px-2 py-1 rounded-md text-sm font-bold">{{ $ticket->status }}</span>
                </div>
                
                <div>
                    <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Priority</p>
                    @php
                        $pColor = match($ticket->priority) {
                            'Urgent/P1' => 'bg-red-100 text-red-700',
                            'High' => 'bg-orange-100 text-orange-700',
                            'Medium' => 'bg-blue-100 text-blue-700',
                            'Low' => 'bg-green-100 text-green-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <span class="{{ $pColor }} px-2 py-1 rounded-md text-sm font-bold">{{ $ticket->priority }}</span>
                </div>
                
                <div>
                    <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Digital Service</p>
                    <p class="text-sm font-medium text-navy">{{ $ticket->digitalService->name ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-navy mb-4 border-b pb-2 flex items-center gap-2">
                <i class="fas fa-stopwatch text-indigo"></i> SLA Timers
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Response Target</p>
                    @if($ticket->response_due_at)
                        <p class="text-sm font-medium {{ $ticket->responded_at ? 'text-green-600' : (\Carbon\Carbon::now()->gt($ticket->response_due_at) ? 'text-red-600' : 'text-orange-600') }}">
                            {{ $ticket->response_due_at->format('d M Y H:i') }}
                        </p>
                    @else
                        <p class="text-sm text-slate">-</p>
                    @endif
                </div>
                
                <div>
                    <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Resolution Target</p>
                    @if($ticket->resolution_due_at)
                        <p class="text-sm font-medium {{ $ticket->resolved_at ? 'text-green-600' : (\Carbon\Carbon::now()->gt($ticket->resolution_due_at) ? 'text-red-600' : 'text-orange-600') }}">
                            {{ $ticket->resolution_due_at->format('d M Y H:i') }}
                        </p>
                        @if(!$ticket->resolved_at)
                        <p class="text-xs mt-1 bg-gray-100 p-1 rounded font-bold text-center {{ \Carbon\Carbon::now()->gt($ticket->resolution_due_at) ? 'text-red-600 border-red-200' : 'text-indigo border-indigo/20' }} border">
                            {{ \Carbon\Carbon::now()->gt($ticket->resolution_due_at) ? 'BREACHED' : $ticket->resolution_due_at->diffForHumans() }}
                        </p>
                        @endif
                    @else
                        <p class="text-sm text-slate">-</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
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
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Type your message here...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });
    
    var form = document.querySelector('#comment-form');
    if(form) {
        form.onsubmit = function() {
            var messageInput = document.querySelector('#message-input');
            var htmlContent = quill.root.innerHTML;
            
            if (quill.getText().trim().length === 0) {
                messageInput.value = '';
            } else {
                messageInput.value = htmlContent;
            }
        };
    }
</script>
@endpush
