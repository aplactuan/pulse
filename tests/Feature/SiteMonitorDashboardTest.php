<?php

use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('dashboard shows site monitor heading', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Site Monitor');
    $response->assertSee('Real-time website monitoring');
});

test('site monitor component shows default sites and stats', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Google');
    $response->assertSee('GitHub');
    $response->assertSee('Twitter');
    $response->assertSee('Operational');
    $response->assertSee('Degraded');
});

test('site monitor can add a website', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('site-monitor')
        ->set('newUrl', 'https://example.com')
        ->call('addSite')
        ->assertSet('newUrl', '')
        ->assertSee('Example');
});

test('site monitor can remove a website', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test('site-monitor');
    $component->assertSee('Google');
    $sites = $component->get('sites');
    $firstId = $sites[0]['id'];

    $component->call('removeSite', $firstId);
    $component->assertDontSee('Google');
});

test('site monitor refresh all runs without error', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('site-monitor')
        ->call('refreshAll')
        ->assertOk();
});
