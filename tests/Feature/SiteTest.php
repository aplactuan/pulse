<?php

use App\Models\Site;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('site has required attributes', function () {
    $site = Site::factory()->create([
        'name' => 'Example Site',
        'url' => 'https://example.com',
        'response_time' => 150,
        'status' => 'up',
    ]);

    expect($site->name)->toBe('Example Site')
        ->and($site->url)->toBe('https://example.com')
        ->and($site->response_time)->toBe(150)
        ->and($site->status)->toBe('up');
});

test('response_time is cast to integer', function () {
    $site = Site::factory()->create(['response_time' => 200]);

    expect($site->response_time)->toBeInt()->toBe(200);
});
