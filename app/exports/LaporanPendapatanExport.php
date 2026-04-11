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

    public function __construct($transaksis, $filters)
    {
        $this->transaksis = $transaksis;
        $this->filters = $filters;
    }

    public function collection()
    {
        $rows = [];
        $grouped = $this->transaksis->groupBy(function ($trx) {
            $tanggal = $trx->tanggal
                ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d')
                : '0000-00-00';
            return $tanggal . '|' . ($trx->id_kasir ?? 'unknown');
        });

        $no = 1;
        $prevGroupKey = null;

        foreach ($grouped as $groupKey => $group) {
            $first = $group->first();
            $tanggalFull = $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y') : '-';
            $kasir = $first->kasir->nama ?? '-';
            $jmlTransaksi = $group->count();

            foreach ($group as $index => $trx) {
                foreach ($trx->detailTransaksi as $detailIndex => $detail) {
                    $namaMenu = $detail->menu->nama_makanan ?? 'Menu Tidak Diketahui';

                    // Cek apakah ini baris pertama di group ini
                    $isFirstRow = ($prevGroupKey !== $groupKey);

                    $rows[] = [
                        // ✅ No: cuma tampil di baris pertama group, sisanya kosong
                        $isFirstRow ? $no : '',
                        $isFirstRow ? $tanggalFull : '',  // Tanggal
                        $isFirstRow ? $kasir : '',         // Kasir
                        $isFirstRow ? $jmlTransaksi : '',  // Jml Transaksi
                        $namaMenu,                         // Menu (selalu tampil)
                        $detail->qty,
                        'Rp ' . number_format($detail->subtotal, 0, ',', '.'),
                    ];

                    // Increment no HANYA kalau ini baris pertama group
                    if ($isFirstRow) {
                        $prevGroupKey = $groupKey;
                        $no++;
                    }
                }
            }
        }

        // Baris TOTAL (2 baris: spacer + total)
        $rows[] = [''];
        $rows[] = [
            'TOTAL',
            '',
            '',
            $this->transaksis->count(),
            '',
            $this->transaksis->sum(fn($trx) => $trx->detailTransaksi->sum('qty') ?? 0),
            'Rp ' . number_format($this->transaksis->sum('total_harga'), 0, ',', '.')
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENDAPATAN KASIR'],
            ['Periode: ' . ($this->filters['dari'] ?? 'Awal') . ' s/d ' . ($this->filters['sampai'] ?? 'Sekarang')],
            ['Kasir: ' . (!empty($this->filters['id_kasir'])
                ? optional(User::find($this->filters['id_kasir']))->nama
                : 'Semua Kasir')],
            [''],
            ['No', 'Tanggal', 'Kasir', 'Jml Transaksi', 'Menu', 'Qty', 'Pendapatan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,     // No
            'B' => 16,    // Tanggal
            'C' => 28,    // Kasir
            'D' => 18,    // Jml Transaksi
            'E' => 35,    // Menu
            'F' => 12,    // Qty
            'G' => 28,    // Pendapatan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();

                // Border untuk semua data
                $sheet->getStyle('A5:' . $lastCol . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Alignment angka (kolom D-G)
                $sheet->getStyle('D5:G' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Styling TOTAL row
                $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981'],
                    ],
                ]);

                // ✅ Merge cells: tambah kolom 'A' (No) + B + C + D
                $dataEndRow = $lastRow - 2; // Exclude spacer + TOTAL
                if ($dataEndRow >= 6) {
                    $this->mergeGroupedCells($sheet, 'A', 6, $dataEndRow); // ✅ No
                    $this->mergeGroupedCells($sheet, 'B', 6, $dataEndRow); // Tanggal
                    $this->mergeGroupedCells($sheet, 'C', 6, $dataEndRow); // Kasir
                    $this->mergeGroupedCells($sheet, 'D', 6, $dataEndRow); // Jml Transaksi
                }

                // Auto size column
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    private function mergeGroupedCells($sheet, $column, $startRow, $endRow)
    {
        $prevValue = null;
        $mergeStart = null;

        for ($row = $startRow; $row <= $endRow; $row++) {
            $cell = $column . $row;
            $currentValue = trim($sheet->getCell($cell)->getValue() ?? '');

            if (!empty($currentValue) && $currentValue !== $prevValue) {
                if ($mergeStart !== null && $mergeStart < $row - 1) {
                    $sheet->mergeCells("{$column}{$mergeStart}:{$column}" . ($row - 1));
                    $sheet->getStyle("{$column}{$mergeStart}:{$column}" . ($row - 1))
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $mergeStart = $row;
            }

            $prevValue = $currentValue;
        }

        if ($mergeStart !== null && $mergeStart < $endRow) {
            $sheet->mergeCells("{$column}{$mergeStart}:{$column}{$endRow}");
            $sheet->getStyle("{$column}{$mergeStart}:{$column}{$endRow}")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }
}
