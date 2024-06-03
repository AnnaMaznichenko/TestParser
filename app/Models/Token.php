<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property string $token
 * @property int $type_id
 * @property int $account_id
 * @property int $service_api_id
 */
class Token extends Pivot
{
}
