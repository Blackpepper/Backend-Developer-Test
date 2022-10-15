<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('points', false, true);
            $table->timestamps();
        });

        Schema::create('martians_x_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('martian_id', false, true);
            $table->bigInteger('item_id', false, true);
            $table->integer('quantity', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('martians_x_items');
        Schema::dropIfExists('items');
    }
}
