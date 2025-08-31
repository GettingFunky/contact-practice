<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse {
        $data = $request->validate([
            'name'    => ['required','string','min:3'],
            'email'   => ['required','email'],
            'phone'   => ['nullable','string','max:30'],
            'message' => ['required','string','min:10','max:1000'],
        // Απλό honeypot: πρέπει να μείνει άδειο. Αν το γεμίσει bot → 422
            'website' => ['nullable','prohibited'],
        ]);

        // Δεν θέλουμε να αποθηκευτεί το honeypot στην DB
        unset($data['website']);

        // Δημιουργία εγγραφής (χρειάζεται $fillable στο Model)
        Contact::create($data);

        // PRG pattern: redirect back + flash μήνυμα
        return back()->with('success', 'Ευχαριστούμε! Το μήνυμά σου καταχωρήθηκε.');
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