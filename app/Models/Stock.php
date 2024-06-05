<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string
 * @property string date
 * @property string last_change_date
 * @property string supplier_article
 * @property string tech_size
 * @property string barcode
 * @property int quantity
 * @property int is_supply
 * @property int is_realization
 * @property int quantity_full
 * @property string warehouse_name
 * @property int in_way_to_client
 * @property int in_way_from_client
 * @property int nm_id
 * @property string subject
 * @property string category
 * @property string brand
 * @property string sc_code
 * @property int price
 * @property int discount
 */
class Stock extends Model
{
    public $timestamps = false;
    protected $fillable = [
        "date",
        "last_change_date",
        "supplier_article",
        "tech_size",
        "barcode",
        "quantity",
        "is_supply",
        "is_realization",
        "quantity_full",
        "warehouse_name",
        "in_way_to_client",
        "in_way_from_client",
        "nm_id",
        "subject",
        "category",
        "brand",
        "sc_code",
        "price",
        "discount",
    ];
}
