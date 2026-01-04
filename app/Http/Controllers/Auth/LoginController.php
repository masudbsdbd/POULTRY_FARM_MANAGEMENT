<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Activitylog;
use App\Models\User;
use App\Models\DemoUser;
use Hash;
use Illuminate\Support\Facades\Http;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('auth.login', compact('pageTitle'));
    }
    public function showRegisterForm()
    {
        $pageTitle = "Admin Register";
        return view('auth.register', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('web');
    }

    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        $request->session()->regenerateToken();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $activitylog = new Activitylog();
            $activitylog->user_id = auth()->user()->id ?? null;
            $activitylog->action_type = 'LOGIN';
            $activitylog->table_name = 'users';
            $activitylog->record_id = auth()->user()->id ?? null;
            $activitylog->ip_address = $request->ip();
            $activitylog->user_agent = $request->userAgent();
            $activitylog->remarks = 'User logged in successfully';
            $activitylog->timestamp = now();
            $activitylog->save();
            session()->put('ip_address', $request->ip());
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {
        $this->guard('web')->logout();
        $request->session()->invalidate();
        session()->forget('ip_address');
        return $this->loggedOut($request) ?: redirect($this->redirectTo);
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required',
            'mobile' => 'required'
        ]);

        $input = $request->all();
        $rawPassword = $input['password']; // Keep raw password for SMS
        $input['password'] = Hash::make($rawPassword);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $demoUser = new DemoUser();
        $demoUser->user_id = $user->id;
        $demoUser->name = $request->name;
        $demoUser->lastNmae = $request->lastNmae;
        $demoUser->email = $request->email;
        $demoUser->mobile = $request->mobile;
        $demoUser->address = $request->address; 
        $demoUser->save();

        $smsMessage = "Dear {$request->name},\nYour account has been created successfully.\nLogin Email: {$request->email}\nPassword: {$rawPassword}\n\n- BSDBD Accounting Software";
        $this->sendSms($request->mobile, $smsMessage);

        $message = 'Your Account Created Successfully';
        $notify[] = ['success', $message];
        return to_route('login.form')->withNotify($notify);
    }

    private function sendSms($toPhone, $message)
    {
        $apiUrl = 'http://sms.robotispsoft.net/api/smsapi';
        $apiKey = 'G41Wl5mOATUqcu2PXZFE';
        $senderId = 'BSDBD';

        try {
            $response = Http::get($apiUrl, [
                'api_key'   => $apiKey,
                'senderid'  => $senderId,
                'number'    => $toPhone,
                'message'   => $message
            ]);

            \Log::info('SMS sent to ' . $toPhone . ': ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }
    


}
