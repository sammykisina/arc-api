<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('counter_items', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name');
            $table->integer(column: 'assigned');
            $table->integer(column: 'sold')->default(0);
            $table->integer(column: 'price');
            $table->string(column: 'form'); // to be used when tying to identify the specific product in either Products or Variants table
            $table->integer(column: 'product_id'); // to be used when tying to identify the specific product in either Products or Variants table

            $table->foreignId(column: 'counter_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
