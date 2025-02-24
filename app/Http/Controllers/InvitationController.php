<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BrandInvitation;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
  // Send an invitation
  public function store(Request $request, Brand $brand)
  {
    $request->validate([
      'email' => 'required|email',
      'role' => 'required|in:admin,employee',
    ]);

    // Check if the user is already a member
    if ($brand->users()->where('email', $request->email)->exists()) {
      return redirect()->back()->with('error', 'User is already a member of this brand.');
    }

    // Check for pending invitations
    if ($brand->invitations()->where('email', $request->email)->exists()) {
      return redirect()->back()->with('error', 'An invitation is already pending for this email.');
    }

    // Create the invitation
    $invitation = $brand->invitations()->create([
      'sender_id' => auth()->id(),
      'email' => $request->email,
      'role' => $request->role,
      'token' => Str::random(60),
    ]);

    // Send the invitation email
    Mail::to($request->email)->send(new BrandInvitation($invitation));

    return redirect()->back()->with('success', 'Invitation sent successfully!');
  }

  // Accept an invitation
  public function accept(Request $request, string $token)
  {
    $invitation = Invitation::where('token', $token)->firstOrFail();

    // Check if the invitation has already been accepted
    if ($invitation->accepted_at) {
      return redirect()->route('dashboard')->with('error', 'Invitation has already been accepted.');
    }

    // Find or create the user
    $user = User::firstOrCreate(
      ['email' => $invitation->email],
      ['name' => $invitation->email, 'password' => bcrypt(Str::random(16))]
    );

    // Add the user to the brand
    $invitation->brand->users()->attach($user->id, ['role' => $invitation->role]);

    // Mark the invitation as accepted
    $invitation->update(['accepted_at' => now()]);

    return redirect()->route('dashboard')->with('success', 'Invitation accepted successfully!');
  }

  // Reject an invitation
  public function reject(Request $request, string $token)
  {
    $invitation = Invitation::where('token', $token)->firstOrFail();

    // Delete the invitation
    $invitation->delete();

    return redirect()->route('dashboard')->with('success', 'Invitation rejected successfully!');
  }
}
