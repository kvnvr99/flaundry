<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->integer('transaksi_id');
            $table->integer('harga_id');
            $table->integer('jumlah')->nullable(true);
            $table->decimal('harga_satuan', $precision = 20)->nullable(true);
            $table->decimal('harga_jumlah', $precision = 20)->nullable(true);
            $table->string('qty_kg')->nullable(true);
            $table->string('special_treatment')->nullable(true);
            $table->string('qty_special_treatment')->nullable(true);
            $table->decimal('harga_special_treatment', $precision = 20)->nullable(true);
            $table->decimal('harga_jumlah_special_treatment', $precision = 20)->nullable(true);
            $table->decimal('total', $precision = 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
}
