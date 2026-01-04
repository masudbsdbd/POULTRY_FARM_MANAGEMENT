<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayableHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payable_heads')->insert([

            [
                'name' => 'Purchase Due',
                'description' => 'Purchase Due',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Customer Advance',
                'description' => 'This is Customer Advance',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Employee Salary Payable',
                'description' => 'This is Employee Salary Payable',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Expense Amount',
                'description' => 'This is Expense Amount.',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supplier Due',
                'description' => 'This is Supplier Due.',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ]);
    }
}
