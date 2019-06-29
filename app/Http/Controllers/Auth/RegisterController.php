<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
       // $value = "5d096cdd2012702b040015f2";
       // Session::set('userId', $value);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'handle' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'handle' => $data['handle'],
            'cFromDate' => \Carbon\Carbon::createFromFormat('m-d-Y', "01-01-2010")->timestamp,
            'cToDate' => \Carbon\Carbon::createFromFormat('m-d-Y', date('m-d-Y'))->timestamp,
            'cGym' => true,
            'pFromDate' => \Carbon\Carbon::createFromFormat('m-d-Y', "01-01-2010")->timestamp,
            'pToDate'   => \Carbon\Carbon::createFromFormat('m-d-Y', date('m-d-Y'))->timestamp,
            'pGym'  => true,
            'pRatingFrom' => 1,
            'pRatingTo' => 5000,
            'sortByDate' => true,
            'order'=>true

        ]);
    }
}
