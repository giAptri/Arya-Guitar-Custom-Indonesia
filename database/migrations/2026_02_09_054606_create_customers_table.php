<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 30)->nullable(); // WA
            $table->timestamps();

            $table->index('name');
            $table->index('phone');
            // Jika mau 1 WA = 1 customer, aktifkan:
            // $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
