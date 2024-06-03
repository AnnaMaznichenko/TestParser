<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $host
 * @property int $port
 */
class ServiceApi extends Model
{
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(
            Account::class,
            "tokens",
            "service_api_id",
            "account_id"
        );
    }
}
