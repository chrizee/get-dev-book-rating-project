<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson() || $request->expectsJson()) {
            // Define the response
            $response = [
                'errors' => 'Sorry, something went wrong.'
            ];

            $response['message'] = '';
            // If the app is in debug mode
            if (app()->environment('local', 'testing')) {                
                // Add the exception class name, message and stack trace to response
                $response['exception'] = get_class($exception);
                $response['message'] = $exception->getMessage();
                $response['trace'] = $exception->getTrace();
            }

            // Default response of 400
            $status = 400;
            if(method_exists($exception, 'getStatusCode')) {
                $status = $exception->getStatusCode();
            }
            // Return a JSON response with the response array and status code
            return response()->json(array('error' => $response['errors'], 'message' => $response['message'], 'status' => $status), $status);
        }
        return parent::render($request, $exception);
    }
}
