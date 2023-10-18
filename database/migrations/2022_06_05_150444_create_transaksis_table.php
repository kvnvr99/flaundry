<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            $table->string('kode_transaksi')->unique();
            $table->integer('kasir_id')->nullable(true);
            $table->integer('permintaan_laundry_id')->nullable(true);
            $table->integer('qc_id')->nullable(true);
            $table->integer('cuci_id')->nullable(true);
            $table->integer('pengeringan_id')->nullable(true);
            $table->integer('setrika_id')->nullable(true);
            $table->integer('outlet_id')->nullable(true);
            $table->integer('member_id')->nullable(true);

            $table->string('nama')->nullable(true);
            $table->string('alamat')->nullable(true);
            $table->string('parfume')->nullable(true);
            $table->string('no_handphone')->nullable(true);

            $table->decimal('total', $precision = 20)->nullable(true);
            $table->decimal('bayar', $precision = 20)->nullable(true);
            $table->enum('pembayaran', ['tunai', 'non_tunai', 'pot_deposit']);
            $table->enum('kategori', ['reguler', 'express', 'super_express']);
            $table->text('note')->nullable(true);
            $table->enum('is_done', ['0', '1'])->default('0');
            $table->enum('status', ['registrasi', 'qc', 'cuci', 'pengeringan', 'setrika', 'expedisi']);

            $table->string('quantity_qc')->nullable(true);
            $table->string('quantity_cuci')->nullable(true);
            $table->string('quantity_pengeringan')->nullable(true);
            $table->string('quantity_setrika')->nullable(true);
            $table->string('quantity_expedisi')->nullable(true);

            $table->string('kg_qc')->nullable(true);
            $table->string('kg_cuci')->nullable(true);
            $table->string('kg_pengeringan')->nullable(true);
            $table->string('kg_setrika')->nullable(true);
            $table->string('kg_expedisi')->nullable(true);

            $table->text('catatan_kurir')->nullable(true);
            $table->enum('kepuasan_pelanggan', ['netral', 'ya', 'tidak'])->default('netral');
            $table->text('catatan_pelanggan')->nullable(true);

            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('deliver_by')->nullable(true);
            $table->timestamp('deliver_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
