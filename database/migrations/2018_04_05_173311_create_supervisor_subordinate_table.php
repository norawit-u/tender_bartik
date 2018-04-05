<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorSubordinateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisor_subordinate', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger ('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger ('subordinate_id');
            $table->foreign('subordinate_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supervisor_subordinate');
    }
}
