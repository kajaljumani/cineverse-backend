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
        Schema::table('media', function (Blueprint $table) {
            $table->json('cast')->nullable();
            $table->string('certification')->nullable();
            $table->string('trailer_key')->nullable();
            $table->integer('runtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['cast', 'certification', 'trailer_key', 'runtime']);
        });
    }
};
