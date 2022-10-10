<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Ruoli extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_ruoli';
    protected $primaryKey='rid';
    protected $table_ruolilingue='meoh_ruolilingue_ass';
    protected $table_storie_collaboratori='meoh_storie_collaboratori';
    protected $table_storiecollaboratori='meoh_storiecollaboratori';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    

    public function getAll($order=0){
        $queryBuilder=DB::table($this->table.' AS r')->select('rl.*','r.ordine_ruolo')
                ->leftJoin($this->table_ruolilingue.' AS rl','rl.rid','r.rid')
                ->where('rl.lid',$this->lang);
        
        if($order==1){
            $queryBuilder->orderBy('rl.nomeruolo','ASC');
        }elseif($order==2){
            $queryBuilder->orderBy('rl.nomeruolo','DESC');
        }
    
        return $queryBuilder->get();
    }

}
