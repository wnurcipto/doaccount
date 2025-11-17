<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'kode_barang' => 'LAPTOP-001',
                'nama_barang' => 'Laptop HP Core i5 8GB RAM 256GB SSD',
                'kategori' => 'Komputer',
                'satuan' => 'UNIT',
                'harga_beli' => 5000000,
                'harga_jual' => 6500000,
                'stok' => 0,
                'stok_minimal' => 3,
                'keterangan' => 'Laptop untuk kebutuhan bisnis',
                'is_active' => true
            ],
            [
                'kode_barang' => 'LAPTOP-002',
                'nama_barang' => 'Laptop Dell Core i7 16GB RAM 512GB SSD',
                'kategori' => 'Komputer',
                'satuan' => 'UNIT',
                'harga_beli' => 8500000,
                'harga_jual' => 10500000,
                'stok' => 0,
                'stok_minimal' => 2,
                'keterangan' => 'Laptop high performance',
                'is_active' => true
            ],
            [
                'kode_barang' => 'MOUSE-001',
                'nama_barang' => 'Mouse Wireless Logitech M185',
                'kategori' => 'Aksesoris Komputer',
                'satuan' => 'PCS',
                'harga_beli' => 75000,
                'harga_jual' => 120000,
                'stok' => 0,
                'stok_minimal' => 10,
                'keterangan' => 'Mouse wireless dengan baterai tahan lama',
                'is_active' => true
            ],
            [
                'kode_barang' => 'KEYBOARD-001',
                'nama_barang' => 'Keyboard Mechanical RGB',
                'kategori' => 'Aksesoris Komputer',
                'satuan' => 'PCS',
                'harga_beli' => 450000,
                'harga_jual' => 650000,
                'stok' => 0,
                'stok_minimal' => 5,
                'keterangan' => 'Keyboard mechanical dengan lampu RGB',
                'is_active' => true
            ],
            [
                'kode_barang' => 'MONITOR-001',
                'nama_barang' => 'Monitor LED 24 inch Full HD',
                'kategori' => 'Komputer',
                'satuan' => 'UNIT',
                'harga_beli' => 1200000,
                'harga_jual' => 1650000,
                'stok' => 0,
                'stok_minimal' => 5,
                'keterangan' => 'Monitor 24 inch resolusi 1920x1080',
                'is_active' => true
            ],
            [
                'kode_barang' => 'PRINTER-001',
                'nama_barang' => 'Printer Canon G2010 All-in-One',
                'kategori' => 'Perangkat Kantor',
                'satuan' => 'UNIT',
                'harga_beli' => 1850000,
                'harga_jual' => 2350000,
                'stok' => 0,
                'stok_minimal' => 3,
                'keterangan' => 'Printer all-in-one dengan tank ink',
                'is_active' => true
            ],
            [
                'kode_barang' => 'HDISK-001',
                'nama_barang' => 'Hard Disk External 1TB Seagate',
                'kategori' => 'Storage',
                'satuan' => 'PCS',
                'harga_beli' => 650000,
                'harga_jual' => 850000,
                'stok' => 0,
                'stok_minimal' => 8,
                'keterangan' => 'Hard disk external portable 1TB USB 3.0',
                'is_active' => true
            ],
            [
                'kode_barang' => 'SSD-001',
                'nama_barang' => 'SSD Kingston 480GB SATA',
                'kategori' => 'Storage',
                'satuan' => 'PCS',
                'harga_beli' => 550000,
                'harga_jual' => 750000,
                'stok' => 0,
                'stok_minimal' => 10,
                'keterangan' => 'SSD 480GB SATA untuk upgrade laptop/PC',
                'is_active' => true
            ],
            [
                'kode_barang' => 'RAM-001',
                'nama_barang' => 'RAM DDR4 8GB Corsair Vengeance',
                'kategori' => 'Komponen Komputer',
                'satuan' => 'PCS',
                'harga_beli' => 450000,
                'harga_jual' => 650000,
                'stok' => 0,
                'stok_minimal' => 15,
                'keterangan' => 'RAM DDR4 8GB 2666MHz',
                'is_active' => true
            ],
            [
                'kode_barang' => 'WEBCAM-001',
                'nama_barang' => 'Webcam Logitech C270 HD',
                'kategori' => 'Aksesoris Komputer',
                'satuan' => 'PCS',
                'harga_beli' => 320000,
                'harga_jual' => 450000,
                'stok' => 0,
                'stok_minimal' => 8,
                'keterangan' => 'Webcam HD 720p untuk video call',
                'is_active' => true
            ],
            [
                'kode_barang' => 'ROUTER-001',
                'nama_barang' => 'Router TP-Link Dual Band AC1200',
                'kategori' => 'Networking',
                'satuan' => 'UNIT',
                'harga_beli' => 285000,
                'harga_jual' => 420000,
                'stok' => 0,
                'stok_minimal' => 5,
                'keterangan' => 'Router wireless dual band 1200Mbps',
                'is_active' => true
            ],
            [
                'kode_barang' => 'UPS-001',
                'nama_barang' => 'UPS APC 650VA BX650LI',
                'kategori' => 'Perangkat Kantor',
                'satuan' => 'UNIT',
                'harga_beli' => 750000,
                'harga_jual' => 950000,
                'stok' => 0,
                'stok_minimal' => 4,
                'keterangan' => 'UPS 650VA dengan AVR',
                'is_active' => true
            ],
            [
                'kode_barang' => 'HEADSET-001',
                'nama_barang' => 'Headset Gaming HyperX Cloud',
                'kategori' => 'Aksesoris Komputer',
                'satuan' => 'PCS',
                'harga_beli' => 550000,
                'harga_jual' => 750000,
                'stok' => 0,
                'stok_minimal' => 6,
                'keterangan' => 'Headset gaming dengan mic noise cancelling',
                'is_active' => true
            ],
            [
                'kode_barang' => 'CABLE-001',
                'nama_barang' => 'Kabel HDMI 2.0 High Speed 1.5 Meter',
                'kategori' => 'Kabel & Aksesoris',
                'satuan' => 'PCS',
                'harga_beli' => 35000,
                'harga_jual' => 60000,
                'stok' => 0,
                'stok_minimal' => 20,
                'keterangan' => 'Kabel HDMI 2.0 support 4K',
                'is_active' => true
            ],
            [
                'kode_barang' => 'POWERBANK-001',
                'nama_barang' => 'Powerbank Xiaomi 20000mAh',
                'kategori' => 'Aksesoris Mobile',
                'satuan' => 'PCS',
                'harga_beli' => 220000,
                'harga_jual' => 320000,
                'stok' => 0,
                'stok_minimal' => 10,
                'keterangan' => 'Powerbank 20000mAh fast charging',
                'is_active' => true
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
