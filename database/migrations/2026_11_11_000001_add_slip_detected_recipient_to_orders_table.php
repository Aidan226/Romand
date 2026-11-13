<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'slip_detected_recipient')) {
                $table->string('slip_detected_recipient', 120)->nullable()->after('slip_detected_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'slip_detected_recipient')) {
                $table->dropColumn('slip_detected_recipient');
            }
        });
    }
};

