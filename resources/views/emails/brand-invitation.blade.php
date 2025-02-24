@component('mail::message')
  # Invitation to Join {{ $invitation->brand->name }}

  You have been invited to join **{{ $invitation->brand->name }}** as a **{{ $invitation->role }}**.

  @component('mail::button', ['url' => route('invitations.accept', $invitation->token)])
    Accept Invitation
  @endcomponent

  @component('mail::button', ['url' => route('invitations.reject', $invitation->token), 'color' => 'red'])
    Reject Invitation
  @endcomponent

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent
