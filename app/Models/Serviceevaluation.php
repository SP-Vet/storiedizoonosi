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
 * Manages the service evaluation of the system
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Serviceevaluation extends Model
{
    public $whereand=[];
    public $whereor=[];
    public $wherenot=[]; 
    public $wheresame=[];
    public $keyindex=0;
    
    //don't save created_at ed updated_at
    public $timestamps = false;
    use HasFactory;
    protected $table='meoh_serviceevaluation';
    protected $table_answer='meoh_serviceevaluation_answer';
    protected $table_answer_user='meoh_serviceevaluation_answer_user';
    protected $primaryKey='seid';
    protected $lang=1;
    
    /**
     * Method for searches all questions of the evaluation service
     *
     *  @param Array $where the conditions of where in query
     *  @return Object
     */
    public function getAllQuestions($where=[],$wherein=[]){
        $queryBuilder=DB::table($this->table.' AS se')->select('se.*');
        if(!empty($where))
            $queryBuilder->where($where);   
        if(!empty($wherein))
            $queryBuilder->wherein('se.seid',$wherein); 
        $queryBuilder->orderBy('se.orderquestion','ASC');

        //echo $queryBuilder->toSql();exit;
        return $queryBuilder->get();
    }   

    /**
     * Method for searches all questions and answers of the evaluation service
     *
     *  @param Array $where the conditions of where in query
     *  @return Object
     */
    public function getAllQuestionsAndAnswers($where=[],$wherein=[]){
        $queryBuilder=DB::table($this->table.' AS se')->select('se.*','sea.seaid','sea.typeanswer','sea.actualanswer')
        ->leftJoin($this->table_answer.' AS sea','sea.seid','se.seid');
        if(!empty($where))
            $queryBuilder->where($where);   
        if(!empty($wherein))
            $queryBuilder->wherein('se.seid',$wherein); 
        $queryBuilder->orderBy('se.orderquestion','ASC');

        //echo $queryBuilder->toSql();exit;
        return $queryBuilder->get();
    } 
    
    /**
     * Method for searches all user's answer given to the system
     *
     *  @param Array $where the conditions of where in query
     *  @return Object
     */
    public function getAllUsersAnswers(){
        $queryBuilder=DB::table($this->table_answer_user.' AS seau')->select('seau.*','sea.typeanswer','se.seid','se.question')
        ->leftJoin($this->table_answer.' AS sea','seau.seaid','sea.seaid')
        ->leftJoin($this->table.' AS se','se.seid','sea.seid');
        $queryBuilder->orderBy('se.seid','ASC')->orderBy('seau.seaid','ASC');

        //echo $queryBuilder->toSql();exit;
        return $queryBuilder->get();
    } 


    /**
     * Method that insert a group of evaluation data answer 
     *
     *  @param Array $dati array with values of all data answer
     *  @return BOOL
     * 
     */
    public function setEvaluationAnswer($dati){
        DB::table($this->table_answer_user)->insert($dati);
        return true;
    }
}