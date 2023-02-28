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
 * Manages all settings
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Settings extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[];
    public $wheresame=[];
    public $keyindex=0;
    
    //don't save created_at ed updated_at
    public $timestamps = false;
    use HasFactory;
    protected $table='meoh_configuration';
    protected $primaryKey='confid';
    protected $lang=1;
    
    /**
     * CONFIGURATION TYPE: 0-input, 1-text, 2-check, 3-radio, 4-file
     * CONFIGURATION SECTION: 0-general, 1-stories, 2-zoonoses, 3-integrations, 4-users
     *      
     */

    /**
     * Method for searches configurations in the system
     *
     *  @param Array $where the conditions of where in query
     *  @return Object
     */
    public function getAll($where=[],$wherein=[]){
        $queryBuilder=DB::table($this->table.' AS c')->select('c.*');
        if(!empty($where))
            $queryBuilder->where($where);   
        if(!empty($wherein))
            $queryBuilder->wherein('c.groupsection',$wherein); 
        $queryBuilder->orderBy('c.groupsection','ASC')->orderBy('c.confid','ASC');

        //echo $queryBuilder->toSql();exit;
        return $queryBuilder->get();
    }   

    /**
     * Method for get configuration from ID
     *
     *  @param Integer $confid the configuration's ID
     *  @return Object
     */
    public function getConfFromID($confid){
        $queryBuilder=DB::table($this->table.' AS c')->select('c.*')->where('c.confid',$confid);    
        return $queryBuilder->get();
    }
}