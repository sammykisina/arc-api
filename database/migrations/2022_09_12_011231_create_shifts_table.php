<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name');
            $table->date(column: 'start_date');
            $table->time(column: 'start_time');

            $table->date(column: 'end_date');
            $table->time(column: 'end_time');
            $table->integer(column: 'total_amount')->default(0);
            $table->string(column: 'creator');

            $table->boolean(column: 'active');
            $table->timestamps();
        });
    }
};
