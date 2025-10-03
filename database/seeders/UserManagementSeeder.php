<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\UserManagement\Module;
use App\Models\UserManagement\SubModule;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $modules = [
            [
                'module_name' => 'Accounts',
                'submodules' => [
                    ['submodule_name' => 'Charts', 'permissions' => ['chart.list',  'chart.update']],
                    ['submodule_name' => 'Second Charts', 'permissions' => ['secondchart.list', 'secondchart.create', 'secondchart.update', 'secondchart.delete']],
                    ['submodule_name' => 'Third Charts', 'permissions' => ['thirdchart.list', 'thirdchart.create', 'thirdchart.update', 'thirdchart.delete']],
                    ['submodule_name' => 'Accounts', 'permissions' => ['account.list', 'account.create', 'account.update', 'account.delete']],
                    ['submodule_name' => 'Branches', 'permissions' => ['branch.list', 'branch.create', 'branch.update', 'branch.delete']],
                    ['submodule_name' => 'Openinig Balance', 'permissions' => ['opening_balance.list', 'opening_balance.create', 'opening_balance.update', 'opening_balance.delete']],
                    // Processing
                    ['submodule_name' => 'Cash Receipt', 'permissions' => ['cash_receipt.list', 'cash_receipt.create', 'cash_receipt.update', 'cash_receipt.delete']],
                    ['submodule_name' => 'Cash Payment', 'permissions' => ['cash_payment.list', 'cash_payment.create', 'cash_payment.update', 'cash_payment.delete']],
                    ['submodule_name' => 'Bank Receipt', 'permissions' => ['bank_receipt.list', 'bank_receipt.create', 'bank_receipt.update', 'bank_receipt.delete']],
                    ['submodule_name' => 'Cash Payment', 'permissions' => ['bank_payment.list', 'bank_payment.create', 'bank_payment.update', 'bank_payment.delete']],
                    ['submodule_name' => 'Journal', 'permissions' => ['journal.list', 'journal.create', 'journal.update', 'journal.delete']],
                    ['submodule_name' => 'DSR Entry', 'permissions' => ['dsr.list', 'dsr.create', 'dsr.update', 'dsr.delete']],
                    ['submodule_name' => 'Payment Approval', 'permissions' => ['payment_approval.list', 'payment_approval.create', 'payment_approval.update', 'payment_approval.delete']],
                    // Reports
                    ['submodule_name' => 'Voucher', 'permissions' => ['voucher.list']],
                    ['submodule_name' => 'General Ledger', 'permissions' => ['general_ledger.list']],
                    ['submodule_name' => 'Balance Sheet', 'permissions' => ['balance_sheet.list']],
                    ['submodule_name' => 'Trial Balance', 'permissions' => ['trial_balance.list']],
                    ['submodule_name' => 'Subtrail Report', 'permissions' => ['subtrail.list']],
                    // ['submodule_name' => 'Profit And Loss', 'permissions' => ['profit_and_loss.list', 'profit_and_loss.create', 'profit_and_loss.update', 'profit_and_loss.delete']],
                ],
            ],
            [
                'module_name' => 'User Management',
                'submodules' => [
                    ['submodule_name' => 'Manage User', 'permissions' => ['user.list', 'user.create', 'user.update', 'user.delete']],
                    ['submodule_name' => 'User Privilages', 'permissions' => ['user_privialages.list', 'user_privialages.create']],
                    ['submodule_name' => 'User Roles', 'permissions' => ['role.list', 'role.create', 'role.update', 'role.delete']],
                    ['submodule_name' => 'Roles Privilages', 'permissions' => ['role_privialages.list', 'role_privialages.create']],
                ],
            ],
            [
                'module_name' => 'My Control Panel',
                'submodules' => [
                    ['submodule_name' => 'Profile', 'permissions' => ['profile.list', 'profile.create', 'profile.update', 'profile.delete']],
                ],
            ],
            // [
            //     'module_name' => 'Inventory',
            //     'submodules' => [
            //         ['submodule_name' => 'Item Setup', 'permissions' => ['item_setup.list', 'item_setup.create', 'item_setup.update', 'item_setup.delete']],
            //         ['submodule_name' => 'Company', 'permissions' => ['company.list', 'company.create', 'company.update', 'company.delete']],
            //         ['submodule_name' => 'Category', 'permissions' => ['category.list', 'category.create', 'category.update', 'category.delete']],
            //         ['submodule_name' => 'Stock Opening', 'permissions' => ['stock_opening.list', 'stock_opening.create', 'stock_opening.update', 'stock_opening.delete']],
            //         ['submodule_name' => 'Stock Details', 'permissions' => ['stock_details.list']],
            //     ],
            // ],
            // [
            //     'module_name' => 'Purchase',
            //     'submodules' => [
            //         ['submodule_name' => 'Supplier Setup', 'permissions' => ['supplier_setup.list', 'supplier_setup.create', 'supplier_setup.update', 'supplier_setup.delete']],
            //         ['submodule_name' => 'Purchase Invoice', 'permissions' => ['purchase_invoice.list', 'purchase_invoice.create', 'purchase_invoice.update', 'purchase_invoice.delete']],
            //     ],
            // ],
            // [
            //     'module_name' => 'Sale',
            //     'submodules' => [
            //         ['submodule_name' => 'Sale Booking', 'permissions' => ['sale_booking.list', 'sale_booking.create', 'sale_booking.update', 'sale_booking.delete']],
            //         ['submodule_name' => 'Sale Invoice', 'permissions' => ['sale_invoice.list', 'sale_invoice.create', 'sale_invoice.update', 'sale_invoice.delete']],
            //         ['submodule_name' => 'Sale Return', 'permissions' => ['sale_return.list', 'sale_return.create', 'sale_return.update', 'sale_return.delete']],
            //     ],
            // ],
        ];

        // foreach($modules as $module){
        //     $submodules = $module['submodules'];
        //     foreach($submodules as $submodule){
        //         $submodule['submodule_name'];
        //         foreach($submodule['permissions'] as $permission){
        //         }
        //     }
        // }

        foreach($modules as $moduleData){
            $module = Module::create([
                'module_name' => $moduleData['module_name']
            ]);

            foreach($moduleData['submodules'] as $submoduleData){
                $submodule = SubModule::create([
                    'module_id' => $module->id,
                    'submodule_name' => $submoduleData['submodule_name']
                ]);

                foreach($submoduleData['permissions'] as $permission){
                    Permission::create([
                        'module_id' => $module->id,
                        'submodule_id' => $submodule->id,
                        'permission_name' => Str::snake($submoduleData['submodule_name']),
                        'name' => $permission
                    ]);
                }
            }
        }

    }
}
