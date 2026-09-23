@extends('spbe-sla::public.layout', ['title' => 'Ticket Details - ' . $ticket->ticket_number])

@section('content')
    <div class="mb-6">
        <a href="{{ plugin_route('spbe-sla.public.dashboard') }}"
            class="text-indigo hover:underline flex items-center gap-1 text-sm font-semibold">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex gap-2 mb-4">
                    <span
                        class="bg-indigo text-white px-2 py-1 rounded text-xs font-bold">{{ $ticket->ticket_number }}</span>
                    <span
                        class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">{{ $ticket->category }}</span>
                </div>

                <h2 class="text-2xl font-bold text-navy mb-4">{{ $ticket->title }}</h2>
                <div class="prose max-w-none text-slate text-sm mb-6 ticket-content">
                    {!! $ticket->description !!}
                </div>

                @php
                    $attachment = $ticket->files()->where('purpose', 'attachment')->first();
                @endphp
                @if($attachment)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm font-bold text-navy mb-2"><i class="fas fa-paperclip"></i> Attachment</p>
                        <a href="{{ url('media/' . $attachment->file_name) }}" target="_blank"
                            class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm text-indigo hover:bg-gray-100 transition">
                            <i class="fas fa-file-download"></i> Download / View Attached File
                        </a>
                    </div>
                @endif
            </div>

            <!-- Discussion Thread -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-xl font-bold text-navy mb-6 border-b border-gray-100 pb-3">Discussion Thread</h3>

                <div class="space-y-6 mb-8 max-h-96 overflow-y-auto pr-2">
                    @forelse($ticket->comments as $comment)
                        <div class="flex gap-4 {{ $comment->user_id == $user->id ? 'flex-row-reverse' : '' }}">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm {{ $comment->user->level == 'admin' ? 'bg-indigo' : 'bg-slate' }}">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <div class="max-w-[80%] {{ $comment->user_id == $user->id ? 'text-right' : '' }}">
                                <p class="text-xs text-slate mb-1">
                                    <span class="font-bold text-navy">{{ $comment->user->name }}</span>
                                    @if($comment->user->level == 'admin')
                                        <span
                                            class="bg-indigo/10 text-indigo text-[10px] px-1.5 py-0.5 rounded ml-1 font-bold">STAFF</span>
                                    @endif
                                    &bull; {{ $comment->created_at->format('d M Y H:i') }}
                                </p>
                                <div
                                    class="bg-gray-50 border border-gray-100 p-4 rounded-2xl text-sm text-navy inline-block text-left ticket-content {{ $comment->user_id == $user->id ? 'rounded-tr-sm bg-indigo/5 border-indigo/10' : 'rounded-tl-sm' }}">
                                    {!! $comment->message !!}

                                    @php
                                        $cAttach = $comment->files()->where('purpose', 'comment-attachment')->first();
                                    @endphp
                                    @if($cAttach)
                                        <div
                                            class="mt-3 pt-3 border-t {{ $comment->user_id == $user->id ? 'border-indigo/10' : 'border-gray-200' }}">
                                            <a href="{{ url('media/' . $cAttach->file_name) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-[11px] font-bold {{ $comment->user_id == $user->id ? 'text-indigo hover:text-indigo/80' : 'text-slate hover:text-navy' }}">
                                                <i class="fas fa-paperclip"></i> View Attached File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate">
                            <i class="far fa-comments text-3xl mb-3 text-gray-300"></i>
                            <p class="text-sm">No comments yet. If you need updates, ask here.</p>
                        </div>
                    @endforelse
                </div>

                @if(!in_array($ticket->status, ['Resolved', 'Closed']))
                    <form id="comment-form" action="{{ plugin_route('spbe-sla.public.tickets.comment', $ticket) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <div id="editor-container"
                                class="bg-white rounded-b-xl border border-gray-200 border-t-0 transition"
                                style="height: 120px;"></div>
                            <input type="hidden" name="message" id="message-input" required>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 mb-4">
                            <div class="flex items-center gap-2 text-sm text-slate">
                                <i class="fas fa-paperclip ml-2"></i>
                                <input type="file" name="attachment"
                                    class="text-xs file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo/10 file:text-indigo hover:file:bg-indigo/20 transition cursor-pointer">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-indigo/90 shadow-sm flex items-center gap-2 transition">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                        <p class="text-sm font-bold text-slate"><i class="fas fa-lock mr-1"></i> This ticket is
                            {{ strtolower($ticket->status) }}.</p>
                        <p class="text-xs text-gray-500 mt-1">You can no longer reply to this ticket.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-navy mb-4 border-b border-gray-100 pb-2">Ticket Details</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Status</p>
                        @php
                            $sColor = match ($ticket->status) {
                                'Resolved', 'Closed' => 'bg-green-100 text-green-700',
                                'Open' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-blue-100 text-blue-700'
                            };
                        @endphp
                        <span class="{{ $sColor }} px-2.5 py-1 rounded-md text-xs font-bold">{{ $ticket->status }}</span>
                    </div>

                    <div>
                        <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Priority</p>
                        @php
                            $pColor = match ($ticket->priority) {
                                'Urgent/P1' => 'bg-red-100 text-red-700',
                                'High' => 'bg-orange-100 text-orange-700',
                                'Medium' => 'bg-blue-100 text-blue-700',
                                'Low' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="{{ $pColor }} px-2.5 py-1 rounded-md text-xs font-bold">{{ $ticket->priority }}</span>
                    </div>

                    <div>
                        <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Digital Service</p>
                        <p class="text-sm font-medium text-navy">{{ $ticket->digitalService->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate uppercase tracking-wider mb-1 font-semibold">Created Date</p>
                        <p class="text-sm font-medium text-navy">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!in_array($ticket->status, ['Resolved', 'Closed']))
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            .ql-toolbar.ql-snow {
                border-radius: 0.75rem 0.75rem 0 0;
                border-color: #e5e7eb;
                background-color: #f9fafb;
            }

            .ql-container.ql-snow {
                border-color: #e5e7eb;
                border-radius: 0 0 0.75rem 0.75rem;
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
                placeholder: 'Type your message here...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            var form = document.querySelector('#comment-form');
            if(form) {
                form.onsubmit = function () {
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
    @endif

@endsection