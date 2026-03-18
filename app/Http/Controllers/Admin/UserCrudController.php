<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class UserCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation, ShowOperation;

    public function setup()
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email'],
            ['name' => 'role', 'type' => 'text', 'label' => 'Role'],
            ['name' => 'is_blocked', 'type' => 'boolean', 'label' => 'Blocked'],
            ['name' => 'created_at', 'type' => 'datetime', 'label' => 'Created'],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,moderator,user',
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email'],
            ['name' => 'password', 'type' => 'password', 'label' => 'Password'],
            [
                'name' => 'role',
                'type' => 'select_from_array',
                'label' => 'Role',
                'options' => ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'],
                'default' => 'user',
            ],
            ['name' => 'is_blocked', 'type' => 'checkbox', 'label' => 'Blocked'],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->crud->getCurrentEntryId(),
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,moderator,user',
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email'],
            ['name' => 'password', 'type' => 'password', 'label' => 'Password', 'hint' => 'Leave empty to keep current'],
            [
                'name' => 'role',
                'type' => 'select_from_array',
                'label' => 'Role',
                'options' => ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'],
            ],
            ['name' => 'is_blocked', 'type' => 'checkbox', 'label' => 'Blocked'],
        ]);
    }
}
