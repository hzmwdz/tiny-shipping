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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->unsignedDecimal('base_rate', 12, 4)->default(0);
            $table->unsignedDecimal('weight_rate', 12, 4)->default(0);
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
};
