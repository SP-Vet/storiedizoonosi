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
 * Manages all functions of the stories phases
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Storiesphases extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //do not save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storiefasi';
    protected $primaryKey='sfid';
    protected $table_storiefasilingue='meoh_storiefasilingue_ass';
    protected $table_storie_approfondimenti='meoh_storie_approfondimenti';
    protected $table_storie_snippets='meoh_storie_snippets';
    protected $table_storie_snippetslingue='meoh_storie_snippetslingue_ass';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $lang=1;
    
     /**
     * Method for set data of a story phase
     *
     *  @param Integer $sfid id of the phase
     *  @param Array $dati data to store
     *  @return BOOL
     *  [if exist sfid then update otherwise insert a new record]
     */
    public function setStoriafaselinguaAss($sfid,$dati){
        if (DB::table($this->table_storiefasilingue)->where('lid', 1)->where('sfid',$sfid)->exists()) {
            //update
            DB::table($this->table_storiefasilingue)
                ->where('sfid', $sfid)->where('lid', 1)
                ->update($dati);
        }else{
            //insert
            $dati['sfid']=$sfid;
            $dati['lid']=$this->lang;
            DB::table($this->table_storiefasilingue)->insert($dati);
        }
        return true;
    }
}
