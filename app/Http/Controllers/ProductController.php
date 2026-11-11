<?php
namespace App\Http\Controllers;
use App\Models\{Product,Lipstick};
use Illuminate\Http\Request;
class ProductController extends Controller {
 public function index(Request $r){$q=Product::with('shades')->whereIn('product_type',['lipstick','cushion']);if(in_array($r->type,['lipstick','cushion']))$q->where('product_type',$r->type);if($r->filled('q'))$q->where('name','like','%'.mb_substr($r->q,0,100).'%');return view('v2.catalog',['products'=>$q->orderBy('is_demo')->orderBy('name')->get()]);}
 public function show(int $id){$selected=Lipstick::with('product.shades')->findOrFail($id);return view('v2.product',['product'=>$selected->product,'selected'=>$selected]);}
 public function category(string $category){$names=['glossy'=>'Glossy & Dewy','velvety'=>'Velvet & Matte','lip-balm'=>'Balm & Care'];abort_unless(isset($names[$category]),404);return view('v2.catalog',['products'=>Product::with('shades')->where('category',$names[$category])->get()]);}
 public function about(){return view('about');} public function privacyPolicy(){return view('privacy');} public function howToBuy(){return view('how_to_buy');} public function payment(){return view('payment');} public function returnsExchange(){return view('returns');} public function delivery(){return view('delivery');}
}
