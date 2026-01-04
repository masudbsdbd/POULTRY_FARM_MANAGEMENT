<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Role' => [
                'role-list', 'role-create', 'role-edit', 'role-delete',
                'order' => 1,
            ],
            'User' => [
                'user-list', 'user-create', 'user-edit', 'user-delete',
                'order' => 2,
            ],
            'Product' => [
                'product-list', 'product-create', 'product-edit', 'product-delete',
                'order' => 3,
            ],
            'Category' => [
                'category-list', 'category-create', 'category-edit', 'category-delete',
                'order' => 4,
            ],
            'Sub Category' => [
                'sub-category-list', 'sub-category-create', 'sub-category-edit', 'sub-category-delete',
                'order' => 5,
            ],
            'Brand' => [
                'brand-list', 'brand-create', 'brand-edit', 'brand-delete',
                'order' => 6,
            ],
            'Customer' => [
                'customer-list', 'customer-create', 'customer-edit', 'customer-delete', 'customer-payment',
                'order' => 7,
            ],
            'Supplier' => [
                'supplier-list', 'supplier-create', 'supplier-edit', 'supplier-delete', 'supplier-payment',
                'order' => 8,
            ],
            'Purchase' => [
                'purchase-list', 'purchase-create', 'purchase-edit', 'purchase-delete', 'purchase-payment',
                'order' => 9,
            ],
            'Purchase Return' => [
                'purchase-return-list', 'purchase-return-create', 'purchase-return-edit', 'purchase-return-delete',
                'order' => 10,
            ],
            'Sell' => [
                'sell-list', 'sell-create', 'sell-edit', 'sell-delete', 'sell-payment', 'sell-delivery',
                'order' => 11,
            ],
            'Income' => [
                'income-list', 'income-create', 'income-edit', 'income-delete',
                'order' => 12,
            ],
            'Income List' => [
                'income-list-list', 'income-list-create', 'income-list-edit', 'income-list-delete',
                'order' => 13,
            ],
            'Sell Return' => [
                'sell-return-list', 'sell-return-create', 'sell-return-edit', 'sell-return-delete',
                'order' => 14,
            ],
            'Expense Head' => [
                'expense-head-list', 'expense-head-create', 'expense-head-edit', 'expense-head-delete',
                'order' => 15,
            ],
            'Expense' => [
                'expense-list', 'expense-create', 'expense-edit', 'expense-delete',
                'order' => 16,
            ],
            'Damage' => [
                'damage-list', 'damage-create', 'damage-edit', 'damage-delete',
                'order' => 17,
            ],
            'Bank' => [
                'bank-list', 'bank-create', 'bank-edit', 'bank-delete',
                'order' => 18,
            ],
            'Bank Transaction' => [
                'bank-transaction', 'bank-diposit', 'bank-withdraw',
                'order' => 19,
            ],
            'Employee' => [
                'employee-list', 'employee-create', 'employee-edit', 'employee-delete',
                'order' => 20,
            ],
            'Employee Transaction' => [
                'employee-transaction-list', 'employee-transaction-create', 'employee-transaction-edit', 'employee-transaction-delete',
                'order' => 21,
            ],
            'Investor' => [
                'investor-list', 'investor-create', 'investor-edit', 'investor-delete',
                'order' => 22,
            ],
            'Investment' => [
                'investment-list', 'investment-create', 'investment-edit', 'investment-delete',
                'order' => 23,
            ],
            'Unit' => [
                'unit-list', 'unit-create', 'unit-edit', 'unit-delete',
                'order' => 24,
            ],
            'Customer Type' => [
                'customer-type-list', 'customer-type-create', 'customer-type-edit', 'customer-type-delete',
                'order' => 25,
            ],
            'Stock' => [
                'stock-list',
                'order' => 26,
            ],
            'Reports' => [
                'balance-sheet-report-list', 'profit-list', 'delivery-report-list', 'purchase-report-list', 
                'expense-report-list', 'sell-report-list', 'discount-report-list', 'damage-report-list',
                'order' => 27,
            ],
            'Maintain' => [
                'asset-maintain', 'balance-sheet-maintain', 'general-setting-maintain', 'account-maintain',
                'order' => 28,
            ],
        ];

        foreach ($permissions as $section => $items) {
            foreach ($items as $key => $item) {
                if ($key !== 'order') {
                    Permission::create([
                        'section_name' => $section,
                        'name' => $item
                    ]);
                } else {
                    // Update the order field after creating permissions
                    Permission::where('section_name', $section)->update([
                        'order' => $item
                    ]);
                }
            }
        }
    }
}
