<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Distributor;
use App\Models\Pengiriman;
use App\Models\JenisObat;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;
use Illuminate\Support\Facades\DB;

class DashboardPemilikController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'totalObat' => Obat::count(),
            'totalPelanggan' => Pelanggan::count(),
            'totalPenjualan' => Penjualan::count(),
            'totalPembelian' => Pembelian::count(),
            'totalPendapatan' => Penjualan::sum('total_bayar'),
            'totalPengeluaran' => Pembelian::sum('total_bayar'),
        ];

        // Sales Data
        $salesData = Penjualan::selectRaw('YEAR(created_at) year, MONTH(created_at) month, SUM(total_bayar) as total')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'total' => $item->total
                ];
            });

        // Top Obats
        $topObats = Obat::with('jenisObat')
            ->withCount(['detailPenjualans as total_terjual' => function($query) {
                $query->selectRaw('COALESCE(SUM(jumlah_beli), 0)');
            }])
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // Recent Data
        $recentSales = Penjualan::with('pelanggan')->latest()->take(5)->get();
        $recentPurchases = Pembelian::with('distributor')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->formatted_date = $item->tgl_pembelian 
                    ? Carbon::parse($item->tgl_pembelian)->format('d/m/Y') 
                    : '-';
                return $item;
            });
        $recentDeliveries = Pengiriman::with(['penjualan', 'jenisPengiriman'])->latest()->take(5)->get();
        $recentObats = Obat::with('jenisObat')->latest()->take(5)->get();
        $recentPelanggans = Pelanggan::latest()->take(5)->get();
        $recentDistributors = Distributor::latest()->take(5)->get();

        // Sales Status Stats
        $salesStats = [
            'completed' => Penjualan::where('status_order', 'Selesai')->count(),
            'pending' => Penjualan::whereIn('status_order', ['Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir'])->count(),
            'cancelled' => Penjualan::whereIn('status_order', ['Dibatalkan Pembeli', 'Dibatalkan Penjual'])->count(),
        ];

        // Delivery Stats
        $deliveryStats = [
            'processing' => Pengiriman::where('status_kirim', 'Sedang Dikirim')->count(),
            'shipped' => Pengiriman::where('status_kirim', 'Tiba Di Tujuan')->count(),
            'failed' => Pengiriman::where('status_kirim', 'Gagal')->count(), // Tambahkan jika ada status gagal
        ];

        return view('be.pemilik.index', [
            'title' => 'Dashboard Pemilik',
            'stats' => $stats,
            'salesData' => $salesData,
            'topObats' => $topObats,
            'recentSales' => $recentSales,
            'recentPurchases' => $recentPurchases,
            'recentDeliveries' => $recentDeliveries,
            'recentObats' => $recentObats,
            'recentPelanggans' => $recentPelanggans,
            'recentDistributors' => $recentDistributors,
            'deliveryStats' => $deliveryStats,
            'salesStats' => $salesStats,
        ]);
    }

    public function exportPdf()
    {
        // Basic statistics
        $data = [
            'stats' => [
                'totalObat' => Obat::count(),
                'totalPelanggan' => Pelanggan::count(),
                'totalPenjualan' => Penjualan::count(),
                'totalPembelian' => Pembelian::count(),
                'totalPendapatan' => Penjualan::sum('total_bayar'),
                'totalPengeluaran' => Pembelian::sum('total_bayar'),
            ],
            
            // Sales status statistics (aligned with status_order enum)
            'salesStats' => [
                'completed' => Penjualan::where('status_order', 'Selesai')->count(),
                'pending' => Penjualan::whereIn('status_order', ['Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir'])->count(),
                'cancelled' => Penjualan::whereIn('status_order', ['Dibatalkan Pembeli', 'Dibatalkan Penjual'])->count(),
                'problem' => Penjualan::where('status_order', 'Bermasalah')->count(),
            ],
            
            // Delivery status statistics (aligned with status_kirim enum)
            'deliveryStats' => [
                'shipped' => Pengiriman::where('status_kirim', 'Tiba Di Tujuan')->count(),
                'processing' => Pengiriman::where('status_kirim', 'Sedang Dikirim')->count(),
            ],
            
            // Top 5 best selling medicines with total sold quantities
            'topObats' => Obat::with('jenisObat')
                ->select('obats.*', 
                    DB::raw('(SELECT COALESCE(SUM(detail_penjualans.jumlah_beli), 0) 
                            FROM detail_penjualans 
                            WHERE detail_penjualans.id_obat = obats.id) as total_terjual'))
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get(),
            
            // Recent sales with relationships
            'recentSales' => Penjualan::with(['pelanggan', 'metodeBayar', 'jenisPengiriman'])
                ->orderByDesc('tgl_penjualan')
                ->limit(5)
                ->get(),
            
            // Recent purchases
            'recentPurchases' => Pembelian::with('distributor')
                ->orderByDesc('tgl_pembelian')
                ->limit(5)
                ->get(),
            
            // Recent deliveries with relationships
            'recentDeliveries' => Pengiriman::with(['penjualan', 'penjualan.pelanggan'])
                ->orderByDesc('tgl_kirim')
                ->limit(5)
                ->get(),
        ];

        // Generate PDF with proper settings
        $pdf = PDF::loadView('be.pemilik.export_pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => true,
            ]);

        return $pdf->download('Laporan_Statistik_Apotek_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("Apotek Management System")
            ->setLastModifiedBy("Apotek Management System")
            ->setTitle("Statistik Apotek")
            ->setSubject("Laporan Statistik Apotek")
            ->setDescription("Laporan statistik apotek yang di-generate otomatis oleh sistem.")
            ->setKeywords("apotek statistik laporan")
            ->setCategory("Laporan");

        // Set title and header style
        $sheet->setCellValue('A1', 'LAPORAN STATISTIK APOTEK');
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Set date generated
        $sheet->setCellValue('A2', 'Dibuat pada: ' . date('d F Y H:i:s'));
        $sheet->mergeCells('A2:B2');
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Data headers
        $sheet->setCellValue('A4', 'STATISTIK');
        $sheet->setCellValue('B4', 'NILAI');
        
        // Style for headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A4:B4')->applyFromArray($headerStyle);

        // Data rows
        $data = [
            ['Total Obat', Obat::count()],
            ['Total Pelanggan', Pelanggan::count()],
            ['Total Penjualan', Penjualan::count()],
            ['Total Pembelian', Pembelian::count()],
            ['Total Pendapatan', Penjualan::sum('total_bayar')],
            ['Total Pengeluaran', Pembelian::sum('total_bayar')],
            ['Laba/Rugi', Penjualan::sum('total_bayar') - Pembelian::sum('total_bayar')]
        ];

        $row = 5;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            
            // Format currency for financial values
            if (in_array($item[0], ['Total Pendapatan', 'Total Pengeluaran', 'Laba/Rugi'])) {
                $sheet->getStyle('B' . $row)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
            }
            
            $row++;
        }

        // Style for data rows
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
        ];
        $sheet->getStyle('A5:B' . ($row-1))->applyFromArray($dataStyle);

        // Alternating row colors
        for ($i = 5; $i <= $row-1; $i++) {
            $fillColor = $i % 2 == 0 ? 'E6E6E6' : 'FFFFFF';
            $sheet->getStyle('A' . $i . ':B' . $i)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($fillColor);
        }

        // Auto size columns
        foreach (range('A', 'B') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add some summary styling
        $sheet->getStyle('A' . ($row-1) . ':B' . ($row-1))->getFont()->setBold(true);
        $sheet->getStyle('B' . ($row-1))->getFont()->getColor()->setRGB(
            ($data[6][1] >= 0) ? '2E7D32' : 'C62828' // Green for profit, red for loss
        );

        // Create a second sheet for chart
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle('Grafik');

        // Write the Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Statistik_Apotek_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}