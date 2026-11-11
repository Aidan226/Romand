<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Product,Lipstick};

class SuppliedLipCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach (config('supplied_lip_catalog', []) as $row) {
                $product = Product::firstOrCreate(
                    ['name'=>$row['product'], 'brand'=>'rom&nd', 'product_type'=>'lipstick'],
                    ['category'=>$row['category'], 'finish'=>$row['finish'], 'coverage'=>'light',
                     'is_demo'=>false, 'description'=>'Lip color collection from the supplied product photos. Shade names are transcribed when visible; other options show the supplied file number pending identity review. Display photos are not calibrated shade measurements.']
                );
                // Find by code or known name; preserve old IDs, prices, stock and review tags.
                $existing = Lipstick::where('product_id',$product->id)
                    ->where(function ($q) use ($row) {
                        $q->where('shade_code',$row['code']);
                        if ($row['named']) $q->orWhere('shade_name',$row['shade']);
                    })->first();
                if ($existing) {
                    $patch=[];
                    if (!$existing->shade_code) $patch['shade_code']=$row['code'];
                    if (str_contains($existing->shade_name ?? '', '(name pending review)') && $row['named']) {$patch['shade_name']=$row['shade'];$patch['name']=$product->name.' - '.$row['shade'];}
                    if (!$existing->image || !is_file(public_path('images/'.$existing->image))) $patch['image']=$row['image'];
                    if ($patch) $existing->update($patch);
                    continue;
                }
                $price = Lipstick::where('product_id',$product->id)->where('price','>',0)->value('price');
                Lipstick::create([
                    'product_id'=>$product->id, 'name'=>$product->name.' - '.$row['shade'],
                    'shade_name'=>$row['shade'], 'shade_code'=>$row['code'],
                    'category'=>$product->category, 'price'=>$price ?? 0, 'stock'=>0,
                    'image'=>$row['image'], 'shade_hex'=>null, 'undertone'=>'unknown', 'depth'=>null,
                    'color_family'=>'other', 'verified'=>false,
                    'source'=>'User supplied image: '.$row['image'].'. '.($row['named']?'Name checked against supplied labels/product listings; see LIP_CATALOG_SOURCES.md.':'Code from supplied filename; shade identity needs review.').' Photo/code correspondence and color tags need review. Price, when set, follows an existing shade in the same series; stock must be entered by store owner.'
                ]);
            }
        });
    }
}
