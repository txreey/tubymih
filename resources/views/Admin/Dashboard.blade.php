@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="dash">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-left">
                <h2 class="page-title">Beranda</h2>
                <p class="page-sub">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &mdash; selamat datang kembali
                </p>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="accent" style="background:#1D9E75;"></div>
                <div class="stat-inner">
                    <div>
                        <div class="label">Total Menu</div>
                        <div class="value">{{ $data['total_menu'] }}</div>
                        <div class="sub">{{ $data['total_kategori'] ?? 0 }} kategori aktif</div>
                    </div>
                    <div class="stat-icon" style="background:#E1F5EE; color:#1D9E75;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 0v20M2 12h20" />
                            <path d="M12 2c3 4 3 12 0 20M12 2C9 6 9 14 12 22" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="accent" style="background:#378ADD;"></div>
                <div class="stat-inner">
                    <div>
                        <div class="label">Transaksi Hari Ini</div>
                        <div class="value">{{ $data['total_transaksi'] }}</div>
                        <div class="sub">{{ $data['transaksi_belum_lunas'] ?? 0 }} belum lunas</div>
                    </div>
                    <div class="stat-icon" style="background:#EBF4FD; color:#378ADD;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" />
                            <path d="M9 12h6M9 16h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="accent" style="background:#7F77DD;"></div>
                <div class="stat-inner">
                    <div>
                        <div class="label">Pendapatan Hari Ini</div>
                        <div class="value value-rp">Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}</div>
                        <div class="sub sub-up">{{ $data['persentase_pendapatan'] ?? '' }}</div>
                    </div>
                    <div class="stat-icon" style="background:#F0EFFE; color:#7F77DD;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v2m0 8v2M9 9a3 3 0 016 0c0 1.5-1 2.5-3 3s-3 2-3 3a3 3 0 006 0" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO CARDS --}}
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon" style="color:#1D9E75;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                </div>
                <div class="info-num">{{ $data['total_kasir'] }}</div>
                <div class="info-lbl">Kasir Aktif</div>
            </div>
            <div class="info-card">
                <div class="info-icon" style="color:#378ADD;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="10" rx="2" />
                        <path d="M6 7V5m12 2V5M6 17v2m12-2v2" />
                    </svg>
                </div>
                <div class="info-num">{{ $data['meja_tersedia'] }}</div>
                <div class="info-lbl">Meja Tersedia</div>
            </div>
            <div class="info-card">
                <div class="info-icon" style="color:#6b7280;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="10" rx="2" />
                        <path d="M6 7V5m12 2V5M6 17v2m12-2v2" />
                    </svg>
                </div>
                <div class="info-num">{{ $data['total_meja'] }}</div>
                <div class="info-lbl">Total Meja</div>
            </div>
            <div class="info-card">
                <div class="info-icon" style="color:#D97706;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4l3 3" />
                    </svg>
                </div>
                <div class="info-num warn">{{ $data['transaksi_belum_lunas'] ?? 0 }}</div>
                <div class="info-lbl">Belum Lunas</div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="main-grid">

            {{-- MENU TERLARIS --}}
            <div class="main-card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <span class="card-dot" style="background:#1D9E75;"></span>
                        <h3>Menu terlaris hari ini</h3>
                    </div>
                    <a href="{{ route('admin.menu.index') }}" class="card-link">Lihat semua →</a>
                </div>
                @forelse($data['menu_terlaris'] ?? [] as $i => $menu)
                    @php $pct = round(($menu['total'] / ($data['menu_terlaris'][0]['total'] ?: 1)) * 100); @endphp
                    <div class="menu-row">
                        <div class="menu-left">
                            <span class="menu-rank rank-{{ $i + 1 }}">{{ $i + 1 }}</span>
                            <span class="menu-name">{{ $menu['nama_makanan'] }}</span>
                        </div>
                        <div class="menu-right">
                            <div class="menu-bar-wrap">
                                <div class="menu-bar" style="width:{{ $pct }}%;"></div>
                            </div>
                            <span class="menu-count">{{ $menu['total'] }}x</span>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Belum ada data menu hari ini.</p>
                @endforelse
            </div>

            {{-- STATUS MEJA --}}
            <div class="main-card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <span class="card-dot" style="background:#378ADD;"></span>
                        <h3>Status meja</h3>
                    </div>
                    <a href="{{ route('admin.meja.index') }}" class="card-link">Kelola →</a>
                </div>
                <div class="meja-grid">
                    @foreach ($data['semua_meja'] ?? [] as $meja)
                        <div class="meja-box {{ $meja->status === 'tersedia' ? 'meja-available' : 'meja-occupied' }}">
                            {{ $meja->no_meja }}
                        </div>
                    @endforeach
                </div>
                <div class="meja-legend">
                    <span class="leg-item">
                        <span class="leg-dot" style="background:#5DCAA5;"></span>
                        Tersedia ({{ $data['meja_tersedia'] }})
                    </span>
                    <span class="leg-item">
                        <span class="leg-dot" style="background:#F09595;"></span>
                        Terisi ({{ $data['total_meja'] - $data['meja_tersedia'] }})
                    </span>
                </div>
            </div>

        </div>

        {{-- SHORTCUT NAVIGASI --}}
        <div class="section-label">Navigasi cepat</div>
        <div class="shortcut-grid">
            <a href="{{ route('admin.users.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="background:#EBF4FD; color:#378ADD;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
                <span class="shortcut-label">Users</span>
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="background:#FFF7E6; color:#D97706;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                </div>
                <span class="shortcut-label">Kategori</span>
            </a>
            <a href="{{ route('admin.menu.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="background:#E1F5EE; color:#1D9E75;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 0v20M2 12h20" />
                        <path d="M12 2c3 4 3 12 0 20M12 2C9 6 9 14 12 22" />
                    </svg>
                </div>
                <span class="shortcut-label">Menu</span>
            </a>
            <a href="{{ route('admin.meja.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="background:#FDF2F8; color:#9D174D;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="10" rx="2" />
                        <path d="M6 7V5m12 2V5M6 17v2m12-2v2" />
                    </svg>
                </div>
                <span class="shortcut-label">Meja</span>
            </a>
            <a href="{{ route('admin.riwayat') }}" class="shortcut-card">
                <div class="shortcut-icon" style="background:#F0EFFE; color:#7F77DD;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="M9 12h6M9 16h4" />
                    </svg>
                </div>
                <span class="shortcut-label">Riwayat Transaksi</span>
            </a>
        </div>

    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        .dash {
            padding: 1.5rem 0;
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .page-sub {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-role {
            font-size: 11px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 5px 12px;
            border-radius: 20px;
            color: #6b7280;
            text-transform: capitalize;
            letter-spacing: 0.03em;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #0F6E56;
            color: #9FE1CB;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* STAT CARDS */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 12px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        }

        .stat-card .accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            border-radius: 16px 0 0 16px;
        }

        .stat-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .value {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .value-rp {
            font-size: 19px;
        }

        .sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 6px;
        }

        .sub-up {
            color: #1D9E75;
            font-weight: 600;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* INFO CARDS */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .info-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1rem 0.9rem;
            text-align: center;
            transition: box-shadow 0.2s;
        }

        .info-card:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        }

        .info-icon {
            margin-bottom: 6px;
            display: flex;
            justify-content: center;
        }

        .info-num {
            font-size: 24px;
            font-weight: 700;
            color: #0F6E56;
        }

        .info-num.warn {
            color: #D97706;
        }

        .info-lbl {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* MAIN GRID */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }

        .main-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .card-title-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .card-header h3 {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .card-link {
            font-size: 12px;
            color: #0F6E56;
            text-decoration: none;
            font-weight: 600;
        }

        .card-link:hover {
            text-decoration: underline;
        }

        /* MENU TERLARIS */
        .menu-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f9fafb;
        }

        .menu-row:last-child {
            border-bottom: none;
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 9px;
            overflow: hidden;
        }

        .menu-rank {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rank-1 {
            background: #FEF3C7;
            color: #92400E;
        }

        .rank-2 {
            background: #F3F4F6;
            color: #374151;
        }

        .rank-3 {
            background: #FEE2E2;
            color: #991B1B;
        }

        .rank-4,
        .rank-5 {
            background: #f9fafb;
            color: #9ca3af;
        }

        .menu-name {
            font-size: 13px;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .menu-bar-wrap {
            width: 70px;
            height: 5px;
            background: #f3f4f6;
            border-radius: 3px;
            overflow: hidden;
        }

        .menu-bar {
            height: 100%;
            background: #1D9E75;
            border-radius: 3px;
        }

        .menu-count {
            font-size: 12px;
            color: #6b7280;
            min-width: 28px;
            text-align: right;
            font-weight: 600;
        }

        /* STATUS MEJA */
        .meja-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
        }

        .meja-box {
            border-radius: 8px;
            padding: 8px 4px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            border: 1.5px solid;
            transition: transform 0.15s;
            cursor: default;
        }

        .meja-box:hover {
            transform: scale(1.06);
        }

        .meja-available {
            background: #E1F5EE;
            color: #085041;
            border-color: #5DCAA5;
        }

        .meja-occupied {
            background: #FCEBEB;
            color: #501313;
            border-color: #F09595;
        }

        .meja-legend {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }

        .leg-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: #6b7280;
        }

        .leg-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* SHORTCUT */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .shortcut-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .shortcut-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.1rem 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 9px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .shortcut-card:hover {
            border-color: #1D9E75;
            box-shadow: 0 6px 16px rgba(29, 158, 117, 0.12);
            transform: translateY(-3px);
        }

        .shortcut-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shortcut-label {
            font-size: 12px;
            color: #374151;
            font-weight: 600;
            text-align: center;
            line-height: 1.3;
        }

        .empty-text {
            font-size: 13px;
            color: #9ca3af;
            text-align: center;
            padding: 1.5rem 0;
        }

        @media (max-width:768px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .main-grid {
                grid-template-columns: 1fr;
            }

            .shortcut-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>

@endsection
