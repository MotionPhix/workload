<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $comment;

  public function __construct(Comment $comment)
  {
    $this->comment = $comment;
  }

  public function broadcastOn()
  {
    return new PrivateChannel('task.' . $this->comment->task_id);
  }

  public function broadcastAs()
  {
    return 'comment.created';
  }

  public function broadcastWith()
  {
    return [
      'id' => $this->comment->id,
      'content' => $this->comment->content,
      'user' => $this->comment->user,
      'created_at' => $this->comment->created_at,
    ];
  }
}
