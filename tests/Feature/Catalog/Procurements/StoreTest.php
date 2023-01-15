<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Supplier;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a procurement if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:procurements:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('ensure the procurement supplier is available', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => 40,
        ]
    )->assertSessionHasErrors(keys: ['supplier_id']);
})->with('admin');

it('ensures that the procurement is of the allowed types', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'type' => 'some unknown type',
        ]
    )->assertSessionHasErrors(keys: ['type']);
})->with('admin');

it('ensures that no procurements of un allowed variants', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => 50,
        ]
    )->assertSessionHasErrors(keys: ['item_id']);
})->with('admin');

it('validates the number of single pieces if the form is singles', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'procurement_details' => [
                'form' => ProcurementItemForms::singles()->label,
            ],
        ]
    )->assertSessionHasErrors(keys: ['procurement_details.number_of_single_pieces']);
})->with('admin');

it('validates the form quantity if the form is not singles', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
            ],
        ]
    )->assertSessionHasErrors(keys: ['procurement_details.form_quantity']);
})->with('admin');

it('ensures that no procurement of un allowed products', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => 50,
        ]
    )->assertSessionHasErrors(keys: ['item_id']);
})->with('admin');

it('ensures that the procurement item is of the allowed form', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'procurement_details' => [
                'form' => 'some unknown form',
            ],
        ]
    )->assertSessionHasErrors(keys: ['procurement_details.form']);
})->with('admin');

it('validates the all the procurement details', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(uri: route('api:v1:executive:procurements:store'))->assertSessionHasErrors(keys: [
        'supplier_id',
        'type',
        'procurement_details.form',
        'procurement_details.measure',
    ]);
})->with('admin');

it('ensures that the variant intended for procurement is supplied by the selected supplier', function (
    User $admin,
    Supplier $supplier,
    Variant $variant
) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'supplier', 'variant');

it('ensures that the product intended for procurement is supplied by the selected supplier', function (
    User $admin,
    Supplier $supplier,
    Product $independent_product
) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $supplier->id,
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => $independent_product->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'supplier', 'independent_product');

it('ensures that the selected supplier is active for procurements', function (
    User $super_admin,
    Supplier $inactive_supplier,
    Variant $variant
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $inactive_supplier->variants()->attach($variant->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $inactive_supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('super_admin', 'inactive_supplier', 'variant');

it('can create a procurement for a variant with form of box for super admin', function (
    User $super_admin,
    Supplier $active_supplier,
    Variant $variant
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $active_supplier->variants()->attach($variant->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('super_admin', 'active_supplier', 'variant');

it('can create a procurement for a variant with form of box for admin', function (
    User $admin,
    Supplier $active_supplier,
    Variant $variant
) {
    actingAs(user: $admin, abilities: ['admin']);
    $active_supplier->variants()->attach($variant->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('admin', 'active_supplier', 'variant');

it('can create a procurement for a variant with form of singles for super admin', function (
    User $super_admin,
    Supplier $active_supplier,
    Variant $variant
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $active_supplier->variants()->attach($variant->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::singles()->label,
                'number_of_single_pieces' => 10,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('super_admin', 'active_supplier', 'variant');

it('can create a procurement for a variant with form of singles for admin', function (
    User $admin,
    Supplier $active_supplier,
    Variant $variant
) {
    actingAs(user: $admin, abilities: ['admin']);
    $active_supplier->variants()->attach($variant->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::VARIANT->value,
            'item_id' => $variant->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::singles()->label,
                'number_of_single_pieces' => 10,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('admin', 'active_supplier', 'variant');

it('can create a procurement for a product with form of box for super admin', function (
    User $super_admin,
    Supplier $active_supplier,
    Product $independent_product
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $active_supplier->products()->attach($independent_product->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => $independent_product->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('super_admin', 'active_supplier', 'independent_product');

it('can create a procurement for a product with form of box for admin', function (
    User $admin,
    Supplier $active_supplier,
    Product $independent_product
) {
    actingAs(user: $admin, abilities: ['admin']);
    $active_supplier->products()->attach($independent_product->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => $independent_product->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::box()->label,
                'form_quantity' => 2,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('admin', 'active_supplier', 'independent_product');

it('can create a procurement for a product with form of singles for super admin', function (
    User $super_admin,
    Supplier $active_supplier,
    Product $independent_product
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $active_supplier->products()->attach($independent_product->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => $independent_product->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::singles()->label,
                'number_of_single_pieces' => 10,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('super_admin', 'active_supplier', 'independent_product');

it('can create a procurement for a product with form of singles for admin', function (
    User $admin,
    Supplier $active_supplier,
    Product $independent_product
) {
    actingAs(user: $admin, abilities: ['admin']);
    $active_supplier->products()->attach($independent_product->id);

    post(
        uri: route('api:v1:executive:procurements:store'),
        data: [
            'supplier_id' => $active_supplier->id,
            'type' => AllowedItemTypes::PRODUCT->value,
            'item_id' => $independent_product->id,
            'procurement_details' => [
                'form' => ProcurementItemForms::singles()->label,
                'number_of_single_pieces' => 10,
                'measure' => 250,
            ],
        ]
    )->assertStatus(status: Http::CREATED())
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'procurement')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Created Successfully.')
          ->has(
              'procurement',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected:'procurement')
            ->where(key: 'relationships.supplier.id', expected: $active_supplier->id)
            ->where(key: 'relationships.supplier.attributes.uuid', expected: $active_supplier->uuid)
            ->etc()
          )
        );

    $this->assertDatabaseCount(table: 'procurements', count: 1)
          ->assertDatabaseHas('procurements', [
              'status' => ProcurementStatus::pending()->label,
              'supplier_id' => $active_supplier->id,
          ]);
})->with('admin', 'active_supplier', 'independent_product');
