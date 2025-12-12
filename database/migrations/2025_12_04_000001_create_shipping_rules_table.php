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
        Schema::create('shipping_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_carrier_id');
            $table->string('title');
            $table->unsignedDecimal('base_rate', 12, 4)->default(0);
            $table->unsignedDecimal('weight_rate', 12, 4)->default(0);
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->index('shipping_carrier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_rules');
    }
};
