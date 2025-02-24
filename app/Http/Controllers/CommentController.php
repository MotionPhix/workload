<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function store(Request $request, Task $task)
  {
    $request->validate(['content' => 'required|string']);

    $task->comments()->create([
      'content' => $request->content,
      'user_id' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Comment added successfully!');
  }

  public function destroy(Comment $comment)
  {
    $comment->delete();
    return redirect()->back()->with('success', 'Comment deleted successfully!');
  }
}
