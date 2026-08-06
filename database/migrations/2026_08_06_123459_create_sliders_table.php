<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar');                 // path file gambar (disk: public)
            $table->string('url_tujuan')->nullable();  // link saat banner diklik (opsional)
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('status')->default(true);  // true = aktif/tampil, false = nonaktif
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};