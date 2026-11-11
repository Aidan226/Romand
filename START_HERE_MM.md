# အရင်ဆုံး UPDATE_CAMERA_MM.md ကိုဖတ်ပါ

Existing romand_store database သုံးသူများသည် UPDATE_CAMERA_MM.md ထဲက update လုပ်နည်းကို လိုက်လုပ်ပါ။

# စတင်အသုံးပြုရန်

ဒီ project က မူလ Laravel project ကို upgrade လုပ်ထားတဲ့ local demo version ဖြစ်ပါတယ်။

## ၁။ PHP version စစ်ပါ

Terminal / CMD မှာ `php -v` ရိုက်ပါ။ **PHP 8.3.6 နှင့်အထက်** လိုအပ်ပါတယ်။ မူလ XAMPP က PHP 8.2 ဖြစ်နေပါက PHP အသစ်ကို အရင်ပြင်ဆင်ရပါမယ်။

## ၂။ Project folder ထဲ Terminal ဖွင့်ပါ

ZIP ဖြည်ပြီး `romand-v2` folder ထဲဝင်ပါ။ Windows မှာ `setup.bat` run ပါ။ macOS/Linux မှာ `bash setup.sh` run ပါ။ Default က SQLite ဖြစ်လို့ MySQL ဖွင့်စရာမလိုပါ။

## ၃။ Admin account ဖန်တီးပါ

```sh
php artisan studio:admin your-email@example.com
```

ကိုယ့် email နဲ့အစားထိုးပါ။ Password ကို မေးလာရင် အနည်းဆုံး ၁၂ လုံးထည့်ပါ။ ရိုက်နေတဲ့ password ကို မျက်နှာပြင်ပေါ်မမြင်ရတာ ပုံမှန်ပါ။ Default admin password မထားပေးထားပါ။

## ၄။ Project ဖွင့်ပါ

```sh
php artisan serve
```

Browser မှာ `http://127.0.0.1:8000` ကိုဖွင့်ပါ။ Customer account ကို Register ကနေ သီးခြားဖန်တီးပါ။

## ၅။ Demo လုပ်ကြည့်ပါ

Find my shade → Foundation → Depth 4 → Neutral → Natural → Medium → Show recommendations ကိုနှိပ်ပါ။

Result မှာ shade ၃ ခုအထိ၊ ရွေးပေးရတဲ့အကြောင်းပြချက်၊ price နဲ့ stock တွေတွေ့ရမယ်။ Add to cart လုပ်ပြီး Customer account နဲ့ COD order တင်ကြည့်ပါ။ တခြား browser/session မှာ admin နဲ့ဝင်ပြီး order status ပြောင်းပါ။

## သတိပြုရန်

- Studio Demo လို့ပါတဲ့ foundation/cushion/lipstick တွေက **စမ်းသပ်ပြသရန် ဖန်တီးထားတဲ့ sample data** တွေပါ။ တကယ့် rom&nd shade data မဟုတ်ပါ။
- မူလ lipstick data တွေက Needs review ဖြစ်နေမယ်။ Admin က source, undertone နဲ့ color family ကို စစ်ပြီး Reviewed လုပ်မှ recommendation ထဲပါမယ်။
- Camera က cheek sample ကနေ depth group ကို အကြမ်းဖျင်းအကြံပြုပါတယ်။ Undertone ကို user ကရွေးပေးရမယ်။ Professional color diagnosis မဟုတ်ပါ။
- Camera model ဖွင့်ဖို့ internet လိုပါတယ်။ Camera မရလည်း manual selection နဲ့ဆက်သုံးနိုင်ပါတယ်။
- Payment screenshot က proof တင်တဲ့အဆင့်ပဲဖြစ်ပြီး bank/payment gateway နဲ့ချိတ်ထားတာမဟုတ်ပါ။ Demo မှာ ပိုက်ဆံတကယ်မလွှဲပါနဲ့။
- မူလ database ကို မဖျက်ပါနဲ့။ Uploaded SQL ထဲက shade ID/stock အတိုင်းစချင်ရင် **setup script မ run ခင်** README ထဲက legacy JSON import လမ်းကြောင်းကိုသုံးပါ။

အသေးစိတ် setup, database migration, source code map နဲ့ limitation တွေကို README.md မှာရေးထားပါတယ်။
