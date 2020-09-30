<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Geo\DistrictController;
use App\Http\Controllers\Geo\RegionController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Вызов страницы регистрации пользователя
Route::get('register', [AuthController::class, 'register']);   
// Пользователь заполнил форму регистрации и отправил
Route::post('register', [AuthController::class, 'registerProcess']); 
// Пользователь получил письмо для активации аккаунта со ссылкой сюда
Route::get('activate/{id}/{code}', [AuthController::class, 'activate']);
// Вызов страницы авторизации
Route::get('login', [AuthController::class, 'login'])->name('login');
// Пользователь заполнил форму авторизации и отправил
Route::post('login', [AuthController::class, 'loginProcess']);
// Выход пользователя из системы
Route::get('logout', [AuthController::class, 'logoutuser']);
// Пользователь забыл пароль и запросил сброс пароля. Это начало процесса - 
// Страница с запросом E-Mail пользователя
Route::get('reset', [AuthController::class, 'resetOrder']);
// Пользователь заполнил и отправил форму с E-Mail в запросе на сброс пароля
Route::post('reset', [AuthController::class, 'resetOrderProcess']);
// Пользователю пришло письмо со ссылкой на эту страницу для ввода нового пароля
Route::get('reset/{id}/{code}', [AuthController::class, 'resetComplete']);
// Пользователь ввел новый пароль и отправил.
Route::post('reset/{id}/{code}', [AuthController::class, 'resetCompleteProcess']);
// Сервисная страничка, показываем после заполнения рег формы, формы сброса и т.
// о том, что письмо отправлено и надо заглянуть в почтовый ящик.
Route::get('wait', [AuthController::class, 'wait']);

//Route::get('import/district_names', [ImportController::class, 'districtNames']);

Route::resources([
    'geo/district' => DistrictController::class,
    'geo/region'   => RegionController::class,
]);
/*
Route::resource('corpus/district', 'DistrictController',
               ['names' => ['update' => 'district.update',
                            'store' => 'district.store',
                            'destroy' => 'district.destroy']]);

Route::resource('corpus/informant', 'Corpus\InformantController',
               ['names' => ['update' => 'informant.update',
                            'store' => 'informant.store',
                            'destroy' => 'informant.destroy']]);

Route::resource('corpus/place', 'Corpus\PlaceController',
               ['names' => ['update' => 'place.update',
                            'store' => 'place.store',
                            'destroy' => 'place.destroy']]);

Route::resource('corpus/recorder', 'Corpus\RecorderController',
               ['names' => ['update' => 'recorder.update',
                            'store' => 'recorder.store',
                            'destroy' => 'recorder.destroy']]);

Route::resource('corpus/region', 'Corpus\RegionController',
               ['names' => ['update' => 'region.update',
                            'store' => 'region.store',
                            'destroy' => 'region.destroy']]);

Route::resource('role', 'RoleController',
               ['names' => ['update' => 'role.update',
                            'store' => 'role.store',
                            'destroy' => 'role.destroy']]);

Route::resource('user', 'UserController',
               ['names' => ['update' => 'user.update',
                            'destroy' => 'user.destroy']]);
*/
