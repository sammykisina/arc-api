<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'number')->unique();
            $table->string(column: 'method'); // mpesa or cash
            $table->string(column: 'owner')->nullable(); // mpesa number or name
            $table->string(column: 'code')->nullable(); // mpesa code
            $table->integer(column: 'amount');

            $table->date(column: 'date');
            $table->time(column: 'time');
            $table->timestamps();
        });
    }
};
