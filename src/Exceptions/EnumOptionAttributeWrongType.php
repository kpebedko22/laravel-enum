<?php

namespace Kpebedko22\Enum\Exceptions;

use RuntimeException;

final class EnumOptionAttributeWrongType extends RuntimeException
{
    protected $code = 500;
}
