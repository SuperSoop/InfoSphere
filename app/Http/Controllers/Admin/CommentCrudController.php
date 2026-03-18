<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

class CommentCrudController extends CrudController
{
    use ListOperation, DeleteOperation, ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Comment::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comment');
        $this->crud->setEntityNameStrings('comment', 'comments');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'user.name', 'type' => 'text', 'label' => 'Author'],
            ['name' => 'article.title', 'type' => 'text', 'label' => 'Article'],
            ['name' => 'body', 'type' => 'text', 'label' => 'Comment', 'limit' => 100],
            ['name' => 'created_at', 'type' => 'datetime', 'label' => 'Created'],
        ]);
    }
}
