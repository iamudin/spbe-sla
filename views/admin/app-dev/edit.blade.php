@extends('spbe-sla::layout.app', ['title' => 'Edit App Development'])

@section('content')
<div class="mb-6">
    <a href="{{ route('spbe-sla.app-dev.index') }}" class="text-indigo hover:underline flex items-center gap-1 text-sm font-semibold">
        <i class="fas fa-arrow-left"></i> Back to App Development
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-12xl">
    <h2 class="text-xl font-bold text-navy mb-6 border-b pb-4">Edit App Development Project</h2>

    <form action="{{ route('spbe-sla.app-dev.update', $appDev) }}" method="POST">
        @csrf
        @method('PUT')

        @if($appDev->ticket)
        <div class="mb-6 bg-indigo/5 border border-indigo/20 rounded-lg p-4">
            <p class="text-sm font-bold text-navy mb-1"><i class="fas fa-ticket-alt text-indigo"></i> Linked to Ticket: {{ $appDev->ticket->ticket_number }}</p>
            <p class="text-xs text-slate">{{ $appDev->ticket->title }}</p>
            <input type="hidden" name="ticket_id" value="{{ $appDev->ticket_id }}">
        </div>
        @else
            <input type="hidden" name="ticket_id" value="">
        @endif

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-navy mb-2">Digital Service *</label>
                <select name="digital_service_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $appDev->digital_service_id == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-navy mb-2">Status *</label>
                <select name="status" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo max-w-xs">
                    <option value="Planning" {{ $appDev->status == 'Planning' ? 'selected' : '' }}>Planning</option>
                    <option value="Designing" {{ $appDev->status == 'Designing' ? 'selected' : '' }}>Designing</option>
                    <option value="Developing" {{ $appDev->status == 'Developing' ? 'selected' : '' }}>Developing</option>
                    <option value="Testing" {{ $appDev->status == 'Testing' ? 'selected' : '' }}>Testing</option>
                    <option value="Deployed" {{ $appDev->status == 'Deployed' ? 'selected' : '' }}>Deployed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-navy mb-2">Development Progress</label>
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex-1 w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-indigo h-3 rounded-full transition-all" style="width: {{ $appDev->progress_percentage }}%"></div>
                    </div>
                    <span class="font-bold text-indigo text-lg">{{ $appDev->progress_percentage }}%</span>
                </div>
                <p class="text-xs text-slate mt-2"><i class="fas fa-info-circle text-indigo"></i> Progress is calculated automatically based on completed features below.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-navy mb-2">IT Staff (Tenaga IT)</label>
                <input type="text" name="it_staff" value="{{ $appDev->it_staff }}" placeholder="e.g. John Doe, Jane Smith" class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">
            </div>

            <div>
                <label class="block text-sm font-bold text-navy mb-2">Specification / Details *</label>
                <textarea name="specification" rows="5" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-2 focus:ring-indigo/20 focus:border-indigo">{{ $appDev->specification }}</textarea>
            </div>

            <!-- MODULES CHECKLIST ALPINE.JS -->
            <div x-data="moduleBuilder()" class="mt-8 border-t pt-8">
                <h3 class="text-lg font-bold text-navy mb-4"><i class="fas fa-tasks text-indigo"></i> Modules & Features Checklist</h3>
                
                <input type="hidden" name="modules_data" :value="JSON.stringify(modules)">
                
                <template x-for="(module, mIndex) in modules" :key="mIndex">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 relative">
                        <button type="button" @click="removeModule(mIndex)" class="absolute top-3 right-3 text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        
                        <div class="mb-4 pr-8">
                            <label class="block text-xs font-bold text-slate mb-1">Module Name</label>
                            <input type="text" x-model="module.title" placeholder="e.g. Authentication Module" class="w-full rounded border-gray-300 border p-2 text-sm focus:border-indigo focus:ring-indigo/20">
                        </div>
                        
                        <div class="pl-4 border-l-2 border-indigo/20 space-y-2">
                            <template x-for="(feature, fIndex) in module.features" :key="fIndex">
                                <div class="flex items-center gap-3 bg-white p-2 border border-gray-100 rounded shadow-sm">
                                    <input type="checkbox" x-model="feature.is_completed" class="rounded border-gray-300 text-indigo shadow-sm focus:border-indigo focus:ring focus:ring-indigo/20 h-4 w-4">
                                    <input type="text" x-model="feature.title" placeholder="Feature name..." class="flex-1 border-none focus:ring-0 p-0 text-sm font-medium text-navy">
                                    <button type="button" @click="removeFeature(mIndex, fIndex)" class="text-gray-400 hover:text-red-500 px-2">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                            
                            <button type="button" @click="addFeature(mIndex)" class="text-sm font-bold text-indigo hover:text-navy mt-2 flex items-center gap-1">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                        </div>
                    </div>
                </template>
                
                <button type="button" @click="addModule()" class="bg-indigo/10 text-indigo font-bold px-4 py-2 rounded shadow-sm hover:bg-indigo/20 transition flex items-center gap-2 text-sm border border-indigo/20">
                    <i class="fas fa-folder-plus"></i> Add New Module
                </button>
            </div>

            <div class="pt-6 border-t flex justify-end mt-8">
                <button type="submit" class="bg-indigo text-white font-bold px-6 py-2.5 rounded-lg shadow hover:bg-indigo/90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Save & Update Progress
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function moduleBuilder() {
        return {
            modules: {!! $appDev->modules_data ? json_encode($appDev->modules_data) : '[]' !!},
            addModule() {
                this.modules.push({
                    title: '',
                    features: []
                });
            },
            removeModule(index) {
                if(confirm('Are you sure you want to remove this module?')) {
                    this.modules.splice(index, 1);
                }
            },
            addFeature(mIndex) {
                this.modules[mIndex].features.push({
                    title: '',
                    is_completed: false
                });
            },
            removeFeature(mIndex, fIndex) {
                this.modules[mIndex].features.splice(fIndex, 1);
            }
        }
    }
</script>
@endsection
