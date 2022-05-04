<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologyablesTable extends Migration
{
    public function up()
    {
        Schema::create('technologyables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technology_id')->constrained();
            $table->unsignedBigInteger('technologyable_id');
            $table->string('technologyable_type');

            //

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('technologyables');
    }
}
