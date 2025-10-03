<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product\Item;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account\Account;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Area;
use App\Models\Configuration\City;
use Spatie\Permission\Models\Role;
use Database\Seeders\AccountSeeder;
use App\Models\Configuration\SubArea;
use App\Models\Configuration\Category;
use Spatie\Permission\Models\Permission;
use App\Models\Configuration\Classification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        $role = Role::create(['name' => 'SuperAdmin','status' => 2]);
        $user = User::create([
            'name'     => 'Admin',
            'role_id'     => $role->id,
            'email'     => 'admin@admin.com',
            'email_verified_at' => '2022-08-24 18:22:13',
            'account_type'     => 'admin',
            'password'      => bcrypt('password'),
        ]);

       

        $cities = ['Charsadda', 'Peshawar', 'Mardan', 'Shabqadar', 'Nowshera'];
        foreach ($cities as $city) {
            City::create([
                'city_name' => $city,
            ]);
        }
       

        Category::create([
            'category_name' => "NO CATEGORY",
            'category_status' => 3,
        ]);

        $categories = ['Grocery', 'Beverages', 'Snacks', 'Fruits', 'Vegetables'];

        foreach ($categories as $category) {
            Category::create([
                'category_name' => $category,
                'category_status' => 1,
            ]);
        }

        // $this->call([
        //     UserManagementSeeder::class,
        // ]);
        // $role->givePermissionTo(Permission::all());
        // $user->assignRole($role);

        // $userrole = Role::create(['name' => 'Data Operator','status' => 1]);
        // $operator = User::create([
        //     'name'     => 'Operator',
        //     'role_id'     => $userrole->id,
        //     'email'     => 'operator@admin.com',
        //     'email_verified_at' => '2022-08-24 18:22:13',
        //     'account_type'     => 'admin',
        //     'password'      => bcrypt('password'),
        // ]);
        
        // $OperatorPermission = Permission::whereIn('permission_name',
        // [
        //     'voucher',
        //     'fund_transfer',
        //     'fund_in_transit',
        //     'expense',
        //     'general_ledger',
        //     'stock_request',
        //     'stock_request_approval',
        //     'stock_issue',
        //     'stock_in_transit',
        //     'stock_receive',
        //     'stock_details',
        //     'sale_inquiry',
        //     'sale_quotation',
        //     'sale_booking',
        //     'sale_invoice',
        //     'sale_return',
        //     'verification',
        //     'processing',
        //     'customer',
        //     'delivery',
        //     'customer_collection',
        //     'crc_verification',
        //     'sallary_calculation',
        //     'staff_advance',
        //     'staff_salary',
        //     'attentance',
        //     'profile',
        // ])->get();
        // $userrole->givePermissionTo($OperatorPermission);

        // For a supplier
        // $supplier = Account::create([
        //     'level_one' => 3,
        //     'level_two' => 2,
        //     'level_three' => 2,
        //     'account_name' => "LuqmanzaiSupplier",
        //     'account_type' => 5,
        //     'account_code' => '03-02-002',
        //     'type' => 'supplier',
        //     'account_status' => 1,
        //     'owner_name' => 'Luqmanzai',
        //     'address' => 'Charsadda, KPK',
        //     'contact' => '0000000000',
        //     'email' => 'supplier@admin.com',
        // ]);

        // FIrst Item
        // Item::create([
        //     'item_name' => 'Sample Item',
        //     'supplier_id' => $supplier->id, // Assuming supplier with ID 1 exists
        //     'category_id' => 1, // Assuming category with ID 1 exists
        //     'item_code' => 'ITEM-001',
        //     'retail' => 100,
        //     'retail_percentage' => 10,
        //     'retail_value' => 10,
        //     'whole_sale' => 90,
        //     'whole_sale_percentage' => 5,
        //     'whole_sale_value' => 5,
        //     'distributer' => 80,
        //     'distributer_percentage' => 2,
        //     'distributer_value' => 2,
        //     'purchase' => 70,
        //     'packing' => 1,
        //     'can_purchase' => 'yes',
        //     'can_sale' => 'yes',
        //     'item_status' => 1,
        // ]);

        // second Item
        // Item::create([
        //     'item_name' => 'Sample Item 2',
        //     'supplier_id' => $supplier->id, // Assuming supplier with ID 1 exists
        //     'category_id' => 2, // Assuming category with ID 1 exists
        //     'item_code' => 'ITEM-002',
        //     'retail' => 200,
        //     'retail_percentage' => 15,
        //     'retail_value' => 30,
        //     'whole_sale' => 180,
        //     'whole_sale_percentage' => 10,
        //     'whole_sale_value' => 20,
        //     'distributer' => 160,
        //     'distributer_percentage' => 5,
        //     'distributer_value' => 8,
        //     'purchase' => 140,
        //     'packing' => 12,
        //     'can_purchase' => 'yes',
        //     'can_sale' => 'yes',
        //     'item_status' => 1,
        // ]);

        // For a customer
        // $customer = Account::create([
        //     'level_one' => 5,
        //     'level_two' => 5,
        //     'level_three' => 4,
        //     'account_name' => "CustomerAccount",
        //     'account_type' => 9,
        //     'account_code' => '05-05-004',
        //     'type' => 'customer',
        //     'customer_type' => 2,
        //     'account_status' => 1,
        //     'city_id' => 1,
        //     'area_id' => 1,
        //     'subarea_id' => 1,
        //     'classification_id' => 1,
        //     'owner_name' => 'CustomerAccount',
        //     'address' => 'Charsadda, KPK',
        //     'contact' => '0000000000',
        //     'email' => 'customer@admin.com',
        // ]);

        // For salesman

        // $customer = Account::create([
        //     'level_one' => 5,
        //     'level_two' => 8,
        //     'level_three' => 8,
        //     'account_name' => "LuqmanzaiSalesman",
        //     'account_type' => 9,
        //     'account_code' => '05-08-008',
        //     'type' => 'employee',
        //     'account_status' => 1,
        //     'owner_name' => 'LuqmanzaiSalesman',
        //     'address' => 'Charsadda, KPK',
        //     'contact' => '0000000000',
        //     'email' => 'salesman@admin.com',
        // ]);


    }
}
