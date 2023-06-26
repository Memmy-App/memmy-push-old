<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @package App\Models
 * @property int $id
 * @property string $username
 * @property string $instance
 * @property int $last_reply_id
 * @property int $last_mention_id
 * @property int $last_message_id
 * @property string $authToken
 */
class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "username",
        "instance",
        "auth_token"
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
}
