<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @package App\Models
 * @property int $id
 * @property int $account_id
 * @property string $push_token
 * @property Account $account
 */
class PushToken extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_id",
        "push_token"
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
