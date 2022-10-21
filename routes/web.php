<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UsersController,HomeController,StoriesController,MailController,IntegrationsController,PrivacyController,AdminController,CkeditorController,ReviewsController,SitemapController};

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
Route::get('/ilprogetto',[HomeController::class,'project']);

//Route::get('/elencostorie/{zid?}',[StoriesController::class,'elencostorie'])->where(['zid'=>'^[1-9][0-9]*$']);
Route::get('/elencostorie/{slugzoonosi?}',[StoriesController::class,'list'])->where(['slugzoonosi'=>'^[a-z0-9]+(-?[a-z0-9]+)*$']);
//Route::get('/storia/{sid}',[StoriesController::class,'storydetail'])->where(['sid'=>'[1-9][0-9]?+']);
Route::get('/storia/{slug}',[StoriesController::class,'storydetail'])->where(['slug'=>'^[a-z0-9]+(-?[a-z0-9]+)*$']);

/*login and logout routes*/
Route::get('/login', [UsersController::class,'login']);
Route::get('/checklogin', [UsersController::class,'checklogin']);
Route::post('/checklogin', [UsersController::class,'checklogin']);
Route::get('/logout', [UsersController::class,'logout']);
/*registration routes*/
Route::get('/registrazione', [UsersController::class,'registration']);
Route::post('/registrazione', [UsersController::class,'registration']);
/*search routes*/
Route::get('/ricerca', [StoriesController::class,'search']);
Route::post('/elencostorie',[StoriesController::class,'list']);
/*sending stories routes*/
Route::get('/crowdsourcing/submission',[StoriesController::class,'reportstory']);
Route::post('/crowdsourcing/submission',[StoriesController::class,'reportstory']);

/*other*/
Route::get('/comingsoon', function () {return view('comingsoon')->with('title_page','Coming Soon');});
Route::get('/privacy-policy',[PrivacyController::class,'view']);
Route::get('/faq',function () {return view('faq')->with('title_page','FAQ');});
Route::get('/developmentby', function () {return view('developmentby')->with('title_page','Sviluppatore');});
Route::get('/contatti', function () {return view('contacts')->with('title_page','Contatti');});

/*ajax calls*/
Route::post('ajx-getcontextdata', [StoriesController::class, 'getcontextdatastory']);
Route::post('ajx-getreview', [StoriesController::class, 'getreviewzoonosi']);
Route::post('ajx-getsnippet', [StoriesController::class, 'getsnippet']);
Route::post('ajx-putintegrationmessage', [IntegrationsController::class, 'setnewintegration']);

/*verify email*/
Route::get('/confermaemail/{first?}/{second?}/{third?}', [UsersController::class,'checkconfirmationemail'])->where(['second'=>'^[1-9][0-9]*$']);

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

