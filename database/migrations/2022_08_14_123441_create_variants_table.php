<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name');
            $table->integer(column: 'cost');
            $table->integer(column: 'retail');

            $table->integer(column: 'stock'); // the number of items ever brought in the club since the start of the club
            $table->integer(column: 'store'); // the current number of items in the store when some have been allocated to the counter
            $table->integer(column: 'sold')->default(0); // the number of items sold from the counter

            $table->integer(column: 'measure'); // 500ml
            $table->boolean(column: 'active')->default(true);
            $table->boolean(column: 'vat');

            $table->foreignId(column: 'product_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
