# Lipstick full photo-catalog update

ပို့ထားသော lipstick shade ပုံ ၁၁၃ ပုံကို series ၇ မျိုးအောက်မှာ product/shade entries အဖြစ် ထည့်ထားသည်။ ပုံတွေကို picker ထဲမှာပဲထားခြင်းမဟုတ်ပါ။

- Blur Fudge Tint 16
- Dewy-Ful Water Tint 12
- Glasting Melting Balm 15
- Glasting Water Tint 9
- The Juicy Lasting Tint 14
- Zero Velvet Tint 24
- Zero Matte Lipstick 23

အရင် Juicy Lasting Tint နဲ့ The Juicy Lasting Tint အသစ်က numbering မတူလို့သီးခြားထားသည်။ မူလ shade IDs၊ orders၊ prices၊ stocks မဖျက်ပါ။ နာမည်တူသည့် legacy shade ရှိလျှင် existing record ကိုပြန်သုံးသည်။ မူလ catalog ထဲကမမှန်နိုင်သောနာမည်များကို order references မပျက်အောင်ထိန်းထားပြီး Admin မှာ review လုပ်နိုင်သည်။

.env ကိုမဖျက်ပါနှင့်။ Updated files ကိုကူးပြီး ဒီ project folder ထဲမှာ:

```bash
php artisan config:clear
php artisan migrate
php artisan view:clear
php artisan serve
```

migrate --seed / migrate:fresh မလုပ်ပါနှင့်။ Shop → Lips မှာ products တွေကြည့်နိုင်သည်။ Shade အသစ်များရဲ့ stock ကို Admin မှာဖြည့်ပါ။ Same product ရဲ့ရှိပြီးသားဈေးရှိရင်အသုံးပြုထားပြီး မရှိရင် Price to be set ဖြစ်သည်။

Names နဲ့ sources ကို LIP_CATALOG_SOURCES.md မှာကြည့်ပါ။ Photo filename နဲ့ actual shade ကိုဆိုင်ကပြန်စစ်ရမည်။ Camera color tags ကိုမတီထွင်ထားပါ။

---

# Cushion catalog update (November 1)

Bare Water Cushion နဲ့ Nu Zero Cushion ကို real product entries အဖြစ် ထည့်ပြီး တစ်မျိုးမှာ shades ၅ ခုစီပါသည်။

လက်ရှိ .env ကိုမဖျက်ပါနှင့်။ Project folder ကို backup ယူပြီး updated ZIP files ကိုကူးပါ။ .env က အရင်အတိုင်းထားပါ။ MySQL ကိုဖွင့်ပါ။

```bash
php artisan config:clear
php artisan migrate
php artisan view:clear
php artisan serve
```

migrate က cushion entries ထည့်ပေးသည်။ migrate --seed / migrate:fresh မလုပ်ပါနှင့်။
Shop → Cushion ကိုနှိပ်ပါ။ Price/stock မသိသေးလို့ Price to be set / stock 0 အဖြစ်ထားသည်။ Admin → Products & shades မှာ edit လုပ်၍ ရောင်းဈေးနဲ့ stock ဖြည့်ပါ။ Stock 0 ဖြစ်လည်း Camera check သုံးနိုင်သည်။
Shade 01 Porcelain 17 / 02 Pure 21 / 03 Natural 21 / 04 Beige 23 / 05 Sand 25 ပါသည်။ Series cover ပုံကို shade အားလုံးမှာသုံးထားသည်။ ပုံက shade အလိုက်တိုင်းတာထားသောအရောင် မဟုတ်ပါ။ Shade-specific photo ရှိလျှင် admin picker နဲ့ပြောင်းပါ။ Additional cushion photos က public/images/library မှာရှိသည်။
Undertone/depth/HEX မတိုင်းတာရသေးလို့ unknown/empty/Needs review အဖြစ်ထားသည်။ Camera ကဖွင့်ပြီး capture လုပ်နိုင်သော်လည်း catalog tags မပြည့်သေးရင် match အတည်မပြုပါ။ Admin မှာ source နဲ့ review လုပ်ပြီးမှ matching tags ဖြည့်ပါ။

---

# Product အောက်မှာ Camera စမ်းသည့် version

## အရင် romand_store database ကို ဆက်သုံးမယ်

1. အရင် project folder နဲ့ MySQL database ကို backup ယူပါ။
2. ZIP ကို folder အသစ်တစ်ခုမှာဖြည်ပါ။ မူလ project ရဲ့ `.env` ကို folder အသစ်ရဲ့ project root ထဲကူးပါ။ APP_KEY ကို မပြောင်းပါနှင့်။
3. `.env` မှာ DB_CONNECTION=mysql၊ DB_DATABASE=romand_store နဲ့ ကိုယ့် MySQL username/password ကိုထားပါ။ XAMPP MySQL ကိုဖွင့်ပါ။
4. Terminal မှာ ဒီ folder အသစ်ထဲဝင်ပြီး:

