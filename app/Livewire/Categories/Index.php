<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Index extends Component
{
    public $showModal = false;
    public $editingId = null;
    public $name, $name_ku, $description;

    protected $rules = [
        'name' => 'required|min:2',
        'name_ku' => 'nullable',
        'description' => 'nullable',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'name', 'name_ku', 'description']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $c = Category::findOrFail($id);
        $this->editingId = $c->id;
        $this->name = $c->name;
        $this->name_ku = $c->name_ku;
        $this->description = $c->description;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();
        Category::updateOrCreate(['id' => $this->editingId], $data);
        session()->flash('success', $this->editingId ? 'جۆرەکە نوێکرایەوە' : 'جۆری نوێ زیادکرا');
        $this->showModal = false;
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'جۆرەکە سڕایەوە');
    }

    public function render()
    {
        return view('livewire.categories.index', ['categories' => Category::latest()->paginate(10)])
            ->layout('layouts.app', ['title' => 'جۆرەکان']);
    }
}
