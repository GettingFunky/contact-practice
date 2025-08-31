@component('mail::message')
# Νέο μήνυμα επικοινωνίας

Λάβατε νέο μήνυμα από τη φόρμα.

@component('mail::panel')
**Όνομα:** {{ $contact->name }}  
**Email:** {{ $contact->email }}  
**Τηλέφωνο:** {{ $contact->phone ?? '—' }}
@endcomponent

**Μήνυμα:**

{{ $contact->message }}

@slot('subcopy')
Λήφθηκε: {{ $contact->created_at->format('d/m/Y H:i') }}
@endslot

@endcomponent
