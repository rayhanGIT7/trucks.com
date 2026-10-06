<?php

namespace App\Core;

use Exception;

/**
 * Throw this to stop the request and show an error page, e.g.
 *   throw new HttpException(404, 'Booking not found');
 */
class HttpException extends Exception
{
    private int $statusCode;

    public function __construct(int $statusCode, string $message = '')
    {
        $this->statusCode = $statusCode;

        if ($message === '') {
            $message = self::defaultMessage($statusCode);
        }
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private static function defaultMessage(int $code): string
    {
        if ($code === 403) {
            return 'You are not allowed to access this page.';
        }
        if ($code === 404) {
            return 'Page not found.';
        }
        if ($code === 419) {
            return 'Your session has expired. Please go back and try again.';
        }
        return 'Something went wrong.';
    }
}
