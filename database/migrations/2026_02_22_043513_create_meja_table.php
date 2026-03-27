<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('no_meja', 20)->unique();
            $table->string('tipe_meja', 50)->nullable();
            $table->unsignedInteger('kapasitas');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'terisi', 'reserved'])->default('tersedia');
            $table->timestamps();
            // $table->softDeletes();   // optional kalau mau
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};