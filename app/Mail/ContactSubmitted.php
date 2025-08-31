<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact)
    {
        // Χρησιμοποιούμε promoted property για να είναι διαθέσιμο στο view ως $contact.
    }

    public function envelope(): Envelope
    {
        // Θέμα + From + Reply-To
        return new Envelope(
            subject: 'Νέο μήνυμα από ' . $this->contact->name,
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [
                // Έτσι όταν πατήσεις "Reply", απαντάς κατευθείαν στον χρήστη
                new Address($this->contact->email, $this->contact->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-submitted',
            with: [
                'contact' => $this->contact,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
