<?php

declare(strict_types=1);

use App\Actions\Brief\GenerateBrief;
use App\Ai\Agents\BriefGenerator;

test('returns angles from ai agent', function (): void {
    BriefGenerator::fake([
        ['angles' => [
            ['hook' => 'AI se banao content', 'why' => 'AI tools are trending in India'],
            ['hook' => 'Tech for beginners', 'why' => 'Beginner content gets high views'],
        ]],
    ]);

    $angles = (new GenerateBrief)->handle(
        topics: ['AI tools', 'Smartphones'],
        niche: 'Tech',
        language: 'Hindi',
        platform: 'YouTube',
    );

    expect($angles)->toHaveCount(2)
        ->and($angles[0]['hook'])->toBe('AI se banao content')
        ->and($angles[0]['why'])->toBe('AI tools are trending in India');
});

test('includes niche and language in prompt', function (): void {
    BriefGenerator::fake([['angles' => [['hook' => 'Hook', 'why' => 'Why']]]]);

    (new GenerateBrief)->handle(
        topics: ['Topic 1'],
        niche: 'Finance',
        language: 'English',
        platform: 'Instagram',
    );

    BriefGenerator::assertPrompted(fn ($prompt): bool => str_contains($prompt->prompt, 'Finance')
        && str_contains($prompt->prompt, 'English')
        && str_contains($prompt->prompt, 'Instagram'));
});

test('returns empty array when agent returns no angles', function (): void {
    BriefGenerator::fake([['angles' => []]]);

    $angles = (new GenerateBrief)->handle(['Topic'], 'Tech', 'Hindi', 'YouTube');

    expect($angles)->toBe([]);
});
