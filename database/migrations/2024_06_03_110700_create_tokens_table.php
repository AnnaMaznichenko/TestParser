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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->foreignId("token_type_id")
                ->references("id")
                ->on("token_types")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->foreignId("account_id")
                ->references("id")
                ->on("accounts")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->foreignId("service_api_id")
                ->references("id")
                ->on("service_apis")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->timestamps();
            $table->unique(
                ["token_type_id", "account_id", "service_api_id"],
                "idx_token_type_id_account_id_service_api_id_unique"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
