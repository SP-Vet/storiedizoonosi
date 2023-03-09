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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    /**
    * Login page
    *
    * @return view()
    */ 
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
        $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.config('app.MTCAPTCHAprivate').'&token='.$request->input('mtcaptcha-verifiedtoken'));
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
                $user = auth()->guard('admin')->user();
                $to = Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:i:s'));
                $from = Carbon::createFromFormat('Y-m-d H:s:i', $user->password_changed_at);
                $diff_in_days = $to->diffInDays($from);
               
                if($diff_in_days>0 && $diff_in_days>config('auth.password_expires_days')){
                    //auth()->logout();
                    auth()->guard('admin')->logout();
                    \Session::flush();
                    $request->session()->put('password_expired_id',$user->id);
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] postLogin', $this->mod_log->getParamFrontoffice('password scaduta'));
                    return redirect(route('showPasswordExpiration'))->with('error', "La password è scaduta, inserisci una nuova password.");
                }

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