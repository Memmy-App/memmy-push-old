<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @package App\Models
 * @property int $id
 * @property string $username
 * @property string $instance
 * @property int $last_reply_id
 * @property int $last_mention_id
 * @property int $last_message_id
 * @property string $authToken
 * @property string $last_checked
 */
class Account extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "username",
        "instance",
        "auth_token",
        "last_reply_id",
        "last_update_id",
        "last_mention_id",
        "last_checked",
    ];

    public function setLastReplyId(int $id): void {
        $this->update([
            "last_reply_id" => $id,
        ]);
    }

    public function setLastMentionId(int $id): void {
        $this->update([
            "last_mention_id" => $id,
        ]);
    }

    public function setLastMessageId(int $id): void {
        $this->update([
            "last_message_id" => $id,
        ]);
    }

    public function pushTokens(): HasMany
    {
        return $this->hasMany(PushToken::class);
    }

    public function routeNotificationForApn()
    {
        return $this->pushTokens()->pluck("push_token")->toArray();
    }

    public function setChecked(): void {
        $this->update([
            "last_checked" => Carbon::now()
        ]);
    }
}
