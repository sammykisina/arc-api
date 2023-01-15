<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name');
            $table->integer(column: 'number_of_closed_deals')->default(value: 0);
            $table->string(column: 'location');
            $table->string(column: 'phone_number')->unique();
            $table->string(column: 'email')->unique();
            $table->string(column: 'status'); // suspended, active

            $table->timestamps();
        });
    }
};
