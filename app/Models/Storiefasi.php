<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Storiefasi extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storiefasi';
    protected $primaryKey='sfid';
    protected $table_storiefasilingue='meoh_storiefasilingue_ass';
    
   
    protected $table_storie_approfondimenti='meoh_storie_approfondimenti';
  
    protected $table_storie_snippets='meoh_storie_snippets';
    protected $table_storie_snippetslingue='meoh_storie_snippetslingue_ass';
    
    
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';

    protected $lang=1;
    
    
    public function setStoriafaselinguaAss($sfid,$dati){
        if (DB::table($this->table_storiefasilingue)->where('lid', 1)->where('sfid',$sfid)->exists()) {
            //update
            DB::table($this->table_storiefasilingue)
                ->where('sfid', $sfid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['sfid']=$sfid;
            $dati['lid']=$this->lang;
            DB::table($this->table_storiefasilingue)->insert($dati);
        }
    }

}
