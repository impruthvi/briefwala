<?php

declare(strict_types=1);

namespace App\Actions\Brief;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final readonly class FetchTrendingTopics
{
    /** @return array<int, string> */
    public function handle(string $niche, string $language): array
    {
        $serpTopics = $this->fetchFromSerpApi($niche);
        $youtubeTopics = $this->fetchFromYoutube($niche);

        $topics = array_merge($serpTopics, $youtubeTopics);

        if ($topics === []) {
            Log::warning('Trending topics fallback used', ['niche' => $niche, 'language' => $language]);

            return $this->seeds($niche);
        }

        return $topics;
    }

    /** @return array<int, string> */
    private function fetchFromSerpApi(string $niche): array
    {
        $key = config('services.serpapi.key');

        if (empty($key)) {
            return [];
        }

        $response = Http::retry(3, 200)
            ->timeout(10)
            ->connectTimeout(5)
            ->get('https://serpapi.com/search', [
                'engine' => 'google_trends',
                'q' => $niche,
                'geo' => 'IN',
                'api_key' => $key,
            ]);

        if (! $response->successful()) {
            return [];
        }

        /** @var array<string, mixed> $data */
        $data = $response->json();

        /** @var array<int, mixed> $trends */
        $trends = $data['interest_over_time']['timeline_data'] ?? [];

        return collect($trends)
            ->pluck('values')
            ->flatten(1)
            ->pluck('query')
            ->filter()
            ->unique()
            ->values()
            ->take(5)
            ->all();
    }

    /** @return array<int, string> */
    private function fetchFromYoutube(string $niche): array
    {
        $key = config('services.youtube.key');

        if (empty($key)) {
            return [];
        }

        $response = Http::retry(3, 200)
            ->timeout(10)
            ->connectTimeout(5)
            ->get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'q' => $niche,
                'regionCode' => 'IN',
                'order' => 'viewCount',
                'type' => 'video',
                'maxResults' => 5,
                'key' => $key,
            ]);

        if (! $response->successful()) {
            return [];
        }

        /** @var array<string, mixed> $data */
        $data = $response->json();

        /** @var array<int, mixed> $items */
        $items = $data['items'] ?? [];

        return collect($items)
            ->pluck('snippet.title')
            ->filter()
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    private function seeds(string $niche): array
    {
        /** @var array<string, array<int, string>> $seeds */
        $seeds = config('trending_seeds', []);

        return $seeds[$niche] ?? [];
    }
}
