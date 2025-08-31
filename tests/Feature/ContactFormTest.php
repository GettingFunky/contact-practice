<?php

use App\Mail\ContactSubmitted;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

it('shows the contact form page', function () {
    $this->get('/contact')->assertOk()->assertSee('Επικοινωνία');
});

it('rejects invalid submission with errors and keeps old input', function () {
    $payload = [
        'name' => 'Al',            // < min:3
        'email' => 'not-an-email', // invalid
        'message' => 'short',      // < min:10
        'phone' => 'abc',          // ok ως string, απλά θα καθαριστεί
        'website' => '',           // honeypot άδειο = ok
        '_token' => csrf_token(),
    ];

    $response = $this->from('/contact')->post('/contact', $payload);

    $response
        ->assertRedirect('/contact')
        ->assertSessionHasErrors(['name', 'email', 'message']); // 3 βασικά πεδία

    // Old input κρατιέται
    $this->followRedirects($response)
        ->assertSee('not-an-email')
        ->assertSee('Al')
        ->assertSee('short');
});

it('stores a valid contact and flashes success', function () {
    Mail::fake(); // δεν στέλνουμε πραγματικό mail

    $payload = [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'message' => str_repeat('A', 20),
        'phone' => '+30 210-555-5555',
        'website' => '', // honeypot
        '_token' => csrf_token(),
    ];

    $response = $this->post('/contact', $payload);

    // Redirect με success
    $response->assertRedirect(route('contact.create'));
    $response->assertSessionHas('success');

    // DB: δημιουργήθηκε εγγραφή
    $this->assertDatabaseHas('contacts', [
        'email' => 'user@example.com',
        'name'  => 'Test User',
    ]);

    // Mail: στάλθηκε το σωστό mailable
    $contact = Contact::first();
    Mail::assertSent(ContactSubmitted::class, function ($mailable) use ($contact) {
        return $mailable->contact->is($contact);
    });
});

it('blocks honeypot submissions (website filled)', function () {
    $payload = [
        'name' => 'Bot',
        'email' => 'bot@example.com',
        'message' => str_repeat('A', 20),
        'website' => 'http://spam.example', // honeypot γεμάτο -> should fail (size:0)
        '_token' => csrf_token(),
    ];

    $response = $this->from('/contact')->post('/contact', $payload);

    $response
        ->assertRedirect('/contact')
        ->assertSessionHasErrors(['website']);

    $this->assertDatabaseCount('contacts', 0);
});

it('rate limits excessive submissions', function () {
    // Reset τυχόν μετρητές
    RateLimiter::clear('contact|127.0.0.1'); // κλειδί ανάλογα πώς το ορίζεις στο RateLimiter::for

    $validPayload = fn () => [
        'name' => 'User',
        'email' => 'user'.uniqid().'@example.com',
        'message' => str_repeat('A', 20),
        'website' => '',
        '_token' => csrf_token(),
    ];

    // Στείλε 5 επιτυχείς
    for ($i = 0; $i < 5; $i++) {
        $this->post('/contact', $validPayload())->assertRedirect();
    }

    // 6η: αναμένουμε 429 Too Many Requests
    $this->post('/contact', $validPayload())->assertStatus(429);
});


