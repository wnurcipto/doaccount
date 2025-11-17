<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin sebagai Enterprise (bukan owner)
        if (!User::where('email', 'admin@ramaadvertize.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@ramaadvertize.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'plan' => 'enterprise',
                'is_owner' => false, // Enterprise, bukan owner
            ]);
        } else {
            // Update existing user to enterprise (bukan owner)
            $user = User::where('email', 'admin@ramaadvertize.com')->first();
            $user->update([
                'plan' => 'enterprise',
                'is_owner' => false, // Enterprise, bukan owner
            ]);
        }

        // Buat user Wahid sebagai OWNER (satu-satunya owner)
        if (!User::where('email', 'wahid@tpmcmms.id')->exists()) {
            User::create([
                'name' => 'Wahid',
                'email' => 'wahid@tpmcmms.id',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'plan' => 'enterprise',
                'is_owner' => true, // HANYA wahid yang owner
            ]);
        } else {
            $user = User::where('email', 'wahid@tpmcmms.id')->first();
            $user->update([
                'plan' => 'enterprise',
                'is_owner' => true, // HANYA wahid yang owner
            ]);
        }
    }
}
