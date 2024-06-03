<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
/**
 * @property int $id
 * @property int $cabinet_id
 * @property string $name
 */
class Account extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id");
    }

    public function serviceApis(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceApi::class,
            "tokens",
            "account_id",
            "service_api_id"
        );
    }
}
