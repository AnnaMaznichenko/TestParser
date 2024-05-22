<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $income_id
 * @property string $number
 * @property string $date
 * @property string $last_change_date
 * @property string $supplier_article
 * @property string $tech_size
 * @property string $barcode
 * @property int $quantity
 * @property int $total_price
 * @property string $date_close
 * @property string $warehouse_name
 * @property int $nm_id
 * @property string $status
 */
class Income extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        "income_id",
        "number",
        "date",
        "last_change_date",
        "supplier_article",
        "tech_size",
        "barcode",
        "quantity",
        "total_price",
        "date_close",
        "warehouse_name",
        "nm_id",
        "status",
    ];
}
