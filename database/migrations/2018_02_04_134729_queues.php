<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Queues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data');
            $table->enum('type', ['a', 'b', 'c'])->default('a');
            $table->enum('category', ['c1', 'c2'])->default('c2');
            $table->string('reserver_name')->nullable();
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('expire');
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
        Schema::dropIfExists('queues');
    }
}
