<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code', 30)->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('guitar_id')
                ->nullable()
                ->constrained('guitars')
                ->nullOnDelete();

            $table->enum('guitar_type', ['electric', 'acoustic', 'bass']);
            $table->enum('pickup_config', ['SSS', 'HSS', 'HH'])->nullable();
            $table->enum('orientation', ['right', 'left'])->default('right');

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('estimated_price')->nullable();

            $table->enum('status', ['pending', 'produksi', 'selesai'])->default('pending');

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['customer_id', 'created_at']);
            $table->index('guitar_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
