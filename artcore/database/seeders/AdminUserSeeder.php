<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email'=>'admin@artcore.local'],
            ['name'=>'Admin ArtCore','password'=>bcrypt('password'), 'is_admin'=>true, 'phone'=>'08123456789']
        );
    }
}
