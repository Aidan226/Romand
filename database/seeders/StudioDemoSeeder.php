<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Product,Lipstick};
class StudioDemoSeeder extends Seeder {
 public function run(): void {
  foreach(['lipstick','cushion'] as $type){
   $p=Product::firstOrCreate(['name'=>'Studio Demo '.ucfirst($type),'brand'=>'Illustrative sample','product_type'=>$type],['category'=>ucfirst($type).' Demo','finish'=>$type==='lipstick'?'glossy':'natural','coverage'=>'medium','is_demo'=>true,'description'=>'Fictional product for demonstrating the recommendation workflow. Not an actual rom&nd product.']);
   $colors=['#F2D4B8','#E4BD99','#CEA17D','#B78661','#946645','#69462F'];
   foreach(['warm','neutral','cool'] as $tone){
    foreach(range(1,6) as $depth){if($type==='lipstick' && $depth>2)continue;
     $name=ucfirst($tone).' '.($type==='lipstick'?($depth===1?'Rose':'Berry'):'Depth '.$depth);
     $hex=$type==='lipstick'?($depth===1?'#C57D82':'#884E67'):$colors[$depth-1];
     Lipstick::firstOrCreate(['name'=>$p->name.' - '.$name],['product_id'=>$p->id,'shade_name'=>$name,'shade_code'=>strtoupper(substr($tone,0,1)).$depth,'category'=>$p->category,'price'=>25000,'stock'=>15,'shade_hex'=>$hex,'undertone'=>$tone,'depth'=>$type==='lipstick'?null:$depth,'color_family'=>$depth===1?'pink':'berry','source'=>'Illustrative, hand-authored demo tags and display colors; not physical measurements or official shade data.','verified'=>true]);
    }
   }
  }
 }
}
