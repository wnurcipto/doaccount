<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periode;
use Carbon\Carbon;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $tahun = (int) $now->format('Y');
        $bulan = (int) $now->format('m');

        $items = [];

        // Seed 12 bulan untuk tahun berjalan, tandai bulan berjalan sebagai Open, sisanya Closed
        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($tahun, $m, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $items[] = [
                'tahun' => $tahun,
                'bulan' => $m,
                'status' => $m === $bulan ? 'Open' : 'Closed',
                'tanggal_mulai' => $start->toDateString(),
                'tanggal_selesai' => $end->toDateString(),
            ];
        }

        foreach ($items as $data) {
            Periode::updateOrCreate(
                ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
                $data
            );
        }
    }
}
