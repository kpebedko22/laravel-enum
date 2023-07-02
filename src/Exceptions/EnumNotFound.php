<?php

namespace Kpebedko22\Enum\Exceptions;

use RuntimeException;

class EnumNotFound extends RuntimeException
{
    protected $code = 404;
}
