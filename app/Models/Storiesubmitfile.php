<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Storiesubmitfile extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //non salvare created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie_submit_files';
    protected $primaryKey='ssfileid';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    
    public function getFilesFromSSID($ssid){
        $queryBuilder=DB::table($this->table.' AS sf')->select('sf.*')
                ->where('sf.ssid',$ssid);
    
        return $queryBuilder->get();
    }
    


}
