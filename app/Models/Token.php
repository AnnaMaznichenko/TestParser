<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $token
 * @property int $token_type_id
 * @property int $service_api_id
 */
class Token extends Model
{
    protected $fillable = [
        "token",
        "token_type_id",
        "service_api_id",
    ];

    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, "token_type_id");
    }

    public function serviceApi(): BelongsTo
    {
        return $this->belongsTo(ServiceApi::class, "service_api_id");
    }

    public function account(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
