<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_table', function (Blueprint $table) {
            $table->bigIncrements('itemid');
            $table->string('name', 50);
            $table->integer('points');
            $table->timestamps();
        });

        DB::table('price_table')->insert(
            array(
                array(
                    'name' => 'Oxygen',
                    'points' => 6
                ),
                array(
                    'name' => 'Water',
                    'points' => 4
                ),
                array(
                    'name' => 'Food',
                    'points' => 3
                ),
                array(
                    'name' => 'Medication',
                    'points' => 2
                ),
                array(
                    'name' => 'Clothing',
                    'points' => 1
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
        Schema::dropIfExists('price_table');
    }
}
