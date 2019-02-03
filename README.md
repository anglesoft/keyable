# 🗝 Keyable Models in Laravel

## Introduction

Many web applications require unique references to their data. Sometimes, increments and uuids are not enough for specific use-cases. Keyable attempts to solve this problem by providing methods that are automatically called on your models, via a trait.

## Requirements

- PHP >= 7.1
- Laravel >= 5.6
- Relational Database (not tested with other drivers)

## Installation

Install the package via Composer:

```
composer require angle/keyable
```

## Using the Keyable Trait

Granted the Model is using ```Angle\Keyable\Keyable```, unique keys are automatically generated upon the ```creating()``` [Eloquent Model Event](https://laravel.com/docs/5.7/eloquent#events).

```php
<?php

namespace App;

use Angle\Keyable\Keyable;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use Keyable;

    /**
     * Attributes containing unique keys.
     *
     * @var array
     */
    public $keys = ['fingerprint' => 256];
}
```

## Implementing A Custom Strategy

By default, the Keyable Trait uses Laravel [Str::random()](https://laravel.com/api/5.0/Illuminate/Support/Str.html) helper to generate unique keys. You may override this behavior by implementing ```keyableStrategy()``` method on your model.

```php
<?php

namespace App;

use Angle\Keyable\Keyable;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    use Keyable;

    /**
     * Attributes containing unique keys.
     *
     * @var array
     */
    public $keys = [
        'public_key' => 128,
        'private_key' => 128
    ];

    public function keyableStrategy(string $attribute, int $length) : string
    {
        $prefix = 'public-';

        if ($attribute == 'private_key') {
            $prefix = 'private-';
        }

        return $prefix . \Str::random($length);
    }
}
```

## Contributing

Contributions are welcomed! If you have any idea you'd like to implement, feel free to submit a pull request.

## Licence

MIT

Copyright © 2019 [Angle Software](https://angle.software)
