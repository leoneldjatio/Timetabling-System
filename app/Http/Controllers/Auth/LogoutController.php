<?php

namespace App\Http\Controllers\Auth;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * Date: 17/01/17
 * Time: 12:07 AM
 *
 */

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{

    /*
   |--------------------------------------------------------------------------
   | Logout Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles login out of users from the application and
   | redirecting them to the login screen. The controller uses a trait
   | to conveniently provide its functionality to the applications.
   |
   */

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');

    }
}
