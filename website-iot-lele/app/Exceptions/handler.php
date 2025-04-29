<?php 

use Illuminate\Auth\AuthenticationException;

class handler extends Exception
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
        
        return redirect()->guest('/auth-login');
    }
}