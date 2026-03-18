<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ArticleCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation, ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Article::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/article');
        $this->crud->setEntityNameStrings('article', 'articles');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'user.name', 'type' => 'text', 'label' => 'Author'],
            ['name' => 'category.name', 'type' => 'text', 'label' => 'Category'],
            ['name' => 'status', 'type' => 'text', 'label' => 'Status'],
            ['name' => 'views_count', 'type' => 'number', 'label' => 'Views'],
            ['name' => 'created_at', 'type' => 'datetime', 'label' => 'Created'],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
        ]);

        $this->crud->addFields([
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug'],
            [
                'name' => 'user_id',
                'type' => 'select',
                'label' => 'Author',
                'entity' => 'user',
                'model' => 'App\Models\User',
                'attribute' => 'name',
            ],
            [
                'name' => 'category_id',
                'type' => 'select',
                'label' => 'Category',
                'entity' => 'category',
                'model' => 'App\Models\Category',
                'attribute' => 'name',
                'allows_null' => true,
            ],
            [
                'name' => 'community_id',
                'type' => 'select',
                'label' => 'Community',
                'entity' => 'community',
                'model' => 'App\Models\Community',
                'attribute' => 'name',
                'allows_null' => true,
            ],
            [
                'name' => 'tags',
                'type' => 'select_multiple',
                'label' => 'Tags',
                'entity' => 'tags',
                'model' => 'App\Models\Tag',
                'attribute' => 'name',
                'pivot' => true,
            ],
            ['name' => 'excerpt', 'type' => 'textarea', 'label' => 'Excerpt'],
            ['name' => 'content', 'type' => 'textarea', 'label' => 'Content'],
            ['name' => 'image', 'type' => 'upload', 'label' => 'Image', 'upload' => true, 'disk' => 'public', 'prefix' => 'storage/'],
            [
                'name' => 'status',
                'type' => 'select_from_array',
                'label' => 'Status',
                'options' => ['draft' => 'Draft', 'published' => 'Published'],
                'default' => 'draft',
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->setValidation([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $this->crud->getCurrentEntryId(),
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published',
        ]);
    }
}
