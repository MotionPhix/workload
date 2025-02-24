<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BrandInvitation extends Mailable
{
  use Queueable, SerializesModels;

  public $invitation;

  public function __construct(Invitation $invitation)
  {
    $this->invitation = $invitation;
  }

  public function build()
  {
    return $this->subject('Invitation to Join ' . $this->invitation->brand->name)
      ->markdown('emails.brand-invitation', [
        'invitation' => $this->invitation,
      ]);
  }
}
