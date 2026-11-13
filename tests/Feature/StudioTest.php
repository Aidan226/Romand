<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Product,Lipstick,User,Order};
use App\Services\ShadeMatcher;
use Illuminate\Support\Facades\DB;
class StudioTest extends TestCase {
 use RefreshDatabase;
 protected function setUp(): void {parent::setUp();Lipstick::query()->delete();Product::query()->delete();}
 public function test_cushion_catalog_is_repeatable_and_preserves_store_edits():void {
  $seed=new \Database\Seeders\CushionCatalogSeeder;$seed->run();
  $this->assertSame(2,Product::where('product_type','cushion')->count());
  $this->assertSame(10,Lipstick::count());
  $shade=Lipstick::first();$shade->update(['price'=>42000,'stock'=>7]);$seed->run();
  $this->assertSame(10,Lipstick::count());$this->assertDatabaseHas('product_shades',['id'=>$shade->id,'stock'=>7,'price'=>42000]);
  $this->get('/?type=cushion')->assertOk()->assertSee('Bare Water Cushion')->assertSee('Nu Zero Cushion')->assertSee('Price to be set');
  $this->get('/product/'.$shade->id)->assertOk()->assertSee('Capture & Check',false);
 }

 private function shade(array $tags=[],array $product=[]): Lipstick {
  $p=Product::create($product+['name'=>'Test Cushion','brand'=>'Test','product_type'=>'cushion','category'=>'Base','finish'=>'natural','coverage'=>'medium']);
  return Lipstick::create($tags+['product_id'=>$p->id,'name'=>$p->name.' - Sand','shade_name'=>'Sand','category'=>'Base','price'=>30000,'stock'=>3,'shade_hex'=>'#C49B78','undertone'=>'neutral','depth'=>4,'verified'=>true,'source'=>'Test data']);
 }
 private function profile(array $extra=[]):array {return $extra+['type'=>'cushion','depth'=>4,'undertone'=>'neutral','family'=>'any','finish'=>'any','coverage'=>'any'];}
 public function test_catalog_finder_and_product_render():void {$s=$this->shade();$this->get('/')->assertOk()->assertSee('Test Cushion');$this->get('/')->assertDontSee('Find my shade');$this->get('/shade-finder')->assertOk()->assertSee('Start with your skin');$this->get('/product/'.$s->id)->assertOk()->assertSee('Sand')->assertSee('inline-shade-check')->assertSee('Capture & Check', false);$this->get('/shade-check/'.$s->id)->assertRedirect('/product/'.$s->id);}
 public function test_foundation_rejects_distant_depth_and_unreviewed_shades():void {$a=$this->shade();$this->shade(['depth'=>1]);$this->shade(['verified'=>false]);$results=app(ShadeMatcher::class)->rank(Lipstick::with('product')->get(),$this->profile());$this->assertCount(1,$results);$this->assertSame($a->id,$results[0]['shade']->id);}
 public function test_opposite_foundation_undertone_is_not_forced():void {$this->shade(['undertone'=>'cool']);$results=app(ShadeMatcher::class)->rank(Lipstick::with('product')->get(),$this->profile(['undertone'=>'warm']));$this->assertSame([],$results);}
 public function test_no_match_is_shown():void {$this->shade(['depth'=>1]);$this->post('/shade-finder',$this->profile())->assertOk()->assertSee('No suitable match');}
 public function test_recommendations_are_rendered_and_profile_saved():void {$this->shade();$u=User::factory()->create();$this->actingAs($u)->post('/shade-finder',$this->profile(['save_profile'=>1]))->assertOk()->assertSee('Sand');$this->assertDatabaseHas('shade_profiles',['user_id'=>$u->id]);$this->delete('/shade-profile')->assertRedirect();$this->assertDatabaseCount('shade_profiles',0);}
 public function test_add_to_cart_ignores_submitted_price():void {$s=$this->shade();$this->post('/cart/add',['product_id'=>$s->id,'shade_name'=>'Sand','quantity'=>2,'price'=>1])->assertRedirect('/cart')->assertSessionHas('cart.'.sha1((string)$s->id).'.price',30000.0);}
 public function test_stock_limits_are_enforced():void {$s=$this->shade();$this->post('/cart/add',['product_id'=>$s->id,'shade_name'=>'Sand','quantity'=>4])->assertSessionHas('error');}
 public function test_guest_checkout_is_protected():void {$this->post('/checkout/store',[])->assertRedirect('/login');}
 public function test_atomic_checkout_reprices_and_decrements_stock():void {$s=$this->shade();$u=User::factory()->create();$cart=[sha1((string)$s->id)=>['db_id'=>$s->id,'id'=>$s->id,'name'=>'Test Cushion','shade'=>'Sand','quantity'=>2,'price'=>1,'image'=>'']];$this->actingAs($u)->withSession(['cart'=>$cart])->post('/order/confirm',['fullname'=>'Customer','phone'=>'09123456789','address'=>'Yangon','payment_method'=>'cod'])->assertRedirect();$this->assertDatabaseHas('orders',['user_id'=>$u->id,'total_price'=>60000]);$this->assertDatabaseHas('product_shades',['id'=>$s->id,'stock'=>1]);$this->assertDatabaseHas('order_items',['price'=>30000,'quantity'=>2]);}
 public function test_changed_stock_does_not_create_order():void {$s=$this->shade(['stock'=>0]);$u=User::factory()->create();$cart=['item'=>['db_id'=>$s->id,'id'=>$s->id,'name'=>'Test Cushion','shade'=>'Sand','quantity'=>1,'price'=>30000,'image'=>'']];$this->actingAs($u)->withSession(['cart'=>$cart])->post('/order/confirm',['fullname'=>'C','phone'=>'09','address'=>'Yangon','payment_method'=>'cod'])->assertSessionHasErrors('stock');$this->assertDatabaseCount('orders',0);}
 public function test_customer_cannot_write_admin_catalog():void {$u=User::factory()->create(['is_admin'=>false]);$this->actingAs($u)->post('/admin/products',[])->assertRedirect('/');$this->assertDatabaseCount('products',0);}
 public function test_admin_catalog_renders():void {$this->shade();$u=User::factory()->create(['is_admin'=>true]);$this->actingAs($u)->get('/admin/products')->assertOk()->assertSee('Your shade library');$this->get('/admin')->assertOk();}
 public function test_feedback_requires_recommended_shade():void {$s=$this->shade();$u=User::factory()->create();$this->actingAs($u)->post('/shade-feedback',['shade_id'=>$s->id,'feedback'=>'suitable'])->assertForbidden();$this->withSession(['shade_recommendations'=>[$s->id]])->post('/shade-feedback',['shade_id'=>$s->id,'feedback'=>'suitable'])->assertRedirect();$this->assertDatabaseHas('shade_feedback',['user_id'=>$u->id,'shade_id'=>$s->id]);}
 public function test_invalid_profile_is_rejected():void {$this->post('/shade-finder',$this->profile(['depth'=>99]))->assertSessionHasErrors('depth');}
 public function test_seed_is_repeatable_without_resetting_stock():void {$this->seed();$s=Lipstick::first();$s->update(['stock'=>2]);$count=Lipstick::count();$this->seed();$this->assertSame($count,Lipstick::count());$this->assertSame(2,$s->fresh()->stock);}

