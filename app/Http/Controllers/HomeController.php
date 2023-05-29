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
use App\Models\Home;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use App\Models\Settings;


/**
 * Manage all function of the user dashboard 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class HomeController extends Controller
{
    public $mod_home;
    public $mod_settings;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_home = new Home();
        $this->mod_settings = new Settings();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List all zoonoses in the system
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function index(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] homepage', $this->mod_log->getParamFrontoffice());
        $title_page='Homepage';
        $order=[];
        $order['zu.nome']='ASC';
        $zoonosi=$this->mod_home->getZoonosi('',$order);
        $settings=[];
        $settings=array_column($this->mod_settings->getAll([['c.groupsection','0']])->toArray(),NULL,'nameconfig');
        return view('home')->with('zoonosi',$zoonosi)->with('title_page',$title_page)->with('settings',$settings)
                ->with('og_description','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»')
                ->with('art_description','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»')
                ->with('art_abstract','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»');
    }
    
    /**
    *
    * Static project description page
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function project(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] project', $this->mod_log->getParamFrontoffice());
        $settings=[];
        $settings=array_column($this->mod_settings->getAll([['c.groupsection','0']])->toArray(),NULL,'nameconfig');
        return view('project')->with('settings',$settings);
    }
}
