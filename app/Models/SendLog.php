<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SendStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class SendLog extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    /** @return BelongsTo<Subscriber, $this> */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'status' => SendStatus::class,
            'week_of' => 'date',
            'sent_at' => 'datetime',
        ];
    }
}
