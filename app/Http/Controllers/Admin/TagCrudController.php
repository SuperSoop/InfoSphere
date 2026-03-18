<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class TagCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(Tag::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');
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
            'slug' => 'required|string|max:255|unique:tags,slug',
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $this->crud->getCurrentEntryId(),
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
        ]);
    }
}
