<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeratedByToVideosTable extends Migration
{
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            // Use uuid() if users.id is a UUID. Otherwise, use unsignedBigInteger().
            $table->uuid('moderated_by')
                ->nullable()
                ->after('is_approved');

            $table->foreign('moderated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn('moderated_by');
        });
    }
}
