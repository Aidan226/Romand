<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Product, Lipstick};

class CushionCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach ([
                ['Bare Water Cushion', 'dewy', 'light', 'library/Bare Water Cushion/01.webp', 'https://www.romand.co.kr/product/detail.html?product_no=654'],
                ['Nu Zero Cushion', 'matte', 'medium', 'library/Nu Zero Cushion/01.jpg', 'https://global.oliveyoung.com/product/detail?prdtNo=GA220314170'],
            ] as [$name, $finish, $coverage, $image, $url]) {
                $product = Product::firstOrCreate(
                    ['name' => $name, 'brand' => 'rom&nd', 'product_type' => 'cushion'],
                    ['category' => 'Cushion', 'finish' => $finish, 'coverage' => $coverage,
                     'is_demo' => false,
                     'description' => 'rom&nd cushion collection. Product cover photo is shared across shade options; it is not a shade-specific color measurement. Store price and stock are awaiting entry. Finish and coverage are broad catalog descriptions.']
                );
                foreach (['01' => 'Porcelain 17', '02' => 'Pure 21', '03' => 'Natural 21', '04' => 'Beige 23', '05' => 'Sand 25'] as $code => $shade) {
                    Lipstick::firstOrCreate(
                        ['product_id' => $product->id, 'shade_name' => $shade],
                        ['name' => $name.' - '.$shade, 'shade_code' => (string)$code,
                         'category' => $product->category, 'price' => 0, 'stock' => 0,
                         'image' => $image, 'shade_hex' => null, 'depth' => null,
                         'undertone' => 'unknown', 'color_family' => 'other', 'verified' => false,
                         'source' => $url.' | Supplied Bare Water Cushion/11.jpg chart lists Porcelain 17, Pure 21, Natural 21, Beige 23, Sand 25. Nu Zero shade identities also listed in Brunei MOH cosmetics notification list (27 February 2025). No physical HEX/depth measurements supplied. Cover image identifies the series only; review shade tags separately.']
                    );
                }
            }
        });
    }
}
