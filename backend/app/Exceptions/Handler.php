<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\UserCreationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register() : void 
    {
    
        // db and other error
        $this->renderable(function (UserCreationException $e, $request)
        {

            if ($request->expectsJson()){
                return response() -> json([
                    'message' => 'User creation failed',
                   'error' => $e -> getMessage() 
                ], 500);
            }
        } );

// validation error
         $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
        });

         $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found'
                ], 404);
            }
        });

}
}
