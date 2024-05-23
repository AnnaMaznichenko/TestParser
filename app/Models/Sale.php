<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    /**
     * @property string g_number
     * @property string date
     * @property string last_change_date
     * @property string supplier_article
     * @property string tech_size
     * @property string barcode
     * @property int total_price
     * @property int discount_percent
     * @property int is_supply
     * @property int is_realization
     * @property string promo_code_discount
     * @property string warehouse_name
     * @property string country_name
     * @property string oblast_okrug_name
     * @property string region_name
     * @property int income_id
     * @property string sale_id
     * @property string odid
     * @property int spp
     * @property int for_pay
     * @property int finished_price
     * @property int price_with_disc
     * @property int nm_id
     * @property string subject
     * @property string category
     * @property string brand
     * @property string is_storno
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
        "is_supply",
        "is_realization",
        "promo_code_discount",
        "warehouse_name",
        "country_name",
        "oblast_okrug_name",
        "region_name",
        "income_id",
        "sale_id",
        "odid",
        "spp",
        "for_pay",
        "finished_price",
        "price_with_disc",
        "nm_id",
        "subject",
        "category",
        "brand",
        "is_storno",
    ];
}
