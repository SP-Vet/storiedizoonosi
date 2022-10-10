<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Storiesubmit extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie_submit_utente';
    protected $primaryKey='ssid';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    

    public function getStoriaSubmitFromSID($sid){
        $queryBuilder=DB::table($this->table.' AS su')->select('su.*','u.name AS nome_cognome_utente')
                ->leftJoin($this->table_utenti.' AS u','u.id','su.uid')
                ->where('su.sid',$sid);
    
        return $queryBuilder->get();
    }
    
    public function getStorieSubmitNONgestite(){
        $queryBuilder=DB::table($this->table.' AS su')->select('su.*','u.name AS nome_cognome_utente','u.email','s.data_inserimento')
                ->leftJoin($this->table_storie.' AS s','s.sid','su.sid')
                ->leftJoin($this->table_utenti.' AS u','u.id','su.uid')
                ->where('s.stato',0);
    
        return $queryBuilder->get();
    }

}
