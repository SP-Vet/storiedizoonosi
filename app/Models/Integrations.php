<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Integrations extends Model
{
   
    use HasFactory;
    //not save created_at ed updated_at
    public $timestamps = false;
    
    protected $table='meoh_storie_approfondimenti';
    protected $primaryKey='said';

    protected $table_storie_snippets='meoh_storie_snippets';
    protected $table_storie_snippetslingue='meoh_storie_snippetslingue_ass';
    
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    
    protected $table_storiefasi='meoh_storiefasi';
    protected $table_storiefasilingue='meoh_storiefasilingue_ass';

    protected $table_ruoli='meoh_ruoli';
    protected $table_ruolilingue='meoh_ruolilingue_ass';
    
    protected $table_storiecollaboratori='meoh_storiecollaboratori';
    protected $table_collaboratori='meoh_storie_collaboratori';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    
    protected $table_datibase='meoh_datibase';
    protected $table_daticontesto='meoh_quesitichiavelingue_ass';
    //protected $table_zoonosi='meoh_zoonosi';
 
    protected $table_utenti='users';
    protected $lang=1;
    
    /* integration statuts
     * 
     * 0-pending approval, 1-published, 2-hidden
     * 
     */
 
    public function setNewIntegration($uid,$sfid,$approfondimento,$testoapprofondimento='',$idcomrisp=NULL){
        $app_inserito=trim(stripslashes(htmlspecialchars($approfondimento)));
        $app_selezionato=trim(stripslashes(htmlspecialchars($testoapprofondimento)));
        
        $queryBuilder=DB::table($this->table)->insert(array('testoselezionato'=>$app_selezionato,'testoapprofondimento'=>$app_inserito,'data_inserimento'=>DB::raw('CURRENT_TIMESTAMP'),'stato'=>0,'sfid' => $sfid, 'said_genitore'=>$idcomrisp,'uid'=>$uid));
        return true;

    }
    
    public function getAll($where=[],$order=[]){
         $queryBuilder=DB::table($this->table.' AS sa')->select('sa.*','u.name AS nomeutente','u.email','sl.titolo','zl.nome AS nome_zoonosi','s.sid',DB::raw('to_char(sa.data_pubblicazione, \'dd/mm/YYYY HH:mm:ss\') AS data_pubblicazione_format'))
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','sa.sfid')
                ->leftJoin($this->table_storielingue.' AS sl','sl.sid','sf.sid')
                ->leftJoin($this->table_storie.' AS s','s.sid','sl.sid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','s.zid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','zl.zid','z.zid')
                 ->leftJoin($this->table_utenti.' AS u','sa.uid','u.id')
                ->where('sl.lid',$this->lang)
                ->where('zl.lid',$this->lang);
                
        if(!empty($where))
            $queryBuilder->where($where);
                
        //$queryBuilder->groupBy('sl.sid');
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);
   
        return $queryBuilder->get();
    }
    
    public function getIntegration($said){
        $queryBuilder=DB::table($this->table.' AS sa')->select('sa.*','u.name AS nomeutente','u.email','sl.titolo','zl.nome AS nome_zoonosi','sfl.testofase')
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','sa.sfid')
                ->leftJoin($this->table_storiefasilingue.' AS sfl','sfl.sfid','sf.sfid')
                ->leftJoin($this->table_storielingue.' AS sl','sl.sid','sf.sid')
                ->leftJoin($this->table_storie.' AS s','s.sid','sl.sid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','s.zid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','zl.zid','z.zid')
                 ->leftJoin($this->table_utenti.' AS u','sa.uid','u.id')
                ->where('sl.lid',$this->lang)
                ->where('zl.lid',$this->lang)
                ->where('sa.said',$said);
        return $queryBuilder->get();
    }
    
    public function getNumberIntegrationsStory($sid){
        $queryBuilder=DB::table($this->table.' AS sa')->select(DB::raw('COUNT(sa.*) AS totalefase'),'sf.sfid')
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','sa.sfid')
                ->leftJoin($this->table_storie.' AS s','s.sid','sf.sid')
                ->where('s.sid',$sid)->where('sa.stato',1)
                ->groupBy('sf.sfid');
        return $queryBuilder->get();
    }
    
    public function publishIntegration($said,$dati=[]){
        DB::table($this->table)
            ->where('said', $said)
            ->update($dati);
        return true;
    }
}
