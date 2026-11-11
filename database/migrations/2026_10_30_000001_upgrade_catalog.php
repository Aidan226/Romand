<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema,DB};
return new class extends Migration {
 public function up(): void {
  if (!Schema::hasColumn('orders','promotion_name')) Schema::table('orders',function(Blueprint $t){$t->string('promotion_name')->nullable();});
  Schema::create('products',function(Blueprint $t){$t->id();$t->string('name');$t->string('brand')->default('rom&nd');$t->string('product_type')->default('lipstick');$t->string('category');$t->text('description')->nullable();$t->string('finish')->default('natural');$t->string('coverage')->default('medium');$t->boolean('is_demo')->default(false);$t->timestamps();});
  Schema::rename('lipsticks','product_shades');
  Schema::table('product_shades',function(Blueprint $t){$t->foreignId('product_id')->nullable()->constrained('products')->restrictOnDelete();$t->string('shade_name')->nullable();$t->string('shade_code')->nullable();$t->string('undertone')->default('unknown');$t->unsignedTinyInteger('depth')->nullable();$t->string('color_family')->default('other');$t->text('source')->nullable();$t->boolean('verified')->default(false);});
  foreach(DB::table('product_shades')->orderBy('id')->get() as $s){
   $parts=explode(' - ',$s->name,2);$name=trim($parts[0]);
   $p=DB::table('products')->where('name',$name)->where('category',$s->category)->first();
   $id=$p?->id ?? DB::table('products')->insertGetId(['name'=>$name,'category'=>$s->category,'finish'=>str_contains($s->category,'Matte')?'matte':'glossy','created_at'=>now(),'updated_at'=>now()]);
   $config=collect(config('products'))->firstWhere('name',$name);$shade=collect($config['shades']??[])->firstWhere('name',trim($parts[1]??$name));
   DB::table('product_shades')->where('id',$s->id)->update(['product_id'=>$id,'shade_name'=>trim($parts[1]??$name),'shade_hex'=>$s->shade_hex?:($shade['hex']??null),'image'=>$s->image?:($shade['image']??null),'source'=>'Imported legacy catalog; verify shade identity and color before recommending.']);
  }
  Schema::create('shade_profiles',function(Blueprint $t){$t->id();$t->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();$t->json('profile');$t->timestamps();});
  Schema::create('shade_feedback',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('shade_id')->constrained('product_shades')->cascadeOnDelete();$t->string('feedback');$t->timestamps();$t->unique(['user_id','shade_id']);});
 }
 public function down(): void {Schema::dropIfExists('shade_feedback');Schema::dropIfExists('shade_profiles');Schema::table('product_shades',function(Blueprint $t){$t->dropForeign(['product_id']);$t->dropColumn(['product_id','shade_name','shade_code','undertone','depth','color_family','source','verified']);});Schema::rename('product_shades','lipsticks');Schema::dropIfExists('products');}
};
