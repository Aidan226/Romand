<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $guarded=[];
 protected $casts=['is_demo'=>'boolean'];
 public function shades(){return $this->hasMany(Lipstick::class)->orderByRaw("CASE WHEN shade_code IS NULL OR shade_code = '' THEN 1 ELSE 0 END")->orderBy('shade_code')->orderBy('id');}
}
