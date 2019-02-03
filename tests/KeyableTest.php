<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Angle\Keyable\Keyable;

class User extends Model {
    use Keyable;

    protected $keys = [
        'foo' => 32,
        'bar' => 64,
        'baz' => 128
    ];

    public function generateUniqueKey(string $attribute, int $length)
    {
        do {
            $key = $this->keyableStrategy($attribute, $length);
        } while ( ! isset($key));

        return $key;
    }
}

class UserWithStrategy extends User
{
    public function keyableStrategy(string $attribute, int $length = 32) : string
    {
        return uniqid('keyable-');
    }
}

final class KeyableTest extends TestCase
{
    public function testModelCreatingEvent()
    {
        $user = new User;
        $user->setTable('users');
        $user->assignUniqueKeys();

        $this->assertTrue(strlen($user->foo) == 32);
        $this->assertTrue(strlen($user->bar) == 64);
        $this->assertTrue(strlen($user->baz) == 128);
    }

    public function testCustomStrategy()
    {
        $user = new UserWithStrategy;
        $user->setTable('users');
        $user->assignUniqueKeys();

        $this->assertTrue(strlen($user->foo) == 21);
        $this->assertTrue(strlen($user->bar) == 21);
        $this->assertTrue(strlen($user->baz) == 21);
    }
}
