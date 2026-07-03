<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $showModal = false;
    public $editingId = null;
    public $title, $amount, $category, $expense_date, $note;

    protected $rules = [
        'title' => 'required|min:2',
        'amount' => 'required|numeric|min:0',
        'category' => 'nullable',
        'expense_date' => 'required|date',
        'note' => 'nullable',
    ];

    public function mount()
    {
        $this->expense_date = now()->format('Y-m-d');
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'title', 'amount', 'category', 'note']);
        $this->expense_date = now()->format('Y-m-d');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $e = Expense::findOrFail($id);
        $this->editingId = $e->id;
        $this->title = $e->title;
        $this->amount = $e->amount;
        $this->category = $e->category;
        $this->expense_date = $e->expense_date->format('Y-m-d');
        $this->note = $e->note;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();
        $data['user_id'] = Auth::id();
        Expense::updateOrCreate(['id' => $this->editingId], $data);
        session()->flash('success', $this->editingId ? 'خەرجییەکە نوێکرایەوە' : 'خەرجی نوێ زیادکرا');
        $this->showModal = false;
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
        session()->flash('success', 'خەرجییەکە سڕایەوە');
    }

    public function render()
    {
        return view('livewire.expenses.index', ['expenses' => Expense::with('user')->latest('expense_date')->paginate(10)])
            ->layout('layouts.app', ['title' => 'خەرجییەکان']);
    }
}
