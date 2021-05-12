<?php
 
namespace App\Exceptions;
 
use Exception;
 
class myException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
		//dont have to implement, your custom exception is saved to log anyway
    }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view(
                'errors.custom',
                array(
                    'exception' => $this
                )
        );
    }
}