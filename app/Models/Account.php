<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property int $token_id
 */
class Account extends Model
{
    protected $fillable = [
        "name",
        "company_id",
        "token_id",
    ];
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id");
    }

    public function token(): HasOne
    {
        return $this->hasOne(Token::class, "token_id");
    }
}
