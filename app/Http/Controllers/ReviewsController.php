<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Reviews;
use App\Models\Admin;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class ReviewsController extends Controller
{
    public $mod_integrations;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_review = new Reviews();
        $this->mod_log=new LogPersonal($request);
    }
        
    public function eliminareview(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] eliminareview', $this->mod_log->getParamFrontoffice());
        if(($this->request->srid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->srid) )|| $this->request->srid=='' ){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] eliminareview', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con la review selezionata.']);
        }
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] eliminareview', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return response()->json(['error'=>true,'message'=>'Utente non loggato o non autorizzato.']);
        }
        DB::beginTransaction();
        try{
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] eliminareview', $this->mod_log->getParamFrontoffice());
            $review= Reviews::find($this->request->srid);
            $pathpdf = storage_path('app/public/reviews/'.$review->zid.'/'. $review->file_memorizzato);
            if(file_exists($pathpdf))
                unlink($pathpdf);
            //eliminazione da DB
            Reviews::destroy($this->request->srid);           
            DB::commit();
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] eliminareview', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'Review eliminata definitivamente. La pagina verrà ricaricata.']);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] eliminareview', $this->mod_log->getParamFrontoffice($e->getMessage()));
            return response()->json(['error'=>true,'message'=>$e->getMessage()]);
        }
    }
    
    
    public function aggiungireview(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] aggiungireview', $this->mod_log->getParamFrontoffice());
        //echo'<pre>';print_r($this->request->file('review'));exit;
        if($this->request->file()){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] aggiungireview', $this->mod_log->getParamFrontoffice('file presente'));
            if(($this->request->zid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->zid) ) || $this->request->zid=='' || $this->request->zid=='0' ){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] aggiungireview', $this->mod_log->getParamFrontoffice('parametri non validi'));
                return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il caricamento della review selezionata.']);
            }
            
            $allowed_text = array('pdf');
            $originName = $this->request->file('review')->getClientOriginalName();
            $mimeType=$this->request->file('review')->getMimeType();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $this->request->file('review')->getClientOriginalExtension();
            $size=$this->request->file('review')->getSize();
            if($size>10485760)return response()->json(['error'=>true,'message'=>'Il file selezionato è troppo grande.']);
            if(!in_array($extension, $allowed_text))return response()->json(['error'=>true,'message'=>'Il file selezionato non è in formato PDF.']);
            
            $pathst = storage_path('app/public/reviews');
            if(!File::isDirectory($pathst)){
                File::makeDirectory($pathst, 0777, true, true);
            }
            $pathst = storage_path('app/public/reviews/'.$this->request->zid);
            if(!File::isDirectory($pathst)){
                File::makeDirectory($pathst, 0777, true, true);
            }

            $fileName = 'REVIEW_'.time().'.'.$extension;            
            $this->request->file('review')->move($pathst, $fileName);
            
            $NEWfileName =str_replace(' ', '_', $originName);
            $review=new Reviews();
            
            $review->titolo_visualizzato=$NEWfileName;
            $review->file_memorizzato=$fileName;
            $review->tipo_file=$mimeType;
            $review->zid=$this->request->zid;
            $review->save();
            
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] aggiungireview', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'Review inserita con successo. La pagina verrà ricaricata.']);
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] aggiungireview', $this->mod_log->getParamFrontoffice('file non esistente'));
            return response()->json(['error'=>true,'message'=>'File non esistente.']);
        }
    }
}
