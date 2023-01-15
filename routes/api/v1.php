<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 */
Route::prefix('auth')->as('auth:')->group(function () {
    // the login route
    Route::post('login', App\Http\Controllers\Api\V1\Auth\LoginController::class)->name('login');
});

/**
 * Super Admin Routes
 */
Route::group(
    [
        'prefix' => 'superadmin',
        'as' => 'superadmin:',
        'middleware' => ['auth:sanctum', 'ability:super-admin'],
    ],
    function () {
        /**
         * Role Management Routes
         */
        Route::prefix('roles')->as('roles:')->group(function () {
            /**
             * Fetch Available Roles
             */
            Route::get('/', App\Http\Controllers\Api\V1\SuperAdmin\Role\IndexController::class)->name('index');


            /**
             * Create A New Role
             */
            Route::post('/', App\Http\Controllers\Api\V1\SuperAdmin\Role\StoreController::class)->name('store');

            /**
             * Edit A Role
             */
            Route::patch('{role:uuid}', App\Http\Controllers\Api\V1\SuperAdmin\Role\UpdateController::class)->name('update');

            /**
             * Delete A Role
             */
            Route::delete('{role:uuid}', App\Http\Controllers\Api\V1\SuperAdmin\Role\DeleteController::class)->name('delete');
        });

        /**
         * Employee Management Routes
         */
        Route::prefix('employees')->as('employees:')->group(function () {
            /**
             * create a new employee
             */
            Route::post('/', App\Http\Controllers\Api\V1\SuperAdmin\Employee\StoreController::class)->name('store');

            /**
             * show all employee
             */
            Route::get('/', App\Http\Controllers\Api\V1\SuperAdmin\Employee\IndexController::class)->name('index');

            /**
             * edit an employee details
             */
            Route::patch('{user:uuid}', App\Http\Controllers\Api\V1\SuperAdmin\Employee\UpdateController::class)->name('update');

            /**
             * delete an employee
             */
            Route::delete('{user:uuid}', App\Http\Controllers\Api\V1\SuperAdmin\Employee\DeleteController::class)->name('delete');
        });
    }
);

/**
 * Super Admin and Admin Routes
 */
