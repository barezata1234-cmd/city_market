<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;

class Index extends Component
{
    public $showModal = false;
    public $editingId = null;
    public $name, $phone, $email, $address;

    protected $rules = [
        'name' => 'required|min:2',
        'phone' => 'nullable',
        'email' => 'nullable|email',
        'address' => 'nullable',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'name', 'phone', 'email', 'address']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $s = Supplier::findOrFail($id);
        $this->editingId = $s->id;
        $this->name = $s->name;
        $this->phone = $s->phone;
        $this->email = $s->email;
        $this->address = $s->address;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();
        Supplier::updateOrCreate(['id' => $this->editingId], $data);
        session()->flash('success', $this->editingId ? 'دابینکەرەکە نوێکرایەوە' : 'دابینکەری نوێ زیادکرا');
        $this->showModal = false;
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        session()->flash('success', 'دابینکەرەکە سڕایەوە');
    }

    public function render()
    {
        return view('livewire.suppliers.index', ['suppliers' => Supplier::latest()->paginate(10)])
            ->layout('layouts.app', ['title' => 'دابینکەران']);
    }
}
