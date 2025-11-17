<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index()
    {
        $barangQuery = Barang::query();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('nama_barang')->get();
        return view('kartu-stok.index', compact('barangs'));
    }

    public function show(Request $request, Barang $barang)
    {
        // Pastikan user hanya bisa lihat barang miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $barang->user_id !== $user->id) {
            return redirect()->route('kartu-stok.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat kartu stok barang ini');
        }

        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-t'));

        // Ambil transaksi stok masuk (hanya milik user)
        $stokMasukQuery = StokMasuk::where('barang_id', $barang->id)
            ->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        $stokMasukQuery = $this->scopeUser($stokMasukQuery);
        $stokMasuks = $stokMasukQuery->orderBy('tanggal_masuk')
            ->orderBy('created_at')
            ->get();

        // Ambil transaksi stok keluar (hanya milik user)
        $stokKeluarQuery = StokKeluar::where('barang_id', $barang->id)
            ->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        $stokKeluarQuery = $this->scopeUser($stokKeluarQuery);
        $stokKeluars = $stokKeluarQuery->orderBy('tanggal_keluar')
            ->orderBy('created_at')
            ->get();

        // Gabungkan dan urutkan
        $transaksi = [];

        foreach ($stokMasuks as $sm) {
            $transaksi[] = [
                'tanggal' => $sm->tanggal_masuk,
                'no_bukti' => $sm->no_bukti,
                'keterangan' => 'Pembelian' . ($sm->supplier ? ' - ' . $sm->supplier : ''),
                'masuk' => $sm->qty,
                'keluar' => 0,
                'harga' => $sm->harga,
                'created_at' => $sm->created_at
            ];
        }

        foreach ($stokKeluars as $sk) {
            $transaksi[] = [
                'tanggal' => $sk->tanggal_keluar,
                'no_bukti' => $sk->no_bukti,
                'keterangan' => 'Penjualan' . ($sk->customer ? ' - ' . $sk->customer : ''),
                'masuk' => 0,
                'keluar' => $sk->qty,
                'harga' => $sk->harga,
                'created_at' => $sk->created_at
            ];
        }

        // Urutkan berdasarkan tanggal dan created_at
        usort($transaksi, function($a, $b) {
            if ($a['tanggal'] == $b['tanggal']) {
                return $a['created_at'] <=> $b['created_at'];
            }
            return $a['tanggal'] <=> $b['tanggal'];
        });

        // Hitung saldo awal (transaksi sebelum tanggal mulai, hanya milik user)
        $saldoAwalMasukQuery = StokMasuk::where('barang_id', $barang->id)
            ->where('tanggal_masuk', '<', $tanggalMulai);
        $saldoAwalMasukQuery = $this->scopeUser($saldoAwalMasukQuery);
        $saldoAwalMasuk = $saldoAwalMasukQuery->sum('qty');
        
        $saldoAwalKeluarQuery = StokKeluar::where('barang_id', $barang->id)
            ->where('tanggal_keluar', '<', $tanggalMulai);
        $saldoAwalKeluarQuery = $this->scopeUser($saldoAwalKeluarQuery);
        $saldoAwalKeluar = $saldoAwalKeluarQuery->sum('qty');
        
        $saldoAwal = $saldoAwalMasuk - $saldoAwalKeluar;

        // Hitung running balance
        $saldo = $saldoAwal;
        foreach ($transaksi as &$t) {
            $saldo += $t['masuk'] - $t['keluar'];
            $t['saldo'] = $saldo;
            $t['nilai'] = $saldo * $barang->harga_beli;
        }

        return view('kartu-stok.show', compact('barang', 'transaksi', 'saldoAwal', 'tanggalMulai', 'tanggalSelesai'));
    }
}
