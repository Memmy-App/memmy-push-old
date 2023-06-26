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

    public function pushTokens(): HasMany
    {
        return $this->hasMany(PushToken::class);
    }
}
