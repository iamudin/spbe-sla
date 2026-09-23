<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SPBE SLA Portal' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1E293B',
                        indigo: '#4F46E5',
                        slate: '#64748B'
                    }
                }
            }
        }
    </script>
    <style>
        .ticket-content p { margin-bottom: 0.75em; }
        .ticket-content p:last-child { margin-bottom: 0; }
        .ticket-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 0.75em; }
        .ticket-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 0.75em; }
        .ticket-content a { color: #4F46E5; text-decoration: underline; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-gray-800">
    
    @if(isset($user))
    <nav class="bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Brand / Logo -->
                <a href="{{ plugin_route('spbe-sla.public.dashboard') }}" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                    <div class="bg-gradient-to-br from-indigo to-indigo/80 w-10 h-10 rounded-xl shadow-lg shadow-indigo/30 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-xl text-navy tracking-tight leading-none">SPBE SLA</span>
                        <span class="text-[11px] font-bold text-indigo uppercase tracking-wider">Service Portal</span>
                    </div>
                </a>
                
                <!-- User Menu -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-3 bg-slate-50/80 px-3 py-2 rounded-2xl border border-gray-100 hover:bg-slate-100 transition">
                        <div class="w-10 h-10 rounded-xl bg-indigo/10 flex items-center justify-center text-indigo font-bold text-lg border border-indigo/20 shadow-inner">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col hidden sm:flex pr-2">
                            <span class="text-sm font-bold text-navy leading-tight">{{ $user->name }}</span>
                            <span class="text-[11px] text-slate font-medium">{{ $user->unitKerja->name ?? 'No Unit' }}</span>
                        </div>
                    </div>
                    
                    <div class="h-8 w-px bg-gray-200 mx-1 hidden sm:block"></div>
                    
                    <form action="{{ plugin_route('spbe-sla.public.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-11 h-11 flex items-center justify-center rounded-xl text-slate hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100" title="Logout">
                            <i class="fas fa-power-off text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
