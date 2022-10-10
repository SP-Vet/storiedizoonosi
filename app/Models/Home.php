<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Home extends Model
{
    use HasFactory;
    //protected $table='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_zoonosi='meoh_zoonosi';

    //protected $primaryKey='zid';
   
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function getZoonosi($where=[],$order=[]){
        $queryBuilder=DB::table($this->table_zoonosi.' AS z')->select('zu.*','z.linktelegram')->leftJoin($this->table_zoonosilingue.' AS zu','z.zid','zu.zid');
        if(!empty($where))
            foreach ($where AS $key=>$wh)
                $queryBuilder->where($key,$wh);
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);

        return $queryBuilder->get()->toArray();
     }
    
   



}
