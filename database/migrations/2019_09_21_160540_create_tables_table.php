<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name')->unique();
            $table->integer(column: 'number_of_seats');

            $table->boolean(column: 'extendable'); // can more seats be added to the table
            $table->integer(column: 'number_of_extending_seats')
                ->nullable();

            $table->timestamps();
        });
    }
};
