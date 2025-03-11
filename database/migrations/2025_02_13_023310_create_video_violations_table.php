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
            $table->uuid('video_id');
            $table->unsignedTinyInteger('violation_id'); // Match violations.id
            $table->timestamps();
        
            // Foreign Key Constraints
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('violation_id')->references('id')->on('violations')->onDelete('cascade');
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
