<?php

namespace App\Exceptions;

use App\Http\Response\BodyResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (env('APP_ENV') === 'prod') {
                Log::error($e->getMessage(), $e);
            }
        });

        $this->renderable(function (Throwable $e, $request) {
            $body = new BodyResponse();
            if ($request->is('api/*')) {
                $body->setResponseError($e->getMessage());
                return response()->json($body->getResponse(), $body->getResponseCode()->value);
            }
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $body = new BodyResponse();
        $body->setResponseAuthFailed();
        return $this->shouldReturnJson($request, $exception)
            ? response()->json($body->getResponse(), $body->getResponseCode()->value)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException  $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        $body = new BodyResponse();
        $body->setResponseValidationError($e->errors(), 'Data', $e->getMessage());
        return $this->shouldReturnJson($request, $e)
            ? response()->json($body->getResponse(), $body->getResponseCode()->value)
            : $this->invalid($request, $e);
    }

    /**
     * Convert a validation exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function invalid($request, ValidationException $exception)
    {
        // dd($exception);
        $input = $request->input();
        // dd($input);
        return redirect($exception->redirectTo ?? url()->previous())
            ->withInput([])
            ->withErrors($exception->errors(), $request->input('_error_bag', $exception->errorBag));
    }
}
