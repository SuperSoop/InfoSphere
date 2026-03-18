<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CategoryCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(Category::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/category');
        $this->crud->setEntityNameStrings('category', 'categories');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug', 'hint' => 'Auto-generated from name if left empty'],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $this->crud->getCurrentEntryId(),
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
        ]);
    }
}
