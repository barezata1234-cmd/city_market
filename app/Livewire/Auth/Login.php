<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\NetworkService;
use App\Models\User;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;
    public $offlineUsers = []; 

    public function mount()
    {
        // ئەگەر ئۆفلاین بووین، کەنێکشنەکە دەگۆڕین پێش ئەوەی پشکنینی لۆگین بکات
        if (!NetworkService::isOnline()) {
            config(['database.default' => 'sqlite_offline']);
            config(['session.driver' => 'file']);
        }

        if (Auth::check()) {
            return redirect()->route('sales.create');
        }
    }

    public function login()
    {
        // ⚡ ناردنی ڕێساکان و نامەکان ڕاستەوخۆ بۆ ناو میتۆدی validate (تایبەت بە Livewire v3)
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'تكایە ئیمێڵەکەت بنووسە.',
            'email.email' => 'شێوازی ئیمێڵەکە دروست نییە.',
            'password.required' => 'تكایە وشەی نهێنی بنووسە.',
        ]);

        $isOnline = NetworkService::isOnline();

        if ($isOnline) {
            // 🌐 لۆگینی ئۆنلاین (MySQL)
            config(['database.default' => 'mysql']);
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                session()->regenerate();
                return redirect()->intended(route('sales.create'));
            }
        } else {
            // 🔌 لۆگینی ئۆفلاین (SQLite)
            try {
                config(['database.default' => 'sqlite_offline']);
                
                $user = User::where('email', $this->email)
                            ->where('is_active', 1)
                            ->where('can_use_offline', 1)
                            ->first();

                if ($user && Hash::check($this->password, $user->password)) {
                    Auth::login($user, $this->remember);
                    session()->regenerate();
                    
                    session()->flash('warning', 'تۆ بە سەرکەوتوویی لە دۆخی ئۆفلایندا چوویتە ژوورەوە!');
                    return redirect()->route('sales.create');
                }
            } catch (\Exception $e) {
                $this->addError('email', 'کێشەی ئۆفلاین: ' . $e->getMessage());
                return;
            }
        }

        $this->addError('email', 'ئیمێڵ یان وشەی نهێنی دەستنیشانکراو هەڵەیە!');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest'); 
    }
}