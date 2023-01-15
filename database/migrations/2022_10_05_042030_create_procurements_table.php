<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'number')->unique();
            $table->integer(column: 'total_cost')->nullable(); // from supplier
            $table->string(column: 'status'); // pending, delivered
            $table->dateTime(column: 'procurement_date');
            $table->dateTime(column:'due_date');
            $table->dateTime(column: 'delivered_date')->nullable(); // will be used for rating
            $table->dateTime(column: 'cancelled_date')->nullable(); // will be used for rating

            $table->foreignId(column: 'supplier_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
