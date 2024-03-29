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
 * Manages all functions of the stories sended by a user
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Storiessubmit extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //do not save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_storie_submit_utente';
    protected $primaryKey='ssid';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    
    /**
     * Method for get the story, by the id, submitted by a user
     *
     *  @param Integer $sid id of story
     *  @return Object
     * 
     */
    public function getStoriaSubmitFromSID($sid){
        $queryBuilder=DB::table($this->table.' AS su')->select('su.*','u.name AS nome_cognome_utente')
                ->leftJoin($this->table_utenti.' AS u','u.id','su.uid')
                ->where('su.sid',$sid);
    
        return $queryBuilder->get();
    }
    
    /**
     * Method for get all stories not manged yet
     *
     *  @return Object
     * 
     */
    public function getStorieSubmitNONgestite(){
        $queryBuilder=DB::table($this->table.' AS su')->select('su.*','u.name AS nome_cognome_utente','u.email','s.data_inserimento')
                ->leftJoin($this->table_storie.' AS s','s.sid','su.sid')
                ->leftJoin($this->table_utenti.' AS u','u.id','su.uid')
                ->where('s.stato',0);
    
        return $queryBuilder->get();
    }
}
