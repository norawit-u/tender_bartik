<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('start');
            $table->date('end');
            $table->string('type');
            $table->string('status');
            $table->string('note');
            $table->unsignedInteger ('leaver_id');
            $table->unsignedInteger ('substitution_id');
            $table->unsignedInteger ('task_id');
            $table->foreign('leaver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('substitution_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
