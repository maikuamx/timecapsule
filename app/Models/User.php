<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Carbon\Carbon;

class TimeCapsule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'encrypted_content',
        'content_type',
        'unlock_date',
        'is_unlocked',
        'opened_at',
        'reminder_sent',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'unlock_date' => 'datetime',
            'opened_at' => 'datetime',
            'is_unlocked' => 'boolean',
            'reminder_sent' => 'boolean',
            'attachments' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isUnlockable(): bool
    {
        return $this->unlock_date <= now();
    }

    public function getDaysUntilUnlock(): int
    {
        if ($this->isUnlockable()) {
            return 0;
        }

        return now()->diffInDays($this->unlock_date);
    }

    public function getTimeUntilUnlock(): array
    {
        if ($this->isUnlockable()) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0];
        }

        $now = now();
        $unlock = $this->unlock_date;

        return [
            'days' => $now->diffInDays($unlock),
            'hours' => $now->diffInHours($unlock) % 24,
            'minutes' => $now->diffInMinutes($unlock) % 60,
        ];
    }

    public function encryptContent(string $content): void
    {
        $key = Key::loadFromAsciiSafeString(config('app.encryption_key'));
        $this->encrypted_content = Crypto::encrypt($content, $key);
    }

    public function decryptContent(): string
    {
        if (!$this->isUnlockable()) {
            throw new \Exception('Time capsule is not yet unlockable');
        }

        $key = Key::loadFromAsciiSafeString(config('app.encryption_key'));
        return Crypto::decrypt($this->encrypted_content, $key);
    }

    public function markAsOpened(): void
    {
        if ($this->isUnlockable() && !$this->is_unlocked) {
            $this->update([
                'is_unlocked' => true,
                'opened_at' => now(),
            ]);
        }
    }

    public function scopeUnlockable($query)
    {
        return $query->where('unlock_date', '<=', now());
    }

    public function scopePending($query)
    {
        return $query->where('unlock_date', '>', now());
    }

    public function scopeNeedingReminder($query)
    {
        return $query->where('reminder_sent', false)
            ->where('unlock_date', '<=', now()->addDays(7))
            ->where('unlock_date', '>', now());
    }
}
