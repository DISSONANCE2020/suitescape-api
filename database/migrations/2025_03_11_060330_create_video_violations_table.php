<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('video_violations', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignUuid('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignId('violation_id')->constrained('violations')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_violations');
    }
};
