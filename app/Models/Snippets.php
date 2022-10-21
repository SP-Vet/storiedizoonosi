<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Snippets extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    
    //do not save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie_snippets';
    protected $primaryKey='snid';
    protected $table_snippetslingue='meoh_storie_snippetslingue_ass';
    
   
    protected $lang=1;
    
    
    public function setSnippetslanguageAss($snid,$dati){
        if (DB::table($this->table_snippetslingue)->where('lid', 1)->where('snid',$snid)->exists()) {
            //update
            DB::table($this->table_snippetslingue)
                ->where('snid', $snid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['snid']=$snid;
            $dati['lid']=$this->lang;
            DB::table($this->table_snippetslingue)->insert($dati);
        }
    }

}
