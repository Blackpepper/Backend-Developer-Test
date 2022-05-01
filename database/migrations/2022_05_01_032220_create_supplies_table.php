<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('point');
            $table->timestamps();
        });

        $supplies = [
            ['name' => 'Oxygen', 'point' => 6],
            ['name' => 'Water', 'point' => 4],
            ['name' => 'Food', 'point' => 3],
            ['name' => 'Medication', 'point' => 2],
            ['name' => 'Clothing', 'point' => 1]
        ];

        // Insert supplies when creating a table
        DB::table('supplies')->insert($supplies);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplies');
    }
};
