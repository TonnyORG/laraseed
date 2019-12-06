<?php

namespace TonnyORG\LaraSeed\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;

abstract class SoftModel extends Model
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
