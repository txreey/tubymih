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
    protected $groupMeta = [];

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

        // headings() = 8 baris (row 1–8), data mulai row 9
        $currentRow = 9;
        $no         = 1;

        foreach ($grouped as $groupKey => $group) {
            $first        = $group->first();
            $tanggalFull  = $first->tanggal
                ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y')
                : '-';
            $kasir        = $first->kasir->nama ?? '-';
            $jmlTransaksi = $group->count();

            $groupRows = [];
            foreach ($group as $trx) {
                foreach ($trx->detailTransaksi as $detail) {
                    $groupRows[] = [
                        '',
                        '',
                        '',
                        '',
                        $detail->menu->nama_makanan ?? 'Menu Tidak Diketahui',
                        $detail->qty,
                        'Rp ' . number_format($detail->subtotal ?? 0, 0, ',', '.'),
                    ];
                }
            }

            if (!empty($groupRows)) {
                $groupRows[0][0] = $no;
                $groupRows[0][1] = $tanggalFull;
                $groupRows[0][2] = $kasir;
                $groupRows[0][3] = $jmlTransaksi;
            }

            $groupStart = $currentRow;
            $groupEnd   = $currentRow + count($groupRows) - 1;

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
        //==================
        // Spacer
        //==================
        $rows[] = ['', '', '', '', '', '', ''];

        //==================
        // Baris TOTAL
        //==================
        $rows[] = [
            'TOTAL',
            '',
            '',
            $this->transaksis->count() . ' Transaksi',
            '',
            $this->transaksis->sum(fn($trx) => $trx->detailTransaksi->sum('qty')),
            'Rp ' . number_format($this->transaksis->sum('total_harga'), 0, ',', '.'),
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        $kasirNama = !empty($this->filters['id_kasir'])
            ? optional(User::find($this->filters['id_kasir']))->nama ?? 'Semua Kasir'
            : 'Semua Kasir';

        $periode = ($this->filters['dari'] ?? 'Awal') . ' s/d ' . ($this->filters['sampai'] ?? 'Sekarang');

        $dicetak = 'Dicetak pada: ' . \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm');

        return [
            // Nama toko
            ['TUBYMIH', '', '', '', '', '', ''],
            // Judul laporan
            ['LAPORAN PENDAPATAN KASIR', '', '', '', '', '', ''],
            // Tagline kecil
            ['Sistem Manajemen Kasir — Dokumen Resmi', '', '', '', '', '', ''],
            // pemisah kosong
            ['', '', '', '', '', '', ''],
            // Periode
            ['Periode  : ' . $periode, '', '', '', '', '', ''],
            // Kasir
            ['Kasir       : ' . $kasirNama, '', '', '', '', '', ''],
            // Dicetak pada
            ['', '', '', '', '', '', $dicetak],
            // Header kolom
            ['No', 'Tanggal', 'Kasir', 'Jml Transaksi', 'Menu', 'Qty', 'Pendapatan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            //  TUBYMIH
            1 => [
                'font'      => ['bold' => true, 'size' => 18, 'color' => ['rgb' => '0F766E']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Judul laporan
            2 => [
                'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Tagline
            3 => [
                'font'      => ['size' => 9, 'color' => ['rgb' => 'AAAAAA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Meta info
            5 => ['font' => ['bold' => true, 'size' => 11]],
            6 => ['font' => ['bold' => true, 'size' => 11]],
            //  Dicetak pada
            7 => [
                'font'      => ['size' => 9, 'color' => ['rgb' => 'AAAAAA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ],
            // Header kolom tabel
            8 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 22,
            'D' => 16,
            'E' => 32,
            'F' => 8,
            'G' => 28,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = 'G';

                // ── Merge baris header ────────────────────────────────────
                foreach ([1, 2, 3, 4, 5, 6] as $row) {
                    $sheet->mergeCells("A{$row}:G{$row}");
                }
                
                $sheet->mergeCells('A7:F7');

                // ── Tinggi baris header ───────────────────────────────────
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(8);  
                $sheet->getRowDimension(5)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(20);
                $sheet->getRowDimension(7)->setRowHeight(16);
                $sheet->getRowDimension(8)->setRowHeight(24);

                $sheet->getStyle('A3:G3')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color'       => ['rgb' => '4F46E5'],
                        ],
                    ],
                ]);

                // ── Background box meta ──────────────────────
                $sheet->getStyle('A5:G7')->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFF'],
                    ],
                ]);
                foreach ([5, 6, 7] as $row) {
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color'       => ['rgb' => '4F46E5'],
                            ],
                        ],
                    ]);
                }

                // ── Border seluruh tabel (baris 8 ke bawah) ──────────────
                $sheet->getStyle('A8:' . $lastCol . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                // ── Alignment center kolom A–D dan F–G ───────────────────
                $sheet->getStyle('A8:D' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('F8:G' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // ── Merge A–D per group ───────────────────────────────────
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

                // ── Baris TOTAL ───────────────────────────────────
                $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size'  => 11,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0F766E'],
                    ],
                ]);
                $sheet->mergeCells('A' . $lastRow . ':C' . $lastRow);
                $sheet->getRowDimension($lastRow)->setRowHeight(22);

                $dataEnd = $lastRow - 2; 
                for ($row = 9; $row <= $dataEnd; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0FDF4'],
                            ],
                        ]);
                    }
                }

                $footerRow = $lastRow + 2;
                $sheet->setCellValue(
                    'A' . $footerRow,
                    'TUBYMIH © ' . date('Y') . ' — Laporan ini digenerate otomatis oleh sistem'
                );
                $sheet->mergeCells('A' . $footerRow . ':E' . $footerRow);
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font'      => ['size' => 8, 'color' => ['rgb' => 'AAAAAA'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $sheet->setCellValue('F' . $footerRow, 'Halaman 1');
                $sheet->mergeCells('F' . $footerRow . ':G' . $footerRow);
                $sheet->getStyle('F' . $footerRow)->applyFromArray([
                    'font'      => ['size' => 8, 'color' => ['rgb' => 'AAAAAA'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            },
        ];
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }
}
