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

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Collaborators;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manages the collaborators inserted and to be inserted in the stories
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminCollaboratorsController extends Controller
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_collaborators = new Collaborators();
        $this->mod_log=new LogPersonal($request);
    }

    /**
     * Returns the collaborator data from the submitted id (from constructor Request $request)
     * @return JSON
     */ 
    public function getcollaborator(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] getcollaborator', $this->mod_log->getParamFrontoffice());
        if($this->request->collid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->collid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] getcollaborator', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il collaboratore selezionato.']);
        }
        $collaboratore=$this->mod_collaborators->find($this->request->collid);
        return response()->json(['error'=>false,'message'=>'','collaboratore'=>$collaboratore]);
    }
  
}
