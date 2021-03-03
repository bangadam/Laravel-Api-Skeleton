<?php
namespace App\Helpers;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomException extends HttpException {
    
    public function __construct(int $statusCode, string $message = null, \Throwable $previous = null, ?int $code = 0, array $headers = [])
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

}
