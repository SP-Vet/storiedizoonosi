<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Daticontesto extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
 
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_datibase';
    protected $primaryKey='dbid';
    protected $table_quesiti='meoh_quesitichiavelingue_ass';
    protected $table_storie='meoh_storie';
   
    protected $table_utenti='users';
    protected $lang=1;
    
    
    public function getDaticontestoFromStoria($sid){
        $queryBuilder=DB::table($this->table.' AS db')->select('db.dbid','db.ordine','qc.*')
            ->leftJoin($this->table_quesiti.' AS qc','qc.dbid','db.dbid')
            ->where('qc.lid',$this->lang)
            ->where('db.sid',$sid)->orderBy('db.ordine','ASC');
    
        return $queryBuilder->get();
    }
    
    public function setDaticontestolinguaAss($dbid,$dati){
        if (DB::table($this->table_quesiti)->where('lid', 1)->where('dbid',$dbid)->exists()) {
            //update
            DB::table($this->table_quesiti)
                ->where('dbid', $dbid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['dbid']=$dbid;
            $dati['lid']=$this->lang;
            DB::table($this->table_quesiti)->insert($dati);
        }
    }
}