```bash
php -v
php artisan config:clear
php artisan migrate
php artisan view:clear
php artisan serve
```

PHP 8.3.6 သို့မဟုတ် ပိုသစ်သော compatible version လိုသည်။ ရှိပြီးသား vendor dependencies ပါပြီးသားဖြစ်သည်။
Database ရှိပြီးသားဖြစ်လို့ setup.sh၊ migrate:fresh၊ db:wipe နဲ့ migrate --seed မလုပ်ပါနှင့်။
SQL အဟောင်းကိုလည်း ထပ် import မလုပ်ပါနှင့်။ Upgrade migration က မူလ shade IDs နဲ့ stock ကိုထိန်းထားသည်။ Existing admin account ကိုဆက်သုံးပါ။

## သုံးပုံ

http://127.0.0.1:8000 → Product ရွေး → Shade ရွေး → “Camera နဲ့ ဒီ shade ကိုစမ်းမယ်” → Camera ခွင့်ပြု → Capture & Check

Camera နဲ့ result က product အောက်မှာပဲပေါ်သည်။ သီးခြား questionnaire page မသွားပါ။ Shade ပြောင်းရင် previous result နဲ့ capture ကိုရှင်းပြီး camera ကိုပိတ်ပေးသည်။ Add to cart က လက်ရှိရွေးထားသော shade ID ကိုပို့သည်။
Old /shade-finder link/bookmark များက Shop ကိုပြန်ပို့သည်။ Homepage ရဲ့ခလုတ်က collection ဆီ scroll လုပ်သည်။

Camera သုံးဖို့ localhost/HTTPS၊ browser camera permission နဲ့ face-model CDN ကိုယူရန် internet လိုသည်။ Camera အရောင်နဲ့ skin-depth estimate တွေက prototype ဖြစ်သည်။ Undertone ကို camera က အတည်မပြုပါ။ Capture ပြီးနောက် Optional adjustments မှာ သိပြီးသား undertone ရှိလျှင် ထည့်နိုင်သည်။
Lipstick လိုက်ဖက်မှုကို camera တစ်ခုတည်းနဲ့အတည်ပြုမထားပါ။ Catalog tags reviewed ဖြစ်လျှင် preference နဲ့နှိုင်းပေးသည်။ မစစ်ရသေးသော catalog၊ demo data တွေမှာ review လိုကြောင်းပြသည်။ Foundation သည် physical jawline swatch နဲ့ပြန်စစ်ရမည်။

## ပုံအသစ် ၁၂၆ ပုံ

public/images/library/ အောက်မှာ series/folder နဲ့ filename အတိုင်းပါသည်။
Admin → Products & Shades → ရှိပြီးသား shade ကို Edit → Choose a supplied product photo → မှန်သော series/code ကိုရွေး → Save shade.

ပုံနံပါတ်နဲ့ shade name ကို အလိုအလျောက်မချိတ်ပါ။ ဥပမာ Bare Water Cushion/11.jpg သည် shade 11 ဟုတ်မဟုတ်မသေချာသဖြင့် ကိုယ်တိုင်စစ်ရမည်။ ပုံရွေးခြင်းက HEX/undertone ကိုအလိုအလျောက် verified မလုပ်ပါ။ သီးခြား upload သည် picker ထက်ဦးစားပေးသည်။ JPG/JPEG/PNG/WebP/AVIF ကို 4 MB အထိလက်ခံသည်။ AVIF browser decode support မရှိရင် preview မပေါ်နိုင်သည်။

## ပထမဆုံး database အသစ်နဲ့စမယ်ဆိုရင်သာ

bash setup.sh
php artisan studio:admin ကိုယ့်-email
php artisan serve

Default က SQLite ဖြစ်ပြီး demo products ပါမည်။ Existing MySQL database သုံးသူများက ဒီ clean setup ကိုမသုံးပါနှင့်။

## ပြင်ထားသော အဓိက files

resources/views/v2/product.blade.php
resources/views/v2/catalog.blade.php
resources/views/layouts/app.blade.php
public/js/product-camera.js
public/js/shade-camera.js
public/js/shade-assessment.mjs
public/css/studio.css
routes/web.php
app/Http/Controllers/AdminCatalogController.php
resources/views/v2/admin.blade.php
config/image_library.php
public/images/library/

Customer database၊ payment proofs၊ .env နဲ့ private session/log files များကို ZIP ထဲမထည့်ထားပါ။
