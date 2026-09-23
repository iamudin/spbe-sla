@extends('spbe-sla::public.layout', ['title' => 'Login - SPBE SLA Portal'])

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo/10 mb-4 text-indigo text-2xl">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy">SPBE SLA Portal</h1>
            <p class="text-slate text-sm mt-2">Login for Unit Kerja</p>
        </div>
        
        <form action="{{ plugin_route('spbe-sla.public.login.submit') }}" method="POST">
            @csrf
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-navy mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition" placeholder="Enter your email">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-navy mb-1">Password</label>
                    <input type="password" name="password" required class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-indigo/20 focus:border-indigo transition" placeholder="Enter your password">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-indigo text-white font-bold py-3 px-4 rounded-lg shadow-md hover:bg-indigo/90 transition">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection
