<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::OpenAI)]
#[Model('gpt-4o')]
#[Temperature(0.7)]
final class BriefGenerator implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are a content strategist for Indian creators. Respond only in the format specified.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'angles' => $schema->array()
                ->items($schema->object([
                    'hook' => $schema->string()->required(),
                    'why' => $schema->string()->required(),
                ]))
                ->required(),
        ];
    }
}
