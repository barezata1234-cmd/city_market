<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
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
        $c = Customer::findOrFail($id);
        $this->editingId = $c->id;
        $this->name = $c->name;
        $this->phone = $c->phone;
        $this->email = $c->email;
        $this->address = $c->address;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();
        Customer::updateOrCreate(['id' => $this->editingId], $data);
        session()->flash('success', $this->editingId ? 'شەریکەکە نوێکرایەوە' : 'شەریکی نوێ زیادکرا');
        $this->showModal = false;
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
        session()->flash('success', 'شەریکەکە سڕایەوە');
    }

    public function render()
    {
        return view('livewire.customers.index', ['customers' => Customer::latest()->paginate(10)])
            ->layout('layouts.app', ['title' => 'شەریکەکان']);
    }
}
