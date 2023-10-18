<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanLaundryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaan_laundries', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->date('tanggal')->nullable();
            $table->string('waktu')->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_jemput', [0, 1])->default(0);
            $table->integer('parfume_id');
            $table->integer('layanan_id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('picked_by')->nullable(true);
            $table->timestamp('picked_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permintaan_laundries');
    }
}
