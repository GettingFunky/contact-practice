<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('blocks guests from admin contacts', function () {
    $this->get('/admin/contacts')->assertRedirect('/login');
});

it('allows authenticated users to see admin contacts', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/contacts')
        ->assertOk()
        ->assertSee('Μηνύματα Επικοινωνίας');
});
