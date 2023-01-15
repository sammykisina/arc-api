<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();
            $table->string(column: 'name');

            $table->foreignId(column: 'shift_id')
                ->index()
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }
};
