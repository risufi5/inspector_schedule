<?php

declare(strict_types=1);

namespace App\Enum;

use Datomatic\EnumHelper\EnumHelper;

enum InspectorLocationEnum: string
{
    use EnumHelper;

    case MADRID = 'madrid';
    case MEXICO_CITY = 'mexico_city';
    case UK = 'uk';
}
