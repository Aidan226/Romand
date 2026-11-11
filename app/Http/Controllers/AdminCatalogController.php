<?php
namespace App\Http\Controllers;
use App\Models\{Product,Lipstick};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminCatalogController extends Controller {
 public function index(Request $r){return view('v2.admin',['products'=>Product::with('shades')->orderBy('name')->get(),'editing'=>$r->filled('edit')?Lipstick::with('product')->findOrFail($r->integer('edit')):null,'feedback'=>DB::table('shade_feedback')->join('product_shades','shade_feedback.shade_id','=','product_shades.id')->select('product_shades.name','shade_feedback.feedback')->get()]);}
 public function save(Request $r,?int $id=null){
  $d=$r->validate(['product_name'=>'required|string|max:100|not_regex:/ - /','brand'=>'required|string|max:80','product_type'=>'required|in:lipstick,cushion','category'=>'required|string|max:100','finish'=>'required|in:matte,glossy,natural,dewy','coverage'=>'required|in:light,medium,full','description'=>'nullable|string|max:2000','shade_name'=>'required|string|max:100','shade_code'=>'nullable|string|max:40','shade_hex'=>['required','regex:/^#[a-fA-F0-9]{6}$/'],'undertone'=>'required|in:warm,cool,neutral,unknown','depth'=>'nullable|integer|min:1|max:6','color_family'=>'required|in:nude,pink,red,coral,berry,brown,other','price'=>'required|numeric|min:0|max:99999999','stock'=>'required|integer|min:0|max:100000','source'=>'nullable|string|max:1000','verified'=>'nullable|boolean','is_demo'=>'nullable|boolean','existing_image'=>['nullable',\Illuminate\Validation\Rule::in(array_column(config('image_library',[]),'path'))],'image'=>'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:4096']);
  if($r->boolean('verified') && (empty($d['source']) || ($d['product_type']!=='lipstick' && (empty($d['depth'])||$d['undertone']==='unknown'))))return back()->withErrors(['verified'=>'Verified shades need a source;cushion also needs depth and undertone.'])->withInput();
  $existingProduct=Product::where('name',$d['product_name'])->where('brand',$d['brand'])->where('product_type',$d['product_type'])->first();
  if($existingProduct && Lipstick::where('product_id',$existingProduct->id)->where('shade_name',$d['shade_name'])->when($id,fn($q)=>$q->where('id','!=',$id))->exists())return back()->withErrors(['shade_name'=>'This product already has that shade name.'])->withInput();
  DB::transaction(function()use($r,$d,$id){
   $s=$id?Lipstick::findOrFail($id):new Lipstick;
   $product=Product::firstOrCreate(['name'=>$d['product_name'],'brand'=>$d['brand'],'product_type'=>$d['product_type']],['category'=>$d['category']]);
   $product->update(collect($d)->only(['category','finish','coverage','description'])->all()+['is_demo'=>$r->boolean('is_demo')]);
   // Metadata applies to the product and all its shades.
   Lipstick::where('product_id',$product->id)->update(['category'=>$d['category']]);
   $data=collect($d)->only(['shade_name','shade_code','shade_hex','undertone','depth','color_family','price','stock','source'])->all();
   $data+=['product_id'=>$product->id,'name'=>$product->name.' - '.$d['shade_name'],'category'=>$product->category,'verified'=>$r->boolean('verified')];
   if(!empty($d['existing_image']))$data['image']=$d['existing_image'];
   if($r->hasFile('image')){$f=$r->file('image');$n=bin2hex(random_bytes(12)).'.'.$f->extension();$f->move(public_path('images/uploads'),$n);$data['image']='uploads/'.$n;}
   $s->fill($data)->save();
  });return redirect()->route('admin.products.index')->with('success','Product and shade saved.');
 }
 public function destroy(int $id)
{
    $shade = Lipstick::find($id);

    if (!$shade) {
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'This shade has already been deleted.');
    }

    $shade->delete();

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Shade deleted successfully.');
}
}
