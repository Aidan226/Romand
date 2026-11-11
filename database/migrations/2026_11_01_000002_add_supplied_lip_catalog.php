<?php
use Illuminate\Database\Migrations\Migration;
return new class extends Migration {
    public function up(): void {(new \Database\Seeders\SuppliedLipCatalogSeeder)->run();}
    public function down(): void {
        // Preserve store edits and order references.
    }
};
