<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bs_types')->insert([
            ['main_type' => 1, 'sub_type' => 1, 'type' => 1, 'name' => 'Cash - Current Assets'],
            ['main_type' => 1, 'sub_type' => 1, 'type' => 2, 'name' => 'Bank - Current Assets'],
            ['main_type' => 3, 'sub_type' => 1, 'type' => null, 'name' => 'Owner’s Capital'],
            ['main_type' => 4, 'sub_type' => null, 'type' => null, 'name' => 'Utilities - Expense'],
            ['main_type' => 1, 'sub_type' => 2, 'type' => 1, 'name' => 'Long-term investments - Non-current Assets'],
            ['main_type' => 1, 'sub_type' => 2, 'type' => 2, 'name' => 'Property, plant and equipment - Non-current Assets'],
            ['main_type' => 1, 'sub_type' => 3, 'type' => 3, 'name' => 'Supplier Advance - Receivable'],
            ['main_type' => 3, 'sub_type' => 2, 'type' => null, 'name' => 'Sales Revenue A/C - Income'],
            ['main_type' => 1, 'sub_type' => 3, 'type' => 1, 'name' => 'Customer Sell Due - Receivable'],
            ['main_type' => 1, 'sub_type' => 3, 'type' => 2, 'name' => 'Employee Advance Salary'],
            ['main_type' => 2, 'sub_type' => 1, 'type' => 2, 'name' => 'Customer Advance - Payable'],
            ['main_type' => 2, 'sub_type' => 1, 'type' => 1, 'name' => 'Purchase Due - Payable'],
            ['main_type' => 3, 'sub_type' => 2, 'type' => null, 'name' => 'Purchase A/C - Income'],
            ['main_type' => 3, 'sub_type' => 2, 'type' => null, 'name' => 'Salary Expense A/C - Income'],
            ['main_type' => 2, 'sub_type' => 1, 'type' => 3, 'name' => 'Employee Salary - Payable'],
            ['main_type' => 2, 'sub_type' => 1, 'type' => 4, 'name' => 'Expense - Payable'],
            ['main_type' => 3, 'sub_type' => 2, 'type' => null, 'name' => 'Office Expense A/C - Income'],
        ]);
    }
}

