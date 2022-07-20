<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('itemid');
            $table->integer('quantity');
            $table->integer('martianid');
            $table->timestamps();
        });

        DB::table('inventory_supplies')->insert(
            array(
                array(
                    'itemid' => 2,
                    'quantity' => 20,
                    'martianid' => 1,
                ),
                array(
                    'itemid' => 5,
                    'quantity' => 10,
                    'martianid' => 1,
                ),
                array(
                    'itemid' => 3,
                    'quantity' => 30,
                    'martianid' => 2,
                ),
                array(
                    'itemid' => 1,
                    'quantity' => 20,
                    'martianid' => 3,
                ),
                array(
                    'itemid' => 3,
                    'quantity' => 30,
                    'martianid' => 3,
                ),
                array(
                    'itemid' => 2,
                    'quantity' => 10,
                    'martianid' => 3,
                ),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_supplies');
    }
}
