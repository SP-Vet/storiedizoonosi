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
use App\Models\ConfirmAdmin;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
     * Show the application login process.
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
                $user = auth()->guard('admin')->user();
                $to = Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:i:s'));
                $from = Carbon::createFromFormat('Y-m-d H:s:i', $user->password_changed_at);
                $diff_in_days = $to->diffInDays($from);
               
                if(($diff_in_days>0 && $diff_in_days>config('auth.password_expires_days')) || $user->reset_password==1){
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
     * Send email to an admin for recovery a password.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function passwordrecovery(Request $request){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] passwordrecovery', $this->mod_log->getParamFrontoffice('recupero password'));
        $request_post=json_decode(json_encode($request->all()));
        if(!isset($request_post->email) || $request_post->email=='' || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",strtolower(trim($request_post->email)))){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] passwordrecovery', $this->mod_log->getParamFrontoffice('indirizzo email non valido'));
            return redirect(route('adminLogin'));
        }
        $mod_admin=new Admin();
        
        $admin=$mod_admin->getAll(['a.email'=>$request_post->email])->first();
        if(!isset($admin->email)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] passwordrecovery', $this->mod_log->getParamFrontoffice('indirizzo email non esistente'));
            return redirect(route('adminLogin'));
        }

        $amministratore=Admin::find($admin->id);
        $amministratore->reset_password=1;
        $amministratore->save();

        $confirm=new ConfirmAdmin();
        $linkreset=$confirm->getEmailResetLink($admin->id,$admin->email);
        $linkreset_clean= str_replace('//', 'https://', $confirm->getEmailResetLink($admin->id,$admin->email));

        //sending email with reset password link
        $datimail=array('linkreset' => $linkreset,'linkreset_clean'=>$linkreset_clean,'email'=>$admin->email,'nome_sito'=>config('app.name'));
        $this->email_admin=$admin->email_real;
        Mail::send('emails.resetpasswordadmin', $datimail, function($message){
            $message->subject('Reimposta password');
            $message->to($this->email_admin);
        });
        unset($this->email_admin);
        $request->session()->flash('messageinfo', '<h3>Link reset password inviato con successo!</h3><h4>Controlla la casella di posta legata all\'account per cui hai fatto richiesta di recupero password.</h4>');   
        return redirect(route('adminLogin'));
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