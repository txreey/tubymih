<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            padding: 32px 36px;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 3px solid #4F46E5;
        }

        .header .toko {
            font-size: 20px;
            font-weight: bold;
            color: #0F766E;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header .judul {
            font-size: 13px;
            font-weight: bold;
            color: #4F46E5;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        .header .tagline {
            font-size: 9px;
            color: #888;
            margin-top: 2px;
        }

        /* ── META INFO ── */
        .meta-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            background: #F8FAFF;
            border: 1px solid #E0E7FF;
            border-left: 4px solid #4F46E5;
            padding: 8px 12px;
            border-radius: 3px;
        }

        .meta-box .meta-item {
            font-size: 10px;
            color: #333;
        }

        .meta-box .meta-item span {
            font-weight: bold;
            color: #1a1a2e;
        }

        .meta-box .print-date {
            font-size: 9px;
            color: #888;
            text-align: right;
            align-self: center;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        thead tr th {
            background-color: #4F46E5;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 7px 6px;
            border: 1px solid #3730a3;
            font-size: 10px;
            letter-spacing: 0.3px;
        }

        /* Garis bawah header */
        thead tr th:first-child {
            border-radius: 0;
        }

        tbody tr td {
            border: 1px solid #E5E7EB;
            padding: 5px 6px;
            vertical-align: middle;
            font-size: 9.5px;
            color: #222;
        }

        tbody tr:hover td {
            background-color: #EEF2FF;
        }

        tr.zebra td {
            background-color: #F0FDF4;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* Kolom No & Qty */
        td.no-col,
        td.qty-col {
            text-align: center;
        }

        /* ── TOTAL ROW ── */
        tfoot tr td {
            background-color: #0F766E;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 7px 6px;
            border: 1px solid #0d6b63;
            text-align: center;
        }

        tfoot tr td:nth-child(5) {
            background-color: #0a5c56;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            color: #aaa;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="toko">TUBYMIH</div>
        <div class="judul">LAPORAN PENDAPATAN KASIR</div>
        <div class="tagline">Sistem Manajemen Kasir &mdash; Dokumen Resmi</div>
    </div>

    {{-- META INFO --}}
    <div class="meta-box">
        <div>
            <div class="meta-item">Periode &nbsp;: <span>{{ $periode }}</span></div>
            <div class="meta-item" style="margin-top:4px">Kasir &nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $kasirNama }}</span>
            </div>
        </div>
        <div class="print-date">
            Dicetak pada<br>
            <strong>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</strong>
        </div>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:12%">Tanggal</th>
                <th style="width:18%">Kasir</th>
                <th style="width:10%">Jml Transaksi</th>
                <th style="width:32%">Menu</th>
                <th style="width:6%">Qty</th>
                <th style="width:18%">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $rowIndex = 0;
            @endphp

            @foreach ($grouped as $groupKey => $group)
                @php
                    $first = $group->first();
                    $tanggalFull = $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y') : '-';
                    $kasir = $first->kasir->nama ?? '-';
                    $jmlTransaksi = $group->count();
                    $isFirstRow = true;
                @endphp

                @foreach ($group as $trx)
                    @foreach ($trx->detailTransaksi as $detail)
                        @php $rowIndex++; @endphp
                        <tr class="{{ $rowIndex % 2 === 0 ? 'zebra' : '' }}">
                            @if ($isFirstRow)
                                <td class="center">{{ $no }}</td>
                                <td class="center">{{ $tanggalFull }}</td>
                                <td class="center">{{ $kasir }}</td>
                                <td class="center">{{ $jmlTransaksi }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td>{{ $detail->menu->nama_makanan ?? 'Menu Tidak Diketahui' }}</td>
                            <td class="center">{{ $detail->qty }}</td>
                            <td class="center">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @php $isFirstRow = false; @endphp
                    @endforeach
                @endforeach

                @php $no++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td>{{ $grouped->count() }} Transaksi</td>
                <td></td>
                <td>{{ $totalQty }}</td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <span>TUBYMIH &copy; {{ date('Y') }} &mdash; Laporan ini digenerate otomatis oleh sistem</span>
        <span>Halaman 1</span>
    </div>

</body>

</html>
