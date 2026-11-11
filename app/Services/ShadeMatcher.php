<?php
namespace App\Services;
class ShadeMatcher {
 // Rule scores are ranking aids, never accuracy probabilities.
 public function rank(iterable $shades,array $p): array {
  $out=[];
  foreach($shades as $s){
   if(!$s->verified || $s->stock<1 || !$s->product || $s->product->product_type!==$p['type'])continue;
   if(!empty($p['product_id']) && $s->product_id!=(int)$p['product_id'])continue;
   $reasons=[];$score=0;
   if($p['type']!=='lipstick'){
    if($s->depth===null || $s->undertone==='unknown')continue;
    $gap=abs($s->depth-(int)$p['depth']);if($gap>1)continue;
    if($p['undertone']!=='unknown' && $s->undertone!==$p['undertone'] && $s->undertone!=='neutral' && $p['undertone']!=='neutral')continue;
    $score+=60-$gap*35;$reasons[]=$gap===0?'Same selected skin-depth group':'Adjacent skin-depth group; compare before buying';
   } else {
    if(($p['family']??'any')!=='any') {if($s->color_family===$p['family']){$score+=35;$reasons[]='Your preferred color family';}else $score-=15;}
   }
   if($p['undertone']!=='unknown' && $s->undertone===$p['undertone']){$score+=25;$reasons[]='Matches your confirmed undertone preference';}
   elseif($s->undertone==='neutral'){$score+=10;$reasons[]='Neutral option';}
   if(($p['finish']??'any')!=='any' && $s->product->finish===$p['finish']){$score+=15;$reasons[]='Your preferred finish';}
   if($p['type']!=='lipstick' && ($p['coverage']??'any')===$s->product->coverage){$score+=10;$reasons[]='Your preferred coverage';}
   $out[]=['shade'=>$s,'score'=>$score,'reasons'=>$reasons?:['Available verified shade; explore according to your preference']];
  }
  usort($out,fn($a,$b)=>($b['score']<=>$a['score'])?:($a['shade']->id<=>$b['shade']->id));
  return array_slice($out,0,3);
 }
}
