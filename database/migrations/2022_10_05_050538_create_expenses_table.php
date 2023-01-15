<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->mediumText(column: 'description');
            $table->integer(column: 'amount');
            $table->dateTime(column: 'date');

            $table->foreignId(column: 'spender_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId(column: 'shift_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
