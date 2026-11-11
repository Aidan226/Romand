<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder { public function run(): void {$this->call([LipstickSeeder::class,StudioDemoSeeder::class,CushionCatalogSeeder::class,SuppliedLipCatalogSeeder::class]);} }
