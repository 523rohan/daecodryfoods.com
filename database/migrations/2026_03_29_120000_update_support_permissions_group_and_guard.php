<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Support permissions
        $permissions = [
            'support.index',
            'support.category.index',
            'support.priority.index',
            'support.ticket.index'
        ];

        foreach ($permissions as $name) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                [
                    'guard_name' => 'web',
                    'group_name' => 'Support',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->where('group_name', 'Support')->delete();
    }
};
