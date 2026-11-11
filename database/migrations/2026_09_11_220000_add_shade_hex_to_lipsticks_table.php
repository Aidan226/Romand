<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lipsticks') && !Schema::hasColumn('lipsticks', 'shade_hex')) {
            Schema::table('lipsticks', function (Blueprint $table) {
                $table->string('shade_hex', 7)->nullable()->after('image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lipsticks') && Schema::hasColumn('lipsticks', 'shade_hex')) {
            Schema::table('lipsticks', function (Blueprint $table) {
                $table->dropColumn('shade_hex');
            });
        }
    }
};
