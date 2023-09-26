<?php

declare(strict_types=1);

namespace App\Enum;

use Datomatic\EnumHelper\EnumHelper;

enum AssessmentStatusEnum: string
{
    use EnumHelper;

    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
