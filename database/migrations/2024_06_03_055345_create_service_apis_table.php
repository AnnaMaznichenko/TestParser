<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_apis', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->foreignId("token_type_id")
                ->references("id")
                ->on("token_types")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_apis');
    }
};
