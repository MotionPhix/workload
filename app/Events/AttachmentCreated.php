<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachmentCreated implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $media;

  public function __construct(Media $media)
  {
    $this->media = $media;
  }

  public function broadcastOn()
  {
    return new PrivateChannel('task.' . $this->media->model_id);
  }

  public function broadcastAs()
  {
    return 'attachment.created';
  }

  public function broadcastWith()
  {
    return [
      'id' => $this->media->id,
      'file_name' => $this->media->file_name,
      'original_url' => $this->media->original_url,
      'mime_type' => $this->media->mime_type,
    ];
  }
}
