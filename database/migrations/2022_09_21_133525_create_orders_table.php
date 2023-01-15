<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'number')->unique();
            $table->string(column: 'status');
            $table->float(column: 'sub_total'); // total sum of the prices of the ordered items + plus tax
            $table->float(column: 'discount'); // a % of sub_total
            $table->float(column: 'total'); // sub_total - discount

            $table->foreignId(column: 'table_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId(column: 'payment_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId(column: 'shift_id')
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp(column: 'completed_at')
                ->nullable();
            $table->timestamp(column: 'cancelled_at')
                ->nullable();
            $table->timestamps();
        });
    }
};
