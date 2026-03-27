<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Panel') - Tuangeun by Mimih</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts (Plus Jakarta Sans + DM Serif Display) -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 64px;
            --teal-dark: #0f3d38;
            --accent: #2dd4bf;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        .sidebar-active {
            @apply bg-teal-800/50 text-teal-300 border-l-4 border-teal-400;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 antialiased">

    <!-- NAVBAR FIXED -->
    <header
        class="fixed top-0 left-0 right-0 z-50 h-[var(--navbar-height)] bg-teal-950 text-white shadow-lg flex items-center justify-between px-7">
        <div class="flex items-center gap-4">
            <button class="lg:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
            <div class="text-xl font-serif tracking-wide">
                Tuangeun <span class="text-teal-400">by Mimih</span>
                <span
                    class="text-sm font-semibold uppercase tracking-wider ml-3 bg-white/10 px-2 py-1 rounded-full">Owner</span>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div
                class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-teal-700 flex items-center justify-center text-white font-bold shadow-inner">
                O
            </div>
        </div>
    </header>

    <!-- SIDEBAR FIXED -->
    <aside
        class="fixed top-[var(--navbar-height)] left-0 bottom-0 w-[var(--sidebar-width)] bg-teal-950 text-white z-40 overflow-y-auto">
        <div class="p-6 flex flex-col h-full">

            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-teal-300/70 mb-3">Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.dashboard') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-home w-5 text-center"></i>
                        Beranda
                    </a>
                </nav>
            </div>

            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-teal-300/70 mb-3">Manajemen</div>
                <nav class="space-y-1">
                    <a href="{{ route('owner.users.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.users.index') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        User
                    </a>

                    <a href="{{ route('owner.menu') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.menu') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-utensils w-5 text-center"></i>
                        Menu
                    </a>

                    <a href="{{ route('owner.meja') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.meja') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-chair w-5 text-center"></i>
                        Meja
                    </a>
                </nav>
            </div>

            <div class="mb-8">
                <div class="text-xs font-bold uppercase tracking-widest text-teal-300/70 mb-3">Laporan</div>
                <nav class="space-y-1">
                    <a href="{{ route('owner.riwayat') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.riwayat') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-history w-5 text-center"></i>
                        Riwayat Transaksi
                    </a>

                    <a href="{{ route('owner.laporan') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.laporan') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-chart-bar w-5 text-center"></i>
                        Laporan Keuangan
                    </a>

                    <a href="{{ route('owner.log') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('owner.log') ? 'sidebar-active' : 'text-teal-200/80 hover:bg-teal-900/40 hover:text-white' }}">
                        <i class="fas fa-clipboard-list w-5 text-center"></i>
                        Log Aktivitas
                    </a>
                </nav>
            </div>

            <!-- Logout di bawah -->
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
        class="ml-[var(--sidebar-width)] mt-[var(--navbar-height)] min-h-[calc(100vh-var(--navbar-height))] p-8 lg:p-10 bg-gray-50">
        @yield('content')
    </main>

    <!-- Scripts global -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts khusus per halaman -->
    @yield('scripts')

</body>

</html>