Route::group([
    'prefix' => 'executive',
    'as' => 'executive:',
    'middleware' => ['auth:sanctum', 'ability:admin,super-admin'],
], function () {
    /**
     * Categories Routes
     */
    Route::prefix('categories')->as('categories:')->group(function () {
        /**
         * Fetch all categories
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Category\IndexController::class)->name('index');

        /**
         * Create a new category
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Category\StoreController::class)->name('store');

        /**
         * Edit a category
         */
        Route::patch('{category:uuid}', App\Http\Controllers\Api\V1\Executive\Category\UpdateController::class)->name('update');

        /**
         * Delete a category
         */
        Route::delete('{category:uuid}', App\Http\Controllers\Api\V1\Executive\Category\DeleteController::class)->name('delete');
    });

    /**
     * Products Routes
     */
    Route::prefix('products')->as('products:')->group(function () {
        /**
         * Fetch all products
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Products\IndexController::class)->name('index');

        /**
         * Create a new product
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Products\StoreController::class)->name('store');

        /**
         * Update a specific product details
         */
        Route::patch('{product:uuid}', App\Http\Controllers\Api\V1\Executive\Products\UpdateController::class)->name('update');

        /**
         * Delete a specific product
         */
        Route::delete('{product:uuid}', App\Http\Controllers\Api\V1\Executive\Products\DeleteController::class)->name('delete');
    });

    /**
     * Variant Routes
     */
    Route::prefix('variants')->as('variants:')->group(function () {
        /**
         * Fetch all variants
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Variants\IndexController::class)->name('index');

        /**
         * Create a new variant
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Variants\StoreController::class)->name('store');

        /**
         * Update a variant details
         */
        Route::patch('{variant:uuid}', App\Http\Controllers\Api\V1\Executive\Variants\UpdateController::class)->name('update');

        /**
         * Delete a variant
         */
        Route::delete('{variant:uuid}', App\Http\Controllers\Api\V1\Executive\Variants\DeleteController::class)->name('delete');
    });

    /**
     * Table routes
     */
    Route::prefix('tables')->as('tables:')->group(function () {
        /**
         * Fetch all tables
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Table\IndexController::class)->name('index');

        /**
         * Create a new table
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Table\StoreController::class)->name('store');

        /**
         * Update a table
         */
        Route::patch('{table:uuid}', App\Http\Controllers\Api\V1\Executive\Table\UpdateController::class)->name('update');

        /**
         * Delete a table
         */
        Route::delete('{table:uuid}', App\Http\Controllers\Api\V1\Executive\Table\DeleteController::class)->name('delete');
    });

    /**
     * Shift routes
     */
    // Route::prefix('shifts')->as('shifts:')->group(function () {
    //     /**
    //      * Fetch all created shifts
    //      */
    //     Route::get('/', App\Http\Controllers\Api\V1\Admin\Shift\IndexController::class)->name('index');

    //     /**
    //      * Create a new Shift
    //      */
    //     Route::post('/', App\Http\Controllers\Api\V1\Admin\Shift\StoreController::class)->name('store');
    // });

    /**
     * Suppliers routes
     */
    Route::prefix('suppliers')->as('suppliers:')->group(function () {
        /**
         * Fetch all created suppliers
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Suppliers\IndexController::class)->name('index');

        /**
         * Create a new supplier
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Suppliers\StoreController::class)->name('store');

        /**
         * Update a supplier
         */
        Route::patch('{supplier:uuid}', App\Http\Controllers\Api\V1\Executive\Suppliers\UpdateController::class)->name('update');

        /**
         * Delete a supplier
         */
        Route::delete('{supplier:uuid}', App\Http\Controllers\Api\V1\Executive\Suppliers\DeleteController::class)->name('delete');

        /**
         * Create Supplier Supply Items
         */
        Route::post('{supplier:uuid}/items', App\Http\Controllers\Api\V1\Executive\Suppliers\Items\StoreController::class)->name('items_store');

        /**
         * Delete Supplier Supply Items
         */
        Route::delete('{supplier:uuid}/items', App\Http\Controllers\Api\V1\Executive\Suppliers\Items\DeleteController::class)->name('items_delete');
    });

    /**
     * Procurement routes
     */
    Route::prefix('procurements')->as('procurements:')->group(function () {
        /**
         * Fetch all available procurements
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Procurements\IndexController::class)->name('index');

        /**
         * Create a new procurement
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Procurements\StoreController::class)->name('store');

        /**
         * Update a procurement
         */
        Route::patch('{procurement:uuid}', App\Http\Controllers\Api\V1\Executive\Procurements\UpdateController::class)->name('update');

        /**
         * Update a procurement item
         */
        Route::patch('{procurement:uuid}/item', App\Http\Controllers\Api\V1\Executive\Procurements\Items\UpdateController::class)->name('update_procurement_item');

        /**
         * Update store quantity
         */
        Route::patch('{procurement:uuid}/item/update-store', App\Http\Controllers\Api\V1\Executive\Procurements\Store\UpdateController::class)->name('update_store');

        /**
         * Delete a procurement
         */
        Route::delete('{procurement:uuid}', App\Http\Controllers\Api\V1\Executive\Procurements\DeleteController::class)->name('delete');
    });

    /**
     * Tokens routes
     */
    Route::prefix('tokens')->as("tokens:")->group(function () {
        /**
         * Fetch all available tokens
         */
        Route::get('/', App\Http\Controllers\Api\V1\Executive\Tokens\IndexController::class)->name('index');

        /**
         * Create a token
         */
        Route::post('/', App\Http\Controllers\Api\V1\Executive\Tokens\StoreController::class)->name('store');

        /**
         * Update a token
         */
        Route::patch('{token:uuid}', App\Http\Controllers\Api\V1\Executive\Tokens\UpdateController::class)->name('update');

        /**
         * Update store quantity
         */
        Route::patch('{token:uuid}/item/update-store', App\Http\Controllers\Api\V1\Executive\Tokens\Store\UpdateController::class)->name('update_store');

        /**
         * Delete a token
         */
        Route::delete('{token:uuid}', App\Http\Controllers\Api\V1\Executive\Tokens\DeleteController::class)->name('delete');
    });
});

/**
 * Bartender Routes
 */
Route::group([
    'prefix' => 'bartender',
    'as' => 'bartender:',
    'middleware' => ['auth:sanctum', 'ability:bartender,admin,super-admin'],
], function () {
    /**
     * Counter routes
     */
    Route::prefix('counters')->as('counters:')->group(function () {
        /**
         * Fetch all available counters
         */
        Route::get('/', App\Http\Controllers\Api\V1\Bartender\Counter\IndexController::class)->name('index');
    });

    /**
     * Shift routes
     */
    Route::prefix('shifts')->as('shifts:')->group(function () {
        /**
         * End an active shift
         */
        Route::delete('/', App\Http\Controllers\Api\V1\Bartender\Shift\DeleteController::class)->name('delete');
    });

    /**
     * Order routes
     */
    Route::prefix('orders')->as('orders:')->group(function () {
        /**
         * Create a new Order
         */
        Route::post('/', App\Http\Controllers\Api\V1\Bartender\Order\StoreController::class)->name('store');

        /**
         * Fetch all available  orders
         */
        Route::get('/', App\Http\Controllers\Api\V1\Bartender\Order\IndexController::class)->name('index');
    });
});
