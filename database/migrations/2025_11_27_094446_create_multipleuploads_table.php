<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multipleuploads', function (Blueprint $table) {
            $table->id();
            $table->string('ref_table', 100)->nullable(); // Untuk nama tabel pemilik file
            $table->unsignedBigInteger('ref_id')->nullable(); // Untuk ID data pada tabel tersebut
            $table->string('filename'); // Nama file yang disimpan
            $table->string('original_name'); // Nama asli file
            $table->string('file_path'); // Path file di storage
            $table->integer('file_size')->nullable(); // Ukuran file dalam bytes
            $table->string('mime_type')->nullable(); // Tipe MIME file
            $table->string('extension')->nullable(); // Ekstensi file
            $table->timestamps();

            // Index untuk performa query
            $table->index(['ref_table', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multipleuploads');
    }
};