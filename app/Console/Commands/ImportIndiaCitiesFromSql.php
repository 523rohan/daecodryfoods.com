<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportIndiaCitiesFromSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cities:import-india-sql {file=cities.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import India cities from a phpMyAdmin SQL dump into the cities table without wiping existing data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $file = base_path($this->argument('file'));

        if (!is_file($file)) {
            $this->error("SQL file not found: {$file}");
            return self::FAILURE;
        }

        $indiaStateIds = DB::table('states')->where('country_id', 101)->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (empty($indiaStateIds)) {
            $this->error('No India states found in the states table.');
            return self::FAILURE;
        }

        $indiaStateLookup = array_flip($indiaStateIds);
        $handle = fopen($file, 'r');

        if ($handle === false) {
            $this->error("Unable to open file: {$file}");
            return self::FAILURE;
        }

        $inInsertBlock = false;
        $imported = 0;
        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            while (($line = fgets($handle)) !== false) {
                $trimmed = trim($line);

                if (str_starts_with($trimmed, 'INSERT INTO `cities`')) {
                    $inInsertBlock = true;
                    continue;
                }

                if (!$inInsertBlock) {
                    continue;
                }

                if ($trimmed === '' || $trimmed[0] !== '(') {
                    if (str_ends_with($trimmed, ';')) {
                        $inInsertBlock = false;
                    }
                    continue;
                }

                $tuple = trim($trimmed, " \t\n\r\0\x0B,;");
                $tuple = trim($tuple, '()');
                $columns = str_getcsv($tuple, ',', "'", "\\");

                if (count($columns) < 4) {
                    $skipped++;
                    if (str_ends_with($trimmed, ';')) {
                        $inInsertBlock = false;
                    }
                    continue;
                }

                $stateId = (int) $columns[1];

                if (!isset($indiaStateLookup[$stateId])) {
                    if (str_ends_with($trimmed, ';')) {
                        $inInsertBlock = false;
                    }
                    continue;
                }

                $name = trim($columns[2]);
                $isActive = isset($columns[3]) ? (int) $columns[3] : 1;

                if ($name === '') {
                    $skipped++;
                    if (str_ends_with($trimmed, ';')) {
                        $inInsertBlock = false;
                    }
                    continue;
                }

                $existing = DB::table('cities')
                    ->where('state_id', $stateId)
                    ->where('name', $name)
                    ->first();

                if ($existing) {
                    DB::table('cities')
                        ->where('id', $existing->id)
                        ->update([
                            'is_active' => $isActive,
                            'updated_at' => now(),
                            'deleted_at' => null,
                        ]);
                    $updated++;
                } else {
                    DB::table('cities')->insert([
                        'state_id' => $stateId,
                        'name' => $name,
                        'is_active' => $isActive,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'deleted_at' => null,
                    ]);
                    $imported++;
                }

                if (str_ends_with($trimmed, ';')) {
                    $inInsertBlock = false;
                }
            }

            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            fclose($handle);
            DB::rollBack();
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Imported: {$imported}");
        $this->info("Updated: {$updated}");
        $this->info("Skipped: {$skipped}");
        $this->info('India city import completed successfully.');

        return self::SUCCESS;
    }
}
