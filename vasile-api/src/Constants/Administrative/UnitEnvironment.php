<?php

namespace App\Constants\Administrative;

use App\Constants\AbstractEnum;
use PhpParser\Builder\Property;

abstract class UnitEnvironment extends AbstractEnum
{
    const NONE = 0;
    const URBAN = 1;
    const RURAL = 3;
}
