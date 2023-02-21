<?php
/*
 * Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC
 * Created on 2023
 * @author Eros Rivosecchi <e.rivosecchi@izsum.it>
 * @author IZSUM Sistema Informatico <sistemainformatico@izsum.it>
 * 
 * @license 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at

 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 * 
 * @version 1.0
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

/**
 * Manages all functions of the stories
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Stories extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
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
    protected $table_utenti='users';
    protected $lang=1;
    
    /**
     * STORIES STATUS: 0-pending approval,1-under processing,2-published,3-hidden 
     *      
     */

    /**
     * Method for searches stories in the system
     *
     *  @param Array $where the conditions of where in query
     *  @param Array $order the sorting conditions in query
     *  @return Object
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
     *  @param Array $where the conditions of where in query
     *  @param Array $whereand the conditions of whereand in query
     *  @param Array $whereor the conditions of whereor in query
     *  @param Array $wherenot the conditions of wherenot in query
     *  @param Array $wheresame the conditions of wheresame in query
     *  @param Array $order the sorting conditions in query
     *  @return Object
     * 
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
                    }
                });
            }
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
                    }
                });
            }
        }
        
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);

        return $queryBuilder->get();
     }
     
    /**
     * Method that search for a specific story
     *
     *  @param Integer $sid id of the story
     *  @return Object
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
     * Method search for a specific story from slug
     *
     *  @param String $slug slug of the story
     *  @return Object
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
    
    /**
     * Method search for a specific story from phase's ID
     *
     *  @param String $sfid id of the phases
     *  @return Object
     * 
     */
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
     *  @param Integer $sid id of the story
     *  @param Integer $order [1: order ASC, 2: order DESC, default random]
     *  
     *  @return Object
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
     *  @param Integer $sid id of the story
     *  @return Object
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
     *  @param String $elencofasi id list of story phases
     *  @param String $stati status list [default 0 = all]
     *  @return Object
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
    
    /**
     * Method that get Context data from a story
     *
     *  @param Integer $sid id of the story
     *  @return Object
     *  
     */
    public function getContextdataFromStory($sid){
        $queryBuilder=DB::table($this->table_datibase.' AS dtb')->select('dtb.ordine','dtc.*')
                ->leftJoin($this->table_daticontesto.' AS dtc','dtc.dbid','dtb.dbid')
                 ->where('dtc.lid',$this->lang)
                ->where('dtb.sid',$sid)
                ->orderBy('dtb.ordine','ASC');
         return $queryBuilder->get();
    }
    
    /**
     * Method that get reviews from a zoonosi
     *
     *  @param Integer $zid id of the zoonosi
     *  @return Object
     *  
     */
    public function getReviewsFromZoonosi($zid){
        $queryBuilder=DB::table($this->table_storie_review.' AS sr')->select('sr.*')
                 ->where('sr.zid',$zid)
                ->orderBy('sr.titolo_visualizzato','ASC');
         return $queryBuilder->get();
    }
    
    /**
     * Method that get a snippet from its id
     *
     *  @param Integer $snid id of the snippet
     *  @return Object
     *  
     */
    public function getSnippet($snid){
        $queryBuilder=DB::table($this->table_storie_snippetslingue.' AS sn')->select('sn.*')
                 ->where('sn.snid',$snid)
                    ->where('sn.lid',$this->lang);
         return $queryBuilder->get();
    }
    
    /**
     * Method that get the snippets from a story
     *
     *  @param Integer $sid id of the story
     *  @return Object
     *  
     */
    public function getSnippetsFromStory($sid){
        $queryBuilder=DB::table($this->table_storie_snippetslingue.' AS sn')->select('sn.*','ss.sfid')
                ->leftJoin($this->table_storie_snippets.' AS ss','ss.snid','sn.snid')
                ->leftJoin($this->table_storiefasi.' AS sf','sf.sfid','ss.sfid')
                ->where('sf.sid',$sid)
                ->where('sn.lid',$this->lang);
         return $queryBuilder->get();
    }
    
    /**
     * Method that insert or update a story's data
     *
     *  @param Integer $sid id of the story
     *  @param Array $dati array of story's data 
     *  [if sid exist then update the story otherwise insert]
     *  @return Object
     *  
     */
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
        return true;
    }
    
    /**
     * Method that update a story's data
     *
     *  @param Integer $sid id of the story
     *  @param Array $dati array of story's data 
     *  @return BOOL
     *  
     */
    public function publishStory($sid,$dati=[]){
        DB::table($this->table)
            ->where('sid', $sid)
            ->update($dati);
        return true;
    }
    
     /**
     * Method that check if already exists a slug in the DB
     *
     *  @param String $slug slug to search
     *  @param Integer $sid id of the story
     *  [if a sid is provided, exclude it from the search]
     *  @return Object
     *  
     */
    public function checkExistSlug($slug,$sid=0){
        $queryBuilder=DB::table($this->table_storielingue.' AS sl')->select('sl.*')
                ->where('sl.slug',$slug);
        if($sid>0)
            $queryBuilder->where('sl.sid','!=',$sid);
         return $queryBuilder->get();
    }
}
