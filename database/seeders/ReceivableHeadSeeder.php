<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceivableHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('receivable_heads')->insert([
            [
                'name' => 'Customer Sell Due',
                'description' => 'This is Customer Sell Due.',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // [
            //     'name' => 'Offers Income',
            //     'description' => 'This is Offers Income.',
            //     'type' => 2,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],

            [
                'name' => 'Employee Advance Salary',
                'description' => 'This is Employee Salary Advance',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Supplier Advance',
                'description' => 'This is Supplier Advance.',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Customer Due',
                'description' => 'This is Customer Due.',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
