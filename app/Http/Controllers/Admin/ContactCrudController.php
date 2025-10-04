<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

class ContactCrudController extends CrudController
{
    use ListOperation, CreateOperation, UpdateOperation, DeleteOperation, ShowOperation;
    public function setup(): void
    {
        CRUD::setModel(\App\Models\Contact::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('contact', 'contacts');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name');
        CRUD::column('email');
        CRUD::addColumn([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'image',
            'disk' => 'public',
            'default' => asset('images/placeholder-avatar.png'),
            'height' => '50px',
            'width' => '50px',
        ]);
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(ContactRequest::class);

        CRUD::field('name');
        CRUD::field('email');
        CRUD::addField([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'upload', // using core upload field; image field requires Backpack Pro
            'upload' => true,
            'disk' => 'public',
            'path' => 'contacts', // stored at storage/app/public/contacts
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
