<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('site')->index();
            $table->uuid('origin_id')->nullable()->index();
            $table->boolean('published')->default(true);
            $table->string('slug')->nullable();
            $table->string('uri')->nullable()->index();
            $table->string('date')->nullable()->index();
            $table->integer('order')->nullable()->index();
            $table->string('collection')->index();
            $table->string('blueprint', 30)->nullable()->index();
            $table->json('data');
            $table->timestamps();

            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('entries');
    }
};
