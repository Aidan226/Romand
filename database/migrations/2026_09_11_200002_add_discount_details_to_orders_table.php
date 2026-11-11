<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void { Schema::table('orders', function (Blueprint $table) { if (!Schema::hasColumn('orders', 'subtotal')) { $table->decimal('subtotal', 12, 2)->nullable(); }
if (!Schema::hasColumn('orders', 'discount_amount')) {
        $table->decimal('discount_amount', 12, 2)->default(0);
    }

    if (!Schema::hasColumn('orders', 'discount_name')) {
        $table->string('discount_name')->nullable();
    }

    if (!Schema::hasColumn('orders', 'payment_method')) {
        $table->string('payment_method')->nullable();
    }

    if (!Schema::hasColumn('orders', 'payment_provider')) {
        $table->string('payment_provider')->nullable();
    }

    if (!Schema::hasColumn('orders', 'payment_reference')) {
        $table->string('payment_reference')->nullable();
    }

    if (!Schema::hasColumn('orders', 'payment_status')) {
        $table->string('payment_status')->default('pending');
    }
});
}

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_amount', 'promotion_name']);
        });
    }
};
