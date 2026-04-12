<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class LaporanPendapatanExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithEvents
{
    protected $transaksis;
    protected $filters;

    // Simpan info per group untuk merge yang akurat
    protected $groupMeta = []; // [startRow => endRow] untuk setiap group

    public function __construct($transaksis, $filters)
    {
        $this->transaksis = $transaksis;
        $this->filters    = $filters;
    }

    public function collection()
    {
        $rows    = [];
        $grouped = $this->transaksis->groupBy(function ($trx) {
            $tanggal = $trx->tanggal
                ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d')
                : '0000-00-00';
            return $tanggal . '|' . ($trx->id_kasir ?? 'unknown');
        });

        // Baris data mulai dari row 7 (headings 5 baris + 1 header kolom = row 6)
        // headings(): 5 baris → row 1–5, header kolom → row 6, data mulai row 7
        $currentRow = 7;
        $no         = 1;

        foreach ($grouped as $groupKey => $group) {
            $first        = $group->first();
            $tanggalFull  = $first->tanggal
                ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y')
                : '-';
            $kasir        = $first->kasir->nama ?? '-';
            $jmlTransaksi = $group->count();

            // Kumpulkan semua detail baris untuk group ini
            $groupRows = [];
            foreach ($group as $trx) {
                foreach ($trx->detailTransaksi as $detail) {
                    $groupRows[] = [
                        '',   // No (diisi manual di baris pertama)
                        '',   // Tanggal
                        '',   // Kasir
                        '',   // Jml Transaksi
                        $detail->menu->nama_makanan ?? 'Menu Tidak Diketahui',
                        $detail->qty,
                        'Rp ' . number_format($detail->subtotal ?? 0, 0, ',', '.'),
                    ];
                }
            }

            // Isi kolom A–D hanya di baris pertama group
            if (!empty($groupRows)) {
                $groupRows[0][0] = $no;
                $groupRows[0][1] = $tanggalFull;
                $groupRows[0][2] = $kasir;
                $groupRows[0][3] = $jmlTransaksi;
            }

            $groupStart = $currentRow;
            $groupEnd   = $currentRow + count($groupRows) - 1;

            // Simpan meta untuk merge di AfterSheet
            // Merge hanya jika lebih dari 1 baris detail
            if (count($groupRows) > 1) {
                $this->groupMeta[] = [
                    'start' => $groupStart,
                    'end'   => $groupEnd,
                ];
            }

            foreach ($groupRows as $r) {
                $rows[] = $r;
            }

            $currentRow = $groupEnd + 1;
            $no++;
        }

        // Spacer
        $rows[] = ['', '', '', '', '', '', ''];

        // Baris TOTAL
        $rows[] = [
            'TOTAL',
            '',
            '',
            $this->transaksis->count(),
            '',
            $this->transaksis->sum(fn($trx) => $trx->detailTransaksi->sum('qty') ?? 0),
            'Rp ' . number_format($this->transaksis->sum('total_harga'), 0, ',', '.'),
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        $kasirNama = !empty($this->filters['id_kasir'])
            ? optional(User::find($this->filters['id_kasir']))->nama
            : 'Semua Kasir';

        $periode = ($this->filters['dari'] ?? 'Awal') . ' s/d ' . ($this->filters['sampai'] ?? 'Sekarang');

        return [
            // Baris 1 — Nama toko di tengah (paling atas)
             ['LAPORAN PENDAPATAN KASIR', '', '', '', '', '', ''],
            // Baris 2 — Judul di tengah (di bawah nama toko)
            ['TUBYMIH', '', '', '', '', '', ''],
            // Baris 3 — kosong pemisah
            ['', '', '', '', '', '', ''],
            // Baris 4 — Periode & Kasir (kiri)
            ['Periode: ' . $periode, '', '', '', '', '', ''],
            // Baris 5 — Kasir
            ['Kasir: ' . $kasirNama, '', '', '', '', '', ''],
            // Baris 6 — Header kolom
            ['No', 'Tanggal', 'Kasir', 'Jml Transaksi', 'Menu', 'Qty', 'Pendapatan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 — Nama toko TUBYMIH (center, teal)
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '0F766E']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Baris 2 — Judul laporan (center, hitam, besar)
            2 => [
                'font'      => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Baris 4–5 — Info periode & kasir (lebih besar)
            4 => ['font' => ['bold' => true, 'size' => 12]],
            5 => ['font' => ['bold' => true, 'size' => 12]],
            // Baris 6 — Header kolom
            6 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No — kecil
            'B' => 15,   // Tanggal
            'C' => 22,   // Kasir
            'D' => 16,   // Jml Transaksi
            'E' => 32,   // Menu
            'F' => 8,    // Qty
            'G' => 22,   // Pendapatan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = 'G';

                // ── Merge & center nama toko TUBYMIH (baris 1) ──────────
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(24);

                // ── Merge & center judul laporan (baris 2) ───────────────
                $sheet->mergeCells('A2:G2');
                $sheet->getStyle('A2')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(2)->setRowHeight(30);

                // ── Periode & Kasir tetap di kiri (baris 4–5) ────────────
                $sheet->mergeCells('A4:G4');
                $sheet->mergeCells('A5:G5');

                // ── Border seluruh data (baris 6 ke bawah) ───────────────
                $sheet->getStyle('A6:' . $lastCol . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // ── Alignment tengah untuk kolom angka ───────────────────
                $sheet->getStyle('A6:D' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('F6:G' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // ── Merge A–D per group (hanya group dengan >1 baris) ────
                foreach ($this->groupMeta as $meta) {
                    $s = $meta['start'];
                    $e = $meta['end'];

                    foreach (['A', 'B', 'C', 'D'] as $col) {
                        $sheet->mergeCells("{$col}{$s}:{$col}{$e}");
                        $sheet->getStyle("{$col}{$s}:{$col}{$e}")
                            ->getAlignment()
                            ->setVertical(Alignment::VERTICAL_CENTER)
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // ── Styling baris TOTAL ───────────────────────────────────
                $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size'  => 11,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981'],
                    ],
                ]);
                $sheet->mergeCells('A' . $lastRow . ':C' . $lastRow);

                // ── Zebra striping baris data (baris 7 ke atas, skip spacer & total) ──
                $dataEnd = $lastRow - 2; // -2 = spacer + total
                for ($row = 7; $row <= $dataEnd; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0FDF4'],
                            ],
                        ]);
                    }
                }

                // ── Tinggi baris header kolom ─────────────────────────────
                $sheet->getRowDimension(6)->setRowHeight(22);

                // freeze pane dimatikan — header ikut scroll
            },
        ];
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }
}
