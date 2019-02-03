<?php

namespace Angle\Keyable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Keyable
{
    /**
     * Boots the Keyable trait.
     */
    public static function bootKeyable() : void
    {
        static::creating(function ($model) {
            $model->assignUniqueKeys();
        });
    }

    /**
     * Assign keys to the current model instance.
     */
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

    /**
     * Generates a random string.
     *
     * @param  string   $attribute
     * @param  integer  $length
     * @return string
     */
    public function keyableStrategy(string $attribute, int $length = 32) : string
    {
        return Str::random($length);
    }

    /**
     * Generates a unique key against the current table.
     *
     * @param  string   $attribute
     * @param  int      $length
     * @return string
     */
    public function generateUniqueKey(string $attribute, int $length) : string
    {
        $table = $this->getTable();

        do {
            $key = $this->keyableStrategy($attribute, $length);
        } while (DB::table($table)->where($attribute, $key)->exists());

        return $key;
    }

    /**
     * Find a model by its key.
     *
     * @param  string $attribute
     * @param  string $value
     * @return Model|null
     */
    static function findByKey(string $attribute, string $value) : ?Model
    {
        return static::where($attribute, $value)->first();
    }

    /**
     * Find a model by its key or throw an exception.
     *
     * @param  string   $key
     * @param  integer  $code
     * @return Model|void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    static function findByKeyOrFail(string $attribute, string $value, int $code = 404)
    {
        $model = static::findByKey($attribute, $value);

        if ( ! $model) {
            return abort($code);
        }

        return $model;
    }
}
