<?php
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
Artisan::command('studio:admin {email} {--name=Studio Admin}',function(){
 $email=$this->argument('email');if(!filter_var($email,FILTER_VALIDATE_EMAIL)){$this->error('Enter a valid email.');return 1;}
 if(User::where('email',$email)->exists()){$this->error('Account already exists. Use a new email; this command will not elevate existing users.');return 1;}
 $password=$this->secret('Choose an admin password (at least 8 characters)');if(strlen($password??'')<8){$this->error('Password must have at least 8 characters.');return 1;}
 User::create(['name'=>$this->option('name'),'email'=>$email,'password'=>Hash::make($password),'is_admin'=>true]);$this->info('Admin created.');
})->purpose('Create a new administrator without hard-coded credentials');

Artisan::command('studio:import-legacy {file}',function(){
 $file=$this->argument('file');if(!is_file($file)){$this->error('JSON file not found.');return 1;}
 $rows=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 if(\App\Models\Lipstick::count()){$this->error('Import requires an empty catalog. Use a fresh database without running seeders.');return 1;}
 \Illuminate\Support\Facades\DB::transaction(function()use($rows){foreach($rows as $row){
  validator($row,['id'=>'required|integer|min:1','name'=>'required|string','category'=>'required|string','price'=>'required|numeric|min:0','stock'=>'required|integer|min:0'])->validate();
  $parts=explode(' - ',$row['name'],2);$p=\App\Models\Product::firstOrCreate(['name'=>trim($parts[0]),'brand'=>'rom&nd','product_type'=>'lipstick'],['category'=>$row['category'],'finish'=>str_contains($row['category'],'Matte')?'matte':'glossy']);
  $c=collect(config('products'))->firstWhere('name',$p->name);$shade=collect($c['shades']??[])->firstWhere('name',trim($parts[1]??$p->name));
  \App\Models\Lipstick::create($row+['product_id'=>$p->id,'shade_name'=>trim($parts[1]??$p->name),'shade_hex'=>$shade['hex']??null,'image'=>$shade['image']??null,'source'=>'Imported supplied lipsticks.sql. Color metadata from legacy config; needs review.','verified'=>false]);
 }});$this->info('Imported '.count($rows).' shades with original IDs and stocks.');
})->purpose('Import the supplied legacy catalog JSON into an empty upgraded database');

Artisan::command('studio:prepare',function(){
 if(!config('app.key')){$this->call('key:generate');}else{$this->info('Existing app key preserved.');}
})->purpose('Generate an app key only when none exists');
