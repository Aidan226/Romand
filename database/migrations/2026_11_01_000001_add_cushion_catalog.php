<?php
use Illuminate\Database\Migrations\Migration;
return new class extends Migration {
    public function up(): void {
        (new \Database\Seeders\CushionCatalogSeeder)->run();
    }
    public function down(): void {
        // Preserve catalog records and possible order references on rollback.
    }
};
