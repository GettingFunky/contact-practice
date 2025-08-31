<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact');
    }

    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();

        $contact = Contact::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
        ]);

        try {
            Mail::to(env('CONTACT_TO'))
                ->send(new ContactSubmitted($contact));
        } catch (\Throwable $e) {
            // Προαιρετικά: log και συνέχισε. Δεν "σπάμε" τη UX ροή του χρήστη.
            logger()->error('Contact mail failed: ' . $e->getMessage());
            // Αν θες, μπορείς να εμφανίσεις ειδικό μήνυμα admin-only ή να κρατήσεις flag στη DB.
        }

        return redirect()
            ->route('contact.create')
            ->with('success', 'Ευχαριστούμε! Λάβαμε το μήνυμά σου και θα επικοινωνήσουμε σύντομα.');
    }

    /**
     * Λίστα contacts (admin protected)
     */
    public function index(): View
    {
        $contacts = Contact::query()
            ->latest()          // created_at desc
            ->paginate(10);     // LengthAwarePaginator

        return view('admin.contacts.index', compact('contacts'));
    }
}