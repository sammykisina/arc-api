<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name');
            $table->integer(column: 'cost')->nullable();
            $table->integer(column: 'retail')->nullable();

            $table->integer(column: 'stock')->nullable(); // the number of items ever brought in the club since the start of the club
            $table->integer(column: 'store')->nullable(); // the current number of items in the store when some have been allocated to the counter
            $table->integer(column: 'sold')->nullable()->default(0); // the number of items sold from the counter

            $table->integer(column: 'measure')->nullable(); // 500ml
            $table->string(column: 'form'); // independent (no variants) or dependent (has variants)

            $table->boolean(column: 'active')->nullable()->default(true);
            $table->boolean(column: 'vat')->nullable();

            $table->foreignId(column: 'category_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
