<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 */
class TokenType extends Model
{
    protected $fillable = [
        "name",
    ];

    public function serviceApi(): HasMany
    {
        return $this->hasMany(ServiceApi::class);
    }

    public function token(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
