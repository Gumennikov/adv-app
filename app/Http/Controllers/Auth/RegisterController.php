<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\Auth\VerifyMail;
use App\Providers\RouteServiceProvider;
use App\Entity\User;
use App\UseCases\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    private $service;
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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @param RegisterService $service
     */
    public function __construct(RegisterService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
    }

    /**
     * Show the application registration form.
     *
     * @return View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse|JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $this->service->register($request);

        return redirect()->route('login')
            ->with('success', 'Please, check your email and complete the registration.');
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
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        return redirect()->route('login')
            ->with('success', 'Please, check your email and complete the registration.');
    }

    /**
     * Verifies user who comes from the link in registration email.
     *
     * @param $token
     * @return RedirectResponse
     */
    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')->with('error', 'Sorry, your link cannot be identified.');
        }

        try {
            $user->verify();
            return redirect()->route('login')->with('success', 'Your email is verified. You can login now.');
        } catch (\DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
