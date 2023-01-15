<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shift_workers', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->foreignId(column: 'shift_id')
            ->constrained()
            ->cascadeOnDelete();

            $table->foreignId(column: 'user_id')->constrained()->cascadeOnDelete();
            $table->unique(columns: ['shift_id', 'user_id']);

            $table->timestamps();
        });
    }
};
