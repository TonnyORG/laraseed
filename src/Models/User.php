<?php

namespace TonnyORG\LaraSeed\Models;

use TonnyORG\LaraSeed\Models\Base\Authenticatable;

abstract class User extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
