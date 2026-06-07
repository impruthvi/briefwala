<?php

declare(strict_types=1);

use App\Actions\Brief\FetchTrendingTopics;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

test('returns merged topics from serpapi and youtube', function (): void {
    Http::fake([
        'serpapi.com/*' => Http::response([
            'interest_over_time' => [
                'timeline_data' => [
                    ['values' => [['query' => 'Tech trend A']]],
                    ['values' => [['query' => 'Tech trend B']]],
                ],
            ],
        ]),
        'googleapis.com/*' => Http::response([
            'items' => [
                ['snippet' => ['title' => 'YouTube video 1']],
                ['snippet' => ['title' => 'YouTube video 2']],
            ],
        ]),
    ]);

    config(['services.serpapi.key' => 'fake-serp-key']);
    config(['services.youtube.key' => 'fake-yt-key']);

    $topics = (new FetchTrendingTopics)->handle('Tech', 'Hindi');

    expect($topics)->toContain('Tech trend A')
        ->and($topics)->toContain('YouTube video 1');
});

test('falls back to seeds when both apis return empty', function (): void {
    Http::fake([
        'serpapi.com/*' => Http::response(['interest_over_time' => ['timeline_data' => []]]),
        'googleapis.com/*' => Http::response(['items' => []]),
    ]);

    config(['services.serpapi.key' => 'fake-key']);
    config(['services.youtube.key' => 'fake-key']);
    config(['trending_seeds' => ['Tech' => ['Seed topic 1', 'Seed topic 2']]]);

    $topics = (new FetchTrendingTopics)->handle('Tech', 'Hindi');

    expect($topics)->toBe(['Seed topic 1', 'Seed topic 2']);
});

test('logs warning when falling back to seeds', function (): void {
    Http::fake([
        'serpapi.com/*' => Http::response(['interest_over_time' => ['timeline_data' => []]]),
        'googleapis.com/*' => Http::response(['items' => []]),
    ]);

    config(['services.serpapi.key' => 'fake-key']);
    config(['services.youtube.key' => 'fake-key']);
    config(['trending_seeds' => ['Tech' => ['Seed topic']]]);

    Log::shouldReceive('warning')
        ->once()
        ->with('Trending topics fallback used', ['niche' => 'Tech', 'language' => 'Hindi']);

    (new FetchTrendingTopics)->handle('Tech', 'Hindi');
});

test('skips serpapi when key not configured', function (): void {
    Http::fake();

    config(['services.serpapi.key' => '']);
    config(['services.youtube.key' => '']);
    config(['trending_seeds' => ['Tech' => ['Fallback']]]);

    $topics = (new FetchTrendingTopics)->handle('Tech', 'Hindi');

    Http::assertNothingSent();
    expect($topics)->toBe(['Fallback']);
});

test('returns empty array when no seeds configured for niche', function (): void {
    Http::fake([
        'serpapi.com/*' => Http::response(['interest_over_time' => ['timeline_data' => []]]),
        'googleapis.com/*' => Http::response(['items' => []]),
    ]);

    config(['services.serpapi.key' => 'fake-key']);
    config(['services.youtube.key' => 'fake-key']);
    config(['trending_seeds' => []]);

    $topics = (new FetchTrendingTopics)->handle('UnknownNiche', 'Hindi');

    expect($topics)->toBe([]);
});