 public function test_admin_can_create_normalized_shade():void {
  $u=User::factory()->create(['is_admin'=>true]);$data=['product_name'=>'Real Product','brand'=>'Test','product_type'=>'cushion','category'=>'Base','finish'=>'natural','coverage'=>'medium','shade_name'=>'Sand','shade_hex'=>'#C49B78','undertone'=>'neutral','depth'=>4,'color_family'=>'other','price'=>30000,'stock'=>4,'source'=>'Test source','verified'=>1];
  $this->actingAs($u)->post('/admin/products',$data)->assertRedirect('/admin/products')->assertSessionHasNoErrors();$this->assertDatabaseHas('product_shades',['shade_name'=>'Sand','verified'=>true]);$this->post('/admin/products',$data)->assertSessionHasErrors('shade_name');
 }
 public function test_populated_legacy_migration_preserves_ids_and_stock():void {
  $migration=require database_path('migrations/2026_10_30_000001_upgrade_catalog.php');$migration->down();
  DB::table('lipsticks')->insert(['id'=>777,'name'=>'Legacy Product - Rose','category'=>'Glossy & Dewy','price'=>12345,'stock'=>7,'shade_hex'=>'#C87D77']);
  $migration->up();$this->assertDatabaseHas('product_shades',['id'=>777,'stock'=>7,'shade_name'=>'Rose','price'=>12345]);$this->assertDatabaseHas('products',['name'=>'Legacy Product']);
 }
 public function test_other_customers_cannot_read_order_or_payment_proof():void {
  $owner=User::factory()->create();$other=User::factory()->create();$order=Order::create(['user_id'=>$owner->id,'fullname'=>'A','phone'=>'09','address'=>'Test','total_price'=>1,'status'=>'Pending']);
  $this->actingAs($other)->get('/orders/'.$order->id)->assertForbidden();$this->get('/orders/'.$order->id.'/payment-proof')->assertForbidden();
 }
 public function test_image_library_picker_and_path_validation():void {
  $u=User::factory()->create(['is_admin'=>true]);
  $this->actingAs($u)->get('/admin/products')->assertOk()->assertSee('Choose a supplied product photo')->assertSee('swatchup.avif');
  $this->post('/admin/products',['existing_image'=>'../../.env'])->assertSessionHasErrors('existing_image');
 }
 public function test_supplied_lip_catalog_covers_all_photos_and_preserves_edits():void {
  $this->assertCount(113,config('supplied_lip_catalog'));
  $seed=new \Database\Seeders\SuppliedLipCatalogSeeder;$seed->run();
  $this->assertSame(7,Product::count());$this->assertSame(113,Lipstick::count());
  foreach(config('supplied_lip_catalog') as $row){$this->assertFileExists(public_path('images/'.$row['image']));}
  $shade=Lipstick::first();$shade->update(['price'=>21500,'stock'=>9]);$id=$shade->id;
  $seed->run();$this->assertSame(113,Lipstick::count());$this->assertDatabaseHas('product_shades',['id'=>$id,'price'=>21500,'stock'=>9]);
  $this->get('/?type=lipstick')->assertOk()->assertSee('The Juicy Lasting Tint')->assertSee('Zero Matte Lipstick');
  $this->get('/product/'.$id)->assertOk()->assertSee('Capture & Check',false);
 }
 public function test_lip_import_reuses_known_legacy_shade():void {
  $p=Product::create(['name'=>'Blur Fudge Tint','brand'=>'rom&nd','product_type'=>'lipstick','category'=>'Velvet & Matte','finish'=>'matte','coverage'=>'light']);
  $s=Lipstick::create(['product_id'=>$p->id,'name'=>'Blur Fudge Tint - Pomeloco','shade_name'=>'Pomeloco','category'=>'Velvet & Matte','stock'=>8,'price'=>21000]);
  (new \Database\Seeders\SuppliedLipCatalogSeeder)->run();
  $this->assertDatabaseHas('product_shades',['id'=>$s->id,'shade_code'=>'01','stock'=>8,'price'=>21000]);
  $this->assertSame(1,Lipstick::where('product_id',$p->id)->where('shade_name','Pomeloco')->count());
 }
}
