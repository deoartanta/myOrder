<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'store_id'=>1,
            'nm_product'=>'Mie Ayam',
            'hrg_product'=>15000,
        ]);
        DB::table('products')->insert([
            'store_id'=>1,
            'nm_product'=>'Ayam Geprek',
            'hrg_product'=>17000,
        ]);
        DB::table('products')->insert([
            'store_id'=>1,
            'nm_product'=>'Es Jeruk',
            'hrg_product'=>5000,
        ]);
        DB::table('products')->insert([
            'store_id'=>1,
            'nm_product'=>'Es Teh',
            'hrg_product'=>4000,
        ]);
    }
}
