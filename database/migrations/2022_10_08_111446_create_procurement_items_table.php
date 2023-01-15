<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name'); // tusker_cedar, play etc
            $table->string(column: 'form'); // crate,pack,box,singles
            $table->integer(column:'form_quantity')->nullable(); // 2 ==>  (2 crates)
            $table->integer(column: 'number_of_pieces_in_form')->nullable(); // 24 pieces per crate
            $table->integer(column: 'number_of_single_pieces')->nullable(); // 20 mzinga
            $table->integer(column: 'measure'); // 500 ml, 250 ml
            $table->integer(column: 'item_id');
            $table->string(column: 'type'); // Product or Variant
            $table->boolean(column: 'added_to_store')->default(false);

            $table->foreignId(column:'procurement_id')
            ->index()
            ->constrained()
            ->cascadeOnDelete();

            $table->timestamps();
        });
    }
};
