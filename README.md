# 🗝 Keyable Models in Laravel

The Keyable trait will automatically generate random keys for the model on the creating event. 

```php
use Angle\Keyable;

$keys = ['key' => 128];

protected function useKeyableStrategy($length) : string
{
  return ...
}

```
