<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->foreignId(column: 'order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId(column: 'payment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }
};
