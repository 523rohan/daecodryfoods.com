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
            $newPath = $currentPath;

            // 1. Remove public/ if it exists as we handle it via asset() or it's implicitly handled by the web root
            // But actually, the app seems to expect 'public/' internally then strips it in views.
            // However, the user is saying they found the file with a '_.trashed-' prefix.

            // 2. Scan the uploads/ticket and uploads/media directories for the file, handling the _.trashed- prefix
            $filename = basename($currentPath);
            $searchPaths = [
                public_path('uploads/ticket/'),
                public_path('uploads/ticket/reply/'),
                public_path('uploads/media/'),
            ];

            foreach ($searchPaths as $dir) {
                if (File::exists($dir)) {
                    $foundFiles = File::files($dir);
                    foreach ($foundFiles as $f) {
                        $fName = $f->getFilename();
                        // Check if the filename matches or is part of a trashed match
                        if ($fName == $filename || 
                            str_contains($fName, $filename) || 
                            (str_contains($filename, '-') && str_contains($fName, explode('-', $filename)[0]))) {
                            
                            // Determine the base path relative to public/
                            $relativeDir = str_replace(public_path(''), '', $dir);
                            $relativeDir = trim($relativeDir, DIRECTORY_SEPARATOR);
                            $newPath = 'public/' . $relativeDir . '/' . $fName;
                            $newPath = str_replace(['//', '\\'], ['/', '/'], $newPath);
                            
                            DB::table('ticket_files')->where('id', $file->id)->update([
                                'file_path' => $newPath
                            ]);
                            break 2;
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No easy way to reverse this
    }
};
