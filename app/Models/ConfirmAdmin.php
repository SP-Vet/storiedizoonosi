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
 * Manages the reset password link 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class ConfirmAdmin extends Model
{
    protected $kpu=[];
    protected $kpr=[];
    use HasFactory;
    protected $table='admins';
    protected $primaryKey='id';
  
    public function __construct()
    {
        $this->kpu=config('app.publickeystring');
        $this->kpr=config('app.privatekeystring');
        
         /*
         * STRUTTURA LINK RESET PASSWORD
         *  $a=KPU
         *  $b=id ADMIN
         *  $c=sha1($a.KPR.$b).md5(email fittizia admin)
         * 
         */
    }
    
    public function getKPU(){
        return $this->kpu;
    }
    public function getKPR(){
        return $this->kpr;
    }
    
     /**
     * Method that generate and get the reset link 
     *
     *  @param Integer $id id of the admin 
     *  @param String $email fictitious email of the admin                 
     *  @return String
     */
    public function getEmailResetLink($id,$email){
        //creazione Link conferma
        $a=$this->kpu;
        $b=$id;
        $c=sha1($a.$this->kpr.$b).md5($email);
        $link='//'.$_SERVER['HTTP_HOST'].'/checkresetpasswordadmin/'.$c.'/'.$b.'/'.$a;
        
        return $link;
    }
    
    /**
     * Method that check the validity of the reset link 
     *
     *  @param String $first string to check 
     *  @param String $second id to check  
     *  @param String $third public key to check                
     *  @return BOOL
     */
    public function checkResetPassword($first,$second,$third){
        $idcheck=$second;
        $kpucheck=$third;
        $stringcheck=$first;
        $emailmd5check=substr($stringcheck, -32);
        $sha1check=substr($stringcheck,0, 40);
        
        //kpu modificata
        if($kpucheck!==$this->kpu)return false;
        
        //id NON numerico
        if(!preg_match('/^[1-9][0-9]*$/', $idcheck))return false;
        
        //estrazione admin da id
        $queryBuilder=DB::table($this->table.' AS a')->select('a.*')
            ->where('a.id',$idcheck);
        $admin=$queryBuilder->first();
        
        
        //email nel link modificata (manomissione MD5 email)
        if($emailmd5check!==md5($admin->email))return false;
        
        //sha1 con kpu, kpr ed id modificato
        if($sha1check!==sha1($this->kpu.$this->kpr.$admin->id))return false;
        
        return true;
    }
}
