<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kasir')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_meja')->constrained('meja')->onDelete('restrict');
            $table->enum('jenis_pemesanan', ['dine-in', 'takeaway'])->default('dine-in'); 
            $table->string('nama_pelanggan')->nullable();
            $table->date('tanggal');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('kembalian', 12, 2)->default(0);
            $table->enum('status', ['tunggak', 'lunas'])->default('tunggak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};