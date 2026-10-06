<?php

namespace App\Services;

use Exception;

/**
 * A business rule was broken (e.g. "truck already booked").
 * The message is safe to show to the user.
 */
class ServiceException extends Exception
{
}
