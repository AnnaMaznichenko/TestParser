<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * @property string $g_number
     * @property string date
     * @property string last_change_date
     * @property string supplier_article
     * @property string tech_size
     * @property string barcode
     * @property int total_price
     * @property int discount_percent
     * @property string warehouse_name
     * @property string oblast
     * @property int income_id
     * @property string odid
     * @property int nm_id
     * @property string subject
     * @property string category
     * @property string brand
     * @property int is_cancel
     * @property string cancel_dt
     */
    public $timestamps = false;
    protected $fillable = [
        "g_number",
        "date",
        "last_change_date",
        "supplier_article",
        "tech_size",
        "barcode",
        "total_price",
        "discount_percent",
        "warehouse_name",
        "oblast",
        "income_id",
        "odid",
        "nm_id",
        "subject",
        "category",
        "brand",
        "is_cancel",
        "cancel_dt",
    ];
}
