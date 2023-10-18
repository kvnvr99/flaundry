<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpedisiJemputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedisi_jemputs', function (Blueprint $table) {
            $table->id();
            $table->integer('permintaan_laundry_id');
            $table->string('kode', $precision = 20)->nullable();
            $table->string('status_jemput')->nullable();
            $table->decimal('titip_saldo', $precision = 20)->nullable();
            $table->text('catatan')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('expedisi_jemputs');
    }
}
