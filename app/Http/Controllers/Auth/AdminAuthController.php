<?php
namespace App\Http\Controllers\Auth;

use Validator;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use Illuminate\Support\Facades\Http;

class AdminAuthController extends Controller
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
    protected $redirectTo = '/admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->mod_log=new LogPersonal($request);
    }

    public function getLogin()
    {
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] login', $this->mod_log->getParamFrontoffice());
        return view('admin.login');
    }

    /**
     * Show the application loginprocess.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] postLogin', $this->mod_log->getParamFrontoffice());
        $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.env('MTCAPTCHA_PRIVATE').'&token='.$request->input('mtcaptcha-verifiedtoken'));
        $dataRresponse=$responseMTCaptcha->json();
        if($dataRresponse['success']){
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
            {
                //$user = auth()->guard('admin')->user();
                //\Session::put('success','You are Login successfully!!');
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] postLogin', $this->mod_log->getParamFrontoffice('utente autenticato'));
                return redirect()->route('dashboard');
            } else {
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] postLogin', $this->mod_log->getParamFrontoffice('post errato'));
                return back()->with('error','Email o password errati');
            }
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] postLogin', $this->mod_log->getParamFrontoffice('captcha non validato'));
            return back()->with('error','Captcha non validato correttamente');
        }
        
        

    }

    /**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] logout', $this->mod_log->getParamFrontoffice());
        auth()->guard('admin')->logout();
        \Session::flush();
        return redirect()->route('adminLogin');
    }
}