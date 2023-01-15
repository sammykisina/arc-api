<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Domains\Bartender\Actions\Shift\ClearShift as ShiftClearShift;
use Illuminate\Console\Command;

class ClearShiftCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will be used to clear a shift 
    (make a shift as inactive) 
    and create a last shift counter report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        // call clear shift action
        if (! ShiftClearShift::handle()) {
            // Todo:: send notification to bartender him/her that shift should end and so clear any pending bills
        }

        return 0;
    }
}
