<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SendStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SendLog extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    protected function casts(): array
    {
        return [
            'status' => SendStatus::class,
            'week_of' => 'date',
            'sent_at' => 'datetime',
        ];
    }
}
