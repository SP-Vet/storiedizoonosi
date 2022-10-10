<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Collaboratori extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie_collaboratori';
    protected $primaryKey='collid';
    protected $table_storiecollaboratori='meoh_storiecollaboratori';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    

    public function getAll($order=0){
        $queryBuilder=DB::table($this->table.' AS coll')->select('coll.*');
        
        if($order==1){
            $queryBuilder->orderBy('coll.cognome','ASC')->orderBy('coll.nome','ASC');
        }elseif($order==2){
            $queryBuilder->orderBy('coll.cognome','DESC')->orderBy('coll.nome','DESC');
        }
    
        return $queryBuilder->get();
    }
    
    public function deleteStorieCollaboratoriAss($sid){
        $queryBuilder=DB::table($this->table_storiecollaboratori)->where('sid', $sid)->delete();
        return true; 
    }
    
    public function addCollaboratoriStoria($sid,$collaboratori,$ruoli){
        foreach ($collaboratori AS $tc=>$collid){
            DB::table($this->table_storiecollaboratori)->insert( ['collid' =>$collid, 'sid'=>$sid ,'rid'=>$ruoli[$tc]]);
        }
        return true; 
    }
    

}
