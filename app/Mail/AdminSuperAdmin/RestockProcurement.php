<?php

declare(strict_types=1);

namespace App\Mail\AdminSuperAdmin;

use Carbon\Carbon;
use Domains\Catalog\Models\Procurement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockProcurement extends Mailable {
    use Queueable;
    use SerializesModels;

    public $procurement;

    public function __construct(
        Procurement $procurement
    ) {
        $this->procurement = $procurement;
    }

   public function build() {
       return $this->view('emails.adminSuperAdmin.restock_procurement')
           ->with(
               [
                   'number_of_procurement_items' => $this->procurement->items->count(),
                   'due_date' => Carbon::parse($this->procurement->due_date)->format('l jS \of F Y h:i:s A'),
               ]
           );
   }
}
