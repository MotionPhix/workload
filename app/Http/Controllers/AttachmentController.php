<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
  public function store(Request $request, Task $task)
  {
    $request->validate(['file' => 'required|file|max:2048']); // 2MB max

    $path = $request->file('file')->store('attachments');

    $task->attachments()->create([
      'filename' => $request->file('file')->getClientOriginalName(),
      'path' => $path,
      'user_id' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'File uploaded successfully!');
  }

  public function destroy(Attachment $attachment)
  {
    Storage::delete($attachment->path);
    $attachment->delete();
    return redirect()->back()->with('success', 'File deleted successfully!');
  }
}
