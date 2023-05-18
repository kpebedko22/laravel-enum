<?php

namespace Kpebedko22\LaravelEnum\Exceptions;

use RuntimeException;

class EnumNotFound extends RuntimeException
{
    protected $code = 404;
}
