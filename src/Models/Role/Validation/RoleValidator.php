<?php

namespace Nodes\Backend\Models\Role\Validation;

use Weatherbys\Validation\AbstractValidator;

/**
 * Class RoleValidator.
 */
class RoleValidator extends AbstractValidator
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'create' => [
            'slug' => ['required', 'unique:backend_roles,slug,{:id}'],
            'title' => ['required'],
        ],
    ];
}
