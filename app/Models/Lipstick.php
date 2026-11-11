<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// Compatibility model: old cart and order code continues to use preserved shade IDs.
class Lipstick extends Model {
 protected $table='product_shades';
 protected $guarded=[];
 protected $casts=['verified'=>'boolean','depth'=>'integer','price'=>'decimal:2'];
 public function product(){return $this->belongsTo(Product::class);}
}
