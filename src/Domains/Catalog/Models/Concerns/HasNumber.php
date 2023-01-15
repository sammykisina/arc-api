<?php

declare(strict_types=1);

namespace Domains\Catalog\Models\Concerns;

use Carbon\Carbon;
use Domains\Catalog\Models\Procurement;
use Illuminate\Database\Eloquent\Model;

trait HasNumber {
    public static function bootHasNumber(): void {
        $date_time = Carbon::now();
        $date = $date_time->toDateString();
        $time = $date_time->toTimeString();

        static::saving(
            fn (Model $model) => $model->number = class_basename($model).'/'.Procurement::count().'/'.$date.'/'.$time
        );
    }
}
