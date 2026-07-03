<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public $showModal = false;
    public $editingId = null;
    public $name, $email, $phone, $password, $role = 'cashier';

    public function rules()
    {
        $rules = [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $this->editingId,
            'phone' => 'nullable',
            'role' => 'required|in:admin,manager,cashier',
        ];
        $rules['password'] = $this->editingId ? 'nullable|min:6' : 'required|min:6';
        return $rules;
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name', 'email', 'phone', 'password']);
        $this->role = 'cashier';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $u = User::findOrFail($id);
        $this->editingId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->phone = $u->phone;
        $this->role = $u->role;
        $this->password = '';
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        User::updateOrCreate(['id' => $this->editingId], $data);
        session()->flash('success', $this->editingId ? 'بەکارهێنەرەکە نوێکرایەوە' : 'بەکارهێنەری نوێ زیادکرا');
        $this->showModal = false;
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'ناتوانیت هەژماری خۆت بسڕیتەوە');
            return;
        }
        User::findOrFail($id)->delete();
        session()->flash('success', 'بەکارهێنەرەکە سڕایەوە');
    }

    public function render()
    {
        return view('livewire.users.index', ['users' => User::latest()->paginate(10)])
            ->layout('layouts.app', ['title' => 'بەکارهێنەران']);
    }
}
