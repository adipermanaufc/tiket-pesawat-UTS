<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pemesanan Pesawat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #000000 !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">

    <!-- Navbar -->
    <nav class="no-print bg-slate-900/90 border-b border-slate-800 sticky top-0 z-50 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('flights.index') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-400 font-bold text-lg">
                    ✈
                </div>
                <div>
                    <span class="font-bold text-lg tracking-wide text-white">Sky<span class="text-sky-400">Pass</span></span>
                    <p class="text-xs text-slate-400">Sistem Pemesanan Tiket Pesawat</p>
                </div>
            </a>

            <!-- Breadcrumb Step Indicator -->
            <div class="hidden md:flex items-center gap-2 text-xs font-medium">
                <a href="{{ route('flights.index') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('flights.index') ? 'bg-sky-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white' }}">
                    1. Katalog Tiket
                </a>
                <span class="text-slate-600">→</span>
                <span class="px-3 py-1.5 rounded-lg {{ request()->routeIs('flights.show') ? 'bg-sky-500 text-slate-950 font-bold' : 'text-slate-400' }}">
                    2. Rincian Tiket
                </span>
                <span class="text-slate-600">→</span>
                <span class="px-3 py-1.5 rounded-lg {{ request()->routeIs('flights.booking') ? 'bg-sky-500 text-slate-950 font-bold' : 'text-slate-400' }}">
                    3. Pilih Kursi & Data
                </span>
                <span class="text-slate-600">→</span>
                <span class="px-3 py-1.5 rounded-lg {{ request()->routeIs('flights.eticket') ? 'bg-sky-500 text-slate-950 font-bold' : 'text-slate-400' }}">
                    4. E-Ticket
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8">
        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/15 border border-red-500/40 text-red-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="no-print border-t border-slate-800/80 py-5 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Pemesanan Pesawat &bull; Laravel {{ app()->version() }} &bull; PHP {{ phpversion() }}
    </footer>

    @stack('scripts')
</body>
</html>