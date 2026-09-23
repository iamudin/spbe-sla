<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SPBE SLA & Ticketing Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1E293B',
                        indigo: '#4F46E5',
                        slate: '#64748B',
                        light: '#F8FAFC'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .ticket-content p {
            margin-bottom: 0.75em;
        }

        .ticket-content p:last-child {
            margin-bottom: 0;
        }

        .ticket-content ul {
            list-style-type: disc;
            padding-left: 1.5em;
            margin-bottom: 0.75em;
        }

        .ticket-content ol {
            list-style-type: decimal;
            padding-left: 1.5em;
            margin-bottom: 0.75em;
        }

        .ticket-content a {
            color: #4F46E5;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-light text-navy antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-navy text-white flex flex-col shadow-xl">
            <div class="p-6 border-b border-gray-700">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-shield-alt text-indigo"></i>
                    SPBE SLA Panel
                </h2>
                <p class="text-xs text-gray-400 mt-1">Indicator 19 Standards</p>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('spbe-sla.dashboard.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.dashboard.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('spbe-sla.tickets.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.tickets.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Tickets</span>
                </a>
                <a href="{{ route('spbe-sla.services.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.services.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-server"></i>
                    <span>Digital Services</span>
                </a>
                <a href="{{ route('spbe-sla.app-dev.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.app-dev.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-code"></i>
                    <span>App Development</span>
                </a>
                <a href="{{ route('spbe-sla.reviews.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.reviews.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>SLA Reviews</span>
                </a>
                <a href="{{ route('spbe-sla.unit-kerja.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.unit-kerja.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-building"></i>
                    <span>Unit Kerja</span>
                </a>
                <a href="{{ route('spbe-sla.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo hover:text-white transition-colors {{ request()->routeIs('spbe-sla.settings.*') ? 'bg-indigo text-white' : 'text-gray-300' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <a href="/{{ admin_path() }}/dashboard"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Main CMS</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b px-8 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-navy">{{ $title ?? 'Dashboard' }}</h1>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <i class="fas fa-bell text-slate hover:text-indigo cursor-pointer text-xl"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full bg-indigo text-white flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')
</body>

</html>