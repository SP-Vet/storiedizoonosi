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
use DB;

/**
 * Manages the functions of the homepage 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class Home extends Model
{
    use HasFactory;
    protected $table_zoonosilingue='meoh_zoonosilingue_ass';
    protected $table_zoonosi='meoh_zoonosi';

    /**
     * Method that get all zoonoses stored in the db 
     *
     *  @param Array $where all conditions for the query
     *  @param Array $order array with all order parameters
     *  @return Array
     * 
     */
    public function getZoonosi($where=[],$order=[]){
        $queryBuilder=DB::table($this->table_zoonosi.' AS z')->select('zu.*','z.linktelegram')->leftJoin($this->table_zoonosilingue.' AS zu','z.zid','zu.zid');
        if(!empty($where))
            foreach ($where AS $key=>$wh)
                $queryBuilder->where($key,$wh);
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);

        return $queryBuilder->get()->toArray();
     }
}
