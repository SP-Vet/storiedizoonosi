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
 * Manages all functions of the zoonoses
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
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
     *  @param Array $where the conditions of where in query
     *  @param Array $order the sorting conditions in queries
     *  @return Object
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
    
    /**
     * Method for get a zoonosi by its id
     *
     *  @param Integer $zid id of the zoonosi
     *  @return Object
     */
    public function getZoonosi($zid){
        $queryBuilder=DB::table($this->table.' AS z')->select('z.linktelegram','z.linkraccoltereview','zl.*')
                ->leftJoin($this->table_zoonosilingue.' AS zl','z.zid','zl.zid')
                ->where('zl.lid',$this->lang)
                ->where('z.zid',$zid);
        return $queryBuilder->get();
    }
    
    /**
     * Method for insert or update a zoonosi into the DB
     *
     * @param Array $datizoo data to store/update
     * @param Integer $zid id of the zoonosi
     * @param Integer $update 1-update 2-insert
     * @return BOOL
     */
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
    
    /**
     * Method check if exists zoonosi into the DB
     *
     * @param String $slug slug to search
     * @param Integer $zid id of the zoonosi [if exists then exclude it from the research]
     * @return Object
     * 
     */
    public function checkExistSlugzoonosi($slug,$zid=0){
        $queryBuilder=DB::table($this->table_zoonosilingue.' AS zl')->select('zl.*')
                ->where('zl.slugzoonosi',$slug);
        if($zid>0)
            $queryBuilder->where('zl.zid','!=',$zid);
         return $queryBuilder->get();
    }

    /**
     * Method check if exists zoonosi's name into the DB
     *
     * @param String $nome name to search
     * @param Integer $zid id of the zoonosi [if exists then exclude it from the research]
     * @return Object
     * 
     */
    public function checkExistNamezoonosi($nome,$zid=0){
        $queryBuilder=DB::table($this->table_zoonosilingue.' AS zl')->select('zl.*')
                ->where('zl.nome',$nome);
        if($zid>0)
            $queryBuilder->where('zl.zid','!=',$zid);
         return $queryBuilder->get();
    }
}
