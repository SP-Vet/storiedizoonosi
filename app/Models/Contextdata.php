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
 * Manages all the context data linked to the stories 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Contextdata extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
 
    //don't save created_at ed updated_at
    public $timestamps = false;
    use HasFactory;
    protected $table='meoh_datibase';
    protected $primaryKey='dbid';
    protected $table_quesiti='meoh_quesitichiavelingue_ass';
    protected $table_storie='meoh_storie';
    protected $table_utenti='users';
    protected $lang=1;
    
    /**
     * Method that get the context data from specific story 
     *
     *  @param Integer $sid id of the story 
     *  @return Object
     */
    public function getContextdataFromStory($sid){
        $queryBuilder=DB::table($this->table.' AS db')->select('db.dbid','db.ordine','qc.*')
            ->leftJoin($this->table_quesiti.' AS qc','qc.dbid','db.dbid')
            ->where('qc.lid',$this->lang)
            ->where('db.sid',$sid)->orderBy('db.ordine','ASC');
        return $queryBuilder->get();
    }
    
    /**
     * Method that insert or update a group of data context 
     *
     *  @param Integer $dbid id of the context data
     *  @return Array $dati array with values of all data context
     * 
     *  [if exists the $dbid then update values otherwise insert a new record]
     */
    public function setContextdatalanguageAss($dbid,$dati){
        if (DB::table($this->table_quesiti)->where('lid', 1)->where('dbid',$dbid)->exists()) {
            //update
            DB::table($this->table_quesiti)
                ->where('dbid', $dbid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['dbid']=$dbid;
            $dati['lid']=$this->lang;
            DB::table($this->table_quesiti)->insert($dati);
        }
    }
}
