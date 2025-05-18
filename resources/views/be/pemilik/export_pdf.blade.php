<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik Apotek</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; margin-bottom: 5px; }
        .date { text-align: center; margin-bottom: 20px; color: #555; }
        h2 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f2f2f2; text-align: left; padding: 8px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .success { background-color: #28a745; color: white; }
        .warning { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <h1>Laporan Statistik Apotek</h1>
    <div class="date">Dicetak pada: {{ now()->format('d F Y H:i') }}</div>

    <h2>Statistik Utama</h2>
    <table>
        <tr>
            <th>Total Obat</th>
            <td>{{ $stats['totalObat'] }}</td>
            <th>Total Pelanggan</th>
            <td>{{ $stats['totalPelanggan'] }}</td>
        </tr>
        <tr>
            <th>Total Penjualan</th>
            <td>{{ $stats['totalPenjualan'] }}</td>
            <th>Total Pembelian</th>
            <td>{{ $stats['totalPembelian'] }}</td>
        </tr>
    </table>

    <h2>Ringkasan Keuangan</h2>
    <table>
        <tr>
            <th>Total Pendapatan</th>
            <td class="text-right">Rp {{ number_format($stats['totalPendapatan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Pengeluaran</th>
            <td class="text-right">Rp {{ number_format($stats['totalPengeluaran'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Laba/Rugi</th>
            <td class="text-right">Rp {{ number_format($stats['totalPendapatan'] - $stats['totalPengeluaran'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <h2>Status Penjualan & Pengiriman</h2>
    <table>
        <tr>
            <th>Status Penjualan</th>
            <th>Jumlah</th>
            <th>Status Pengiriman</th>
            <th>Jumlah</th>
        </tr>
        <tr>
            <td>Selesai</td>
            <td>{{ $salesStats['completed'] ?? 0 }}</td>
            <td>Tiba Di Tujuan</td>
            <td>{{ $deliveryStats['shipped'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Pending</td>
            <td>{{ $salesStats['pending'] ?? 0 }}</td>
            <td>Sedang Dikirim</td>
            <td>{{ $deliveryStats['processing'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Dibatalkan</td>
            <td>{{ $salesStats['cancelled'] ?? 0 }}</td>
        </tr>
    </table>

    <h2>5 Obat Terlaris</h2>
    <table>
        <tr>
            <th>Nama Obat</th>
            <th>Jenis</th>
            <th>Terjual</th>
            <th>Stok</th>
        </tr>
        @foreach($topObats as $obat)
        <tr>
            <td>{{ $obat->nama_obat }}</td>
            <td>{{ $obat->jenisObat->nama_jenis ?? '-' }}</td>
            <td>{{ $obat->total_terjual }}</td>
            <td>{{ $obat->stok }}</td>
        </tr>
        @endforeach
    </table>

    <h2>5 Penjualan Terakhir</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
        @foreach($recentSales as $penjualan)
        <tr>
            <td>{{ $penjualan->id }}</td>
            <td>{{ $penjualan->pelanggan->nama ?? 'Guest' }}</td>
            <td class="text-right">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
            <td>{{ $penjualan->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>

    <h2>5 Pembelian Terakhir</h2>
    <table>
        <tr>
            <th>No Nota</th>
            <th>Distributor</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
        @foreach($recentPurchases as $pembelian)
        <tr>
            <td>{{ $pembelian->no_nota }}</td>
            <td>{{ $pembelian->distributor->nama_distributor ?? '-' }}</td>
            <td class="text-right">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</td>
            <td>{{ $pembelian->tgl_pembelian ? Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') : '-' }}</td>
        </tr>
        @endforeach
    </table>

    <h2>5 Pengiriman Terakhir</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Penjualan</th>
            <th>Status</th>
        </tr>
        @foreach($recentDeliveries as $pengiriman)
        <tr>
            <td>{{ $pengiriman->id }}</td>
            <td>#{{ $pengiriman->penjualan->id ?? '-' }}</td>
            <td>
                <span class="badge {{ $pengiriman->status_kirim == 'Tiba Di Tujuan' ? 'success' : 'warning' }}">
                    {{ $pengiriman->status_kirim }}
                </span>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>