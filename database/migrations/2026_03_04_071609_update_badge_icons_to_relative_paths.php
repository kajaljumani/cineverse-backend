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
        $badges = \App\Models\Badge::all();

        foreach ($badges as $badge) {
            if ($badge->icon && filter_var($badge->icon, FILTER_VALIDATE_URL)) {
                $urlPath = parse_url($badge->icon, PHP_URL_PATH);
                // Remove '/storage/' prefix if it exists at the start of the path
                $relativePath = preg_replace('/^\/storage\//', '', $urlPath);
                
                $badge->update(['icon' => $relativePath]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration back to absolute URLs is not feasible without knowing the original host
    }
};
