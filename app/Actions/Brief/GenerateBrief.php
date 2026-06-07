<?php

declare(strict_types=1);

namespace App\Actions\Brief;

use App\Ai\Agents\BriefGenerator;

final readonly class GenerateBrief
{
    /**
     * @param  array<int, string>  $topics
     * @return array<int, array{hook: string, why: string}>
     */
    public function handle(array $topics, string $niche, string $language, string $platform): array
    {
        $googleTrends = implode(', ', array_slice($topics, 0, 5));
        $youtubeTrending = implode(', ', array_slice($topics, 5));

        $prompt = <<<PROMPT
        Creator profile:
        - Niche: {$niche}
        - Primary platform: {$platform}
        - Language: {$language}

        Trending this week in India:
        - Google Trends: {$googleTrends}
        - YouTube trending ({$niche} category): {$youtubeTrending}

        Generate exactly 5 content angles. For each angle provide a hook (title in {$language}) and a one-sentence English explanation of why it is trending and fits this niche.

        Rules:
        - Hooks must be in {$language}. If language is Hinglish, mix Hindi and English naturally.
        - Each hook must be usable as a YouTube video title or Instagram Reel caption opener verbatim.
        - Do not repeat hooks across the 5 angles.
        - Do not include hashtags.
        PROMPT;

        $result = (new BriefGenerator)->prompt($prompt);

        /** @var array<int, array{hook: string, why: string}> $angles */
        $angles = $result['angles'] ?? [];

        return $angles;
    }
}
