@extends('spbe-sla::layout.app', ['title' => 'SLA Reviews'])

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-navy">SLA Reviews & Action Plans</h2>
</div>

<div class="space-y-6">
    @forelse($reviews as $review)
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-navy">{{ $review->title }}</h3>
                <p class="text-sm text-slate">Period: Q{{ $review->period_quarter }} {{ $review->period_year }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate uppercase tracking-wider font-semibold mb-1">Compliance Rate</p>
                <p class="text-2xl font-bold {{ $review->compliance_rate >= 90 ? 'text-green-600' : 'text-red-600' }}">{{ $review->compliance_rate }}%</p>
            </div>
        </div>
        
        <div class="p-6">
            <h4 class="font-bold text-navy text-sm mb-3">Action Plans / Improvements</h4>
            @if($review->actionPlans->count() > 0)
                <div class="space-y-4">
                    @foreach($review->actionPlans as $plan)
                    <div class="border rounded-lg p-4 bg-gray-50/50">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-sm text-navy">{{ $plan->finding_aspect }}</span>
                            <span class="text-xs font-bold px-2 py-1 rounded {{ $plan->status == 'Completed' ? 'bg-green-100 text-green-700' : ($plan->status == 'In Progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700') }}">{{ $plan->status }}</span>
                        </div>
                        <p class="text-sm text-slate mb-3">{{ $plan->action_taken }}</p>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo h-2.5 rounded-full" style="width: {{ $plan->progress_percentage }}%"></div>
                        </div>
                        <div class="text-right mt-1">
                            <span class="text-xs font-semibold text-slate">{{ $plan->progress_percentage }}% Complete</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate italic">No action plans recorded for this review.</p>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
        <p class="text-slate">No SLA reviews found.</p>
    </div>
    @endforelse
    
    @if($reviews->hasPages())
    <div class="py-4">
        {{ $reviews->links() }}
    </div>
    @endif
</div>
@endsection
