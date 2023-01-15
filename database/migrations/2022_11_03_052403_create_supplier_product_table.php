<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_product', function (Blueprint $table) {
            $table->foreignId(column: 'supplier_id')->references(column:'id')->on(table: 'suppliers')->cascadeOnDelete();

            $table->foreignId(column: 'product_id')->references(column:'id')->on(table: 'products')->cascadeOnDelete();
        });
    }
};
