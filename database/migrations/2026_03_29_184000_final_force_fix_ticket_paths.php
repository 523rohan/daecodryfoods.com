<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = DB::table('ticket_files')->get();

        foreach ($files as $file) {
            $currentPath = $file->file_path;
            
            // foolproof: strip 'public/' if it's there
            $cleanPath = str_replace('public/', '', $currentPath);
            $cleanPath = trim($cleanPath, '/');

            // 1. First, search for the exact file in common locations
            $filename = basename($cleanPath);
            $searchPaths = [
                public_path('uploads/ticket/'),
                public_path('uploads/ticket/reply/'),
                public_path('uploads/media/'),
            ];

            $finalPath = $cleanPath; // Default to relative clean path

            foreach ($searchPaths as $dir) {
                if (File::exists($dir)) {
                    $foundFiles = File::files($dir);
                    foreach ($foundFiles as $f) {
                        $fName = $f->getFilename();
                        // Match original name OR handle the '_.trashed-' anomaly
                        if ($fName == $filename || 
                            str_contains($fName, $filename) || 
                            ($filename && str_contains($fName, explode('_', str_replace('Screenshot_', '', $filename))[0]))) {
                            
                            $relativeDir = str_replace(public_path(''), '', $dir);
                            $relativeDir = trim($relativeDir, DIRECTORY_SEPARATOR);
                            $finalPath = $relativeDir . '/' . $fName;
                            break 2;
                        }
                    }
                }
            }

            // Standardize path separators and trim
            $finalPath = str_replace(['//', '\\'], ['/', '/'], $finalPath);
            $finalPath = trim($finalPath, '/');

            DB::table('ticket_files')->where('id', $file->id)->update([
                'file_path' => $finalPath
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
