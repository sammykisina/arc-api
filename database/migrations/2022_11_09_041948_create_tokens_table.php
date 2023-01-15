<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'name')->nullable()->unique();
            $table->integer(column: 'number_of_single_pieces');
            $table->integer(column: 'measure');
            $table->string(column: 'owner');
            $table->boolean(column: 'added_to_store')->default(false);
            $table->string(column: 'item_type')->nullable();
            $table->integer(column: 'item_id')->nullable();
            $table->boolean(column: "approved")->default(false);

            $table->timestamps();
        });
    }
};
