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
 * Manages all functions of collaborators's roles
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Ruoli extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //do not save created_at ed updated_at
    public $timestamps = false;
    
    use HasFactory;
    protected $table='meoh_ruoli';
    protected $primaryKey='rid';
    protected $table_ruolilingue='meoh_ruolilingue_ass';
    protected $table_storie_collaboratori='meoh_storie_collaboratori';
    protected $table_storiecollaboratori='meoh_storiecollaboratori';
    protected $table_storie='meoh_storie';
    protected $table_storielingue='meoh_storielingue_ass';
    protected $table_storie_review='meoh_storie_review';
    protected $table_zoonosi='meoh_zoonosi';
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_utenti='users';
    protected $lang=1;
    
    /**
     * Method that get the roles of the colalborators 
     *
     *  @param Integer $order order of extraction [1: nomeruolo ASC, 2: nomeruolo DESC]
     *  @return Object
     * 
     */
    public function getAll($order=0){
        $queryBuilder=DB::table($this->table.' AS r')->select('rl.*','r.ordine_ruolo')
                ->leftJoin($this->table_ruolilingue.' AS rl','rl.rid','r.rid')
                ->where('rl.lid',$this->lang);
        
        if($order==1){
            $queryBuilder->orderBy('rl.nomeruolo','ASC');
        }elseif($order==2){
            $queryBuilder->orderBy('rl.nomeruolo','DESC');
        }
        return $queryBuilder->get();
    }
}
