<?php

namespace TonnyORG\LaraSeed\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

abstract class SoftUser extends User
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];
}
