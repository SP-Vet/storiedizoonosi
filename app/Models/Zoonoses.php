<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Zoonoses extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //do not save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_zoonosi';
    protected $primaryKey='zid';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';

    protected $lang=1;
    
    /**
     * Method for searches zoonoses in the system
     *
     *  @param array $where the conditions of where in query
     *  @param array $order the sorting conditions in queries
     *  @return 
     */
    public function getAll($where=[],$order=[]){
        
        $queryBuilder=DB::table($this->table.' AS z')->select('z.linktelegram','zl.*')
                ->leftJoin($this->table_zoonosilingue.' AS zl','z.zid','zl.zid')
                ->where('zl.lid',$this->lang);
                
        if(!empty($where))
            $queryBuilder->where($where);
                
        //$queryBuilder->groupBy('sl.sid');
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);
   
        return $queryBuilder->get();
    }
    
    public function getZoonosi($zid){
        $queryBuilder=DB::table($this->table.' AS z')->select('z.linktelegram','z.linkraccoltereview','zl.*')
                ->leftJoin($this->table_zoonosilingue.' AS zl','z.zid','zl.zid')
                ->where('zl.lid',$this->lang)
                ->where('z.zid',$zid);
        return $queryBuilder->get();
    }
    
    
    
    public function setZoonosiLang($datizoo,$zid,$update=0){
        $nome=trim(stripslashes(htmlspecialchars($datizoo['nome'])));
        $descrizione=trim(stripslashes(htmlspecialchars($datizoo['descrizione'])));
        $img_url=trim(stripslashes(htmlspecialchars($datizoo['img_url'])));
        $img_desc=trim(stripslashes(htmlspecialchars($datizoo['img_desc'])));
        $slugzoonosi=trim(stripslashes(htmlspecialchars($datizoo['slugzoonosi'])));
       
        if(!$update){
            $queryBuilder=DB::table($this->table_zoonosilingue)
                ->insert([
                    'zid' => $zid,
                    'lid' => $this->lang,
                    'nome' => $nome,
                    'descrizione' => $descrizione,
                    'img_url' => $img_url,
                    'img_desc' => $img_desc,
                    'slugzoonosi' => $slugzoonosi
                ]);
        }else{
            $queryBuilder=DB::table($this->table_zoonosilingue)->where('zid',$zid)
                ->update([
                    'lid' => $this->lang,
                    'nome' => $nome,
                    'descrizione' => $descrizione,
                    'img_url' => $img_url,
                    'img_desc' => $img_desc,
                    'slugzoonosi' => $slugzoonosi
                ]);
        }
        return true;  
    }
    
    public function checkExistSlugzoonosi($slug,$zid=0){
        $queryBuilder=DB::table($this->table_zoonosilingue.' AS zl')->select('zl.*')
                ->where('zl.slugzoonosi',$slug);
        if($zid>0)
            $queryBuilder->where('zl.zid','!=',$zid);
         return $queryBuilder->get();
    }
    public function checkExistNamezoonosi($nome,$zid=0){
        $queryBuilder=DB::table($this->table_zoonosilingue.' AS zl')->select('zl.*')
                ->where('zl.nome',$nome);
        if($zid>0)
            $queryBuilder->where('zl.zid','!=',$zid);
         return $queryBuilder->get();
    }
    
    

}
