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
use Illuminate\Support\Carbon;
use DB;

/**
 * Manages all functions of privacy policy of the system 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Privacy extends Model
{
   
    use HasFactory;
    protected $table='meoh_privacypolicy';
    protected $primaryKey='ppid';
    protected $table_users='users';
    protected $table_privacy_accettazione='meoh_privacypolicy_accettazione';
  
    public function __construct(){}
    
     /**
     * Method that get the current privacy of the system
     *  @return Object
     * 
     */
    public function getCurrentPrivacy(){
        $queryBuilder=DB::table($this->table.' AS pp')->select('pp.*')->where('pp.attuale',1);
        return $queryBuilder->first();
    }
    
     /**
     * Method that set the parameters of privacy policy acceptance
     *  @return BOOL
     * 
     */
    public function setAccept($uid,$tipologia=0){
        $privacyattuale=$this->getCurrentPrivacy();
        $queryBuilder=DB::table($this->table_privacy_accettazione)->insert(array('ppid' => $privacyattuale->ppid, 'uid' => $uid,'data_accettazione_visione'=>DB::raw('CURRENT_TIMESTAMP'),'tipologia'=>$tipologia));
        return true;  
    }
}
