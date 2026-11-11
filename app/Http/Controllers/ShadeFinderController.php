<?php
namespace App\Http\Controllers;
use App\Models\Lipstick;
use App\Services\ShadeMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ShadeFinderController extends Controller {
 public function index(Request $r){$profile=$r->user()?json_decode(DB::table('shade_profiles')->where('user_id',$r->user()->id)->value('profile')??'{}',true):[];if($r->integer('product_id')){$product=\App\Models\Product::findOrFail($r->integer('product_id'));$profile['type']=$product->product_type;}return view('v2.finder',['saved'=>$profile,'scope'=>$r->integer('product_id')]);}
 public function analyze(Request $r,ShadeMatcher $matcher){
  $p=$r->validate(['type'=>'required|in:lipstick,cushion','depth'=>'required|integer|min:1|max:6','undertone'=>'required|in:warm,cool,neutral,unknown','family'=>'required|in:any,nude,pink,red,coral,berry,brown,other','finish'=>'required|in:any,matte,glossy,natural,dewy','coverage'=>'required|in:any,light,medium,full','product_id'=>'nullable|integer|exists:products,id','save_profile'=>'nullable|boolean','reference_note'=>'nullable|string|max:300']);
  if($r->boolean('save_profile') && $r->user()) DB::table('shade_profiles')->updateOrInsert(['user_id'=>$r->user()->id],['profile'=>json_encode($p),'updated_at'=>now(),'created_at'=>now()]);
  $results=$matcher->rank(Lipstick::with('product')->get(),$p);
  session(['shade_recommendations'=>array_map(fn($x)=>$x['shade']->id,$results)]);
  return view('v2.results',compact('results','p'));
 }
 public function feedback(Request $r){$d=$r->validate(['shade_id'=>'required|integer|exists:product_shades,id','feedback'=>'required|in:suitable,too_light,too_dark,too_pink,too_yellow,like,dislike']);abort_unless(in_array((int)$d['shade_id'],session('shade_recommendations',[])),403);DB::table('shade_feedback')->updateOrInsert(['user_id'=>$r->user()->id,'shade_id'=>$d['shade_id']],['feedback'=>$d['feedback'],'updated_at'=>now(),'created_at'=>now()]);return redirect()->route('shade.finder')->with('success','Feedback saved. It is available for review; it does not automatically retrain the rules.');}
 public function clearProfile(Request $r){DB::table('shade_profiles')->where('user_id',$r->user()->id)->delete();return back()->with('success','Saved shade profile deleted.');}
}
