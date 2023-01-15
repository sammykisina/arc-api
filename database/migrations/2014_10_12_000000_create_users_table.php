<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->uuid(column: 'uuid')->unique();
                $table->string(column: 'work_id')->unique();

                $table->string(column: 'first_name');
                $table->string(column: 'last_name');
                $table->string(column: 'email')->unique();
                $table->string(column: 'password');
                $table->foreignId(column: 'role_id')
                        ->index()
                        ->constrained()
                        ->cascadeOnDelete();
                $table->string(column: 'active')->default(false); // only the bartender in shift, admin and super-admin will be active by default
                $table->string(column: 'created_by');
                $table->rememberToken();

                $table->timestamps();
            }
        );
    }
};
