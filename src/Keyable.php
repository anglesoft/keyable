<?php

namespace Angle\Keyable;

trait Keyable
{
    public static function bootKeyable()
    {
        static::creating(function ($model) {
            $model->assignUniqueKeys();
        });
    }

    public function useKeyableStrategy($length = 128) : string
    {
        return \Str::random($length);
    }

    public function generateUniqueKey($attribute, $length)
    {
        $table = $this->getTable();

        do {
            $key = $this->useKeyableStrategy($length);
        } while (\DB::table($table)->where($attribute, $key)->first());

        return $key;
    }

    public function assignUniqueKeys() : void
    {
        if ( ! property_exists($this, 'keys')) {
            return;
        }

        foreach ($this->keys as $attribute => $length) {
            if ($model->{$attribute} != null) {
                continue;
            }

            $model->{$attribute} = $this->generateUniqueKey($attribute, $length);
        }
    }

    static function findByKey(string $key)
    {
        return static::where('key', $key)->first();
    }

    static function findByKeyOrFail(string $key, $code = 404)
    {
        $model = static::findByKey($key);

        if ( ! $model) {
            return abort($code);
        }

        return $model;
    }
}
