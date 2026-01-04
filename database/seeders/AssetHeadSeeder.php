<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('asset_heads')->insert([

            [
                'name' => 'Long-term investments',
                'description' => 'Long-term Assets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Property, plant and equipment',
                'description' => 'Long-term Assets',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
