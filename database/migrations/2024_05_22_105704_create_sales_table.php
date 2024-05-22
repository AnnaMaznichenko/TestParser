<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("g_number");
            $table->date("date");
            $table->date("last_change_date");
            $table->string("supplier_article");
            $table->string("tech_size");
            $table->string("barcode");
            $table->integer("total_price");
            $table->integer("discount_percent");
            $table->integer("is_supply");
            $table->integer("is_realization");
            $table->string("promo_code_discount")->nullable();
            $table->string("warehouse_name");
            $table->string("country_name");
            $table->string("oblast_okrug_name");
            $table->string("region_name");
            $table->integer("income_id");
            $table->string("sale_id");
            $table->string("odid")->nullable();
            $table->integer("spp");
            $table->integer("for_pay");
            $table->integer("finished_price");
            $table->integer("price_with_disc");
            $table->integer("nm_id");
            $table->string("subject");
            $table->string("category");
            $table->string("brand");
            $table->string("is_storno")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
