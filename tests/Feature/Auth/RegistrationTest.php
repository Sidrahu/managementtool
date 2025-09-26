<?php

namespace Tests\Feature\Auth;

use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $user = User::where('email', 'test@example.com')->first();

    // Determine expected dashboard route based on role
    if ($user->hasRole('admin')) {
        $expectedRoute = route('admin.dashboard');
    } elseif ($user->hasRole('manager')) {
        $expectedRoute = route('manager.dashboard');
    } elseif ($user->hasRole('contributor')) {
        $expectedRoute = route('contributor.dashboard');
    } else {
        $expectedRoute = route('projects.index'); // default
    }

    $component->assertRedirect($expectedRoute);

    $this->assertAuthenticated();
});