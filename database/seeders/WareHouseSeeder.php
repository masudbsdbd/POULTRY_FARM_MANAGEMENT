<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            Warehouse::create([
                'warehouse_code' => 'WH001',
                'warehouse_name' => 'Main Storage',
                'warehouse_address' => 'Building A, Zone 3',
                'warehouse_manager' => 1, 
                'warehouse_phone' => '123-456-7890',
                'warehouse_email' => 'mainstorage@example.com',
                'warehouse_status' => 1,
            ]);
    }
}
