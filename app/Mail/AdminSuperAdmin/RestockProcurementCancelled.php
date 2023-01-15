<?php

declare(strict_types=1);

namespace App\Mail\AdminSuperAdmin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockProcurementCancelled extends Mailable {
    use Queueable;
    use SerializesModels;

    public function __construct(

    ) {
    }

    public function build(): self {
        return $this->view('emails.adminSuperAdmin.restock_procurement_cancelled');
    }
}
