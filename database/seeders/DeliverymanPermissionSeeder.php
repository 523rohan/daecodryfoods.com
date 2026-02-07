<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliverymanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
            'add_deliveryman',
            'edit_deliveryman',
            'assign_deliveryman',
            'delete_deliveryman',
            'deliveryman_config',
            'deliveryman_list',
            'deliveryman_cancel_request',
            'deliveryman_payment_history',
            'deliveryman_payroll_create',
            'deliveryman_payroll_list',
            'deliveryman_payroll_edit',
        ];

        DB::table('permissions')->insert(
            collect($permissions)->map(function ($name, $index) {
                return [
                    'id'         => 142 + $index,
                    'name'       => $name,
                    'group_name' => 'deliveryman',
                    'guard_name' => 'web',
                ];
            })->toArray()
        );        
    }
}
