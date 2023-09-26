<?php

declare(strict_types=1);

namespace App\Enum;

use Datomatic\EnumHelper\EnumHelper;

enum JobStatusEnum: string
{
    use EnumHelper;

    case NEW = 'new';
    case ASSIGNED = 'assigned';
}
