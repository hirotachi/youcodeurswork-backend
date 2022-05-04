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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('location');
            $table->string('image')->nullable();
            $table->enum('type', ['full-time', 'part-time', 'freelance', 'internship']);
            $table->foreignId('user_id')->constrained("users")->cascadeOnDelete();
            $table->string("company_name");
            $table->string("company_site");
            $table->enum("apply_by", ["email", "url"])->default("email");
            $table->string("company_logo")->nullable();
            $table->boolean("remote")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
