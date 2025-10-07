<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\HasHateoasLinks;

abstract class Controller
{
    use HasHateoasLinks;

    /**
     * The number of items per page.
     */
    const int PER_PAGE = 10;

    /**
     * The default sorting direction.
     */
    const string DEFAULT_SORT_DIRECTION = 'asc';
}
