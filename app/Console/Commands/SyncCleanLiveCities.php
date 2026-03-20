<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCleanLiveCities extends Command
{
    protected $signature = 'cities:sync-live-clean
                            {cities=cities_live.sql : Path to the live cities SQL dump}
                            {states=states_live.sql : Path to the live states SQL dump}
                            {--report=storage/app/city_mapping_review.csv : Review CSV for skipped rows}
                            {--output=storage/app/india_cities_clean.csv : Clean CSV output}
                            {--soft-delete-unmatched : Soft delete India city rows that are not in the clean import set}';

    protected $description = 'Clean and sync India cities from live SQL dumps using only valid state mappings and excluding ambiguous cross-state city names';

    private const VALID_INDIA_STATE_NAMES = [
        'Andaman and Nicobar Islands',
        'Andhra Pradesh',
        'Arunachal Pradesh',
        'Assam',
        'Bihar',
        'Chandigarh',
        'Chhattisgarh',
        'Dadra and Nagar Haveli',
        'Daman and Diu',
        'Delhi',
        'Goa',
        'Gujarat',
        'Haryana',
        'Himachal Pradesh',
        'Jammu and Kashmir',
        'Jharkhand',
        'Karnataka',
        'Kerala',
        'Lakshadweep',
        'Madhya Pradesh',
        'Maharashtra',
        'Manipur',
        'Meghalaya',
        'Mizoram',
        'Nagaland',
        'Odisha',
        'Pondicherry',
        'Punjab',
        'Rajasthan',
        'Sikkim',
        'Tamil Nadu',
        'Telangana',
        'Tripura',
        'Uttar Pradesh',
        'Uttarakhand',
        'West Bengal',
    ];

    public function handle(): int
    {
        $citiesFile = base_path($this->argument('cities'));
        $statesFile = base_path($this->argument('states'));
        $reportFile = base_path($this->option('report'));
        $outputFile = base_path($this->option('output'));

        if (!is_file($citiesFile)) {
            $this->error("Cities SQL file not found: {$citiesFile}");
            return self::FAILURE;
        }

        if (!is_file($statesFile)) {
            $this->error("States SQL file not found: {$statesFile}");
            return self::FAILURE;
        }

        $liveStates = $this->parseStates($statesFile);

        if (empty($liveStates)) {
            $this->error('No states could be parsed from the states dump.');
            return self::FAILURE;
        }

        $currentStates = DB::table('states')
            ->select('id', 'country_id', 'name')
            ->get()
            ->keyBy('id');

        $validStateNames = array_flip(self::VALID_INDIA_STATE_NAMES);
        $eligibleStateIds = [];
        $reviewRows = [];
        $invalidLiveStateIds = [];

        foreach ($liveStates as $stateId => $state) {
            if ($state['country_id'] !== 101) {
                continue;
            }

            if (!isset($validStateNames[$state['name']])) {
                $invalidLiveStateIds[$stateId] = $state['name'];
                continue;
            }

            $dbState = $currentStates->get($stateId);
            if (!$dbState || (int) $dbState->country_id !== 101 || $dbState->name !== $state['name']) {
                $reviewRows[] = [
                    'reason' => 'state_mismatch',
                    'city_name' => '',
                    'city_state_id' => $stateId,
                    'city_state_name' => $state['name'],
                    'details' => $dbState
                        ? "db_state={$dbState->name}, db_country_id={$dbState->country_id}"
                        : 'missing_in_db',
                ];
                continue;
            }

            $eligibleStateIds[$stateId] = $state['name'];
        }

        if (empty($eligibleStateIds)) {
            $this->error('No eligible India state IDs matched between the live dump and the current database.');
            return self::FAILURE;
        }

        $rawCities = $this->parseCities($citiesFile, $eligibleStateIds, $liveStates, $reviewRows);

        $cityStates = [];
        foreach ($rawCities as $row) {
            $normalizedName = $this->normalizeName($row['name']);
            $cityStates[$normalizedName][$row['state_id']] = $row['state_name'];
        }

        $ambiguousNames = [];
        foreach ($cityStates as $normalizedName => $states) {
            if (count($states) > 1) {
                $ambiguousNames[$normalizedName] = $states;
            }
        }

        $chosenAmbiguousRows = [];
        foreach ($rawCities as $row) {
            $normalizedName = $this->normalizeName($row['name']);
            if (!isset($ambiguousNames[$normalizedName])) {
                continue;
            }

            if (!isset($chosenAmbiguousRows[$normalizedName]) || $row['city_id'] < $chosenAmbiguousRows[$normalizedName]['city_id']) {
                $chosenAmbiguousRows[$normalizedName] = $row;
            }
        }

        $cleanRows = [];
        $cleanTupleLookup = [];
        $seenCleanRows = [];

        foreach ($rawCities as $row) {
            $normalizedName = $this->normalizeName($row['name']);

            if (isset($ambiguousNames[$normalizedName])) {
                $keptRow = $chosenAmbiguousRows[$normalizedName];

                if ($row['city_id'] !== $keptRow['city_id']) {
                    $reviewRows[] = [
                        'reason' => 'ambiguous_city_name_dropped',
                        'city_name' => $row['name'],
                        'city_state_id' => $row['state_id'],
                        'city_state_name' => $row['state_name'],
                        'details' => 'kept ' . $keptRow['state_id'] . ':' . $keptRow['state_name'],
                    ];
                    continue;
                }

                $reviewRows[] = [
                    'reason' => 'ambiguous_city_name_kept',
                    'city_name' => $row['name'],
                    'city_state_id' => $row['state_id'],
                    'city_state_name' => $row['state_name'],
                    'details' => 'kept as canonical mapping from lowest city id',
                ];
            }

            $tupleKey = $row['state_id'] . '|' . $normalizedName;
            if (isset($seenCleanRows[$tupleKey])) {
                continue;
            }

            $seenCleanRows[$tupleKey] = true;
            $cleanRows[] = $row;
            $cleanTupleLookup[$tupleKey] = true;
        }

        $now = now();
        $imported = 0;
        $updated = 0;
        $softDeleted = 0;

        DB::beginTransaction();

        try {
            foreach ($cleanRows as $row) {
                $existing = DB::table('cities')
                    ->where('state_id', $row['state_id'])
                    ->where('name', $row['name'])
                    ->first();

                if ($existing) {
                    DB::table('cities')
                        ->where('id', $existing->id)
                        ->update([
                            'is_active' => $row['is_active'],
                            'updated_at' => $now,
                            'deleted_at' => null,
                        ]);
                    $updated++;
                } else {
                    DB::table('cities')->insert([
                        'state_id' => $row['state_id'],
                        'name' => $row['name'],
                        'is_active' => $row['is_active'],
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                    ]);
                    $imported++;
                }
            }

            if ($this->option('soft-delete-unmatched')) {
                $candidateStateIds = array_keys($eligibleStateIds + $invalidLiveStateIds);

                DB::table('cities')
                    ->whereIn('state_id', $candidateStateIds)
                    ->orderBy('id')
                    ->select('id', 'state_id', 'name', 'deleted_at')
                    ->chunkById(500, function ($cities) use ($cleanTupleLookup, $now, &$softDeleted) {
                        foreach ($cities as $city) {
                            $tupleKey = $city->state_id . '|' . $this->normalizeName($city->name);
                            if (isset($cleanTupleLookup[$tupleKey])) {
                                continue;
                            }

                            if ($city->deleted_at !== null) {
                                continue;
                            }

                            DB::table('cities')
                                ->where('id', $city->id)
                                ->update([
                                    'is_active' => 0,
                                    'updated_at' => $now,
                                    'deleted_at' => $now,
                                ]);
                            $softDeleted++;
                        }
                    });
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->writeCleanCsv($outputFile, $cleanRows);
        $this->writeReviewCsv($reportFile, $reviewRows);

        $this->info('Live city sync completed.');
        $this->line("Eligible states: " . count($eligibleStateIds));
        $this->line("Imported: {$imported}");
        $this->line("Updated: {$updated}");
        $this->line("Ambiguous city names resolved: " . count($ambiguousNames));
        $this->line("Review rows written: " . count($reviewRows));
        $this->line("Clean rows written: " . count($cleanRows));
        $this->line("Soft deleted: {$softDeleted}");
        $this->line("Clean CSV: {$outputFile}");
        $this->line("Review CSV: {$reportFile}");

        return self::SUCCESS;
    }

    private function parseStates(string $file): array
    {
        $sql = file_get_contents($file);
        if ($sql === false) {
            return [];
        }

        preg_match_all("/\\((\\d+),\\s*(\\d+),\\s*'((?:\\\\'|[^'])*)',\\s*(\\d+)/", $sql, $matches, PREG_SET_ORDER);

        $states = [];
        foreach ($matches as $match) {
            $states[(int) $match[1]] = [
                'country_id' => (int) $match[2],
                'name' => str_replace("\\'", "'", $match[3]),
            ];
        }

        return $states;
    }

    private function parseCities(string $file, array $eligibleStateIds, array $liveStates, array &$reviewRows): array
    {
        $sql = file_get_contents($file);
        if ($sql === false) {
            return [];
        }

        preg_match_all("/\\((\\d+),\\s*(\\d+),\\s*'((?:\\\\'|[^'])*)',\\s*(\\d+)/", $sql, $matches, PREG_SET_ORDER);

        $rows = [];
        foreach ($matches as $match) {
            $stateId = (int) $match[2];
            $name = trim(str_replace("\\'", "'", $match[3]));

            if ($name === '') {
                $reviewRows[] = [
                    'reason' => 'empty_city_name',
                    'city_name' => '',
                    'city_state_id' => $stateId,
                    'city_state_name' => $liveStates[$stateId]['name'] ?? '',
                    'details' => 'blank city name',
                ];
                continue;
            }

            if (!isset($eligibleStateIds[$stateId])) {
                if (isset($liveStates[$stateId]) && (int) $liveStates[$stateId]['country_id'] === 101) {
                    $reviewRows[] = [
                        'reason' => 'excluded_state',
                        'city_name' => $name,
                        'city_state_id' => $stateId,
                        'city_state_name' => $liveStates[$stateId]['name'],
                        'details' => 'state excluded from canonical India import',
                    ];
                }
                continue;
            }

            $rows[] = [
                'city_id' => (int) $match[1],
                'state_id' => $stateId,
                'state_name' => $eligibleStateIds[$stateId],
                'name' => $name,
                'is_active' => isset($match[4]) ? (int) $match[4] : 1,
            ];
        }

        return $rows;
    }

    private function normalizeName(string $name): string
    {
        return mb_strtolower(trim(preg_replace('/\\s+/', ' ', $name)));
    }

    private function writeCleanCsv(string $file, array $rows): void
    {
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($file, 'w');
        fputcsv($handle, ['state_id', 'state_name', 'city_name', 'is_active']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['state_id'],
                $row['state_name'],
                $row['name'],
                $row['is_active'],
            ]);
        }

        fclose($handle);
    }

    private function writeReviewCsv(string $file, array $rows): void
    {
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($file, 'w');
        fputcsv($handle, ['reason', 'city_name', 'city_state_id', 'city_state_name', 'details']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['reason'],
                $row['city_name'],
                $row['city_state_id'],
                $row['city_state_name'],
                $row['details'],
            ]);
        }

        fclose($handle);
    }
}
