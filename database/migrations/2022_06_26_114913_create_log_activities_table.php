<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->string('modul')->nullable(true);
            $table->string('model')->nullable(true);
            $table->string('action')->nullable(true);
            $table->integer('user_id')->nullable(true);
            $table->text('note')->nullable(true);
            $table->text('old_data')->nullable(true);
            $table->text('new_data')->nullable(true);
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
        Schema::dropIfExists('log_activities');
    }
}
