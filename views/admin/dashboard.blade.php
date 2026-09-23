@extends('spbe-sla::layout.app', ['title' => 'SPBE SLA Dashboard'])

@section('content')
<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Compliance Rate -->
    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-14 h-14 rounded-full bg-indigo/10 flex items-center justify-center text-indigo text-2xl">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-sm text-slate font-medium">SLA Compliance</p>
            <h3 class="text-2xl font-bold text-navy">{{ $complianceRate }}%</h3>
        </div>
    </div>

    <!-- System Uptime -->
    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-2xl">
            <i class="fas fa-server"></i>
        </div>
        <div>
            <p class="text-sm text-slate font-medium">Global Uptime</p>
            <h3 class="text-2xl font-bold text-navy">{{ $globalUptime }}%</h3>
        </div>
    </div>

    <!-- Avg Response -->
    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl">
            <i class="fas fa-bolt"></i>
        </div>
        <div>
            <p class="text-sm text-slate font-medium">Avg Response (MTTRap)</p>
            <h3 class="text-2xl font-bold text-navy">{{ $avgResponseTime }}m</h3>
        </div>
    </div>

    <!-- Open Tickets -->
    <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-2xl">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div>
            <p class="text-sm text-slate font-medium">Open Tickets</p>
            <h3 class="text-2xl font-bold text-navy">{{ $openTickets }} <span class="text-sm text-slate font-normal">/ {{ $totalTickets }} Total</span></h3>
        </div>
    </div>

</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Ticket Breakdown -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-navy mb-4">Ticket Breakdown</h3>
        <div class="relative h-64 w-full">
            <canvas id="ticketBreakdownChart"></canvas>
        </div>
    </div>

    <!-- System Uptime Trend -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-navy mb-4">System Uptime & Latency Trend</h3>
        <div class="relative h-64 w-full">
            <canvas id="uptimeChart"></canvas>
        </div>
    </div>

    <!-- SLA Breached vs On-Time -->
    <div class="bg-white rounded-xl shadow-sm border p-6 lg:col-span-2">
        <h3 class="font-bold text-navy mb-4">SLA Performance (Breached vs On-Time)</h3>
        <div class="relative h-72 w-full">
            <canvas id="slaPerformanceChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Options
    Chart.defaults.font.family = 'Inter';
    Chart.defaults.color = '#64748B';

    // Ticket Breakdown (Donut)
    new Chart(document.getElementById('ticketBreakdownChart'), {
        type: 'doughnut',
        data: {
            labels: ['Urgent', 'High', 'Medium', 'Low'],
            datasets: [{
                data: [12, 19, 30, 25],
                backgroundColor: ['#EF4444', '#F97316', '#3B82F6', '#10B981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'right' }
            }
        }
    });

    // Uptime Trend (Line)
    new Chart(document.getElementById('uptimeChart'), {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Uptime %',
                data: [99.9, 99.8, 99.9, 100, 99.9, 99.7, 99.9],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { min: 99, max: 100 }
            }
        }
    });

    // SLA Performance (Bar)
    new Chart(document.getElementById('slaPerformanceChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Resolved On-Time',
                    data: [65, 59, 80, 81, 56, 55],
                    backgroundColor: '#10B981',
                    borderRadius: 4
                },
                {
                    label: 'SLA Breached',
                    data: [5, 10, 2, 4, 12, 3],
                    backgroundColor: '#EF4444',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
