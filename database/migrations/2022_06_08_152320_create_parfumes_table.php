<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParfumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parfumes', function (Blueprint $table) {
            $table->id();
            $table->string('kode', $precision = 20);
            $table->string('nama', $precision = 50);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parfumes');
    }
}
