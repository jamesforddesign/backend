<?php

namespace Nodes\Backend\Models\FailedJob;

use Weatherbys\Database\Eloquent\Model;
use Weatherbys\Database\Support\Traits\Date;

/**
 * Class FailedJob.
 */
class FailedJob extends Model
{
    use Date;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['failed_at'];
}
