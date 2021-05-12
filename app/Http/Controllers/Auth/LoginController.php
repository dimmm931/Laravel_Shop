<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//mine
 use Illuminate\Support\Facades\Session; //MINE to redirect to prev page after Login
 //use Illuminate\Support\Facades\URL;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home'; //MINE to redirect to prev page after Login
	
	

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
		//MINE to redirect to prev page after Login
		Session::put('backUrl', url()->previous()); //dd(session()->get('backUrl')); Here we save previous page to SESSION['backUrl']
		
    }
	
	
	

    //MINE added function to redirect to prev page after Login. Was not in original file
    public function redirectTo()
    {
	   //dd(session()->get('backUrl'));
       return session()->get('backUrl') ? session()->get('backUrl') :   $this->redirectTo; //if SESSION['backUrl'] is set, go there, else go "/home"
    }
	
	
	/**
	 * Method if u want to Login with username instead of email. Don't change the function name, regadless what u want to return, i.e "email, name, username" 
     * Get username property.
     *
     * @return string
     */
	 /*
    public function username()
    {
        return 'name'; //or return the DB  field which you want to use, i.e "email, name, username"
    }
	*/

}
