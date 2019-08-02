<?php


namespace Kagatan\UserOption\Traits;


use Illuminate\Database\Eloquent\Builder;

trait OptionStorage
{

    /**
     * Override setKeysForSaveQuery
     *
     * @param Builder $query
     * @return Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('key', '=', $this->getAttribute('key'))
            ->where('user_id', '=', $this->getAttribute('user_id'));

        return $query;
    }

    /**
     * Override getCastType
     *
     * @param string $key
     * @return mixed|string
     */
    protected function getCastType($key)
    {
        if ($key == 'value' && !empty($this->casts[$this->key])) {
            return $this->casts[$this->key];
        }

        return parent::getCastType($key);
    }

    /**
     * Determine if the given option value exists.
     *
     * @param $userID
     * @param $key
     * @return mixed
     */
    public function exists($userID, $key)
    {
        return self::where('key', $key)->where('user_id', $userID)->exists();
    }

    /**
     * Get the specified option value.
     *
     * @param $userID
     * @param $key
     * @param null $default
     * @return |null
     */
    public function get($userID, $key, $default = null)
    {
        $option = self::query()
            ->where('user_id', $userID)
            ->where('key', $key)
            ->first();

        if ($option) {
            return $option->value;
        }
        return $default;
    }

    /**
     * Get all options by condition
     *
     * @param $userID
     * @param bool $condition
     * @return \Illuminate\Support\Collection
     */
    public function getAll($userID, $condition = false)
    {
        if ($condition) {
            return self::query()
                ->where('user_id', $userID)
                ->where('key', 'LIKE', $condition)
                ->get()
                ->pluck('value', 'key');
        } else {
            return self::query()
                ->where('user_id', $userID)
                ->get()
                ->pluck('value', 'key');
        }
    }

    /**
     * Set a given option value.
     *
     * @param array|string $key
     * @param mixed $value
     * @return void
     */
    public function set($userID, $key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            self::updateOrCreate(['key' => $key, 'user_id' => $userID], ['value' => $value]);
        }
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param string $key
     * @return bool
     */
    public function remove($userID, $key)
    {
        return (bool)self::query()
            ->where('user_id', $userID)
            ->where('key', $key)
            ->delete();
    }
}
