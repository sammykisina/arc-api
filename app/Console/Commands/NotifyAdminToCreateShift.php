<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\AdminSuperAdmin\CreateShiftMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAdminToCreateShift extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:createShift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a mail to admin to create a shift when the current one is about to end';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        Mail::to('admin@diana.arc')
            ->send(new CreateShiftMail);

        return 0;
    }
}
