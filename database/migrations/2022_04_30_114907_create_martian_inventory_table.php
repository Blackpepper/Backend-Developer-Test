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
        Schema::create('martian_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('martian_id');
            $table->integer('trade_item_id');
            $table->integer('qty');
            $table->timestamps();
            $table->unique((['martian_id', 'trade_item_id']));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('martian_inventory');
    }
};
