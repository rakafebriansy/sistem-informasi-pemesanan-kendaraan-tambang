<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usage_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('region_id');
            $table->uuid('renter_id');
            $table->uuid('driver_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->float('fuel_consumption')->nullable();
            $table->enum('status', ['not_accepted_yet', 'accepted_by_manager', 'accepted_by_chief', 'done', 'canceled'])->default('not_accepted_yet');
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('renter_id')->references('id')->on('employees');
            $table->foreign('driver_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_histories');
    }
};
