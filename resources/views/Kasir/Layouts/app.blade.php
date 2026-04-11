{{-- resources/views/kasir/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir') - Tuangeun by Mimih</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 64px;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900 antialiased">

    <!-- NAVBAR FIXED -->
    <header
        class="fixed top-0 left-0 right-0 z-50 h-[var(--navbar-height)] bg-teal-950 text-white shadow-lg flex items-center justify-between px-7">
        <div class="text-xl font-serif tracking-wide">
            Tuangeun <span class="text-teal-400">by Mimih</span>
        </div>
        <div class="flex items-center gap-4">
            <span
                class="px-3 py-1 text-xs font-semibold uppercase tracking-wider bg-white/10 border border-white/20 rounded-full">
                Kasir Panel
            </span>
            <div class="flex items-center gap-2">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold leading-tight">{{ auth()->user()->nama ?? 'Kasir' }}</div>
                    <div class="text-xs text-teal-300/70">{{ now()->format('d M Y') }}</div>
                </div>
                <div
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-teal-700 flex items-center justify-center text-white font-bold shadow-inner">
                    {{ auth()->check() && auth()->user()->nama ? strtoupper(substr(auth()->user()->nama, 0, 1)) : 'K' }}
                </div>
            </div>
        </div>
    </header>

    <!-- SIDEBAR FIXED -->
    <aside
        class="fixed top-[var(--navbar-height)] left-0 bottom-0 w-[var(--sidebar-width)] bg-teal-950 text-white z-40 overflow-y-auto">
        <div class="p-6 flex flex-col h-full">

            <!-- Utama -->
            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-teal-300/70 mb-3">Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('kasir.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('kasir.dashboard') ? 'bg-teal-800/50 text-teal-300 border-l-4 border-teal-400' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-home w-5 text-center"></i>
                        Beranda
                    </a>
                </nav>
            </div>

            <!-- Transaksi -->
            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-teal-300/70 mb-3">Transaksi</div>
                <nav class="space-y-1">
                    <a href="{{ route('kasir.order.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('kasir.order.*') ? 'bg-teal-800/50 text-teal-300 border-l-4 border-teal-400' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-cash-register w-5 text-center"></i>
                        Order Baru
                    </a>

                    <a href="{{ route('kasir.riwayat') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('kasir.riwayat') ? 'bg-teal-800/50 text-teal-300 border-l-4 border-teal-400' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-history w-5 text-center"></i>
                        Riwayat Transaksi
                    </a>

                    <!-- Menu Reservasi yang baru ditambahkan -->
                    {{-- <a href="{{ route('kasir.reservasi') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('kasir.reservasi') ? 'bg-teal-800/50 text-teal-300 border-l-4 border-teal-400' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-calendar-alt w-5 text-center"></i>
                        Reservasi
                    </a> --}}
                </nav>
            </div>

            <!-- Logout -->
            <div class="mt-auto pt-6 border-t border-teal-800/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-lg bg-red-900/30 border border-red-800/40 text-red-300 hover:bg-red-900/50 hover:text-red-200 transition font-medium">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main
        class="ml-[var(--sidebar-width)] mt-[var(--navbar-height)] min-h-[calc(100vh-var(--navbar-height))] bg-gray-50">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
