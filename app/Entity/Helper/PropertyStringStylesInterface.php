<?php

namespace App\Entity\Helper;

interface PropertyStringStylesInterface
{
    const CAMEL_CASE = 1;
    const SNAKE_CASE = 2;
    const DEFAULT = PropertyStringStylesInterface::CAMEL_CASE;
}
