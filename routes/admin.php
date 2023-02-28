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

<?php 
/*all admin routes*/
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminZoonosesController,AdminStoriesController,AdminController,AdminWorkgroupController,AdminIntegrationsController,AdminCollaboratorsController,CkeditorController,ReviewsController,AdminContextdataController,AdminSettingsController};

Route::get('/cambiapassword/{id?}',[AdminController::class,'cambiapassword'])->where(['id'=>'[1-9][0-9]?+'])->name('adminCambiapassword');
Route::post('/cambiapassword/{id?}',[AdminController::class,'cambiapassword'])->where(['id'=>'[1-9][0-9]?+'])->name('admincheckCambiapassword');
/*section zoonoses*/
Route::get('/elencozoonosi',[AdminZoonosesController::class,'list'])->name('adminListZoonoses');
Route::get('/modificazoonosi/{zid?}',[AdminZoonosesController::class,'modify'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminModifyZoonoses');
Route::post('/modificazoonosi/{zid?}',[AdminZoonosesController::class,'modify'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminSaveModifyZoonoses');
Route::get('/aggiungizoonosi',[AdminZoonosesController::class,'adding'])->name('adminAddZoonoses');
Route::post('/aggiungizoonosi',[AdminZoonosesController::class,'adding'])->name('adminSaveNewZoonoses');
Route::get('/cancellazoonosi/{zid}',[AdminZoonosesController::class,'erase'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminEraseZoonoses');

/*section stories*/
Route::get('/elencostorie',[AdminStoriesController::class,'list'])->name('adminListStorie');
Route::get('/modificastoria/{sid?}',[AdminStoriesController::class,'modify'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminModifyStory');
Route::post('/modificastoria/{sid?}',[AdminStoriesController::class,'modify'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminSaveModifyStory');
Route::get('/aggiungistoria',[AdminStoriesController::class,'modify'])->name('adminAddStory');
Route::post('/aggiungistoria',[AdminStoriesController::class,'modify'])->name('adminSaveNewStory');
Route::get('/cancellastoria/{sid}',[AdminStoriesController::class,'remove'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminRemoveStory');

/*section contextdata stories*/
Route::get('/daticontestostoria/{sid?}',[AdminContextdataController::class,'contextdatastory'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminContextDataStory');
Route::post('/daticontestostoria/{sid?}',[AdminContextdataController::class,'contextdatastory'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminSaveContextDataStory');

/*section work group*/
Route::get('/elencogruppo',[AdminWorkgroupController::class,'list'])->name('adminListWorkgroup');
Route::get('/aggiungiutente',[AdminWorkgroupController::class,'adding'])->name('adminAddUser');
Route::post('/aggiungiutente',[AdminWorkgroupController::class,'adding'])->name('adminSaveNewUser');
Route::get('/modificautente/{id?}',[AdminWorkgroupController::class,'modify'])->where(['id'=>'[1-9][0-9]?+'])->name('adminModifyUser');
Route::post('/modificautente/{id?}',[AdminWorkgroupController::class,'modify'])->where(['id'=>'[1-9][0-9]?+'])->name('adminSaveModifiedUser');

/*section integrations*/
Route::get('/elencoapprofondimenti',[AdminIntegrationsController::class,'list'])->name('adminListIntegrations');
Route::get('/gestisciapprofondimenti/{said?}',[AdminIntegrationsController::class,'manage'])->where(['said'=>'[1-9][0-9]?+'])->name('adminManageIntegration');
Route::post('/gestisciapprofondimenti/{said?}',[AdminIntegrationsController::class,'manage'])->where(['said'=>'[1-9][0-9]?+'])->name('adminSaveManagementIntegration');

/*section log*/
Route::get('/elencolog',[LogPersonal::class,'elenco'])->name('listLog');
Route::post('/elencolog',[LogPersonal::class,'elenco'])->name('postlistLog');
Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

/*section settings*/
Route::get('/elencoimpostazioni',[AdminSettingsController::class,'list'])->name('adminListSettings');
Route::get('/modificaimpostazione/{confid?}',[AdminSettingsController::class,'modify'])->where(['confid'=>'[1-9][0-9]?+'])->name('adminModifySetting');
Route::post('/modificaimpostazione/{confid?}',[AdminSettingsController::class,'modify'])->where(['confid'=>'[1-9][0-9]?+'])->name('adminSaveManagementSetting');


/*ajax calls*/
Route::post('/ajx-checkslugzoonosi', [AdminZoonosesController::class, 'checkslugzoonosi']);
Route::post('/ajx-checkslug', [AdminStoriesController::class, 'checkslug']);
Route::post('/ajx-publishstory', [AdminStoriesController::class, 'publishstory']);
Route::post('/ajx-publishintegrations', [AdminIntegrationsController::class, 'publishintegrations']);
Route::post('/ajx-getcollaborator', [AdminCollaboratorsController::class, 'getcollaborator']);
Route::post('/ajx-removereview', [ReviewsController::class, 'eliminareview']);
Route::post('/ajx-uploadreview', [ReviewsController::class, 'aggiungireview']);
Route::post('/ajx-getphaseintegrations', [AdminIntegrationsController::class, 'getphaseintegrations']);

Route::get('storagestoriesubmit/{ssid}/{file}', function ($ssid,$file) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'storiesubmit' . DIRECTORY_SEPARATOR  . $ssid . DIRECTORY_SEPARATOR . $file);
    return response()->file($path);
});
?>