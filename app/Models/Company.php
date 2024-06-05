<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 */
class Company extends Model
{
    protected $fillable = [
        "name",
    ];
    public function account(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
