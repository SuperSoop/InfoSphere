<?php

namespace App\Http\Controllers\Admin;

use App\Models\Community;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CommunityCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation, ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Community::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/community');
        $this->crud->setEntityNameStrings('community', 'communities');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
            ['name' => 'creator.name', 'type' => 'text', 'label' => 'Creator'],
            ['name' => 'is_private', 'type' => 'boolean', 'label' => 'Private'],
            ['name' => 'created_at', 'type' => 'datetime', 'label' => 'Created'],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:communities,slug',
            'user_id' => 'required|exists:users,id',
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'cover_image', 'type' => 'upload', 'label' => 'Cover Image', 'upload' => true, 'disk' => 'public', 'prefix' => 'storage/'],
            [
                'name' => 'user_id',
                'type' => 'select',
                'label' => 'Creator',
                'entity' => 'creator',
                'model' => 'App\Models\User',
                'attribute' => 'name',
            ],
            ['name' => 'is_private', 'type' => 'checkbox', 'label' => 'Private'],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:communities,slug,' . $this->crud->getCurrentEntryId(),
            'user_id' => 'required|exists:users,id',
        ]);
    }
}
