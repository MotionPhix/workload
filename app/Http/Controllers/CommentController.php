<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function store(Request $request, Task $task)
  {
    $request->validate([
      'content' => 'required|string',
       'file' => 'required|file|mimes:jpeg,png,pdf,txt|max:2048', // 2MB max
    ]);

    $comment = $task->comments()->create([
      'content' => $request->get('content'),
      'user_id' => auth()->id(),
    ]);

    // Dispatch the event
    event(new CommentCreated($comment));

    return redirect()->back()->with('success', 'Comment added successfully!');
  }

  public function destroy(Comment $comment)
  {
    $comment->delete();
    return redirect()->back()->with('success', 'Comment deleted successfully!');
  }
}
