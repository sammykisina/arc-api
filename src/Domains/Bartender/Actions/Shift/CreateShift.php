<?php

declare(strict_types=1);

namespace Domains\Bartender\Actions\Shift;

use Carbon\Carbon;
use Domains\Bartender\Models\Counter;
use Domains\Bartender\Models\Shift;
use Domains\Bartender\Models\ShiftWorkers;
use Domains\Shared\Models\User;
use Illuminate\Support\Facades\DB;

class CreateShift {
    public static function handle($user_id, $creator, $waiters, $counterItems) {
        $nowCarbonObj = Carbon::now();
        $now = Carbon::parse($nowCarbonObj->toTimeString());
        $shift_start_time = Carbon::parse(config(key: 'arc.shift_start_time'));

        //  find the bartender
        $barTender = User::query()->where('id', $user_id)->first();

        if ($now->gte($shift_start_time) && $barTender) {
            try {
                DB::beginTransaction();

                /**
                 * Create shift
                 */

                // shift
                $shift = Shift::create([
                    'name' => Shift::generateShiftName(
                        work_id:$barTender->work_id,
                        start_date:$now->toDateString(),
                        end_date:$now->addHours(24)->toDateString()
                    ),
                    'start_date' => $nowCarbonObj->toDateString(),
                    'start_time' => $nowCarbonObj->toTimeString(),

                    'end_date' => $nowCarbonObj->addHours(24)->toDateString(),
                    'end_time' => $nowCarbonObj->addHours(24)->toTimeString(),

                    'creator' => $creator,
                    'active' => true,
                ]);

                // creating the workers
                $allWorkers = array_merge($waiters, [$user_id]);
                foreach ($allWorkers as $worker) {
                    ShiftWorkers::create([
                        'shift_id' => $shift->id,
                        'user_id' => $worker,
                    ]);
                }

                // update the status of the workers
                foreach ($allWorkers as $worker) {
                    $worker = User::query()->where('id', $worker)->first();

                    $worker->update([
                        'active' => true,
                    ]);
                }

                $counter = Counter::create([
                    'name' => $barTender->work_id,
                    'shift_id' => $shift->id,
                ]);

                //  adding the counter items
                PopulateCounter::handle(
                    counter_id: $counter->id,
                    counterItems:$counterItems
                );

                DB::commit();

                return $shift;
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }
    }
}
