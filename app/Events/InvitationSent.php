<?php

namespace App\Events;

use App\Models\Invitation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationSent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $invitation;

  public function __construct(Invitation $invitation)
  {
    $this->invitation = $invitation;
  }

  public function broadcastOn()
  {
    return new PrivateChannel('brand.' . $this->invitation->brand_id);
  }

  public function broadcastAs()
  {
    return 'invitation.sent';
  }

  public function broadcastWith()
  {
    return [
      'id' => $this->invitation->id,
      'email' => $this->invitation->email,
      'role' => $this->invitation->role,
      'brand' => $this->invitation->brand->name,
    ];
  }
}
