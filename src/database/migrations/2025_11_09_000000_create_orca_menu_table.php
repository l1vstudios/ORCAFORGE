<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orca_menu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->json('reference_pages')->nullable();
            $table->json('reference_controller')->nullable();
            $table->json('reference_model')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orca_menu');
    }
};
