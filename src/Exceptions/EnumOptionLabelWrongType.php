<?php

namespace Kpebedko22\Enum\Exceptions;

use RuntimeException;

final class EnumOptionLabelWrongType extends RuntimeException
{
    protected $code = 500;
}
