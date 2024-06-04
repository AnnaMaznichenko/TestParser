<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->foreignId("company_id")
                ->references("id")
                ->on("companies")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->foreignId("token_id")
                ->references("id")
                ->on("tokens")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
