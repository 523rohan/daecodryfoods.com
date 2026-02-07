<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganicThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newthemes = [
            [
                'id'         => '4',
                'name'       => 'Organic',
                'code'       => 'organic',
                'is_active'  => '1',
                'is_default' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL
            ],
        ];

        Theme::query()->insert($newthemes);
    }
}
