<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $token_type_id
 */
class ServiceApi extends Model
{
    protected $fillable = [
        "name",
        "token_type_id",
    ];

    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, "token_type_id");
    }

    public function token(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
