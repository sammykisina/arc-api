<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Actions;

use Domains\Fulfillment\Models\Table;
use Domains\Fulfillment\ValueObjects\TableValueObject;

class CreateTable {
    public static function handle(TableValueObject $table): Table {
        return Table::create([
            'name' => $table->name,
            'number_of_seats' => $table->number_of_seats,
            'extendable' => $table->extendable,
            'number_of_extending_seats' => $table->number_of_extending_seats ? $table->number_of_extending_seats : null,
        ]);
    }
}
