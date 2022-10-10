<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DB;

class Conferma extends Model
{
    protected $kpu=[];
    protected $kpr=[];
    use HasFactory;
    protected $table='users';
    protected $table_codfis='users_codfis';
    protected $table_tmpverified='users_tmp_verifiedmail';
    protected $primaryKey='id';
  
    public function __construct()
    {
        $this->kpu=env('PUBLIC_KEY_STRING');
        $this->kpr=env('PRIVATE_KEY_STRING');
        
         /*
         * STRUTTURA LINK CONFERMA EMAIL
         *  $a=KPU
         *  $b=id utente
         *  $c=sha1($a.KPR.$b).md5(email utente)
         * 
         */
    }
    
    public function getKPU(){
        return $this->kpu;
    }
    public function getKPR(){
        return $this->kpr;
    }
    
    public function getLinkConfermaEmail($id,$email){
        //creazione Link conferma
        $a=$this->kpu;
        $b=$id;
        $c=sha1($a.$this->kpr.$b).md5($email);
        $link='//'.$_SERVER['HTTP_HOST'].'/confermaemail/'.$c.'/'.$b.'/'.$a;
        return $link;
    }
    
    public function checkEmailConferma($first,$second,$third){
        $idcheck=$second;
        $kpucheck=$third;
        $stringcheck=$first;
        $emailmd5check=substr($stringcheck, -32);
        $sha1check=substr($stringcheck,0, 40);
        
        //kpu modificata
        if($kpucheck!==$this->kpu)return false;
        
        //id NON numerico
        if(!preg_match('/^[1-9][0-9]*$/', $idcheck))return false;
        
        //estrazione utente da id
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*','ucf.codfis','uvm.startverified')
            ->leftJoin($this->table_codfis.' AS ucf','u.id','ucf.uid')
            ->leftJoin($this->table_tmpverified.' AS uvm','u.id','uvm.uid')
            ->where('u.id',$idcheck);
        $user=$queryBuilder->first();
        
        //email già verificata
        if($user->email_verified_at!='')return false;
        
        //data di richiesta conferma > 48 ore
        if($user->startverified=='')return false;
        $now = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $datarichiesta = Carbon::createFromFormat('Y-m-d H:i:s', $user->startverified);
        if($datarichiesta->diffInHours($now)>48)return false;
        
        //email nel link modificata (manomissione MD5 email)
        if($emailmd5check!==md5($user->email))return false;
        
        //sha1 con kpu, kpr ed id modificato
        if($sha1check!==sha1($this->kpu.$this->kpr.$user->id))return false;
        
        
        return true;
        
    }

    
     
     
    
   



}
