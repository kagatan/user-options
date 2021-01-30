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
            ->where($this->getOwnerKey(), '=', $this->getAttribute($this->getOwnerKey()));

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
    public static function exists($userID, $key)
    {
        $model = new self();

        return $model::where('key', $key)->where($model->getOwnerKey(), $userID)->exists();
    }

    /**
     * Get the specified option value.
     *
     * @param $userID
     * @param $key
     * @param null $default
     * @return |null
     */
    public static function get($userID, $key, $default = null)
    {
        $model = new self();

        $option = $model::query()
            ->where($model->getOwnerKey(), $userID)
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
    public static function getAll($userID, $condition = false)
    {
        $model = new self();

        if ($condition) {
            return $model::query()
                ->where($model->getOwnerKey(), $userID)
                ->where('key', 'LIKE', $condition)
                ->get()
                ->pluck('value', 'key');
        } else {
            return $model::query()
                ->where($model->getOwnerKey(), $userID)
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
    public static function set($userID, $key, $value = null)
    {
        $model = new self();

        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            $model::updateOrCreate(['key' => $key, $model->getOwnerKey() => $userID], ['value' => $value]);
        }
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param string $key
     * @return bool
     */
    public static function remove($userID, $key)
    {
        $model = new self();

        return (bool)$model::query()
            ->where($model->getOwnerKey(), $userID)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Get ownerKey
     * 
     * @return string
     */
    private function getOwnerKey()
    {
        if (isset($this->ownerKey)) {
            return $this->ownerKey;
        } else {
            return 'user_id';
        }
    }
}
