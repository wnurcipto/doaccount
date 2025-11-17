<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coa;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coas = [
            // ASET
            ['kode_akun' => '1-0000', 'nama_akun' => 'ASET', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 1, 'parent_id' => null],
            ['kode_akun' => '1-1000', 'nama_akun' => 'ASET LANCAR', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '1-0000'],
            ['kode_akun' => '1-1001', 'nama_akun' => 'Kas', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-1000'],
            ['kode_akun' => '1-1002', 'nama_akun' => 'Bank', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-1000'],
            ['kode_akun' => '1-1003', 'nama_akun' => 'Piutang Usaha', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-1000'],
            ['kode_akun' => '1-1004', 'nama_akun' => 'Persediaan Barang', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-1000'],
            ['kode_akun' => '1-1005', 'nama_akun' => 'Perlengkapan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-1000'],
            
            ['kode_akun' => '1-2000', 'nama_akun' => 'ASET TETAP', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '1-0000'],
            ['kode_akun' => '1-2001', 'nama_akun' => 'Tanah', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2002', 'nama_akun' => 'Bangunan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2003', 'nama_akun' => 'Peralatan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2004', 'nama_akun' => 'Kendaraan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Debit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2005', 'nama_akun' => 'Akumulasi Penyusutan Bangunan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2006', 'nama_akun' => 'Akumulasi Penyusutan Peralatan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '1-2000'],
            ['kode_akun' => '1-2007', 'nama_akun' => 'Akumulasi Penyusutan Kendaraan', 'tipe_akun' => 'Aset', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '1-2000'],

            // LIABILITAS
            ['kode_akun' => '2-0000', 'nama_akun' => 'LIABILITAS', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 1, 'parent_id' => null],
            ['kode_akun' => '2-1000', 'nama_akun' => 'LIABILITAS JANGKA PENDEK', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '2-0000'],
            ['kode_akun' => '2-1001', 'nama_akun' => 'Utang Usaha', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '2-1000'],
            ['kode_akun' => '2-1002', 'nama_akun' => 'Utang Gaji', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '2-1000'],
            ['kode_akun' => '2-1003', 'nama_akun' => 'Utang Pajak', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '2-1000'],
            
            ['kode_akun' => '2-2000', 'nama_akun' => 'LIABILITAS JANGKA PANJANG', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '2-0000'],
            ['kode_akun' => '2-2001', 'nama_akun' => 'Utang Bank', 'tipe_akun' => 'Liabilitas', 'posisi_normal' => 'Kredit', 'level' => 3, 'parent_id' => '2-2000'],

            // EKUITAS
            ['kode_akun' => '3-0000', 'nama_akun' => 'EKUITAS', 'tipe_akun' => 'Ekuitas', 'posisi_normal' => 'Kredit', 'level' => 1, 'parent_id' => null],
            ['kode_akun' => '3-1001', 'nama_akun' => 'Modal Pemilik', 'tipe_akun' => 'Ekuitas', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '3-0000'],
            ['kode_akun' => '3-1002', 'nama_akun' => 'Prive', 'tipe_akun' => 'Ekuitas', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '3-0000'],
            ['kode_akun' => '3-1003', 'nama_akun' => 'Laba Ditahan', 'tipe_akun' => 'Ekuitas', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '3-0000'],
            ['kode_akun' => '3-2001', 'nama_akun' => 'Ikhtisar Laba Rugi', 'tipe_akun' => 'Ekuitas', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '3-0000'],

            // PENDAPATAN
            ['kode_akun' => '4-0000', 'nama_akun' => 'PENDAPATAN', 'tipe_akun' => 'Pendapatan', 'posisi_normal' => 'Kredit', 'level' => 1, 'parent_id' => null],
            ['kode_akun' => '4-1001', 'nama_akun' => 'Pendapatan Jasa', 'tipe_akun' => 'Pendapatan', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '4-0000'],
            ['kode_akun' => '4-1002', 'nama_akun' => 'Pendapatan Penjualan', 'tipe_akun' => 'Pendapatan', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '4-0000'],
            ['kode_akun' => '4-1003', 'nama_akun' => 'Pendapatan Lain-lain', 'tipe_akun' => 'Pendapatan', 'posisi_normal' => 'Kredit', 'level' => 2, 'parent_id' => '4-0000'],

            // BEBAN
            ['kode_akun' => '5-0000', 'nama_akun' => 'BEBAN', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 1, 'parent_id' => null],
            ['kode_akun' => '5-1001', 'nama_akun' => 'Harga Pokok Penjualan (HPP)', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1002', 'nama_akun' => 'Beban Gaji', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1003', 'nama_akun' => 'Beban Sewa', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1004', 'nama_akun' => 'Beban Listrik', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1005', 'nama_akun' => 'Beban Telepon', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1006', 'nama_akun' => 'Beban Perlengkapan', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1007', 'nama_akun' => 'Beban Penyusutan', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1008', 'nama_akun' => 'Beban Administrasi', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
            ['kode_akun' => '5-1009', 'nama_akun' => 'Beban Lain-lain', 'tipe_akun' => 'Beban', 'posisi_normal' => 'Debit', 'level' => 2, 'parent_id' => '5-0000'],
        ];

        foreach ($coas as $coa) {
            Coa::create($coa);
        }
    }
}

