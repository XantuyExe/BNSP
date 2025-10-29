<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['PAINTING','SCULPTURE_3D','VINTAGE_FURNITURE'] as $name) {
            Category::firstOrCreate(['name'=>$name]);
        }
    }
}
