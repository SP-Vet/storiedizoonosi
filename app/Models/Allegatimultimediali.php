<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Allegatimultimediali extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_allegati_multimediali_storia';
    protected $primaryKey='amsid';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    

    public function getAllegatiMultimedialiFromStoria($sid,$where=[],$wherein=[]){
        $queryBuilder=DB::table($this->table.' AS ams')->select('ams.*')
                ->where('ams.sid',$sid)->where($where);
        if(count($wherein)>0){
            $queryBuilder->whereIn('ams.tipologia',$wherein);
        }
        
        return $queryBuilder->get();
    }
    
    
    public function getAllegatiMultimediali($where=[]){
        $queryBuilder=DB::table($this->table.' AS ams')->select('ams.*')->where($where);
        return $queryBuilder->get();
    }
    
    
    
    

}
