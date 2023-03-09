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

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use App\Models\Admin;
use DB;

class PwdExpirationController extends Controller
{
    private $request;

    public function __construct(Request $request){
        $this->mod_log=new LogPersonal($request);
        $this->request=$request;
    }

    public function showPasswordExpiration(Request $request){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] showPasswordExpirationForm', $this->mod_log->getParamFrontoffice());
        $password_expired_id = $request->session()->get('password_expired_id');
        if(!isset($password_expired_id)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] showPasswordExpirationForm', $this->mod_log->getParamFrontoffice('id expired'));
            return redirect(route('adminLogin'));
        }
        return view('admin.expired');
    }

    public function postPasswordExpiration(Request $request){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] postPasswordExpiration', $this->mod_log->getParamFrontoffice());
        $password_expired_id = $request->session()->get('password_expired_id');
        if(!isset($password_expired_id)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] postPasswordExpiration', $this->mod_log->getParamFrontoffice('id expired'));
            return redirect(route('adminLogin'));
        }

        $user = Admin::find($password_expired_id);
        if (!(Hash::check(trim($request->get('passwordcorrente')), $user->password))) {
            // The passwords NOT matches
            return redirect()->back()->with("error","La password corrente non coincide. Riprova.");
        }

        if(strcmp(trim($request->get('passwordcorrente')), trim($request->get('password'))) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","La nuova password non può essere uguale alla vecchia. Scegli una password differente.");
        }

        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%-*_£()])[0-9A-Za-z!@#$%-*_£()]{8,}$/', trim($request->get('password')))){
            //Not valid password
            return redirect()->back()->with("error","La nuova password non rispetta il formato minimo di sicurezza. Inserire almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali ( !@#$%-*_£() ).");
        }

        if(strcmp(trim($request->get('password')), trim($request->get('ripetipassword'))) != 0){
            //The passwords entered do not match
            return redirect()->back()->with("error","Le nuove password inserite non coincidono. Rioprovare.");
        }
        
        DB::beginTransaction();
        try {
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] postPasswordExpiration', $this->mod_log->getParamFrontoffice());
            //Update password and updation timestamp
            $data=[];
            $data['password']=Hash::make(trim($request->get('password')));
            $data['password_changed_at']=Carbon::now();
            Admin::find($user->id)->update($data);
            DB::commit();
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] postPasswordExpiration', $this->mod_log->getParamFrontoffice());
            $this->request->session()->flash('messageinfo', 'Password modificata correttamente. Effettua il login');
            return redirect(route('adminLogin'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] postPasswordExpiration', $this->mod_log->getParamFrontoffice($e->getMessage()));
            $this->request->session()->flash('messagedanger', 'Errore interno al sistema, password NON modificata');
        }
    }
}