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
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;

/**
 * Manages all functions of a user
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='users';
    protected $table_codfis='users_codfis';
    protected $table_tmpverifiedmail='users_tmp_verifiedmail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Method that searches for users with the email address passed in the parameters
     *
     * @param String  $email email to search in DB
     * @return Object
     *  
     */
    public function getUserFromEmail($email){
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*')
                ->where('u.email',$email);
    
        return $queryBuilder->get();
    }
    
    /**
     * Method that searches for users with the tax code passed in the parameters
     *
     *  @param String  $codfis tax code  to search in DB
     *  @return Object
     *  
     */
    public function getUserFromCodfis($codfis){
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*')
                 ->leftJoin($this->table_codfis.' AS ucf','ucf.uid','u.id')
                ->where('ucf.codfis',$codfis);
    
        return $queryBuilder->get();
    }

    /**
     * Method that searches for users with the id passed in the parameters
     *
     *  @param String  $id id to search in DB
     *  @return Object
     *  
     */
    public function getUserFromID($id){
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*')
                ->where('u.id',$id);
        return $queryBuilder->get();
    }

     
     /**
     * Method that set the verification date for a user
     *
     * @param Integer  $id id of the user
     * @return BOOL
     *  
     */
    public function setTMPVerifiedDate($id){
        $queryBuilder=DB::table($this->table_tmpverifiedmail)->insert(array('uid' => $id, 'startverified' => DB::raw('CURRENT_TIMESTAMP')));
        return true;
    }
    
    /**
     * Method that set tax code of a user
     *
     * @param Integer  $id id of the user
     * @param String  $codfis tax code of the user
     * @return BOOL
     *  
     */
    public function setUserTaxCode($id,$codfis){
      $queryBuilder=DB::table($this->table_codfis)->insert(array('uid' => $id, 'codfis' => strtoupper($codfis)));
        return true;  
    }

     /**
     * Method that delete the verification date of a user
     *
     * @param Integer  $id id of the user
     * @return BOOL
     *  
     */
    public function deleteTMPDateverified($id){
        $queryBuilder=DB::table($this->table_tmpverifiedmail)->where('uid', $id)->delete();
        return true;  
    }
    
}
