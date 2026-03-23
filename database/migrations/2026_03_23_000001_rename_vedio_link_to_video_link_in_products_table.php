<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVedioLinkToVideoLinkInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('products', 'vedio_link')) {
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('vedio_link', 'video_link');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('products', 'video_link')) {
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('video_link', 'vedio_link');
            });
        }
    }
}
