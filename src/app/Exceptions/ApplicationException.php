<?php

namespace App\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    protected $statusCode;

    /**
     * Create a new ApplicationException instance.
     *
     * @param string $message The error message
     * @param int $statusCode HTTP status code (default 500)
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(string $message, int $statusCode = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render the exception for HTTP responses.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => $this->getMessage(),
            ], $this->statusCode);
        }

        return back()->withNotify(['error', translate($this->getMessage())]);
    }
}