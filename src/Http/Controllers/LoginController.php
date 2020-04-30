<?php

namespace Techneved\Admin\Login\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Techneved\Admin\Login\Http\Requests\Login;

class LoginController extends Controller
{
    const GUARD = 'admin-logins';

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:'.static::GUARD)->except('login');
    }
     /**
     *  Get a JWT token through given credentials
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     **/

    public function login(Login $request)
    {

     return $this->authentication();

    }

    /** Credential Authentication
    *
    * @param \Illuminate\Http\Request
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * */

   private function authentication()
   {

       if ($token = $this->attempt()) {

           return $this->successResponse($this->tokenResponse($token), Response::HTTP_OK);
       }

       return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
   }

   public function attempt()
   {
       return $this->guard()->attempt([
           'admin_id' => request()->input('admin_id'),
           'password' => request()->input('password'),
           'status'   => 1
           ]);
   }

    /** Token Response
    *
    * @param string $token
    *
    * @return array
    */
   private function tokenResponse($token)
   {
       return [
           'access_token' => $token,
           'token_type' => 'Bearer',
           'expires_in' => $this->guard()->factory()->getTTL() * 60,
           'admin' => $this->guard()->user()
       ];
   }

   /**
    * Success Response
    *
    * @param array $message
    *
    * @param string $statusCode
    *
    * @return \Illuminate\Http\JsonResponse
    *
    */

   private function successResponse($message, $statusCode)
   {
       return response()->json([

           'data' => $message
       ], $statusCode);
   }

    /** Error Response
    *
    * @param array $message
    *
    * @param string $statusCode
    *
    * @return \Illuminate\Http\JsonResponse
    *
    */
   private function errorResponse($message, $statusCode)
   {
       return response()->json([
           'errors' => [

               'error' => $message
           ]
       ], $statusCode);
   }

    /**
    * Get the guard to be used during authentication.
    *
    * @return \Illuminate\Contracts\Auth\Guard
    */
   private function guard()
   {
       return Auth::guard(static::GUARD);
   }


    /**
    * Logout the admin authentication
    *
    * @return \Illuminate\Http\JsonResponse;
    */

    public function logout()
    {
        $this->guard()->logout();
        return $this->successResponse([ 'message' => 'Successfully logout'], Response::HTTP_OK);
    }
}