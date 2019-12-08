
# LaraSeed

Package built to extend the capabilities of the Seeders and simplify the Models of your Laravel application.

Current features:

- **Seeders trait**: prevent your seeders to run twice.
- **Version variable**: for burst cache of your images and other assets in the front-end.
- **Administrator model**: separate the functionalities for your application's users and your staff.
- **Soft deletes**: adding a soft-delete column to your user's table.
- **Base models**: for your authenticatable models and regular models (with or without soft-delete).

## Installing the package

Configure `tonnyorg/laraseed` as a composer dependency running `composer require tonnyorg/laraseed`.

Register the package in your application's providers array (`config/app.php`):

```php
    'providers' => [
        /*
         * Package Service Providers...
         */
        TonnyORG\LaraSeed\ServiceProvider::class,
    ],
```

---

## Features & How-tos

### Seeders trait

Useful for scenarios when you need to run seeders on production. You can store those seeders that you have run already to prevent them to run again in the future. Imagine that you have a "migrations" table for your seeders, that's it.

**Instructions**:

In order to use this feature, you need to run the package's migrations through `php artisan migrate`, that will create the `seeders` table used by this feature.

Once you're done, the next step is to make use of the `SeedersHandler` class from your `database/seeds/DatabaseSeeder.php` file:

```php
<?php

use Illuminate\Database\Seeder;
use TonnyORG\LaraSeed\Database\Traits\SeedersHandler;

class DatabaseSeeder extends Seeder
{
    use SeedersHandler;

```

The final step is to replace the `call` method with `try`. So instead of:

```php
$this->call(UsersTableSeeder::class);
```

you are going to write it up like this:

```php
$this->try(UsersTableSeeder::class);
```

### Version variable

Imagine that you have CloudFlare or any other strong cache handler; then compiling your assets and updating the version number in the `CSS` and `JS` files is not a big deal. However, in order to burst the cache of your images you need to run a manual process and/or tag them with another name.

This feature allows you to have a "version number" based on the number of commits in your repository. That said, you only have to append `?v={{ config('laraseed.revision_number) }}` everytime you place a new image on your theme (e.g. your header and footer logo images).

**Instructions**:

In order to make use of this feature, the first step is to publish the configuration file:

```shell
php artisan vendor:publish --tag=laraseed-config
```

Add the `LARASEED_REVISION_NUMBER=1` to your `.env` file.

And finally, update it everytime you release your project through the command:

```shell
php artisan laraseed:update-revision-number
```

## Administrator model

By default, Laravel asumes that we want to use a User model for any kind of user across our application; this `Administrator` model allows you to separate the capabilities of your users and your staff.

I know what you are thinking about, YES!, we have ACL packages and so, but this model results helpful when your management application and your customer's one are separated and when you need to restrict the access of your management application to your staff only.

**Instructions**:

The first step to make use of this feature is to copy the migration file:

```shell
php artisan vendor:publish --tag=laraseed-migration-administrators
```

Run the migrations just after to create the `administrators` and the `administrator_password_resets` tables (because... we also need it, right?):

```shell
php artisan migrate
```

Once you're done, don't forget to add the configuration pieces in your `config/auth.php` file:

```php
    'providers' => [
        'users' => [
            // other stuff here
        ],

        'administrators' => [
            'driver' => 'eloquent',
            'model' => App\Administrator::class, // We are going to create this model in the next step
        ],
    ],

    // more stuff here

    'passwords' => [
        'users' => [
            // and more stuff here as well
        ],

        'administrators' => [
            'provider' => 'administrators',
            'table' => 'administrator_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
```

Finally, create a model through `php artisan make:model Administrator` and copy the default properties (of the `User` model).

**Optional**:

Per convenience, I also include a migration to rename the default `password_resets` table to `user_password_resets`.

We need to publish this migration:

```shell
php artisan vendor:publish --tag=laraseed-migration-renaming
```

And run the migration:

```shell
php artisan migrate
```

Once you're done with the migrations, then just update your `config/auth.php` file with the new table name:

```php
    'passwords' => [
        'users' => [
            //
            'table' => 'user_password_resets',
            //
        ],
    ],
```

## Soft deletes

Simple migrations to add `deleted_at` column to your `users` and (if installed) your `administrators` tables.

**Instructions**:

Publish the migration:

```shell
php artisan vendor:publish --tag=laraseed-migration-soft-deletes
```

And run the migration:

```shell
php artisan migrate
```

## Base models

One of the more "juicy" parts of this package is the base models, which are just abstract classes that you can use as a "bootstrap" class for your models.

We have 3 types of base models: `Model`, `SoftModel` and `Authenticatable`, it's up to you which one to use (the `SoftModel` ones require your tables to have the `deleted_at` column).

So to clarify, looking at `TonnyORG\LaraSeed\Models\Base` namespace there are 3 classes:

- Authenticatable: this is an abstract class based on the original `App\User` class, so it does have everything to handle authentication for your application like the default model in a brand new Laravel installation.
- Model: it's an abstract class that includes some default properties such `$dates`. All models extended from this class must have `timestamps`.
- SoftModel: it's based on Model and the only difference is that this model also requires `deleted_at` column because that's the one used for soft-deletes.

Inside the `TonnyORG\LaraSeed\Models` namespace we also have 4 more classes:

- Administrator: this extends from `Authenticatable` but also includes the `$table` property.
- SoftAdministrator: this extends from `Administrator` but also handles soft-deletes.
- User:  this extends from `Authenticatable` but also includes the `$table` property, similar than `Administrator`.
- SoftUser: this extends from `User` but also handles soft-deletes, similar than `SoftAdministrator`.

**Instructions**:

To make use of these classes, you just need to make your models and extend from them instead of the original `Illuminate\Database\Eloquent\Model` class:

```php
// app/User.php
<?php

namespace App;

use TonnyORG\LaraSeed\Models\SoftUser;

class User extends SoftUser
{
    //
}
```

and:

```php
// app/Post.php
<?php

namespace App;

use TonnyORG\LaraSeed\Models\Base\Model;

class Post extends Model
{
    //
}
```

---

## Final notes

### To-Dos

- [ ] Create repository templates for issues.

### License

License details [here](/LICENSE).
