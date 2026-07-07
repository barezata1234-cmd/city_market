<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        // وەرگرتنی ئیمەیل لە ڕێگەی بەستەرەکەوە ئەگەر بوونی هەبێت
        $this->email = request()->email;
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'تکایە وشە نهێنییە نوێیەکە بنووسە.',
            'password.min' => 'وشەی نهێنی دەبێت لانی کەم ٨ پیت یان ژمارە بێت.',
            'password.confirmed' => 'وشە نهێنییەکان وەک یەک نین.',
            'email.required' => 'ئیمەیل پێویستە.',
        ]);

        // جێبەجێکردنی گۆڕانکارییەکە لە ڕێگەی لارافلەوە
        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        // ئەگەر سەرکەوتوو بوو، دەیبەینەوە بۆ لاپەڕەی چوونەژوورەوە
        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', 'وشەی نهێنیت بە سەرکەوتوویی گۆڕدرا. ئێستا دەتوانیت بچیتە ژوورەوە.');
            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.guest');
    }
}