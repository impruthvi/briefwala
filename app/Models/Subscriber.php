<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

final class Subscriber extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    /** @return BelongsTo<Subscriber, $this> */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(self::class, 'referrer_id');
    }

    /** @return HasMany<Subscriber, $this> */
    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'referrer_id');
    }

    /** @return HasMany<SendLog, $this> */
    public function sendLogs(): HasMany
    {
        return $this->hasMany(SendLog::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }
}
