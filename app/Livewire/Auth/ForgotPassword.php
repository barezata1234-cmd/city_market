<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email;
    public $status = null;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.exists' => 'ئەم ئیمەیلە لە سیستەمەکەماندا بوونی نییە.',
        'email.required' => 'تکایە ئیمەیلەکەت بنووسە.',
        'email.email' => 'تکایە ئیمەیلێکی دروست بنووسە.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // ناردنی بەستەرەکە لە ڕێگەی لارافلەوە
        $response = Password::sendResetLink(['email' => $this->email]);

        if ($response == Password::RESET_LINK_SENT) {
            $this->status = 'بەستەری گۆڕینی وشەی نهێنی نێردرا بۆ ئیمەیلەکەت.';
            $this->email = ''; // پاککردنەوەی ئینپوتەکە
        } else {
            $this->addError('email', __($response));
        }
    }

public function render()
{
    // ئێستا بە لایڤوایەر دەڵێین کە قاڵبی گێست بەکاربهێنێت نەک ئەپی سەرەکی
    return view('livewire.auth.forgot-password')->layout('layouts.guest');
}
}