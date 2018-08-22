<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ModelNotFoundException)
            {
                $modelo = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado",404);

            }
        if ($exception instanceof AuthenticationException)
            {
            return $this->unauthenticated($request, $exception);
            }
        if ($exception instanceof AuthorizationException)
            {
            return $this->errorResponse('No posee permisos para ejecutar esta accion',403);
            }
        if ($exception instanceof NotFoundHttpException)
            {
            return $this->errorResponse('No se encontro la URL especificada',404);
            }
         if ($exception instanceof MethodNotAllowedHttpException)
            {
            return $this->errorResponse('El metodo especificado en la peticion no es valido',405);
            }
        if ($exception instanceof HttpException)
            {
            return $this->errorResponse($exception->getmessage(),$exception->getStatusCode());
            }
        if ($exception instanceof QueryException)
            {
                $codigo = $exception->errorInfo[1];
                if ($codigo == 1451)
                    {
                    return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro',409);
                    }
            }
        if (config('app.debug'))
            {
                return parent::render($request, $exception); 
            }
        if ($exception instanceof TokenMismatchException){
          return redirect()->back()->withInput($request->input());  
        }
        return $this->errorResponse('Falla inesperada. Intente luego',500);       
    }


    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
        protected function unauthenticated($request, AuthenticationException $exception)
        {
            if($this->isfrontend($request))
            {
                return redirect()->guest('login');
            }
            if ($request->expectsJson())
            {
                return $this->errorResponse('No autenticado.', 401);
            }
            
            //return redirect()->guest(route('login'));
        //;
        }
        protected function convertValidationExceptionToResponse(ValidationException $e, $request)
         {
            $errors = $e->validator->errors()->getmessages();
             if ($this->isfrontend($request))
            { 
                return $request->ajax() ? response()->json($errors,422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
            }
           // return redirect()->guest(route('login'));
            return $this->errorResponse($errors,422);
         
        }
    private function isfrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
    
}

