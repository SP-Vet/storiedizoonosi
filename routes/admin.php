<?php 
/*tutte le rotte admin*/
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminZoonosiController,AdminStorieController,AdminController,AdminGruppodilavoroController,AdminApprofondimentiController,AdminCollaboratoriController,CkeditorController,ReviewsController,AdminDaticontestoController};

Route::get('/cambiapassword/{id?}',[AdminController::class,'cambiapassword'])->where(['id'=>'[1-9][0-9]?+'])->name('adminCambiapassword');
Route::post('/cambiapassword/{id?}',[AdminController::class,'cambiapassword'])->where(['id'=>'[1-9][0-9]?+'])->name('admincheckCambiapassword');
/*sezione zoonosi*/
Route::get('/elencozoonosi',[AdminZoonosiController::class,'elenco'])->name('adminListZoonosi');
Route::get('/modificazoonosi/{zid?}',[AdminZoonosiController::class,'modifica'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminModificaZoonosi');
Route::post('/modificazoonosi/{zid?}',[AdminZoonosiController::class,'modifica'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminSalvaModificaZoonosi');
Route::get('/aggiungizoonosi',[AdminZoonosiController::class,'aggiungi'])->name('adminAggiungiZoonosi');
Route::post('/aggiungizoonosi',[AdminZoonosiController::class,'aggiungi'])->name('adminSalvaNewZoonosi');
Route::get('/cancellazoonosi/{zid}',[AdminZoonosiController::class,'cancella'])->where(['zid'=>'[1-9][0-9]?+'])->name('adminCancellaZoonosi');

/*sezione stroie*/
Route::get('/elencostorie',[AdminStorieController::class,'elenco'])->name('adminListStorie');
Route::get('/modificastoria/{sid?}',[AdminStorieController::class,'modifica'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminModificaStoria');
Route::post('/modificastoria/{sid?}',[AdminStorieController::class,'modifica'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminSalvaModificaStoria');
Route::get('/aggiungistoria',[AdminStorieController::class,'modifica'])->name('adminAggiungiStoria');
Route::post('/aggiungistoria',[AdminStorieController::class,'modifica'])->name('adminSalvaNewStoria');
Route::get('/cancellastoria/{sid}',[AdminStorieController::class,'cancella'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminCancellaStoria');
Route::get('/daticontestostoria/{sid?}',[AdminDaticontestoController::class,'daticontestostoria'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminDatiContestoStoria');
Route::post('/daticontestostoria/{sid?}',[AdminDaticontestoController::class,'daticontestostoria'])->where(['sid'=>'[1-9][0-9]?+'])->name('adminSalvaDatiContestoStoria');

/*sezione gruppo di lavoro*/
Route::get('/elencogruppo',[AdminGruppodilavoroController::class,'elenco'])->name('adminListGruppodilavoro');
Route::get('/aggiungiutente',[AdminGruppodilavoroController::class,'aggiungi'])->name('adminAggiungiUtente');
Route::post('/aggiungiutente',[AdminGruppodilavoroController::class,'aggiungi'])->name('adminSalvaNewUtente');
Route::get('/modificautente/{id?}',[AdminGruppodilavoroController::class,'modifica'])->where(['id'=>'[1-9][0-9]?+'])->name('adminModificaUtente');
Route::post('/modificautente/{id?}',[AdminGruppodilavoroController::class,'modifica'])->where(['id'=>'[1-9][0-9]?+'])->name('adminSalvaModificaUtente');

/*sezione approfondimenti*/
Route::get('/elencoapprofondimenti',[AdminApprofondimentiController::class,'elenco'])->name('adminListApprofondimenti');
Route::get('/gestisciapprofondimenti/{said?}',[AdminApprofondimentiController::class,'gestisci'])->where(['said'=>'[1-9][0-9]?+'])->name('adminGestisciApprofondimento');
Route::post('/gestisciapprofondimenti/{said?}',[AdminApprofondimentiController::class,'gestisci'])->where(['said'=>'[1-9][0-9]?+'])->name('adminSalvaGestioneApprofondimento');

/*sezione log*/
Route::get('/elencolog',[LogPersonal::class,'elenco'])->name('listLog');
Route::post('/elencolog',[LogPersonal::class,'elenco'])->name('postlistLog');
Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

/*ajax calls*/
Route::post('/ajx-checkslugzoonosi', [AdminZoonosiController::class, 'checkslugzoonosi']);
Route::post('/ajx-checkslug', [AdminStorieController::class, 'checkslug']);
Route::post('/ajx-pubblicastoria', [AdminStorieController::class, 'pubblicastoria']);
Route::post('/ajx-pubblicaapprofondimento', [AdminApprofondimentiController::class, 'pubblicaapprofondimento']);
Route::post('/ajx-getcollaboratore', [AdminCollaboratoriController::class, 'getcollaboratore']);
Route::post('/ajx-removereview', [ReviewsController::class, 'eliminareview']);
Route::post('/ajx-uploadreview', [ReviewsController::class, 'aggiungireview']);
Route::post('/ajx-getintegrazionifase', [AdminApprofondimentiController::class, 'getintegrazionifase']);

Route::get('storagestoriesubmit/{ssid}/{file}', function ($ssid,$file) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'storiesubmit' . DIRECTORY_SEPARATOR  . $ssid . DIRECTORY_SEPARATOR . $file);
    return response()->file($path);
});
?>