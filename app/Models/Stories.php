<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Stories extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    /*
     *status stories: 0-pending approval,1-under processing,2-published,3-hidden 
     *      */
    
    //don't save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie';
    protected $primaryKey='sid';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    
    protected $table_storie_approfondimenti='meoh_storie_approfondimenti';

    protected $table_storie_submitutente='meoh_storie_submit_utente';
    protected $table_storie_submitfiles='meoh_storie_submit_files';

        
    protected $table_storie_snippets='meoh_storie_snippets';
    protected $table_storie_snippetslingue='meoh_storie_snippetslingue_ass';
    
    
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
    
    /**
     * Method for searches stories in the system
     *
     *  @param array $where the conditions of where in query
     *  @param array $order the sorting conditions in queries
     *  @return 
     */
    public function getAll($where=[],$order=[]){
        
        $queryBuilder=DB::table($this->table.' AS s')->select('s.*','sl.titolo','sl.slug','u.name AS nomeutente','ssu.titolo_inserito','ssu.tipozoonosi_inserito','zl.nome AS nome_zoonosi')
                ->leftJoin($this->table_storielingue.' AS sl','s.sid','sl.sid')
                ->leftJoin($this->table_utenti.' AS u','s.uid','u.id')
                ->leftJoin($this->table_storie_submitutente.' AS ssu','ssu.sid','s.sid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','s.zid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','zl.zid','z.zid')
                ->where('sl.lid',$this->lang)->orWhereNull('sl.lid');
                
        if(!empty($where))
            $queryBuilder->where($where);
                
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);
   
        return $queryBuilder->get();
    }
    
    /**
     * Method for searches stories in the system
     *
     *  @param array $where the conditions of where in query
     *  @param array $order the sorting conditions in queries
     *  @return array
     */
    public function getStories($where=[],$whereand=[],$whereor=[],$wherenot=[],$wheresame=[],$order=[]){
        $this->whereand=$whereand;
        $this->whereor=$whereor;
        $this->wherenot=$wherenot;
        $this->wheresame=$wheresame;
        
        
        $queryBuilder=DB::table($this->table.' AS s')->select('s.*','sl.*','zl.nome AS nome_zoonosi','z.linktelegram',DB::raw('CONCAT(coll.nome,\' \',coll.cognome) AS autore'),'coll.grado','u.email')->distinct()
                ->leftJoin($this->table_storielingue.' AS sl','s.sid','sl.sid')
                ->leftJoin($this->table_storiecollaboratori.' AS scoll', function ($join) {
                    $join->on('scoll.sid', '=', 'sl.sid')
                         ->where('scoll.rid', '=', 1);
                })
                ->leftJoin($this->table_collaboratori.' AS coll','coll.collid','scoll.collid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','s.zid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','z.zid','zl.zid')
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sid','sl.sid')
                ->leftJoin($this->table_storiefasilingue.' AS sfl','sfl.sfid','sf.sfid')
                ->leftJoin($this->table_utenti.' AS u','u.id','s.uid')
                ->where('sl.lid',$this->lang)
                ->where('sfl.lid',$this->lang)
                ->where('zl.lid',$this->lang);
                
        
        if(!empty($where))
            $queryBuilder->where($where);
                
        for($i=1;$i<=4;$i++){
            $this->keyindex=$i;
            unset($tw);
            $valori_and=$valori_or=$valori_not=$valori_same=[];
            
            if(isset($this->whereand[$i]) && array_key_exists($i,$this->whereand)){
                $queryBuilder->where(function ($query) {
                    foreach ($this->whereand[$this->keyindex] AS $tw=>$valori_and){
                        //echo '<pre>';print_r($valori_and);exit;
                        switch($valori_and[0]){
                            case 'Autore':
                                $query->where(DB::raw("LOWER(CONCAT(coll.nome,' ',coll.cognome))"),(string)$valori_and[1],(string)$valori_and[2]);
                                break;
                            case 'Titolo':
                                $query->where(DB::raw("LOWER(sl.titolo)"),(string)$valori_and[1],(string)$valori_and[2]);
                                break;
                            case 'Abstract':
                                $query->where(DB::raw("LOWER(sl.abstract)"),(string)$valori_and[1],(string)$valori_and[2]);
                                break;
                            case 'Testo':
                                $query->where(DB::raw("LOWER(sfl.testofase)"),(string)$valori_and[1],(string)$valori_and[2]);
                            default:
                                break;
                        }
                        //$query->where((string)$valori_and[0],(string)$valori_and[1],'\''.(string)$valori_and[2].'\'');
                    }
                });
            }
            if(isset($this->whereor[$i]) && array_key_exists($i,$this->whereor)){
                $queryBuilder->where(function ($query) {
                    foreach ($this->whereor[$this->keyindex] AS $tw=>$valori_or){
                        switch($valori_or[0]){
                            case 'Autore':
                                $query->orWhere(DB::raw("LOWER(CONCAT(coll.nome,' ',coll.cognome))"),(string)$valori_or[1],(string)$valori_or[2]);
                                break;
                            case 'Titolo':
                                $query->orWhere(DB::raw("LOWER(sl.titolo)"),(string)$valori_or[1],(string)$valori_or[2]);
                                break;
                            case 'Abstract':
                                $query->orWhere(DB::raw("LOWER(sl.abstract)"),(string)$valori_or[1],(string)$valori_or[2]);
                                break;
                            case 'Testo':
                                $query->orWhere(DB::raw("LOWER(sfl.testofase)"),(string)$valori_or[1],(string)$valori_or[2]);
                            default:
                                break;
                        }
                        //$query->orWhere((string)$valori_or[0],(string)$valori_or[1],'\''.(string)$valori_or[2].'\'');
                    }
                });
            }
            if(isset($this->wherenot[$i]) && array_key_exists($i,$this->wherenot)){
                $queryBuilder->where(function ($query) {
                    foreach ($this->wherenot[$this->keyindex] AS $tw=>$valori_not){
                        switch($valori_not[0]){
                            case 'Autore':
                                $query->where(DB::raw("LOWER(CONCAT(coll.nome,' ',coll.cognome))"),(string)$valori_not[1],(string)$valori_not[2]);
                                break;
                            case 'Titolo':
                                $query->where(DB::raw("LOWER(sl.titolo)"),(string)$valori_not[1],(string)$valori_not[2]);
                                break;
                            case 'Abstract':
                                $query->where(DB::raw("LOWER(sl.abstract)"),(string)$valori_not[1],(string)$valori_not[2]);
                                break;
                            case 'Testo':
                                $query->where(DB::raw("LOWER(sfl.testofase)"),(string)$valori_not[1],(string)$valori_not[2]);
                            default:
                                break;
                        }
                        //$query->where((string)$valori_not[0],(string)$valori_not[1],'\''.(string)$valori_not[2].'\'');
                    }
                });
            }
            //echo '<pre>';print_r($this->wheresame);exit;
            if(isset($this->wheresame[$i]) && array_key_exists($i,$this->wheresame)){
                $queryBuilder->where(function ($query) {
                    foreach ($this->wheresame[$this->keyindex] AS $tw=>$valori_same){
                        switch($valori_same[0]){
                            case 'Autore':
                                $query->where(DB::raw("LOWER(CONCAT(coll.nome,' ',coll.cognome))"),(string)$valori_same[1],(string)$valori_same[2]);
                                break;
                            case 'Titolo':
                                $query->where(DB::raw("LOWER(sl.titolo)"),(string)$valori_same[1],(string)$valori_same[2]);
                                break;
                            case 'Abstract':
                                $query->where(DB::raw("LOWER(sl.abstract)"),(string)$valori_same[1],(string)$valori_same[2]);
                                break;
                            case 'Testo':
                                $query->where(DB::raw("LOWER(sfl.testofase)"),(string)$valori_same[1],"%".(string)$valori_same[2]."%");
                            default:
                                break;
                        }
                        //$query->where((string)$valori_same[0],(string)$valori_same[1],'\''.(string)$valori_same[2].'\'');
                    }
                });
            }
        }
        
       /* if(!empty($where))
            foreach ($where AS $key=>$wh)
                if(is_array($wh))
                    $queryBuilder->where($key,$wh[0],$wh[1]);
                else
                    $queryBuilder->where($key,$wh);*/
        
        //$queryBuilder->groupBy('sl.sid');
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);

        return $queryBuilder->get();
     }
     
    /**
     * Method search for a specific story
     *
     *  @param int $sid id of the story
     *  
     */
    public function getStory($sid){
        $queryBuilder=DB::table($this->table.' AS s')->select('s.*','sl.*','zl.nome AS nomezoonosi','z.linktelegram')
                ->leftJoin($this->table_storielingue.' AS sl','s.sid','sl.sid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','s.zid','zl.zid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','zl.zid')
                ->where('sl.sid',$sid)
                ->where('sl.lid',$this->lang)
                ->where('zl.lid',$this->lang)->orWhereNull('zl.lid');
    
        return $queryBuilder->get();
    }
    
    
    
    /**
     * Method search for a specific story via slug
     *
     *  @param str $slug slug of the story
     *  
     */
    public function getStoryFromSlug($slug){
        $queryBuilder=DB::table($this->table.' AS s')->select('s.*','sl.*','zl.nome AS nomezoonosi','z.linktelegram','z.linkraccoltereview')
                ->leftJoin($this->table_storielingue.' AS sl','s.sid','sl.sid')
                ->leftJoin($this->table_zoonosilingue.' AS zl','s.zid','zl.zid')
                ->leftJoin($this->table_zoonosi.' AS z','z.zid','zl.zid')
                ->where('sl.slug',$slug)
                ->where('sl.lid',$this->lang)
                ->where('zl.lid',$this->lang);
    
        return $queryBuilder->get();
    }
    
    
    public function getStoryFromPhaseID($sfid){
        $queryBuilder=DB::table($this->table_storiefasilingue.' AS sfl')->select('sfl.titolofase','sfl.testofase','zl.nome AS nomezoonosi','sl.titolo')
            ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','sfl.sfid')
            ->leftJoin($this->table.' AS s','s.sid','sf.sid')
            ->leftJoin($this->table_storielingue.' AS sl','s.sid','sl.sid')
            ->leftJoin($this->table_zoonosilingue.' AS zl','s.zid','zl.zid')
            ->where('sl.lid',$this->lang)
            ->where('zl.lid',$this->lang)
            ->where('sfl.lid',$this->lang)
            ->where('sf.sfid',$sfid);
                
        return $queryBuilder->first();
    }
    
    /**
     * Method that searches for all collaborators linked to a story
     *
     *  @param int $sid id of the story
     *  @param int $order if 1 order ASC, if 2 ordr DESC, default random
     *  
     */
    public function getStoryCollaborators($sid,$order=0){
        $queryBuilder=DB::table($this->table_storiecollaboratori.' AS sc')->select('coll.*','r.nomeruolo','r.rid','ru.ordine_ruolo')
                ->leftJoin($this->table_collaboratori.' AS coll','sc.collid','coll.collid')
                ->leftJoin($this->table_ruolilingue.' AS r','sc.rid','r.rid')
                ->leftJoin($this->table_ruoli.' AS ru','ru.rid','r.rid')
                 ->where('r.lid',$this->lang)
                ->where('sc.sid',$sid);
        
        if($order==1){
            $queryBuilder->orderBy('ru.ordine_ruolo','ASC');
        }elseif($order==2){
            $queryBuilder->orderBy('ru.ordine_ruolo','DESC');
        }
    
        return $queryBuilder->get();
    }
    
    
    /**
     * Method that searches for all the phases of the story
     *
     *  @param int $sid id of the story
     *  
     */
    public function getStoryPhases($sid){
        $queryBuilder=DB::table($this->table_storiefasi.' AS sf')->select('sfl.*')
                ->leftJoin($this->table_storiefasilingue.' AS sfl','sf.sfid','sfl.sfid')
                ->where('sfl.lid',$this->lang)
                ->where('sf.sid',$sid)
                ->orderBy('sf.ordine','ASC');
    
        return $queryBuilder->get();
    }
    
    /**
     * Method that searches all the integrations starting from a list of id phases
     *
     *  @param array $elencofasi id list of story phases
     *  @param array $stati [default 0 = all]
     *  
     */
    public function getIntegrationsPhases($elencofasi,$stati=0){
        $queryBuilder=DB::table($this->table_storie_approfondimenti.' AS aps')->select('aps.*','u.name AS nome_cognome_utente')
                ->leftJoin($this->table_utenti.' AS u','u.id','aps.uid')
                ->whereIn('aps.sfid',$elencofasi)
                ->orderBy('aps.data_pubblicazione','ASC');
        
            if(is_array($stati) && count($stati)>0)
                $queryBuilder->whereIn('aps.stato',$stati);
            
         return $queryBuilder->get();
    }
    
    
    public function getContextdataFromStory($sid){
        $queryBuilder=DB::table($this->table_datibase.' AS dtb')->select('dtb.ordine','dtc.*')
                ->leftJoin($this->table_daticontesto.' AS dtc','dtc.dbid','dtb.dbid')
                 ->where('dtc.lid',$this->lang)
                ->where('dtb.sid',$sid)
                ->orderBy('dtb.ordine','ASC');
         return $queryBuilder->get();
    }
    
    public function getReviewsFromZoonosi($zid){
        $queryBuilder=DB::table($this->table_storie_review.' AS sr')->select('sr.*')
                 ->where('sr.zid',$zid)
                ->orderBy('sr.titolo_visualizzato','ASC');
         return $queryBuilder->get();
    }
    
    public function getSnippet($snid){
        $queryBuilder=DB::table($this->table_storie_snippetslingue.' AS sn')->select('sn.*')
                 ->where('sn.snid',$snid)
                    ->where('sn.lid',$this->lang);
         return $queryBuilder->get();
    }
    
    public function getSnippetsFromStory($sid){
        $queryBuilder=DB::table($this->table_storie_snippetslingue.' AS sn')->select('sn.*','ss.sfid')
                ->leftJoin($this->table_storie_snippets.' AS ss','ss.snid','sn.snid')
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','ss.sfid')
                ->where('sf.sid',$sid)
                ->where('sn.lid',$this->lang);
         return $queryBuilder->get();
    }
    
    
    
    public function setStorylanguageAss($sid,$dati){
        if (DB::table($this->table_storielingue)->where('lid', 1)->where('sid',$sid)->exists()) {
            //update
            DB::table($this->table_storielingue)
                ->where('sid', $sid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['sid']=$sid;
            $dati['lid']=$this->lang;
            DB::table($this->table_storielingue)->insert($dati);
        }
    }
    
    public function publishStory($sid,$dati=[]){
        DB::table($this->table)
            ->where('sid', $sid)
            ->update($dati);
        return true;
    }
    
    public function checkExistSlug($slug,$sid=0){
        $queryBuilder=DB::table($this->table_storielingue.' AS sl')->select('sl.*')
                ->where('sl.slug',$slug);
        if($sid>0)
            $queryBuilder->where('sl.sid','!=',$sid);
         return $queryBuilder->get();
    }
     
    
   



}
