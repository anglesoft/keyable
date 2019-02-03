<?php

namespace Angle\Keyable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Keyable
{
    public static function bootKeyable()
    {
        static::creating(function ($model) {
            $model->assignUniqueKeys();
        });
    }

    public function assignUniqueKeys() : void
    {
        if ( ! property_exists($this, 'keys')) {
            return;
        }

        foreach ($this->keys as $attribute => $length) {
            if ($this->{$attribute} != null) {
                continue;
            }

            $this->{$attribute} = $this->generateUniqueKey($attribute, $length);
        }
    }

    public function keyableStrategy(string $attribute, int $length = 32) : string
    {
        return Str::random($length);
    }

    public function generateUniqueKey(string $attribute, int $length)
    {
        $table = $this->getTable();

        do {
            $key = $this->keyableStrategy($attribute, $length);
        } while (DB::table($table)->where($attribute, $key)->first());

        return $key;
    }

    static function findByKey(string $key) : ?Model
    {
        return static::where('key', $key)->first();
    }

    static function findByKeyOrFail(string $key, $code = 404) : ?Model
    {
        $model = static::findByKey($key);

        if ( ! $model) {
            return abort($code);
        }

        return $model;
    }
}
