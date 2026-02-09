<?php

use App\Models\Site;
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

test('site monitor component shows sites from database and stats', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Site::factory()->create(['name' => 'Google', 'url' => 'https://google.com', 'status' => 'operational']);
    Site::factory()->create(['name' => 'GitHub', 'url' => 'https://github.com', 'status' => 'degraded']);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Google');
    $response->assertSee('GitHub');
    $response->assertSee('Operational');
    $response->assertSee('Degraded');
});

test('site monitor can add a website', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('site-monitor')
        ->set('newName', 'My Example Site')
        ->set('newUrl', 'https://example.com')
        ->call('addSite')
        ->assertSet('newName', '')
        ->assertSet('newUrl', '')
        ->assertSee('My Example Site');

    $this->assertDatabaseHas('sites', [
        'name' => 'My Example Site',
        'url' => 'https://example.com',
    ]);
});

test('site monitor can remove a website', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $site = Site::factory()->create(['name' => 'Google', 'url' => 'https://google.com']);

    Livewire::test('site-monitor')
        ->call('removeSite', (string) $site->id)
        ->assertDontSee('Google');

    $this->assertDatabaseMissing('sites', ['id' => $site->id]);
});

test('site monitor refresh all runs without error', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('site-monitor')
        ->call('refreshAll')
        ->assertOk();
});
