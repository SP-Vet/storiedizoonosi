<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UsersController,HomeController,StorieController,MailController,ApprofondimentiController,PrivacyController,AdminController,CkeditorController,ReviewsController,SitemapController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/',HomeController::class);
Route::get('/ilprogetto',[HomeController::class,'ilprogetto']);

//Route::get('/elencostorie/{zid?}',[StorieController::class,'elencostorie'])->where(['zid'=>'^[1-9][0-9]*$']);
Route::get('/elencostorie/{slugzoonosi?}',[StorieController::class,'elencostorie'])->where(['slugzoonosi'=>'^[a-z0-9]+(-?[a-z0-9]+)*$']);
//Route::get('/storia/{sid}',[StorieController::class,'dettagliostoria'])->where(['sid'=>'[1-9][0-9]?+']);
Route::get('/storia/{slug}',[StorieController::class,'dettagliostoria'])->where(['slug'=>'^[a-z0-9]+(-?[a-z0-9]+)*$']);

/*login and logout routes*/
Route::get('/login', [UsersController::class,'login']);
Route::get('/checklogin', [UsersController::class,'checklogin']);
Route::post('/checklogin', [UsersController::class,'checklogin']);
Route::get('/logout', [UsersController::class,'logout']);
/*registrazione routes*/
Route::get('/registrazione', [UsersController::class,'registrazione']);
Route::post('/registrazione', [UsersController::class,'registrazione']);
/*ricerca routes*/
Route::get('/ricerca', [StorieController::class,'ricerca']);
Route::post('/elencostorie',[StorieController::class,'elencostorie']);
/*inserimento storie routes*/
Route::get('/crowdsourcing/submission',[StorieController::class,'segnalastoria']);
Route::post('/crowdsourcing/submission',[StorieController::class,'segnalastoria']);

/*altro*/
Route::get('/comingsoon', function () {return view('comingsoon')->with('title_page','Coming Soon');});
Route::get('/privacy-policy',[PrivacyController::class,'visualizza']);
Route::get('/faq',function () {return view('faq')->with('title_page','FAQ');});
//Route::get('/faq2',function () {return view('faq2');});
Route::get('/developmentby', function () {return view('developmentby')->with('title_page','Sviluppatore');});
Route::get('/contatti', function () {return view('contatti')->with('title_page','Contatti');});

/*ajax calls*/
Route::post('ajx-getdaticontesto', [StorieController::class, 'getdaticontestostoria']);
Route::post('ajx-getreview', [StorieController::class, 'getreviewzoonosi']);
Route::post('ajx-getsnippet', [StorieController::class, 'getsnippet']);
Route::post('ajx-putintegrationmessage', [ApprofondimentiController::class, 'setnewapprofondimento']);

/*verifica email*/
Route::get('/confermaemail/{first?}/{second?}/{third?}', [UsersController::class,'checkemailconferma'])->where(['second'=>'^[1-9][0-9]*$']);

/*testemail*/
Route::get('sendbasicemail',[MailController::class, 'basic_email']);
Route::get('sendhtmlemail',[MailController::class, 'html_email']);
Route::get('sendattachmentemail',[MailController::class, 'attachment_email']);

/*sitemap*/
Route::get('createsitemap',[SitemapController::class, 'generatesitemap']);
Route::get('sitemap',[SitemapController::class, 'show']);

/*admin login routes*/
use App\Http\Controllers\Auth\{AdminAuthController};

Route::get('admin/login',[AdminAuthController::class, 'getLogin'])->name('adminLogin');
Route::post('admin/login',[AdminAuthController::class, 'postLogin'])->name('adminLoginPost');
Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('adminLogout');
Route::group(['prefix' => 'admin','middleware' => 'adminauth'], function () {
	// Admin Dashboard
	Route::get('dashboard',[AdminController::class, 'dashboard'])->name('dashboard');
});

Route::get('storagestoriesubmit/{ssid}/{file}/{filename}', function ($ssid,$file,$filename) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'storiesubmit' . DIRECTORY_SEPARATOR  . $ssid . DIRECTORY_SEPARATOR . $file);
    $headers = [
            'Content-Disposition: attachment: filename=\"'.basename($filename).'\";"',
        ];
    return response()->download($path, $filename, $headers);
});

Route::get('storageallegatistorie/{sid}/{file}/{filename}', function ($sid,$file,$filename) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public'.DIRECTORY_SEPARATOR.'storieallegatimultimediali' . DIRECTORY_SEPARATOR  . $sid . DIRECTORY_SEPARATOR . $file);
    $headers = [
            'Content-Disposition: attachment: filename=\"'.basename($filename).'\";"',
        ];
    return response()->download($path, $filename, $headers);
});

Route::get('storagereview/{zid}/{file}/{filename}', function ($zid,$file,$filename) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public'.DIRECTORY_SEPARATOR.'reviews' . DIRECTORY_SEPARATOR  . $zid . DIRECTORY_SEPARATOR . $file);
    $headers = [
            'Content-Disposition: attachment: filename=\"'.basename($filename).'\";"',
        ];
    return response()->download($path, $filename, $headers);
});
//route sottostante per aprire il media all'interno del browser
/*Route::get('storagestoriesubmit/{ssid}/{file}/{filename}', function ($ssid,$file,$filename) {
    $path = storage_path('app' . DIRECTORY_SEPARATOR . 'storiesubmit' . DIRECTORY_SEPARATOR  . $ssid . DIRECTORY_SEPARATOR . $file);
    return response()->file($path)->header('Content-Disposition: attachment; filename="' . $fileName . '"');
});*/

Route::post('/ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

