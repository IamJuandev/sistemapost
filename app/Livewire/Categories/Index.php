<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $description = '';
    public $selectedCategory = null;
    public $showCreateForm = false;
    public $showEditForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.categories.index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function store()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        session()->flash('message', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        $this->resetForm();
        $this->selectedCategory = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->selectedCategory) {
            $this->selectedCategory->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $this->resetForm();
            session()->flash('message', 'Categoría actualizada exitosamente.');
        }
    }

    public function delete(Category $category)
    {
        $category->delete();
        session()->flash('message', 'Categoría eliminada exitosamente.');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->selectedCategory = null;
        $this->showCreateForm = false;
        $this->showEditForm = false;
        $this->resetErrorBag();
    }
}
