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
   
    //don't save created_at ed updated_at
    public $timestamps = false;
    use HasFactory;
    protected $table='meoh_privacypolicy';
    protected $primaryKey='ppid';
    protected $table_users='users';
    protected $table_privacy_accettazione='meoh_privacypolicy_accettazione';
  
    public function __construct(){}
    
    /**
     * Method for searches all privacy policy in the system
     *
     *  @param Array $where the conditions of where in query
     *  @param Array $order the sorting conditions in query
     *  @return Object
     */
    public function getAll($where=[],$order=[]){
        $queryBuilder=DB::table($this->table.' AS p')->select('p.*');
        if(!empty($where))
            $queryBuilder->where($where);    
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);

        return $queryBuilder->get();
    }

    /**
     * Method for get a privacy from its id
     *
     *  @param Integer $ppid if of a privacy
     *  @return Object
     */
    public function getPrivacyFromID($ppid){
        $queryBuilder=DB::table($this->table.' AS p')->select('p.*')->where('p.ppid',$ppid);
        return $queryBuilder->get();
    }

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
     * Method that get the last privacy accepted by a user
     * 
     *  @param Integer $uid if of the user
     *  @return Object
     * 
     */
    public function getLastAcceptedPrivacyFromUser($uid){
        $queryBuilder=DB::table($this->table_privacy_accettazione.' AS pa')->select('pa.*','p.testoprivacy')
            ->leftJoin($this->table.' AS p','p.ppid','pa.ppid')
            ->where('pa.uid',$uid)->orderBy('pa.data_accettazione_visione','DESC');
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

    /**
     * Method that set to false old actual privacy
     * 
     *  @param Integer $ppid if of a privacy
     *  @return BOOL
     * 
     */
    public function deactivateOldPrivacy($ppid){
        DB::table($this->table)->whereNotIn('ppid', [$ppid])->update(['attuale'=>0]);
        return true;
    }


}
