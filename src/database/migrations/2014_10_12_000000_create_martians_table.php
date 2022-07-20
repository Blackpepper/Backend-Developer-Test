<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMartiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('martians', function (Blueprint $table) {
            $table->bigIncrements('martianid');
            $table->string('name', 50);
            $table->integer('age');
            $table->string('gender', 1);
            $table->integer('allow');
            $table->timestamps();
        });

        DB::table('martians')->insert(
            array(
                array(
                    'name' => 'John',
                    'age' => 30,
                    'gender' => 'M',
                    'allow' => 1,
                ),
                array(
                    'name' => 'Paul',
                    'age' => 31,
                    'gender' => 'M',
                    'allow' => 1,
                ),
                array(
                    'name' => 'Test',
                    'age' => 32,
                    'gender' => 'M',
                    'allow' => 0,
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
        Schema::dropIfExists('martians');
    }
}
