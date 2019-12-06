<?php

namespace TonnyORG\LaraSeed\Models;

use TonnyORG\LaraSeed\Models\Base\Authenticatable;

abstract class Administrator extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'administrators';
}
