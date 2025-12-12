<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_rule_id');
            $table->unsignedInteger('region_level_1_id');
            $table->string('region_level_1_name');
            $table->unsignedInteger('region_level_2_id')->nullable();
            $table->string('region_level_2_name')->nullable();
            $table->unsignedInteger('region_level_3_id')->nullable();
            $table->string('region_level_3_name')->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->index('shipping_rule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_regions');
    }
};
