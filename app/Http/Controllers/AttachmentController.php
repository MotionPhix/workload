<?php

namespace App\Http\Controllers;

use App\Events\AttachmentCreated;
use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachmentController extends Controller
{
  public function store(Request $request, Task $task)
  {
    $request->validate([
      'file' => 'required|file|mimes:jpeg,png,pdf,txt|max:2048', // 2MB max
    ]);

    $media = $task->addMedia($request->file('file'))->toMediaCollection('attachments');

    // Dispatch the event
    event(new AttachmentCreated($media));

    return redirect()->back()->with('success', 'File uploaded successfully!');
  }

  public function destroy(Task $task, Media $media)
  {
    $media->delete();
    return redirect()->back()->with('success', 'File deleted successfully!');
  }
}
