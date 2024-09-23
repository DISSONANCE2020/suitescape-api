<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->foreignUuid('listing_id');
            $table->foreignUuid('coupon_id')->nullable();
            $table->decimal('amount', 10, 2, true);
            $table->decimal('base_amount', 10, 2, true);
            $table->text('message')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'cancelled', 'completed', 'to_rate'])->default('upcoming');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
