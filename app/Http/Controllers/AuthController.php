<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Login Form ကို ပြပေးမည့် Method
    public function showLoginForm()
    {
        return view('login');
    }

    // Email နဲ့ Password မှန်မမှန် စစ်ဆေးမည့် Method
    public function login(Request $request)
    {
        // User ရိုက်ထည့်လိုက်တဲ့ Data တွေကို စစ်ဆေးခြင်း
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Database ထဲက အကောင့်နဲ့ ကိုက်ညီမှု ရှိမရှိ စစ်ဆေးခြင်း
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('/checkout');
        }

        // အကောင့် သို့မဟုတ် Password မှားနေခဲ့လျှင်
        return back()->withErrors([
            'email' => 'The email or password you entered is incorrect.',
        ]);
    }

    // Register Form ကို ပြပေးမည့် Method
    public function showRegisterForm()
    {
        return view('register');
    }

    // အကောင့်အသစ် ဖန်တီးပေးမည့် Method
    public function register(Request $request)
    {
        // ဖြည့်ထားတဲ့ အချက်အလက်တွေ မှန်မမှန် စစ်ဆေးခြင်း
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // email က ထပ်နေလို့မရပါ
            'password' => 'required|string|min:8|confirmed', // confirmed က password နဲ့ password_confirmation တူမတူ စစ်ပေးတာပါ
        ]);

        // User table ထဲကို အကောင့်အသစ် သိမ်းခြင်း
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password ကို လုံခြုံအောင် ပြောင်းပြီးမှ သိမ်းမယ်
        ]);

        // အကောင့်ဖွင့်ပြီးတာနဲ့ တစ်ခါတည်း Login ဝင်ပေးလိုက်မည်
        Auth::login($user);
        $request->session()->regenerate();

        // Checkout ကို ဆက်သွားပါမည်
        return redirect()->intended('/checkout');
    }

   
    
        public function profile()
    {
        // လက်ရှိ Login ဝင်ထားသူ၏ Order များကို Database မှ ဆွဲထုတ်မည်
        $orders = \App\Models\Order::with('items')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();
        
        return view('profile', compact('orders'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Remove avatar if requested
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // Upload new avatar if provided
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
        
        

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}