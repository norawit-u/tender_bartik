<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('status');
            $table->date('start');
            $table->date('end');
            $table->string('description');
            $table->unsignedInteger('assignee')->nullable();
            $table->unsignedInteger('assigner')->nullable();
            $table->foreign('assignee')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigner')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
