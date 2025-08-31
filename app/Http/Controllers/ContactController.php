<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreContactRequest;

class ContactController extends Controller
{
    public function create(): View {
        return view('contact');
    }

       public function store(StoreContactRequest $request)
    {
        // 1) Validated & normalized data από το Form Request
        $data = $request->validated();

        // 2) Ασφάλεια: ποτέ μην περνάς whole $request->all() σε mass-assignment
        //    Εδώ μόνο validated πεδία
        Contact::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'message' => $data['message'],
        ]);

        // 3) PRG + flash
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