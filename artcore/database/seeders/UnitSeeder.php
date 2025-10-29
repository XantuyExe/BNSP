<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Unit, Category};

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $painting = Category::where('name','PAINTING')->first();
        $sculpt   = Category::where('name','SCULPTURE_3D')->first();
        $furn     = Category::where('name','VINTAGE_FURNITURE')->first();

        if (!$painting || !$sculpt || !$furn) {
            return;
        }

        $units = [
            ['code'=>'1988-LUKISAN-A','name'=>'1988 Lukisan A','category_id'=>$painting->id,'vintage'=>'80s','sale_price'=>25000000,'rent_price_5d'=>1500000],
            ['code'=>'1972-PATUNG-B','name'=>'1972 Patung Keramik B','category_id'=>$sculpt->id,'vintage'=>'70s','sale_price'=>40000000,'rent_price_5d'=>3000000],
            ['code'=>'1968-KURSI-C','name'=>'1968 Kursi Rotan C','category_id'=>$furn->id,'vintage'=>'60s','sale_price'=>10000000,'rent_price_5d'=>800000],
            ['code'=>'1990-LUKISAN-D','name'=>'1990 Lukisan Urban D','category_id'=>$painting->id,'vintage'=>'90s','sale_price'=>18000000,'rent_price_5d'=>1200000],
            ['code'=>'1985-LUKISAN-E','name'=>'1985 Lukisan Pastel E','category_id'=>$painting->id,'vintage'=>'80s','sale_price'=>22000000,'rent_price_5d'=>1400000],
            ['code'=>'1975-PATUNG-C','name'=>'1975 Patung Kayu C','category_id'=>$sculpt->id,'vintage'=>'70s','sale_price'=>35000000,'rent_price_5d'=>2800000],
            ['code'=>'1982-PATUNG-D','name'=>'1982 Patung Bronze D','category_id'=>$sculpt->id,'vintage'=>'80s','sale_price'=>42000000,'rent_price_5d'=>3100000],
            ['code'=>'1970-MEJA-A','name'=>'1970 Meja Retro A','category_id'=>$furn->id,'vintage'=>'70s','sale_price'=>15000000,'rent_price_5d'=>900000],
            ['code'=>'1965-LAMPU-B','name'=>'1965 Lampu Industrial B','category_id'=>$furn->id,'vintage'=>'60s','sale_price'=>9000000,'rent_price_5d'=>700000],
            ['code'=>'1992-KURSI-D','name'=>'1992 Kursi Modern D','category_id'=>$furn->id,'vintage'=>'90s','sale_price'=>12000000,'rent_price_5d'=>850000],

            ['code'=>'1980-LUKISAN-F','name'=>'1980 Lukisan Panorama F','category_id'=>$painting->id,'vintage'=>'80s','sale_price'=>26500000,'rent_price_5d'=>1550000,'description'=>'Panorama senja kota tua dengan sapuan kuas yang luas dan hangat.'],
            ['code'=>'1976-LUKISAN-G','name'=>'1976 Lukisan Laut G','category_id'=>$painting->id,'vintage'=>'70s','sale_price'=>24000000,'rent_price_5d'=>1450000,'description'=>'Lanskap laut hijau kebiruan bertema pelayaran nusantara.'],
            ['code'=>'1969-LUKISAN-H','name'=>'1969 Lukisan Flora H','category_id'=>$painting->id,'vintage'=>'60s','sale_price'=>21000000,'rent_price_5d'=>1300000,'description'=>'Komposisi bunga liar tropis dengan gaya ekspresif.'],
            ['code'=>'1991-LUKISAN-I','name'=>'1991 Lukisan Abstrak I','category_id'=>$painting->id,'vintage'=>'90s','sale_price'=>19500000,'rent_price_5d'=>1180000,'description'=>'Abstraksi warna blok neon yang terinspirasi lampu kota metropolitan.'],

            ['code'=>'1980-PATUNG-E','name'=>'1980 Patung Dirgantara','category_id'=>$sculpt->id,'vintage'=>'80s','sale_price'=>47000000,'rent_price_5d'=>3200000,'description'=>'Patung dirgantara berbentuk aerodinamis sebagai penghormatan bagi penjelajah langit.'],
            ['code'=>'1978-PATUNG-F','name'=>'1978 Patung Marmer Nusantara','category_id'=>$sculpt->id,'vintage'=>'70s','sale_price'=>39000000,'rent_price_5d'=>2950000,'description'=>'Figur marmer putih dengan motif ukir batik di permukaan tubuhnya.'],
            ['code'=>'1983-PATUNG-G','name'=>'1983 Patung Geometri Tembaga','category_id'=>$sculpt->id,'vintage'=>'80s','sale_price'=>45000000,'rent_price_5d'=>3050000,'description'=>'Seni instalasi tembaga berbentuk geometri bertingkat yang memantulkan cahaya.'],
            ['code'=>'1967-PATUNG-H','name'=>'1967 Patung Kayu Penjaga','category_id'=>$sculpt->id,'vintage'=>'60s','sale_price'=>36000000,'rent_price_5d'=>2850000,'description'=>'Totem kayu ulin dengan ukiran figur penjaga desa pesisir Kalimantan.'],

            ['code'=>'1972-KABINET-A','name'=>'1972 Kabinet Teak Heritage','category_id'=>$furn->id,'vintage'=>'70s','sale_price'=>18500000,'rent_price_5d'=>950000,'description'=>'Kabinet jati dengan ukiran flora dan ruang penyimpanan besar.'],
            ['code'=>'1964-MEJA-B','name'=>'1964 Meja Batavia B','category_id'=>$furn->id,'vintage'=>'60s','sale_price'=>16500000,'rent_price_5d'=>880000,'description'=>'Meja makan kayu trembesi dengan kaki lengkung khas gaya Batavia.'],
            ['code'=>'1986-SOFA-C','name'=>'1986 Sofa Velvet Retro','category_id'=>$furn->id,'vintage'=>'80s','sale_price'=>21000000,'rent_price_5d'=>1100000,'description'=>'Sofa tiga dudukan kain velvet merah marun dengan sandaran sayap.'],
            ['code'=>'1979-LAMPU-D','name'=>'1979 Lampu Lantai Orbit','category_id'=>$furn->id,'vintage'=>'70s','sale_price'=>12500000,'rent_price_5d'=>780000,'description'=>'Lampu lantai krom dengan kubah kaca buram bertema orbit luar angkasa.'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['code' => $unit['code']],
                array_merge($unit, [
                    'is_available' => true,
                    'is_sold'      => false,
                ])
            );
        }
    }
}
