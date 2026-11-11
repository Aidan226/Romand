<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Product,Lipstick};
class LipstickSeeder extends Seeder {
 public function run(): void {
  foreach(config('products') as $c){
   $p=Product::firstOrCreate(['name'=>$c['name'],'brand'=>'rom&nd','product_type'=>'lipstick'],['category'=>$c['category'],'description'=>$c['description']??'','finish'=>str_contains($c['category'],'Matte')?'matte':'glossy']);
   foreach($c['shades']??[] as $s) Lipstick::firstOrCreate(['name'=>$c['name'].' - '.$s['name']],['product_id'=>$p->id,'shade_name'=>$s['name'],'category'=>$p->category,'price'=>$c['price'],'stock'=>15,'image'=>$s['image']??null,'shade_hex'=>$s['hex']??null,'source'=>'Legacy student catalog. Verify real shade names, colors and tags before recommending.','verified'=>false]);
  }
 }
}
