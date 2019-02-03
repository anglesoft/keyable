# 🗝 Keyable Models in Laravel

## Introduction

Many web applications require unique references to their data. Sometimes, increments and uuids are not enough for specific use-cases.

## Requirements

- PHP >= 7.1
- Laravel >= 5.7

## Installation

```
composer require angle/keyable
```

## Using the Keyable Trait

Unique keys are automatically generated upon the ```creating``` Eloquent Model event, whenever the ```Angle\Keyable\Keyable``` trait is utilized.

```php
<?php

namespace App;

use Angle\Keyable\Keyable;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use Keyable;

    /**
     * Attributes that hold unique keys.
     *
     * @var array
     */
    public $keys = ['fingerprint' => 128];
}
```

## Custom Strategy

TODO

```php

protected function useKeyableStrategy($attribute, $length) : string
{
  return ...
}
```
