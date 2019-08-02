<?php


namespace Kagatan\UserOption;

use Illuminate\Database\Eloquent\Model;
use Kagatan\UserOption\Traits\OptionStorage;


class UserOption extends Model
{
    use OptionStorage;

    protected $table = 'test_options';

    public $incrementing = false;

    protected $primaryKey = ['user_id', 'key'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var [type]
     */
    protected $fillable = [
        'user_id',
        'key',
        'value'
    ];

    /**
     * Attributes that need to be converted to a native type.
     *
     * @var array
     */
    protected $casts = [
        "value" => 'string', // do not delete, add your own below...
    ];
}